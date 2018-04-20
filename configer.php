<?php session_start();
ini_set( 'display_errors', 0 );
define( '_INCLUDE', 1 );
define( 'ADMIN_DIR', 'admin' );
define( 'INSTALL_DIR', 'install' );
$config_file = 'config.php';
for ( $i = 1; $i <= 10; $i++ ) {
	if ( ! file_exists( $config_file ) ) {
		$config_file = '../' . $config_file;
	}
}
if ( ! file_exists( $config_file ) ) {
	require_once( INSTALL_DIR . '/index.php' );
	die();
}
require_once( $config_file );
require_once( ADMIN_DIR . '/core.php' );
$lang = new RedirMe\Language;
$lang->setLang();
?>