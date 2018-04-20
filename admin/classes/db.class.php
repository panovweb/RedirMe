<?php
namespace RedirMe;
/**
* Database operations
*/
class Database {
	
	function __construct( $host = null, $user = null, $name = null, $password = null, $prefix = null ) {
		$this->host = $host;
		$this->user = $user;
		$this->name = $name;
		$this->password = $password;
		$this->prefix = $prefix;
	}

// Returns the System table prefix
	public function tablePrefix() {
		return $this->prefix;
	}

// Connects to the database
	private function openConnect() {
		$db = array();
		$db['prefix'] = $this->tablePrefix();
		$db['connect'] = new \mysqli( $this->host, $this->user, $this->password, $this->name );
		$db['connect']->set_charset( 'utf8' );
		return $db;
	}
	
// Makes a query to the database
	public function customQuery( $queryText ) {
		if ( empty( $queryText ) )
			return false;
		$db = $this->openConnect();
		$query = $db['connect']->query( $queryText );
		return $query;
	}
	
// Makes a query to the database. Returns the ID of inserted string if used "INSERT INTO" query method
	public function customInsert( $queryText ) {
		if ( empty( $queryText ) )
			return false;
		$db = $this->openConnect();
		$query = $db['connect']->query( $queryText );
		return $db['connect']->insert_id;
	}

}
?>