<?php defined( '_INCLUDE' ) or die();
global $action, $message;
$incder = new RedirMe\Includer;
$config = new Redirme\Config;
$uri = new RedirMe\URI;
$user = new RedirMe\User;
$primary = new RedirMe\Primary;
$lang = new RedirMe\Language;
if ( ! $user->can( 'manage_users' ) ) {
	$user->cant();
}
if ( isset( $_POST['user_do'] ) ) {
	$user->add();
	die();
}
// Messages
$messageBlock = null;
if ( $message ) {
	switch ( $message ) {
		case 'emptyuname':
			$messType = ' fail';
			$messCont = 'Enter Username';
		break;
		case 'nolatinuname':
			$messType = ' fail';
			$messCont = 'The username must consist only of Latin characters and numbers';
		break;
		case 'unameexists':
			$messType = ' fail';
			$messCont = 'The user with such username already exists in the system';
		break;
		case 'emptymail':
			$messType = ' fail';
			$messCont = 'Enter E-mail';
		break;
		case 'errmail':
			$messType = ' fail';
			$messCont = 'Incorrect E-mail';
		break;
		case 'mailexists':
			$messType = ' fail';
			$messCont = 'The user with such e-mail already exists in the system';
		break;
		case 'emptyfname':
			$messType = ' fail';
			$messCont = 'Enter First Name';
		break;
		case 'emptylname':
			$messType = ' fail';
			$messCont = 'Enter Last Name';
		break;
		case 'err':
			$messType = ' fail';
			$messCont = 'Error creating a user';
		break;
		default:
			$messCont = 'Non-existent notice';
	}
	$messageBlock = "<div class=\"message{$messType}\">{$lang->_tr( $messCont, 1 )}</div>\n";
}
$pageTitle = 'Create User';
$incder->getPart( 'header' );
$incder->getPart( 'wrapper' );
$content = array(
	'[message]' => $messageBlock,
	'[required]' => $lang->_tr( 'Required!', 1 ),
	'[max_input_length]' => $config->max_input_length,
	'[uname_label]' => $lang->_tr( 'Username', 1 ),
	'[uname_value]' => null,
	'[email_label]' => $lang->_tr( 'E-mail', 1 ),
	'[email_value]' => null,
	'[fname_label]' => $lang->_tr( 'First Name', 1 ),
	'[fname_value]' => null,
	'[lname_label]' => $lang->_tr( 'Last Name', 1 ),
	'[lname_value]' => null,
	'[perms_label]' => $lang->_tr( 'Permissions', 1 ),
	'[perms]' => $user->perms(),
	'[submit]' => $lang->_tr( 'Create', 1 ),
	'[send_pass]' => null,
);
$incder->view( 'user-do', $content );
$incder->getPart( 'footer' );
?>