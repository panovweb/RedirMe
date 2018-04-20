<?php
namespace RedirMe;
/**
* Language operations
*/
class Language {

	public $sys_langs = array(
		'English' => 'en',
		'Русский' => 'ru'
	);
	
// Returns $sys_langs as options for select box
	public function selects( $current = null ) {
		$return = '';
		foreach ( $this->sys_langs as $value => $key ) {
			$selected = null;
			if ( $key == $current ) {
				$selected = ' selected';
			}
			$return .= "<option value=\"{$key}\"{$selected}>{$value}</option>\n";
		}
		return $return;
	}

// Translates the text from original (English) to selected in the System settings and returns translated phrase or shows original phrase
	public function _tr( $phrase, $version ) {
		$incder = new Includer;
		$opts = new Options;
		$siteLanguage = $opts->optionValue( 'sys_language' );
		if ( ! empty( $_COOKIE['sys_language'] ) ) {
			$siteLanguage = $_COOKIE['sys_language'];
		}
		if ( ! empty( $_SESSION['sys_language'] ) ) {
			$siteLanguage = $_SESSION['sys_language'];
		}
		$languageFile = $incder->lang_dir . $siteLanguage . '.php';
		if ( ! file_exists( $languageFile ) ) {
			$languageFile = ADMIN_DIR . '/' . $languageFile;
		}
	    if ( ! file_exists( $languageFile ) ) {
		    return $phrase;
	    }
		include_once( $languageFile );
		foreach ( Translation::$translations as $trVersion => $trArr ) {
			if ( $trVersion == $version ) {
				foreach ( $trArr as $originalPhrase => $needlePhrase ) {
					if ( $originalPhrase == $phrase )
						return $needlePhrase;
				}
			}
		}
		return $phrase;
	}
	
// Gets user's browser language
	public function browserLang() {
		preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]), $matches);
		$langs = array_combine($matches[1], $matches[2]);
		foreach ($langs as $n => $v) {
			$langs[$n] = $v ? $v : 1;
		}
		arsort($langs);
		$defaultLang = key($langs);
		return $defaultLang;
	}
	
// Set the language according to the browser language, if this setting is enabled
	public function setLang() {
		if ( ! empty( $_COOKIE['sys_language'] ) ) {
			return false;
		}
		$opts = new Options;
		$sess = new Session;
		if ( ! $opts->isOn( 'browser_language' ) ) {
			return false;
		}
		$browserLang = $this->browserLang();
		$sess->setCustomCookie( 'sys_language', $opts->optionValue( 'sys_language' ), null );
		foreach ( $this->sys_langs as $name => $code ) {
			$posLang = null;
			$posLang = strripos( $browserLang, $code );
			if ( $posLang !== false ) {
				$sess->setCustomCookie( 'sys_language', $code, null );
			}
		}
	}

}
?>
