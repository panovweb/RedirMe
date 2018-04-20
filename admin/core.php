<?php
namespace RedirMe;
// Global GET method variables.
$action = (isset( $_GET['action'] ) ) ? $_GET['action'] : null;
$do = (isset( $_GET['do'] ) ) ? $_GET['do'] : null;
$id = (isset( $_GET['id'] ) ) ? $_GET['id'] : null;
$message = (isset( $_GET['message'] ) ) ? $_GET['message'] : null;
/**
* Including operations
*/
class Includer {

	public $class_dir = 'classes/';
	public $action_dir = 'actions/';
	public $inc_dir = 'includes/';
	public $view_dir = 'views/';
	public $file_dir = 'files/';
	public $lang_dir = 'files/langs/';
	public $css_dir = 'files/css/';
	public $js_dir = 'files/js/';
	public $img_dir = 'files/images/';
	
	public $uploads_dir = 'uploads/';

// Includes all files with the classes from their directory
	public function getClasses() {
		$dir = $this->class_dir;
		if ( ! is_dir( $dir ) ) {
			$dir = ADMIN_DIR . '/' . $dir;
		}
		$openDir = opendir( $dir );	
		while ( $classFile = readdir( $openDir ) ) {
			if ( strripos( $classFile, '.class.php' ) ) {
				$file = $this->class_dir . $classFile;
				require_once( $file );
			}
		}
	}

// Includes all files with the template parts from their directory
	public function getPart( $partName ) {
		$primar = new Primary;
		if ( empty( $partName ) || $primar->isAjax() ) {
			return false;
		}
		$dir = $this->inc_dir;
		if ( ! is_dir( $dir ) ) {
			$dir = ADMIN_DIR . '/' . $dir;
		}
		include( $dir . $partName . '.php' );
	}
	
// Includes a "View" file (HTML template)
	private function getView( $name ) {
		if ( empty( $name ) )
			return false;
		ob_start();
		include( $this->view_dir . $name. '.php' );
		$text = ob_get_clean();
		return $text;
	}

// Replaces the shortcodes to PHP variables/methods in a "View" file (HTML template) and shows it
	public function view( $name, $content ) {
		if ( empty( $name ) ) return false;
		echo strtr( $this->getView( $name ), $content );
	}

}
$incder = new Includer;
$incder->getClasses();
if ( ! class_exists( 'RedirMe\Confire' ) ) {
	die( '<h1>Not available</h1>' );
}
$confire = new Confire;
$db = new Database( $confire->db_host, $confire->db_user, $confire->db_name, $confire->db_password, $confire->table_prefix );
$config = new Config;
$lang = new Language;
// "Main Menu" array for the "sidebarMenu()" method of the "Primary" class
$mainMenuLinks = array(
	'/' => $lang->_tr( 'Dashboard', 1 ) . '::manage_profile',
	'/?action=' . $config->actions['user-list'] => $lang->_tr( 'Users', 1 ) . '::manage_users',
	'/?action=' . $config->actions['options'] => $lang->_tr( 'Settings', 1 ) . '::set_options',
	'/?action=' . $config->actions['my-profile'] => $lang->_tr( 'My Profile', 1 ) . '::manage_profile',
	'/?do=logout' => $lang->_tr( 'Log Out', 1 ) . '::manage_profile',
);
// "Create Menu" array for the "sidebarMenu()" method of the "Primary" class
$createMenuLinks = array(
	'/?action=' . $config->actions['link-new'] => $lang->_tr( 'Link', 2 ) . '::manage_links',
	'/?action=' . $config->actions['cat-new'] => $lang->_tr( 'Category', 2 ) . '::manage_cats',
	'/?action=' . $config->actions['user-new'] => $lang->_tr( 'User', 2 ) . '::manage_users',
);
?>