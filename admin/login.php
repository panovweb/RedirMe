<?php require_once( '../configer.php' );
$user = new Redirme\User;
$uri = new Redirme\URI;
if ( $user->logged() ) {
	$uri->redirect( $uri->home() );
	die();
}
$config = new Redirme\Config;
$incder = new Redirme\Includer;
$lang = new Redirme\Language;
$loginScript = $uri->home() . '/login.php';
global $do, $message;
if ( isset( $_POST['auth'] ) ) {
	$user->authorize();
	die();
}
if ( isset( $_POST['restore'] ) ) {
	$user->restore();
	die();
}
$pageTitle = 'Authorization';
$incder->getPart( 'header' );
$messageBlock = null;
if ( $message ) {
	switch ( $message ) {
		case 'loggedout':
			$messType = ' success';
			$messCont = 'You was successfully logged out from the System';
		break;
		case 'youlocked':
			$messType = ' fail';
			$messCont = 'Your account is locked';
		break;
		case 'errcaptcha':
			$messType = ' fail';
			$messCont = 'Error in calculating';
		break;
		case 'emptyuname':
			$messType = ' fail';
			$messCont = 'Enter Your Username';
		break;
		case 'emptypass':
			$messType = ' fail';
			$messCont = 'Enter Your Password';
		break;
		case 'err':
			$messType = ' fail';
			$messCont = 'Error in username or in password';
		break;
		case 'emptymail':
			$messType = ' fail';
			$messCont = 'Enter Your E-mail';
		break;
		case 'errmail':
			$messType = ' fail';
			$messCont = 'Incorrect E-mail';
		break;
		case 'nouser':
			$messType = ' fail';
			$messCont = 'A user with such data doesn\'t exists in the system';
		break;
		case 'sent':
			$messType = ' success';
			$messCont = 'New password sent to your e-mail';
		break;
		case 'errrestoring':
			$messType = ' fail';
			$messCont = 'Error restoring Your password. Please, try again.';
		break;
		default:
			$messCont = 'Non-existent notice';
	}
	$messageBlock = "<div class=\"authMessage{$messType}\">{$lang->_tr( $messCont, 1 )}</div>\n";
}
$content = array(
	'[message]' => $messageBlock,
	'[form_title]' => $lang->_tr( 'Authorization', 1 ),
	'[uname_label]' => $lang->_tr( 'Username', 1 ),
	'[input_max_length]' => $config->max_input_length,
	'[password_label]' => $lang->_tr( 'Password', 1 ),
	'[password_max_length]' => $config->pass_max_length,
	'[captcha_label]' => $user->mathCaptcha(),
	'[captcha_holder]' => $lang->_tr( 'Answer in digits', 1 ),
	'[restore_password_uri]' => $loginScript . '?do=restore',
	'[restore_password_label]' => $lang->_tr( 'Forgot password?', 1 ),
	'[remember_me_label]' => $lang->_tr( 'Remember me', 1 ),
	'[button_submit]' => $lang->_tr( 'Log In', 1 ),
);
$partName = 'login';
if ( $do === 'restore' ) {
	$partName = 'restore-password';
	$content = array(
		'[message]' => $messageBlock,
		'[form_title]' => $lang->_tr( 'Password Recovery', 1 ),
		'[uname_label]' => $lang->_tr( 'Username', 1 ),
		'[email_label]' => $lang->_tr( 'E-mail', 1 ),
		'[input_max_length]' => $config->max_input_length,
		'[captcha_label]' => $user->mathCaptcha(),
		'[captcha_holder]' => $lang->_tr( 'Answer in digits', 1 ),
		'[login_page_url]' => $loginScript,
		'[login_page_link]' => $lang->_tr( 'Log In', 1 ),
		'[button_submit]' => $lang->_tr( 'Submit', 1 ),
	);
}
$incder->view( $partName, $content );
$incder->getPart( 'footer' );
?>