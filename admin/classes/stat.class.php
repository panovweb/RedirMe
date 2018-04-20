<?php
namespace RedirMe;
/**
* Statistics
*/
class Stat {

// Counts categories created by authorized user or by all users
	public function countCats() {
		global $db;
		$user = new User;
		$where = "WHERE category_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$get = $db->customQuery( "SELECT COUNT(*) FROM {$db->tablePrefix()}categories $where" );
		$count = $get->fetch_row();
		return $count[0];
	}

// Counts redirects created by authorized user or by all users
	public function countLinks() {
		global $db;
		$user = new User;
		$where = "WHERE link_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$get = $db->customQuery( "SELECT COUNT(*) FROM {$db->tablePrefix()}links $where" );
		$count = $get->fetch_row();
		return $count[0];
	}

// Counts users
	public function countUsers() {
		global $db;
		$get = $db->customQuery( "SELECT COUNT(*) FROM {$db->tablePrefix()}users" );
		$count = $get->fetch_row();
		return $count[0];
	}

/*
* Returns the ratio of the number of clicks by each link created by authorized user or by all users
* Builds the area chart
*/
	public function linkHitCompare() {
		global $db;
		$user = new User;
		$lang = new Language;
		$where = "AND link_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$get = $db->customQuery( "SELECT link_id FROM {$db->tablePrefix()}links WHERE link_hits != '0' $where" );
		$return = '';
		while ( $array = $get->fetch_array() ) {
			$getLinkHits = $db->customQuery( "SELECT link_title,link_hits FROM {$db->tablePrefix()}links WHERE link_id = '{$array['link_id']}'" );
			$linkData = $getLinkHits->fetch_object();
			$return .= "['{$linkData->link_title}', {$linkData->link_hits}],";
		}
		return $return;
	}

/*
* Returns the ratio of the number of links by category created by authorized user or by all users
* Builds the area chart
*/
	public function catCompare() {
		global $db;
		$user = new User;
		$lang = new Language;
		$where = "WHERE category_author = '{$user->_user()}'";
		if ( $user->can( 'manage_user_items' ) ) {
			$where = null;
		}
		$get = $db->customQuery( "SELECT category_id,category_name FROM {$db->tablePrefix()}categories $where" );
		$return = '';
		$getLinks = $db->customQuery( "SELECT COUNT(*) FROM {$db->tablePrefix()}links WHERE link_category = '0'" );
		$countLinks = $getLinks->fetch_row();
		$return .= "['{$lang->_tr( 'Without Category', 1 )}', {$countLinks[0]}],";
		$countLinks = $getLinks->fetch_row();
		while ( $array = $get->fetch_array() ) {
			$getLinks = $db->customQuery( "SELECT COUNT(*) FROM {$db->tablePrefix()}links WHERE link_category = '{$array['category_id']}'" );
			$countLinks = $getLinks->fetch_row();
			$return .= "['{$array['category_name']}', {$countLinks[0]}],";
		}
		return $return;
	}

}