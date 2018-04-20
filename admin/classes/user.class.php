<?php
namespace RedirMe;
/**
* Operations with user
*/
class User {

	function __construct( $id = null ) {
		$this->id = $id;
	}
	
// Users Permissions
	public $permissions = array(
		'manage_profile' => 'Manage Personal Profile',
		'manage_users' => 'Manage Users',
		'remove_users' => 'Remove Users',
		'set_options' => 'Change System Settings',
		'update_system' => 'Update System',
		'manage_cats' => 'Manage Categories',
		'remove_cats' => 'Remove Categories',
		'manage_links' => 'Manage Links',
		'remove_links' => 'Remove Links',
		'manage_user_items' => 'Manage the items of all users',
	);

// Returns the current authorized user
	public function _user() {
		$user = ( empty( $_COOKIE['redir_user_id'] ) ) ? $_SESSION['redir_user_id'] : $_COOKIE['redir_user_id'];
		return $user;
	}

// Checks does a user authorized
	public function logged() {
		if ( isset( $_SESSION['redir_user_id'] ) && ! empty( $_SESSION['redir_user_id'] ) || isset( $_COOKIE['redir_user_id'] ) && ! empty( $_COOKIE['redir_user_id'] ) ) return true;
	}

// Authorizes a user in the System
	public function authorize() {
		$config = new Config;
		$uri = new URI;
		$sess = new Session;
		global $db;
		$loginScript = $uri->home() . '/login.php';
		$math = trim( htmlspecialchars( stripslashes( $_POST['math'] ) ) );
		if ( $_SESSION['math_captcha_answer'] != $math || ! is_numeric( $math ) ) {
			$uri->redirect( $loginScript . '?message=errcaptcha' );
			return;
		}
		$login = mb_strtolower( trim( htmlspecialchars( stripslashes( $_POST['uname'] ) ) ) );
		if ( empty( $login ) ) {
			$uri->redirect( $loginScript . '?message=emptyuname' );
			return;
		}
		$password = htmlspecialchars( $_POST['password'], ENT_QUOTES );
		if ( empty( $password ) ) {
			$uri->redirect( $loginScript . '?message=emptypass' );
			return;
		}
		$rememberMe = ( ! empty( $_POST['remember_me'] ) ) ? $_POST['remember_me'] : null;
		$getUser = $db->customQuery( "SELECT user_id,user_password FROM {$db->tablePrefix()}users WHERE user_login = '{$login}'" );
		$theUser = $getUser->fetch_object();
		if ( ! $getUser->num_rows || ! password_verify( $password, $theUser->user_password ) ) {
			$uri->redirect( $loginScript . '?message=err' );
			return;
		} else {
			if ( isset( $rememberMe ) ) {
				$sess->setCustomCookie( 'redir_user_id', $theUser->user_id, null );
				$sess->setCustomSession( 'we_set_cookie', '1' );
			} else {
				$sess->setCustomSession( 'redir_user_id', $theUser->user_id );
			}
			$uri->redirect( $uri->home() );
			return;
		}
	}

// Restores a user's password
	public function restore() {
		$uri = new URI;
		$opts = new Options;
		$lang = new Language;
		if ( $opts->isOn( 'demo_mode' ) ) {
			$uri->redirect( $uri->home() . '/login.php' );
			return;
		}
		global $db;
		$restoreScript = $uri->home() . '/login.php?do=restore';
		$math = trim( htmlspecialchars( stripslashes( $_POST['math'] ) ) );
		if ( $_SESSION['math_captcha_answer'] != $math || ! is_numeric( $math ) ) {
			$uri->redirect( $restoreScript . '&message=errcaptcha' );
			return;
		}
		$login = mb_strtolower( trim( htmlspecialchars( stripslashes( $_POST['uname'] ) ) ) );
		if ( empty( $login ) ) {
			$uri->redirect( $restoreScript . "&message=emptyuname" );
			return;
		}
		$email = trim( htmlspecialchars( stripslashes( $_POST['email'] ) ) );
		if ( empty( $email ) ) {
			$uri->redirect( $restoreScript . "&message=emptymail" );
			return;
		}
		if ( ! $this->isCorrectEmail( $email ) ) {
			$uri->redirect( $restoreScript . "&message=errmail" );
			return;
		}
		$getUser = $db->customQuery( "SELECT user_id FROM {$db->tablePrefix()}users WHERE user_login = '{$login}' AND user_email = '{$email}'" );
		if ( ! $getUser->num_rows ) {
			$uri->redirect( $restoreScript . "&message=nouser" );
			return;
		}
		$theUser = $getUser->fetch_object();
		$salt = $this->salt();
		$password = $this->makeRandomString( 20, 1, 1, 1, null );
		$newPassword = $this->toCrypt( $password, $salt );
		$update = $db->customQuery( "UPDATE {$db->tablePrefix()}users SET
			user_salt = '$salt',
			user_password = '$newPassword'
		WHERE user_id = '{$theUser->user_id}'" );
		$primary = new Primary;
		$systemName = $primary->systemName();
		$subject = "{$lang->_tr( 'Restoring the password', 1 )} | {$systemName}";
		$message = "<i>{$lang->_tr( 'Your new password:', 1 )} <strong>{$password}</strong><br /><br />\n";
		$headers = "Content-type: text/html; charset=utf-8 \r\n";
		$headers .= "From: {$systemName} <no-reply@{$uri->_domain()}>\r\n";
		if ( $update == true && $sendMail = mail( $email, $subject, $message, $headers ) ) {
			$uri->redirect( $restoreScript . "&message=sent" );
			return;
		}
		$uri->redirect( $restoreScript . "&message=errrestoring" );
		return;
	}

// Logs out a user from the system
	public function logout() {
		session_destroy();
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach ( $cookies as $cookie ) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
    		}
		}
	}

