<?php require_once( 'configer.php' );
$uri = new RedirMe\URI;
$redirect = new RedirMe\Redirect;
$link = $uri->process_GET();
if ( isset( $link ) && $redirect->isExists( $link ) ) {
	$redirect->move( $link );
	die();
}
$config = new RedirMe\Config;
$opts = new RedirMe\Options;
$hpRedirect = ( ! empty( $opts->optionValue( 'home_page_redirect' ) ) ) ? $opts->optionValue( 'home_page_redirect' ) : $uri->home();
if ( ! empty( $hpRedirect ) ) {
	$uri->redirect( $hpRedirect );
	die();
}
?>