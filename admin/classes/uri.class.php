<?php
namespace RedirMe;
/**
* Parsing URI
*/
class URI {

// Checks and gets the Request Scheme (http:// or https://)
	private function getRequestScheme() {
		if ( isset( $_SERVER['HTTPS'] ) ) {
			$scheme = $_SERVER['HTTPS'];
		} else {
			$scheme = '';
		}
		if ( ( $scheme ) && ( $scheme != 'off' ) ) {
			$scheme = 'https';
		} else {
			$scheme = 'http';
		}
		return $scheme;
	}
	
// Parses current URL and returns it's components
	public function parsingURI() {
		$uriArr = parse_url( $this->getRequestScheme() . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		return $uriArr;
	}
	
// Parses URL in "User Friendly URLs" format
	public function parse_GET() {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			$P = array();
		} else {
			$url = $_SERVER['REQUEST_URI'];
			if ( $url[0] == '/' ) {
				$url = substr( $url, 1 );
			}
			if ( $url[strlen( $url )-1] == '/' ) {
				$url = substr( $url, 0, -1 );
			}
			$url = explode( '/', $url );
			$tmp = count( $url )-1;
			$P = array();
			$j = 0;
			for ( $i = 0; $i <= $tmp; $i++ ) {
				if ( ! empty( $url[$i] ) ) {
					$P[$j] = $url[$i];
					$j++;
				}
			}
		}
		return $P;
	}
	
// Parses URL and gets the redirect alias/ID to make correct redirection
	public function process_GET() {
		$urls = $this->parse_GET();
		$end = end( $urls );
		if ( isset( $_GET ) ) {
			$currAliasArr = explode( '?', $end );
			$currAlias = $currAliasArr[0];
			return $currAlias;
		}
		return false;
	}
	
// Gets current URL
	public function _URI() {
		return $this->getRequestScheme() . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
	
// Returns the admin's main page
	public function home() {
		$opts = new Options;
		return $opts->optionValue( 'home_uri' ) . '/' . ADMIN_DIR;
	}

// Returns the script main page
	public function base() {
		$opts = new Options;
		return $opts->optionValue( 'home_uri' );
	}
	
// Returns the domain (host) where script is located
	public function _domain() {
		$eURI = new eURI;
		$path = $eURI->parsingURI( $this->_URI() );
		return $path['host'];
	}
	
// Redirects to the specified address
	public function redirect( $location ) {
		header( 'Location: ' . $location );
		die();
	}

}


/**
* Extendable Parsing URI
*/
class eURI extends URI {

// Parses specified URL and returns it's components
	public function parsingURI( $uri = null ) {
		if ( empty( $uri ) ) return false;
		$uriArr = parse_url( $uri );
		return $uriArr;
	}

}

?>