// Gets the data of a user by his ID
	public function an( $id = null ) {
		$id = ( empty( $id ) ) ? $this->id : $id;
		global $db;
		$get = $db->customQuery( "SELECT * FROM {$db->tablePrefix()}users WHERE user_id = '{$id}'" );
		return $get->fetch_object();
	}

/* Gets the array of all users.
* Sets the limit for the page navigation.
* Excludes only the user who installed the System.
*/
	public function all() {
		$config = new Config;
		global $db;
		$count = $config->users_per_page;
		$shift = 0;
		if ( isset( $_GET['page'] ) ) {
			$page = $_GET['page'];
			$shift = $count * ($page - 1);
		}
		$limit = "LIMIT $shift, $count";
		$get = $db->customQuery( "SELECT * FROM {$db->tablePrefix()}users WHERE user_permissions != 'all' $limit" );
		$return = array();
		while ( $array = $get->fetch_array() ) {
			$return[] = $array;
		}
		return $return;
	}

/*
* Searches for users by their login.
* Excludes only the user who installed the System.
*/
	public function searchUsers( $query ) {
		global $db;
		$get = $db->customQuery( "SELECT * FROM {$db->tablePrefix()}users WHERE user_login LIKE '%$query%' AND user_permissions != 'all'" );
		$return = array();
		while ( $array = $get->fetch_array() ) {
			$return[] = $array;
		}
		return $return;
	}

/*
* Counts all users in the System
* Excludes only the user who installed the System.
*/
	public function countUsers() {
		global $db;
		$get = $db->customQuery( "SELECT COUNT(*) FROM {$db->tablePrefix()}users WHERE user_permissions != 'all'" );
		$count = $get->fetch_row();
		return $count[0];
	}

// Returns user's display name
	public function name( $id = null ) {
		$id = ( empty( $id) ) ? $this->_user() : $id;
		global $db;
		$get = $db->customQuery( "SELECT user_login,user_firstname,user_lastname FROM {$db->tablePrefix()}users WHERE user_id = '{$id}'" );
		$obj = $get->fetch_object();
		$return = ( empty( $obj->user_firstname ) || empty( $obj->user_lastname ) ) ? $obj->user_login : $obj->user_firstname . ' ' . $obj->user_lastname;
		return $return;
	}
	
