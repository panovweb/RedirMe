<?php defined( '_INCLUDE' ) or die();
global $action, $message;
$incder = new RedirMe\Includer;
$config = new Redirme\Config;
$uri = new RedirMe\URI;
$user = new RedirMe\User;
$lang = new RedirMe\Language;
$date = new Redirme\Date;
if ( ! $user->can( 'manage_users' ) ) {
	$user->cant();
}
if ( isset( $_POST['remove_users'] ) ) {
	if ( isset( $_POST['remove'] ) ) {
		$usersRemove = implode( ',', $_POST['remove'] );
		$user->remove( $usersRemove );
	} else {
		$uri->redirect( $uri->home() . "?action={$action}" );
	}
}
// User List
$query = ( isset( $_POST['s'] ) ) ? $_POST['s'] : null;
if ( ! empty( $query ) ) {
	$users = $user->searchUsers( $query );
} else {
	$users = $user->all();
}
$userList = '';
$pattern = "/((?:^|>)[^<]*)(".$query.")/si";
$replace = '$1<b style="color:black; background:yellow;">$2</b>';
foreach ( $users as $an ) {
	$userList .= "<a href=\"{$uri->home()}/?action={$config->actions['user-edit']}&id={$an['user_id']}\" class=\"row\">
			<section class=\"check\">
				<input type=\"checkbox\" name=\"remove[]\" class=\"autoCheck\" value=\"{$an['user_id']}\" />
			</section>
			<section class=\"uname\">" . preg_replace( $pattern, $replace, $an['user_login'] ) . "</section>
			<section class=\"name\">{$user->name( $an['user_id'] )}</section>
			<section class=\"date\">{$date->dateFormat( $an['user_registered'] )} {$lang->_tr( 'at', 1 )} {$date->timeFormat( $an['user_registered'] )}</section>
		</a>";
}
if ( sizeof( $users ) == 0 ) {
	$noText = 'No created users';
	if ( $query ) {
		$noText = 'No search results';
	}
	$userList = "<div class=\"noItems\">{$lang->_tr( $noText, 1 )}</div>\n";
}
// Disabled Remove Button
$disabled = ' disabled';
if ( $user->can( 'remove_users' ) ) {
	$disabled = null;
}
// Messages
$messageBlock = null;
if ( $message ) {
	switch ( $message ) {
		case 'unselected':
			$messType = ' fail';
			$messCont = 'Please, select users to remove';
		break;
		case 'noperm':
			$messType = ' fail';
			$messCont = 'You do not have permission to perform this operation';
		break;
		case 'removed':
			$messType = ' success';
			$messCont = 'Selected users were successfully removed';
		break;
		case 'err':
			$messType = ' fail';
			$messCont = 'Error removing users';
		break;
		default:
			$messCont = 'Non-existent notice';
	}
	$messageBlock = "<div class=\"message{$messType}\">{$lang->_tr( $messCont, 1 )}</div>\n";
}
$pageTitle = 'Users';
$incder->getPart( 'header' );
$incder->getPart( 'wrapper' );
$content = array(
	'[message]' => $messageBlock,
	'[disabled]' => $disabled,
	'[remove]' => $lang->_tr( 'Remove Selected', 5 ),
	'[search_field_placeholder]' => $lang->_tr( 'Search...', 1 ),
	'[search_field_value]' => $query,
	'[max_input_length]' => $config->max_input_length,
	'[uname_label]' => $lang->_tr( 'Username', 1 ),
	'[name_label]' => $lang->_tr( 'Name', 1 ),
	'[reg_date_label]' => $lang->_tr( 'Registration Date', 1 ),
	'[users]' => $userList,
);
$incder->view( 'user-list', $content );
$user->paginator( $uri->home() . "?action={$action}", $user->countUsers() );
$incder->getPart( 'footer' );
?>