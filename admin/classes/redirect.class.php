<?php
namespace RedirMe;

/**
* Operations with redirects
*/
class Redirect {

	function __construct( $id = null ) {
		$this->id = $id;
	}
	
// Redirect Codes
	private $types = array(
		'301' => '301 - Moved Permanently',
		'302' => '302 - Moved Temporarily',
		'1001' => 'Link to the file',
	);
	
// Extra Features
	private $features = array(
		'delay' => 'Redirect with the delay',
		'password' => 'Password Protection',
	);
	
// Gets the data of a redirect by it's ID or Alias
	public function an( $ID = null ) {
		$id = ( isset( $ID ) ) ? $ID : $this->id;
		$where = "link_id = '$id'";
		if ( ! is_numeric( $id ) ) {
			$where = "link_alias = '$id'";
		}
		global $db;
		$get = $db->customQuery("SELECT * FROM {$db->tablePrefix()}links WHERE $where");
		if ( ! $get->num_rows ) {
			return false;
		}
		return $get->fetch_object();
	}
	
// Returns Redirect Codes as options for select box
	public function types( $current = null ) {
		$lang = new Language;
		$return = '';
		foreach ( $this->types as $key => $value ) {
			$selected = null;
			if ( $current == $key ) {
				$selected = ' selected';
			}
			$return .= "<option value=\"{$key}\"{$selected}>{$lang->_tr( $value, 1 )}</option>\n";
		}
		return $return;
	}
	
// Returns Extra Features as options for select box
	public function features( $current = null ) {
		$lang = new Language;
		$return = '';
		$return .= "<option value=\"0\">{$lang->_tr( 'Not Selected', 1 )}</option>\n";
		foreach ( $this->features as $key => $value ) {
			$selected = null;
			if ( $current == $key ) {
				$selected = ' selected';
			}
			$return .= "<option value=\"{$key}\"{$selected}>{$lang->_tr( $value, 1 )}</option>\n";
		}
		return $return;
	}
	
// Returns the permalink of a redirect
	public function uri( $id ) {
		if ( empty( $id ) ) return false;
		$uri = new URI;
		global $db;
		$get = $db->customQuery("SELECT link_id,link_alias FROM {$db->tablePrefix()}links WHERE link_id = '$id' ");
		$object = $get->fetch_object();
		$return = $uri->base() . "/{$object->link_id}";
		if ( ! empty( $object->link_alias ) ) {
			$return = $uri->base() . "/{$object->link_alias}";
		}
		return $return;
	}
	
// Searches for redirects by their name
	public function redirectSearch( $query ) {
		global $db;
		$user = new User;
		$where = "AND link_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$get = $db->customQuery( "SELECT * FROM {$db->tablePrefix()}links WHERE link_title LIKE '%$query%' $where" );
		$return = array();
		while ( $array = $get->fetch_array() ) {
			$return[] = $array;
		}
		return $return;
	}
	
// Checks in the database alias for a match with another redirect
	public function isAliasExists( $alias, $excludeID = null ) {
		if ( empty( $alias ) ) return false;
		$and = null;
		if ( isset( $excludeID ) ) {
			$and = "AND link_id != '$excludeID'";
		}
		global $db;
		$get = $db->customQuery( "SELECT link_id FROM {$db->tablePrefix()}links WHERE link_alias = '$alias' $and" );
		if ( $get->num_rows ) return true;
	}
	
// Gets the value of a redirect's meta entry by it's key and ID
	public function metaValue( $metaKey, $linkID = null ) {
		if ( empty( $metaKey ) ) return false;
		$id = ( isset( $linkID ) ) ? $linkID : $this->id;
		global $db;
		$get = $db->customQuery("SELECT meta_value FROM {$db->tablePrefix()}link_meta WHERE meta_key = '$metaKey' AND link_id = '$id' ");
		if ( ! $get->num_rows ) {
			return false;
		}
		$object = $get->fetch_object();
		return $object->meta_value;
	}
	
// Creates a new redirect
	public function add() {
		global $action, $db;
		$config = new Config;
		$uri = new URI;
		$user = new User;
		$title = trim( htmlspecialchars( stripslashes( $_POST['title'] ) ) );
		if ( empty( $title ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=emptytitle" );
			return;
		}
		$alias = mb_strtolower( trim( htmlspecialchars( stripslashes( $_POST['alias'] ) ) ) );
		if ( ! empty( $alias ) && ! preg_match( '|^[A-Z0-9_-]+$|i', $alias ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=nolatinalias" );
			return;
		}
		if ( $this->isAliasExists( $alias ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=aliasexists" );
			return;
		}
		$target = trim( htmlspecialchars( stripslashes( $_POST['target'] ) ) );
		if ( empty( $target ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=emptytarget" );
			return;
		}
		$category = trim( htmlspecialchars( stripslashes( $_POST['category'] ) ) );
		if ( ! is_numeric( $category ) ) {
			$category = '0';
		}
		$code = trim( htmlspecialchars( stripslashes( $_POST['code'] ) ) );
		if ( ! is_numeric( $code ) ) {
			$code = '301';
		}
		$extraFeatures = trim( htmlspecialchars( stripslashes( $_POST['meta-extra_features'] ) ) );
		$delayPage = null;
		$salt = null;
		$password = null;
		$dbPass = null;
		if ( $extraFeatures === 'delay' ) {
			$delayPage = trim( htmlspecialchars( stripslashes( $_POST['delay_page'] ) ) );
			if ( empty( $delayPage ) ) {
				$uri->redirect( $uri->home() . "/?action={$action}&message=emptydpage" );
				return;
			}
		} elseif ( $extraFeatures === 'password' ) {
			$password = htmlspecialchars( $_POST['password'], ENT_QUOTES );
			if ( ! empty( $password ) ) {
				$salt = $user->salt();
				$password = $user->toCrypt( $password, $salt );
				$dbPass = ",
				link_salt = '$salt',
				link_password = '$password'";
			} else {
				$uri->redirect( $uri->home() . "/?action={$action}&message=emptypass" );
				return;
			}
		}
		$author = $user->_user();
		$insert = $db->customInsert("INSERT INTO {$db->tablePrefix()}links SET
			link_title = '$title',
			link_alias = '$alias',
			link_target = '$target',
			link_delay_page = '$delayPage',
			link_category = '$category',
			link_code = '$code',
			link_author = '$author'
			$dbPass
		");
		foreach ( $_POST as $key => $value ) {
			if ( strripos( $key, 'meta-' ) !== false ) {
				$nameArray = explode( 'meta-', $key );
				$insertMeta = $db->customInsert("INSERT INTO {$db->tablePrefix()}link_meta SET
					meta_key = '$nameArray[1]',
					meta_value = '$value',
					link_id = '$insert'
				" );
			}
		}
		if ( $insert == true ) {
			$uri->redirect( $uri->home() . "/?action={$config->actions['link-edit']}&id={$insert}&message=created" );
			return;
		}
		$uri->redirect( $uri->home() . "/?action={$action}&message=err" );
		return;
	}
	
// Updates an existing redirect
	public function update() {
		$id = $this->id;
		global $action, $db;
		$config = new Config;
		$uri = new URI;
		$user = new User;
		$where = "AND link_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$title = trim( htmlspecialchars( stripslashes( $_POST['title'] ) ) );
		if ( empty( $title ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=emptytitle" );
			return;
		}
		$alias = mb_strtolower( trim( htmlspecialchars( stripslashes( $_POST['alias'] ) ) ) );
		if ( ! empty( $alias ) && ! preg_match( '|^[A-Z0-9_-]+$|i', $alias ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=nolatinalias" );
			return;
		}
		if ( $this->isAliasExists( $alias, $id ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=aliasexists" );
			return;
		}
		$target = trim( htmlspecialchars( stripslashes( $_POST['target'] ) ) );
		if ( empty( $target ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=emptytarget" );
			return;
		}
		$category = trim( htmlspecialchars( stripslashes( $_POST['category'] ) ) );
		if ( ! is_numeric( $category ) ) {
			$category = '0';
		}
		$code = trim( htmlspecialchars( stripslashes( $_POST['code'] ) ) );
		if ( ! is_numeric( $code ) ) {
			$code = '301';
		}
		$extraFeatures = trim( htmlspecialchars( stripslashes( $_POST['meta-extra_features'] ) ) );
		$delayPage = null;
		$salt = null;
		$password = null;
		$dbPass = null;
		if ( $extraFeatures === 'delay' ) {
			$delayPage = trim( htmlspecialchars( stripslashes( $_POST['delay_page'] ) ) );
			if ( empty( $delayPage ) ) {
				$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=emptydpage" );
				return;
			}
		} elseif ( $extraFeatures === 'password' ) {
			$password = htmlspecialchars( $_POST['password'], ENT_QUOTES );
			if ( ! empty( $password ) ) {
				$salt = $user->salt();
				$password = $user->toCrypt( $password, $salt );
				$dbPass = ",
				link_salt = '$salt',
				link_password = '$password'";
			}
		}
		$update = $db->customQuery( "UPDATE {$db->tablePrefix()}links SET
			link_title = '$title',
			link_alias = '$alias',
			link_target = '$target',
			link_delay_page = '$delayPage',
			link_category = '$category',
			link_code = '$code'
			$dbPass
		WHERE link_id = '$id' $where" );
		$removeMeta = $db->customQuery( "DELETE FROM {$db->tablePrefix()}link_meta WHERE link_id = '$id'" );
		foreach ( $_POST as $key => $value ) {
			if ( strripos( $key, 'meta-' ) !== false ) {
				$nameArray = explode( 'meta-', $key );
				$insertMeta = $db->customInsert("INSERT INTO {$db->tablePrefix()}link_meta SET
					meta_key = '$nameArray[1]',
					meta_value = '$value',
					link_id = '$id'
				" );
			}
		}
		if ( $update == true ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=updated" );
			return;
		}
		$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=err" );
		return;
	}
	
// Removes redirects selected by checkbox in the category page
	public function remove( $ids ) {
		global $action, $id, $db;
		$user = new User;
		$uri = new URI;
		if ( empty( $ids ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=unselected" );
			return;
		}
		if ( ! $user->can( 'remove_links' ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=noperm" );
			return;
		}
		$where = "AND link_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$remove = $db->customQuery( "DELETE FROM {$db->tablePrefix()}links WHERE link_id IN($ids) $where" );
		if ( $remove == true ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=removed" );
			return;
		}
		$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=err" );
		return;
	}

// Checks in the database if the redirect with such ID/Alias exists
	public function isExists( $ID ) {
		$where = "link_id = '$ID'";
		if ( ! is_numeric( $ID ) ) {
			$where = "link_alias = '$ID'";
		}
		global $db;
		$get = $db->customQuery( "SELECT link_id FROM {$db->tablePrefix()}links WHERE $where");
		if ( $get->num_rows ) {
			return true;
		}
		return false;
	}
	
// Makes redirection to the target page or gives a file to download
	public function move( $ID, $authorized = null ) {
		$incder = new Includer;
		$uri = new URI;
		if ( empty( $ID ) ) {
			$uri->redirect( $uri->home() );
			return;
		}
		$where = "link_id = '$ID'";
		if ( ! is_numeric( $ID ) ) {
			$where = "link_alias = '$ID'";
		}
		global $db;
		$get = $db->customQuery( "SELECT * FROM {$db->tablePrefix()}links WHERE $where");
		if ( ! $get->num_rows ) {
			$uri->redirect( $uri->base() );
			return;
		}
		$object = $get->fetch_object();
		$hits = $object->link_hits + 1;
		$extraFeatures = $this->metaValue( 'extra_features', $object->link_id );
		if ( $extraFeatures === 'password' && ! empty( $object->link_password ) && empty( $authorized ) ) {
			$incder->getPart( 'auth_link' );
			return;
		}
		$update = $db->customQuery( "UPDATE {$db->tablePrefix()}links SET link_hits = '$hits' WHERE $where");
		if ( $extraFeatures === 'delay' ) {
			$incder->getPart( 'link_delay' );
			return;
		}
		if ( $object->link_code === '1001' ) {
			$file = new File;
			$fileToDown = str_replace( $uri->base() . '/', '', $object->link_target );
			$file->fileDownload( $fileToDown );
			return;
		}
		header( "Location: {$object->link_target}", true, $object->link_code );
		return;
	}
	
// Verifies the password of the redirect if it was set. if it correct, makes redirection to the target page or gives a file to download
	public function processPassword() {
		$uri = new URI;
		$ID = $uri->process_GET();
		$scriptURI = $uri->base() . "/{$ID}";
		$math = trim( htmlspecialchars( stripslashes( $_POST['math'] ) ) );
		if ( $_SESSION['math_captcha_answer'] != $math || ! is_numeric( $math ) ) {
			$uri->redirect( $scriptURI . '?message=errcaptcha' );
			return;
		}
		$where = "link_id = '$ID'";
		if ( ! is_numeric( $ID ) ) {
			$where = "link_alias = '$ID'";
		}
		global $db;
		$get = $db->customQuery( "SELECT * FROM {$db->tablePrefix()}links WHERE $where");
		$object = $get->fetch_object();
		$password = htmlspecialchars( $_POST['password'], ENT_QUOTES );
		if ( password_verify( $password, $object->link_password ) ) {
			$this->move( $ID, 1 );
			return;
		}
		$uri->redirect( $scriptURI . '?message=err' );
		return;
	}

}
