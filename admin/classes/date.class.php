<?php
namespace RedirMe;
/**
* Operations with user
*/
class Date {

	public $date_formats = array(
		'F j, Y',
		'Y-m-d',
		'm/d/Y',
		'd/m/Y',
		'd.m.Y'
	);

	public $time_formats = array(
		'g:i a',
		'g:i A',
		'H:i'
	);

// Returns $date_formats by options for select box
	public function dateFormats( $current = null ) {
		$return = '';
		foreach ($this->date_formats as $format ) {
			$selected = null;
			if ( $format == $current ) {
				$selected = ' selected';
			}
			$return .= "<option value=\"{$format}\"{$selected}>{$format} (" . date( $format ) . ")</option>\n";
		}
		return $return;
	}

// Returns $time_formats by options for select box
	public function timeFormats( $current = null ) {
		$return = '';
		foreach ($this->time_formats as $format ) {
			$selected = null;
			if ( $format == $current ) {
				$selected = ' selected';
			}
			$return .= "<option value=\"{$format}\"{$selected}>{$format} (" . date( $format ) . ")</option>\n";
		}
		return $return;
	}

// Converts specified date format to selected in the System settings
	public function dateFormat( $current ) {
		$opts = new Options;
		$format = $opts->optionValue( 'date_format' );
		$date = \DateTime::createFromFormat('Y-m-d H:i:s', $current );
		return $date->format( $format );
	}

// Converts specified time format to selected in the System settings
	public function timeFormat( $current ) {
		$opts = new Options;
		$format = $opts->optionValue( 'time_format' );
		$date = \DateTime::createFromFormat('Y-m-d H:i:s', $current );
		return $date->format( $format );
	}

}