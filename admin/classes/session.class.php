<?php
namespace RedirMe;
/**
* Setting sessions & cookies
*/
class Session {

// Sets custom session
	public function setCustomSession( $name, $value ) {
		if ( empty( $name ) || empty( $value ) ) return false;
		$_SESSION[$name] = $value;
	}

// Sets custom cookie
	public function setCustomCookie( $name, $value, $daysAmount ) {
		if ( empty( $name ) || empty( $value ) ) return false;
		$config = new Config;
		$amount = ( empty( $daysAmount ) ) ? $config->num_cookie_days : $daysAmount;
		setcookie( $name, $value, time() + 60*60*24*$amount );
	}

}
?>