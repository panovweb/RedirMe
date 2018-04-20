<?php defined( '_INCLUDE' ) or die();
global $action, $id, $message;
$incder = new RedirMe\Includer;
$config = new Redirme\Config;
$uri = new RedirMe\URI;
$user = new RedirMe\User( $id );
$primary = new RedirMe\Primary;
$lang = new RedirMe\Language;
if ( ! $user->can( 'manage_users' ) ) {
	$user->cant();
}
$an = $user->an();
if ( empty( $an ) ) {
	$primary->e404();
}
if ( isset( $_POST['send_pass'] ) ) {
	$user->newPassword( $an->user_email );
	die();
} elseif ( isset( $_POST['user_do'] ) ) {
	$user->update();
	die();
}
// Messages
$messageBlock = null;
if ( $message ) {
	switch ( $message ) {
		case 'created':
			$messType = ' success';
			$messCont = 'A new user was successfully created. The password was sent to him by e-mail.';
		break;
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
		case 'updated':
			$messType = ' success';
			$messCont = 'The user data was successfully updated';
		break;
		case 'err':
			$messType = ' fail';
			$messCont = 'Error updating the user data';
		break;
		case 'sent':
			$messType = ' success';
			$messCont = 'A new password has sent to the user successfully';
		break;
		case 'errsending':
			$messType = ' fail';
			$messCont = 'Error sending a new password';
		break;
		default:
			$messCont = 'Non-existent notice';
	}
	$messageBlock = "<div class=\"message{$messType}\">{$lang->_tr( $messCont, 1 )}</div>\n";
}
$pageTitle = 'Edit User';
$incder->getPart( 'header' );
$incder->getPart( 'wrapper' );
$content = array(
	'[message]' => $messageBlock,
	'[required]' => $lang->_tr( 'Required!', 1 ),
	'[max_input_length]' => $config->max_input_length,
	'[uname_label]' => $lang->_tr( 'Username', 1 ),
	'[uname_value]' => $an->user_login,
	'[email_label]' => $lang->_tr( 'E-mail', 1 ),
	'[email_value]' => $an->user_email,
	'[fname_label]' => $lang->_tr( 'First Name', 1 ),
	'[fname_value]' => $an->user_firstname,
	'[lname_label]' => $lang->_tr( 'Last Name', 1 ),
	'[lname_value]' => $an->user_lastname,
	'[perms_label]' => $lang->_tr( 'Permissions', 1 ),
	'[perms]' => $user->perms( explode( ',', $an->user_permissions ) ),
	'[submit]' => $lang->_tr( 'Update', 1 ),
	'[send_pass]' => "<button type=\"submit\" name=\"send_pass\" class=\"submit grey\">{$lang->_tr( 'Send new password', 1 )}</button>\n",
);
$incder->view( 'user-do', $content );
$incder->getPart( 'footer' );
?>