// Returns $permissions as options for select box
	public function perms( $current = null ) {
		$lang = new Language;
		$return = '';
		foreach ( $this->permissions as $key => $value ) {
			$checked = null;
			if ( ! empty( $current ) && is_array( $current ) && in_array( $key, $current ) ) {
				$checked = ' checked';
			}
			$return .= "<label class=\"checkLabel\"><input type=\"checkbox\" name=\"permission[]\" value=\"{$key}\"{$checked} />&nbsp;{$lang->_tr( $value, 1 )}</label>\n";
		}
		return $return;
	}

// Checks if user has access to a page/a section/an action
	public function can( $permission ) {
		if ( ! $this->logged() ) return false;
		global $db;
		$getPermissions = $db->customQuery( "SELECT user_permissions FROM {$db->tablePrefix()}users WHERE user_id = '{$this->_user()}'" );
		if ( ! $getPermissions->num_rows ) {
			return false;
		}
		$thePermissions = $getPermissions->fetch_object();
		if ( $thePermissions->user_permissions === 'all' ) {
			return true;
		}
		$userPermissions = explode( ',', $thePermissions->user_permissions );
		if ( in_array( $permission, $userPermissions ) ) {
			return true;
		}
		return false;
	}

// Returns the "Access Denied" page
	public function cant() {
		$incder = new Includer;
		$lang = new Language;
		global $pageTitle;
		$pageTitle = 'Access Denied';
		$incder->getPart( 'header' );
		$incder->getPart( 'wrapper' );
		echo "<div class=\"message fail\">{$lang->_tr( 'You don\'t have permissions to view this page', 1 )}</div>\n";
		$incder->getPart( 'footer' );
		die();
	}

// Checks if e-mail entered by a user is valid according to the template
	public function isCorrectEmail( $email ) {
		if ( preg_match( "/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,50})$/", $email ) ) {
			return true;
		}
	}

// Checks in the database that a user with the such login already exists
	public function isLoginExists( $login, $excludeID = null ) {
		if ( empty( $login ) ) return false;
		$and = null;
		if ( isset( $excludeID ) ) {
			$and = "AND user_id != '$excludeID'";
		}
		global $db;
		$get = $db->customQuery( "SELECT user_id FROM {$db->tablePrefix()}users WHERE user_login = '$login' $and" );
		if ( $get->num_rows ) return true;
	}
// Checks in the database that a user with the such e-mail already exists
	public function isExists( $email, $excludeID = null ) {
		if ( empty( $email ) ) return false;
		$and = null;
		if ( isset( $excludeID ) ) {
			$and = "AND user_id != '$excludeID'";
		}
		global $db;
		$get = $db->customQuery( "SELECT user_id FROM {$db->tablePrefix()}users WHERE user_email = '$email' $and" );
		if ( $get->num_rows ) return true;
	}

// Generates a random string of symbols by set parameters
	public function makeRandomString( $length, $numbers, $low_letters, $up_letters, $symbols ) {
		if ( ! isset( $length ) ) {
			return false;
		}
		if ( ! isset( $numbers ) && ! isset( $low_letters ) && ! isset( $up_letters ) && ! isset( $symbols ) ) {
			return false;
		}
		$num_sym = ( isset( $numbers ) ) ? '1234567890' : null;
		$low_sym = ( isset( $low_letters ) ) ? 'qazxswedcvfrtgbnhyujmkiolp' : null;
		$up_sym = ( isset( $up_letters ) ) ? 'QAZXSWEDCVFRTGBNHYUJMKIOLP' : null;
		$rand_sym = ( isset( $symbols ) ) ? '#$*(!)&:-+=' : null;
		$chars = $num_sym . $low_sym . $up_sym;
		$max = $length;
		$size = strlen( $chars )-1;
		$password = null;
		while ( $max-- ) {
			$password .= $chars[rand( 0, $size )];
		}
		return $password;
	}

// Generates a salt to crypt a password
	public function salt() {
		return '$2a$10$' . substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(), mt_rand()))), 0, 22) . '$';
	}

// Encrypts a password
	public function toCrypt( $password, $salt ) {
		return crypt( $password, $salt );
	}

