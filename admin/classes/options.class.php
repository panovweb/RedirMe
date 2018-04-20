<?php
namespace RedirMe;
/**
* Options operations
*/
class Options {

// Returns the value of an option by it's name
	public function optionValue( $name ) {
		if ( empty( $name ) )
			return false;
		global $db;
		$get = $db->customQuery( "SELECT option_value FROM {$db->tablePrefix()}options WHERE option_name = '$name'" );
		if ( $get->num_rows ) {
			$object = $get->fetch_object();
			return $object->option_value;
		}
		return false;
	}

// Checks if an option's value = 'on'. Returns true/false.
	public function isOn( $name ) {
		if ( empty( $name ) ) {
			return false;
		}
		global $db;
		$get = $db->customQuery( "SELECT option_value FROM {$db->tablePrefix()}options WHERE option_name = '$name'" );
		if ( $get->num_rows ) {
			$object = $get->fetch_object();
			if ( $object->option_value === 'on' ) {
				return true;
			}
			return false;
		}
		return false;
	}

// Returns 'on' and 'off' option types as options for select box
	public function onOff( $status = null ) {
		$lang = new Language;
		$values = array(
			'on' => 'On',
			'off' => 'Off',
		);
		$return = '';
		foreach ( $values as $key => $value ) {
			$selected = null;
			if ( $status == $key ) {
				$selected = ' selected';
			}
			$return .= "<option value=\"{$key}\"{$selected}>{$lang->_tr( $value, 1 )}</option>\n";
		}
		return $return;
	}

// Updates an option. If it doesn't exists, creates it.
	public function modify( $name, $value, $required = null  ) {
		if ( empty( $name ) ) return false;
		if ( ! empty( $required ) && empty( $value ) ) return false;
		$value = trim( htmlspecialchars( stripslashes( $value ) ) );
		global $db;
		$get = $db->customQuery( "SELECT option_id FROM {$db->tablePrefix()}options WHERE option_name = '$name'" );
		$update = null;
		$insert = null;
		if ( $get->num_rows ) {
			$update = $db->customQuery( "UPDATE {$db->tablePrefix()}options SET option_value = '$value' WHERE option_name = '$name'" );
		} else {
			$insert = $db->customQuery( "INSERT INTO {$db->tablePrefix()}options SET option_name = '$name', option_value = '$value'" );
		}
		if ( $update == true || $insert == true ) {
			return true;
		}
		return false;
	}

}

?>