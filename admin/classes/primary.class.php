<?php
namespace RedirMe;
/**
* General functions
*/
class Primary {

// Returns the product Name
	public function systemName() {
		$config = new Config;
		return $config->sys_name;
	}

// Returns a page title for the tag <title>
	public function pageTitle() {
		global $pageTitle;
		$opts = new Options;
		$lang = new Language;
		$title = ( isset( $pageTitle ) ) ? $lang->_tr( $pageTitle, 1 ) . ' | ' . $this->systemName() : $this->systemName();
		return $title;
	}
	
// Returns a page title
	public function sectionTitle() {
		global $pageTitle;
		$lang = new Language;
		$title = $lang->_tr( $pageTitle, 1 );
		return $title;
	}
	
// Converts and returns a specially built array to navigation links with checking users permissions
	public function sidebarMenu( $menu_items ) {
		$usr = new User;
		$uri = new URI;
		$new_menu = array();
		foreach ( $menu_items as $key => $value ) {
			$value_array = explode( '::', $value );
			$current_active = null;
			if ( strripos( $uri->_URI(), $uri->home() . $key ) !== false && $key != '/' || $uri->_URI() == $uri->home() . $key ) {
				$current_active = ' active';
			}
			$last_class = null;
			if ( $value == end( $menu_items ) ) {
				$last_class = ' last';
			}
			if ( $usr->can( $value_array[1] ) ) {
				$new_menu[] = '<a href="' . $uri->home() . $key . '" class="' . $current_active . $last_class . '" title="' . $value_array[0] . '">' . $value_array[0] . '</a>' . "\n";
			}
		}
		if ( sizeof( $new_menu ) == 0 ) {
			return false;
		}
		$returnMenu = '';
		foreach ( $new_menu as $item ) {
			$returnMenu .= $item;
		}
		return $returnMenu;
	}

// Checks a request and finds out if it's ajax
	public function isAjax() {
		if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
			return true;
		}
		return false;
	}
	
// Returns the "Error 404" page
	public function e404() {
		global $pageTitle;
		$incder = new Includer;
		require_once( $incder->action_dir . '404.php' );
		die();
	}

}

?>