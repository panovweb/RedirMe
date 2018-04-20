<?php require_once( '../configer.php' );
$uri = new RedirMe\URI;
$user = new RedirMe\User;
global $action, $do;
if ( ! $user->logged() ) {
	$uri->redirect( $uri->home() . '/login.php' );
	die();
}
if ( $do === 'update' ) {
	$upd = new RedirMe\Update;
	$upd->updateSystem();
	die();
}
if ( $do === 'logout' ) {
	$user->logout();
	$uri->redirect( $uri->home() . '/login.php?message=loggedout' );
	die();
}
if ( ! $user->can( 'manage_profile' ) ) {
	$user->logout();
	$uri->redirect( $uri->home() . '/login.php?message=youlocked' );
	die();
}
if ( $action ) {
	$action_file = $incder->action_dir . $action . '.php';
	if ( file_exists( $action_file ) ) {
		require_once( $action_file );
	} else {
		require_once( $incder->action_dir . '404.php' );
	}
} else {
	require_once( $incder->action_dir . 'index.php' );
}
?>