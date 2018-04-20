<?php
namespace RedirMe;
/**
* Operations with categories
*/
class Category {

	function __construct( $id = null ) {
		$this->id = $id;
	}
	
// Gets the data of a category by it's ID
	public function an( $id = null ) {
		$id = ( isset( $id ) ) ? $id : $this->id;
		global $db;
		$get = $db->customQuery("SELECT * FROM {$db->tablePrefix()}categories WHERE category_id = '$id' ");
		return $get->fetch_object();
	}
	
// Returns the name of a category which was got by it's ID
	public function name( $id = null ) {
		$id = ( isset( $id ) ) ? $id : $this->id;
		if ( $id === '0' ) {
			$lang = new Language;
			return $lang->_tr( 'Without Category', 1 );
		}
		$cat = $this->an( $id );
		return $cat->category_name;
	}
	
// Returns the hierarchy of redirect categories as links
	public function getCategoriesByLinks( $parentId = null, $plus = null ) {
		$parentId = ( empty( $parentId ) ) ? '0' : $parentId;
		global $db;
		$config = new Config;
		$uri = new URI;
		$lang = new Language;
		$user = new User;
		$where = "AND category_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$cats = '';
		if ( empty( $parentId ) ) {
			$requestUri = "/?action={$config->actions['cat']}&id=0";
			$current_active = null;
			if ( strripos( $uri->_URI(), $uri->home() . $requestUri ) !== false ) {
				$current_active = ' active';
			}
			$cats .= "<a href=\"{$uri->home()}{$requestUri}\" class=\"{$current_active}\">{$lang->_tr( 'Without Category', 1 )}</a>\n";
		}
		$getCats = $db->customQuery( "SELECT * FROM {$db->tablePrefix()}categories WHERE parent_id = '$parentId' $where" );
		$plus .= ( $parentId !== '0' ) ? '&#8212;&nbsp;' : $plus;
		while ( $catsArray = $getCats->fetch_array() ) {
			$current_active = null;
			$requestUri = "/?action={$config->actions['cat']}&id={$catsArray['category_id']}";
			if ( strripos( $uri->_URI(), $uri->home() . $requestUri ) !== false ) {
				$current_active = ' active';
			}
			$cats .= "<a href=\"{$uri->home()}{$requestUri}\" class=\"{$current_active}\">{$plus}{$catsArray['category_name']}</a>\n";
			$cats .= $this->getCategoriesByLinks( $catsArray['category_id'], $plus );
		}
		return $cats;
	}

// Returns the hierarchy of redirect categories as options for select box
	public function getCategoriesByOpts( $parentId = null, $plus = null, $current = null ) {
		$parentId = ( empty( $parentId ) ) ? '0' : $parentId;
		global $db;
		$user = new User;
		$where = "AND category_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$getCats = $db->customQuery( "SELECT * FROM {$db->tablePrefix()}categories WHERE parent_id = '$parentId' $where" );
		$plus = ( empty( $plus ) ) ? null : $plus;
		$cats = '';
		$plus .= ( $parentId !== '0' ) ? '&#8212;&nbsp;' : $plus;
		while ( $catsArray = $getCats->fetch_array() ) {
			$selected = null;
			if ( $current == $catsArray['category_id'] ) {
				$selected = ' selected';
			}
			$cats .= "<option value=\"{$catsArray['category_id']}\"{$selected}>{$plus}{$catsArray['category_name']}</option>\n";
			$cats .= $this->getCategoriesByOpts( $catsArray['category_id'], $plus, $current );
		}
		return $cats;
	}
	
/* 
* Returns the hierarchy of redirect categories as options for select box.
* Uses specially in category editing page, because excludes current category.
*/
	public function catsOptsParents( $id, $current, $parentId = null, $plus = null ) {
		$parentId = ( empty( $parentId ) ) ? '0' : $parentId;
		global $db;
		$user = new User;
		$where = "AND category_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$getCats = $db->customQuery( "SELECT * FROM {$db->tablePrefix()}categories WHERE category_id != '$id' AND parent_id = '$parentId' $where" );
		$cats = '';
		$plus .= ( $parentId !== '0' ) ? '&#8212;&nbsp;' : $plus;
		while ( $catsArray = $getCats->fetch_array() ) {
			$selected = null;
			if ( $current == $catsArray['category_id'] ) {
				$selected = ' selected';
			}
			$cats .= "<option value=\"{$catsArray['category_id']}\"{$selected}>{$plus}{$catsArray['category_name']}</option>\n";
			$cats .= $this->catsOptsParents( $id, $current, $catsArray['category_id'], $plus );
		}
		return $cats;
	}
	
/*
* Gets and returns an array of all redirects of the current category.
* Sets the limit for the page navigation.
*/
	public function redirects() {
		$id = $this->id;
		global $db;
		$config = new Config;
		$user = new User;
		$where = "AND link_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$count = $config->links_per_page;
		$shift = 0;
		if ( isset( $_GET['page'] ) ) {
			$page = $_GET['page'];
			$shift = $count * ($page - 1);
		}
		$limit = "LIMIT $shift, $count";
		$get = $db->customQuery( "SELECT * FROM {$db->tablePrefix()}links WHERE link_category = '$id' $where $limit" );
		$return = array();
		while ( $array = $get->fetch_array() ) {
			$return[] = $array;
		}
		return $return;
	}
	
// Count redirects in the current category
	public function redirectCount() {
		$id = $this->id;
		global $db;
		$user = new User;
		$where = "AND link_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$get = $db->customQuery( "SELECT COUNT(*) FROM {$db->tablePrefix()}links WHERE link_category = '$id' $where" );
		$count = $get->fetch_row();
		return $count[0];
	}
	
// Searches for redirects by their name in the current category
	public function redirectSearch( $query ) {
		$id = $this->id;
		global $db;
		$user = new User;
		$where = "AND link_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$get = $db->customQuery( "SELECT * FROM {$db->tablePrefix()}links WHERE link_category = '$id' AND link_title LIKE '%$query%' $where" );
		$return = array();
		while ( $array = $get->fetch_array() ) {
			$return[] = $array;
		}
		return $return;
	}
	
// Creates a new one category
	public function add() {
		global $action, $db;
		$config = new Config;
		$user = new User;
		$uri = new URI;
		$title = trim( htmlspecialchars( stripslashes( $_POST['title'] ) ) );
		if ( empty( $title ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=emptytitle" );
			return;
		}
		$parent = trim( htmlspecialchars( stripslashes( $_POST['parent'] ) ) );
		if ( ! is_numeric( $parent ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&message=numparent" );
			return;
		}
		$author = $user->_user();
		$insert = $db->customInsert( "INSERT INTO {$db->tablePrefix()}categories SET
			category_name = '$title',
			category_author = '$author',
			parent_id = '$parent'
		" );
		if ( $insert == true ) {
			$uri->redirect( $uri->home() . "/?action={$config->actions['cat-edit']}&id={$insert}&message=created" );
			return;
		}
		$uri->redirect( $uri->home() . "/?action={$action}&message=err" );
		return;
	}
	
// Updates an existing category
	public function update() {
		$id = $this->id;
		global $action, $db;
		$user = new User;
		$uri = new URI;
		$where = "AND category_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$title = trim( htmlspecialchars( stripslashes( $_POST['title'] ) ) );
		if ( empty( $title ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=emptytitle" );
			return;
		}
		$parent = trim( htmlspecialchars( stripslashes( $_POST['parent'] ) ) );
		if ( ! is_numeric( $parent ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=numparent" );
			return;
		}
		$update = $db->customQuery( "UPDATE {$db->tablePrefix()}categories SET
			category_name = '$title',
			parent_id = '$parent'
		WHERE category_id = '$id' $where" );
		if ( $update == true ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=updated" );
			return;
		}
		$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=err" );
		return;
	}
	
// Remove a category by clicking special button on it's editing page
	public function remove() {
		$id = $this->id;
		global $action, $db;
		$user = new User;
		$uri = new URI;
		if ( ! $user->can( 'remove_cats' ) ) {
			$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=noperm" );
			return;
		}
		$where = "AND category_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$get = $db->customQuery( "SELECT * FROM {$db->tablePrefix()}categories WHERE category_id = '$id' $where" );
		$object = $get->fetch_object();
		$remove = $db->customQuery( "DELETE FROM {$db->tablePrefix()}categories WHERE category_id = '$id' $where" );
		$update = $db->customQuery( "UPDATE {$db->tablePrefix()}categories SET parent_id = '{$object->parent_id}' WHERE parent_id = '$id'" );
		$updateLinks = $db->customQuery( "UPDATE {$db->tablePrefix()}links SET link_category = '{$object->parent_id}' WHERE link_category = '$id'" );
		if ( $remove == true && $update == true && $updateLinks == true ) {
			$uri->redirect( $uri->home() . "/?message=cat_removed" );
			return;
		}
		$uri->redirect( $uri->home() . "/?action={$action}&id={$id}&message=errremoving" );
		return;
	}
	
// Shows the page navigation on a category page if amount of links more than was configured
	public function paginator( $url, $count ) {
	    if ( empty( $url ) || empty( $count ) ) return false;
		$config = new Config;
		$lang = new Language;
		$count_pages = $count / $config->links_per_page;
		if ( $count <= $config->links_per_page ) {
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