// Creates a new one user
	public function add() {
		global $action, $db;
		$config = new Config;
		$uri = new URI;
		$login = trim( strtolower( htmlspecialchars( stripslashes( $_POST['uname'] ) ) ) );
		if ( empty( $login ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=emptyuname" );
			return;
		}
		if ( ! preg_match( '|^[A-Z0-9]+$|i', $login ) ) {
			$uri->redirect( $uri->home() . "?action={$action}&message=nolatinuname" );
			return;
    	}
		if ( $this->isLoginExists( $login ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=unameexists" );
			return;
		}
		$email = trim( htmlspecialchars( stripslashes( $_POST['email'] ) ) );
		if ( empty( $email ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=emptymail" );
			return;
		}
		if ( ! $this->isCorrectEmail( $email ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=errmail" );
			return;
		}
		if ( $this->isExists( $email ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=mailexists" );
			return;
		}
		$fname = trim( htmlspecialchars( stripslashes( $_POST['fname'] ) ) );
		if ( empty( $fname ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=emptyfname" );
			return;
		}
		$lname = trim( htmlspecialchars( stripslashes( $_POST['lname'] ) ) );
		if ( empty( $lname ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=emptylname" );
			return;
		}
		$perms = ( isset( $_POST['permission'] ) ) ? implode( ',', $_POST['permission'] ) : null;
		$salt = $this->salt();
		$password = $this->makeRandomString( 20, 1, 1, 1, null );
		$newPassword = $this->toCrypt( $password, $salt );
		$date = date( 'Y-m-d H:i:s' );
		$primary = new Primary;
		$insert = $db->customInsert( "INSERT INTO {$db->tablePrefix()}users SET
			user_login = '$login',
			user_email = '$email',
			user_salt = '$salt',
			user_password = '$newPassword',
			user_firstname = '$fname',
			user_lastname = '$lname',
			user_registered = '$date',
			user_permissions = '$perms'
		" );
		$systemName = $primary->systemName();
		$subject = "Welcome | {$systemName}";
		$message = "<h1>Welcome to {$systemName}</h1>\n";
		$message .= "<i>Your username:</i> <strong>{$login}</strong><br />\n";
		$message .= "<i>Your new password:</i> <strong>{$password}</strong><br /><br />\n";
		$message .= "Use this data to authorize in the system by this <a href=\"{$uri->home()}/login.php\">link</a>\n";
		$headers = "Content-type: text/html; charset=utf-8 \r\n";
		$headers .= "From: {$systemName} <no-reply@{$uri->_domain()}>\r\n";
		if ( $insert == true && $sendMail = mail( $email, $subject, $message, $headers ) ) {
			$uri->redirect( $uri->home() . "/?action={$config->actions['user-edit']}&id={$insert}&message=created" );
			return;
		}
		$uri->redirect( $uri->home() . "/?action={$action}&message=err" );
		return;
	}

// Updates an existing user
	public function update() {
		$id = $this->id;
		global $action, $db;
		$config = new Config;
		$uri = new URI;
		$login = mb_strtolower( trim( htmlspecialchars( stripslashes( $_POST['uname'] ) ) ) );
		if ( empty( $login ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=emptyuname" );
			return;
		}
		if ( ! preg_match( '|^[A-Z0-9]+$|i', $login ) ) {
			$uri->redirect( $uri->home() . "?action={$action}&id={$id}&message=nolatinuname" );
			return;
    	}
		if ( $this->isLoginExists( $login, $id ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=unameexists" );
			return;
		}
		$email = trim( htmlspecialchars( stripslashes( $_POST['email'] ) ) );
		if ( empty( $email ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=emptymail" );
			return;
		}
		if ( ! $this->isCorrectEmail( $email ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=errmail" );
			return;
		}
		if ( $this->isExists( $email, $id ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=mailexists" );
			return;
		}
		$fname = trim( htmlspecialchars( stripslashes( $_POST['fname'] ) ) );
		if ( empty( $fname ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=emptyfname" );
			return;
		}
		$lname = trim( htmlspecialchars( stripslashes( $_POST['lname'] ) ) );
		if ( empty( $lname ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=emptylname" );
			return;
		}
		$perms = ( isset( $_POST['permission'] ) ) ? implode( ',', $_POST['permission'] ) : null;
		$update = $db->customQuery( "UPDATE {$db->tablePrefix()}users SET
			user_login = '$login',
			user_email = '$email',
			user_firstname = '$fname',
			user_lastname = '$lname',
			user_permissions = '$perms'
		WHERE user_id = '$id'" );
		if ( $update == true ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=updated" );
			return;
		}
		$uri->redirect( $uri->home() . "/?action={$action}&message=err" );
		return;
	}

// Generates and sends a new password to a user
	public function newPassword( $email ) {
		if ( empty( $email ) ) return false;
		$id = $this->id;
		global $action, $db;
		$config = new Config;
		$uri = new URI;
		$salt = $this->salt();
		$password = $this->makeRandomString( 20, 1, 1, 1, null );
		$newPassword = $this->toCrypt( $password, $salt );
		$update = $db->customQuery( "UPDATE {$db->tablePrefix()}users SET
			user_salt = '$salt',
			user_password = '$newPassword'
		WHERE user_id = '$id'" );
		$primary = new Primary;
		$systemName = $primary->systemName();
		$subject = "New Password | {$systemName}";
		$message = "<i>Your new password: <strong>{$password}</strong><br /><br />\n";
		$message .= "Use your login and password you got to authorize in the system by this <a href=\"{$uri->home()}/login.php\">link</a>\n";
		$headers = "Content-type: text/html; charset=utf-8 \r\n";
		$headers .= "From: {$systemName} <no-reply@{$uri->_domain()}>\r\n";
		if ( $update == true && $sendMail = mail( $email, $subject, $message, $headers ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=sent" );
			return;
		}
		$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=errsending" );
		return;
	}

// Updates a user's profile
	public function updateProfile() {
		global $action, $db;
		$config = new Config;
		$uri = new URI;
		$opts = new Options;
		if ( $opts->isOn( 'demo_mode' ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=demomode" );
			return;
		}
		$email = trim( htmlspecialchars( stripslashes( $_POST['email'] ) ) );
		if ( empty( $email ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=emptymail" );
			return;
		}
		if ( ! $this->isCorrectEmail( $email ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=errmail" );
			return;
		}
		$fname = trim( htmlspecialchars( stripslashes( $_POST['fname'] ) ) );
		if ( empty( $fname ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=emptyfname" );
			return;
		}
		$lname = trim( htmlspecialchars( stripslashes( $_POST['lname'] ) ) );
		if ( empty( $lname ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=emptylname" );
			return;
		}
		$passSQL = null;
		if ( ! empty( $_POST['password'] ) ) {
			$password = htmlspecialchars( $_POST['password'], ENT_QUOTES );
			$repPassword = htmlspecialchars( $_POST['repeat'], ENT_QUOTES );
			if ( $password !== $repPassword ) {
				$uri->redirect( $uri->home() . "/?action={$action}&message=passmatch" );
				return;
			}
			$salt = $this->salt();
			$newPassword = $this->toCrypt( $repPassword, $salt );
			$passSQL = "user_salt = '$salt', user_password = '$newPassword',";
		}
		$update = $db->customQuery( "UPDATE {$db->tablePrefix()}users SET
			user_email = '$email',
			$passSQL
			user_firstname = '$fname',
			user_lastname = '$lname'
		WHERE user_id = '{$this->_user()}'" );
		if ( $update == true ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=updated" );
			return;
		}
		$uri->redirect( $uri->home() . "/?action={$action}&message=err" );
		return;	
	}

// Removes users selected by checkbox in the users page
	public function remove( $ids ) {
		global $action, $db;
		$user = new User;
		$uri = new URI;
		if ( empty( $ids ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=unselected" );
			return;
		}
		if ( ! $user->can( 'remove_users' ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=noperm" );
			return;
		}
		$remove = $db->customQuery( "DELETE FROM {$db->tablePrefix()}users WHERE user_id IN($ids) AND user_permissions != '*'" );
		if ( $remove == true ) {
			$removeCats = $db->customQuery( "DELETE FROM {$db->tablePrefix()}categories WHERE category_author IN($ids)" );
			$removeLinks = $db->customQuery( "DELETE FROM {$db->tablePrefix()}links WHERE link_author IN($ids)" );
			$uri->redirect( $uri->home() . "/?action={$action}&message=removed" );
			return;
		}
		$uri->redirect( $uri->home() . "/?action={$action}&message=err" );
		return;
	}

// Captcha. Returns a mathematical expression, the result of which the user must enter in a special field to prove that he is not a robot.
	public function mathCaptcha() {
		$lang = new Language;
		$association = array(
			'1' => 'one', '2' => 'two', '3' => 'three', '4' => 'four',
			'5' => 'five', '6' => 'six', '7' => 'seven', '8' => 'eight',
			'9' => 'nine', '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
			'13' => 'thirteen', '14' => 'fourteen', '15' => 'fifteen', '16' => 'sixteen',
			'17' => 'seventeen', '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty'
		);
		$digit1 = mt_rand( 1, 20 );
		$digit2 = mt_rand( 1, 20 );
		$cube = mt_rand( 0, 1 );
		$cube2 = mt_rand( 0, 1 );
		if ( $cube === 1 ) {
			$dis_dig1 = strtr( $digit1, $association );
			$dis_dig2 = $digit2;
		} else {
			$dis_dig1 = $digit1;
			$dis_dig2 = strtr( $digit2, $association );
		}
		$_SESSION['math_captcha_answer'] = null;
		if( $cube2 === 1 ) {
			if ( $digit1 < 10 && $digit2 < 10 ) {
				$_SESSION['math_captcha_answer'] = $digit1 * $digit2;
				$math = $lang->_tr( $dis_dig1, 1 ) . ' &times; ' . $lang->_tr( $dis_dig2, 1 );
			} else {
				$_SESSION['math_captcha_answer'] = $digit1 + $digit2;
				$math = $lang->_tr( $dis_dig1, 1 ) . ' + ' . $lang->_tr( $dis_dig2, 1 );
			}
		} else {
			if ( $digit1 > $digit2 ) {
				$_SESSION['math_captcha_answer'] = $digit1 - $digit2;
				$math = $lang->_tr( $dis_dig1, 1 ) . ' - ' . $lang->_tr( $dis_dig2, 1 );
			} else {
				$_SESSION['math_captcha_answer'] = $digit2 - $digit1;
				$math = $lang->_tr( $dis_dig2, 1 ) . ' - ' . $lang->_tr( $dis_dig1, 1 );
			}
		}
		return $math;
	}

// Shows the page navigation on a users page if amount of users more than was configured
	public function paginator( $url, $count ) {
	    if ( empty( $url ) || empty( $count ) ) return false;
		$config = new Config;
		$lang = new Language;
		$count_pages = $count / $config->users_per_page;
		if ( $count <= $config->users_per_page ) {
			return false;
		}
		$active = ( isset( $_GET['page'] ) ) ? $_GET['page'] : '1';
		$count_show_pages = 5;
		$url_page = $url . '&page=';
		if ( $count_pages > 1 ) {
			$left = $active - 1;
			$right = $count_pages - $active;
			if ($left < floor($count_show_pages / 2)) $start = 1;
			else $start = $active - floor($count_show_pages / 2);
			$end = $start + $count_show_pages - 1;
			if ($end > $count_pages) {
				$start -= ($end - $count_pages);
				$end = $count_pages;
				if ($start < 1) $start = 1;
			}
		}
?>
  <div class="pagination">
    <?php if ($active != 1) { ?>
      <a href="<?=$url?>"><?= $lang->_tr( 'First', 2 ); ?></a>
			<a href="<?php if ($active == 2) { ?><?=$url?><?php } else { ?><?=$url_page.($active - 1)?><?php } ?>">&larr;</a>
    <?php } ?>
    <?php for ($i = $start; $i <= $end; $i++) { ?>
      <?php if ($i == $active) { ?><span><?=$i?></span><?php } else { ?><a href="<?php if ($i == 1) { ?><?=$url?><?php } else { ?><?=$url_page.$i?><?php } ?>"><?=$i?></a><?php } ?>
    <?php } ?>
    <?php if ($active != $count_pages) { ?>
			<a href="<?=$url_page.($active + 1)?>">&rarr;</a>
      <a href="<?=$url_page.$count_pages?>"><?= $lang->_tr( 'Last', 2 ); ?></a>
    <?php } ?>
  </div>
<?php } // </paginator>

}
?>