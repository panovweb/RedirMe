<?php defined( '_INCLUDE' ) or die();
global $action, $message;
$incder = new RedirMe\Includer;
$config = new Redirme\Config;
$user = new RedirMe\User;
$opts = new RedirMe\Options;
$uri = new RedirMe\URI;
$lang = new RedirMe\Language;
$date = new Redirme\Date;
if ( ! $user->can( 'set_options' ) ) {
	$user->cant();
}
if ( isset( $_POST['set'] ) ) {
	if ( $opts->isOn( 'demo_mode' ) ) {
		$uri->redirect( $uri->home() . "/?action={$action}&message=demomode" );
		die();
	}
	$homeURI = ( substr( $_POST['home_uri'], strlen( $_POST['home_uri'] )-1 ) == '/' ) ? substr( $_POST['home_uri'], 0, strlen( $_POST['home_uri'] )-1 ) : $_POST['home_uri'];
	$updateHomeURI = $opts->modify( 'home_uri', $homeURI, 1 );
	$updateHomeRedirectURI = $opts->modify( 'home_page_redirect', $_POST['home_page_redirect'] );
	$updateSystemLang = $opts->modify( 'sys_language', $_POST['sys_language'] );
	$updateBrowserLang = $opts->modify( 'browser_language', $_POST['browser_language'] );
	$updateDateFormat = $opts->modify( 'date_format', $_POST['date_format'] );
	$updateTimeFormat = $opts->modify( 'time_format', $_POST['time_format'] );
	if ( $updateHomeURI == true && $updateTimeFormat == true ) {
		$uri->redirect( $uri->home() . "/?action={$action}&message=success" );
		die();
	}
	$uri->redirect( $uri->home() . "/?action={$action}&message=err" );
	die();
}
$pageTitle = 'Settings';
$incder->getPart( 'header' );
$incder->getPart( 'wrapper' );
$messageBlock = null;
if ( $message ) {
	switch ( $message ) {
		case 'demomode':
			$messType = ' fail';
			$messCont = 'Demo mode is enabled. You can not change the settings.';
		break;
		case 'success':
			$messType = ' success';
			$messCont = 'The system settings were successfully updated';
		break;
		case 'err':
			$messType = ' fail';
			$messCont = 'Error updating the system settings';
		break;
		default:
			$messCont = 'Non-existent notice';
	}
	$messageBlock = "<div class=\"message{$messType}\">{$lang->_tr( $messCont, 1 )}</div>\n";
}
$content = array(
	'[message]' => $messageBlock,
	'[home_uri_label]' => $lang->_tr( 'System URL Address', 1 ),
	'[home_uri_value]' => $opts->optionValue( 'home_uri' ),
	'[home_redirect_uri_label]' => $lang->_tr( 'URL address of redirect from the home page', 1 ),
	'[home_redirect_uri_help]' => $lang->_tr( 'Specify the address of the page to which you want to redirect the visitor who came to the main page of the system (not the admin panel). If you leave the field blank, redirection will take place to the admin panel.', 1 ),
	'[home_redirect_uri_value]' => $opts->optionValue( 'home_page_redirect' ),
	'[max_input_length]' => $config->max_input_length,
	'[langs_label]' => $lang->_tr( 'System Language', 1 ),
	'[langs_options]' => $lang->selects( $opts->optionValue( 'sys_language' ) ),
	'[browser_lang_label]' => $lang->_tr( 'Set the system language according to the browser language', 1 ),
	'[browser_lang_help]' => $lang->_tr( 'Enable this option if you want the system to determine the language of the user\'s browser and install it for him. In this case, the language from the list of available in the system will be selected, or if there is no such language in the system, the one you installed as the system language will be selected.', 1 ),
	'[browser_lang_options]' => $opts->onOff( $opts->optionValue( 'browser_language' ) ),
	'[date_format_label]' => $lang->_tr( 'Date Format', 1 ),
	'[date_formats]' => $date->dateFormats( $opts->optionValue( 'date_format' ) ),
	'[time_format_label]' => $lang->_tr( 'Time Format', 1 ),
	'[time_formats]' => $date->timeFormats( $opts->optionValue( 'time_format' ) ),
	'[submit]' => $lang->_tr( 'Update', 1 ),
);
$incder->view( 'settings', $content );
$incder->getPart( 'footer' );
?>