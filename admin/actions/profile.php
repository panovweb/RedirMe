<?php defined( '_INCLUDE' ) or die();
global $action, $message;
$incder = new RedirMe\Includer;
$config = new Redirme\Config;
$uri = new RedirMe\URI;
$user = new RedirMe\User;
$lang = new RedirMe\Language;
if ( ! $user->can( 'manage_profile' ) ) {
	$user->cant();
}
$an = $user->an( $user->_user() );
if ( isset( $_POST['upd_prof'] ) ) {
	$user->updateProfile();
	die();
}
// Messages
$messageBlock = null;
if ( $message ) {
	switch ( $message ) {
		case 'demomode':
			$messType = ' fail';
			$messCont = 'Demo mode is enabled. You can not change the settings.';
		break;
		case 'emptymail':
			$messType = ' fail';
			$messCont = 'Enter your e-mail';
		break;
		case 'errmail':
			$messType = ' fail';
			$messCont = 'Incorrect e-mail';
		break;
		case 'emptyfname':
			$messType = ' fail';
			$messCont = 'Enter your first name';
		break;
		case 'emptylname':
			$messType = ' fail';
			$messCont = 'Enter your last name';
		break;
		case 'passmatch':
			$messType = ' fail';
			$messCont = 'Passwords do not match';
		break;
		case 'passlength':
			$messType = ' fail';
			$messCont = 'Incorrect password length';
		break;
		case 'updated':
			$messType = ' success';
			$messCont = 'Your profile was successfully updated';
		break;
		case 'err':
			$messType = ' fail';
			$messCont = 'Error updating your profile';
		break;
		default:
			$messCont = 'Non-existent notice';
	}
	$messageBlock = "<div class=\"message{$messType}\">{$lang->_tr( $messCont, 1 )}</div>\n";
}
$pageTitle = 'My Profile';
$incder->getPart( 'header' );
$incder->getPart( 'wrapper' );
$content = array(
	'[message]' => $messageBlock,
	'[max_input_length]' => $config->max_input_length,
	'[uname_label]' => $lang->_tr( 'Username', 1 ) . ' (' . $lang->_tr( 'can\'t be changed', 1 ) . ')',
	'[uname_value]' => $an->user_login,
	'[email_label]' => $lang->_tr( 'E-mail', 1 ),
	'[email_value]' => $an->user_email,
	'[fname_label]' => $lang->_tr( 'First Name', 1 ),
	'[fname_value]' => $an->user_firstname,
	'[lname_label]' => $lang->_tr( 'Last Name', 1 ),
	'[lname_value]' => $an->user_lastname,
	'[password_label]' => $lang->_tr( 'New Password', 1 ),
	'[password_placeholder]' => $lang->_tr( 'From', 1 ) . ' ' . $config->pass_min_length . ' ' . $lang->_tr( 'to', 1 ) . ' ' . $config->pass_max_length . ' ' . $lang->_tr( 'symbols', 2 ),
	'[repeat_password_label]' => $lang->_tr( 'Repeat New Password', 1 ),
	'[max_password_length]' => $config->pass_max_length,
	'[submit]' => $lang->_tr( 'Update', 1 ),
);
$incder->view( 'profile', $content );
$incder->getPart( 'footer' );
?>