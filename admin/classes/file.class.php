<?php
namespace Redirme;

class File {

// Uploads file(-s) to the upload directory
    public function upload( $product = null ) {
		$incder = new Includer;
		$config = new Config;
		$lang = new Language;
		$data = array();
		$error = false;
		$files = array();
		$dir = $incder->uploads_dir;
		$uploaddir = '../' . $dir . date( 'Y' );
		if ( ! is_dir( $uploaddir ) ) {
			mkdir( $uploaddir );
		}
		$uploaddir = $uploaddir . '/' . date( 'm' );
		if ( ! is_dir( $uploaddir ) ) {
			mkdir( $uploaddir );
		}
		foreach( $_FILES as $file ) {
			if ( ! in_array( mime_content_type( $file['tmp_name'] ), $config->allMimes() ) ) {
				$files[] = '<div class="message fail">' . $lang->_tr( 'The file has an unsupported format. For security reasons, we can\'t upload it.', 1 ) . '</div>';
			} else {
				$replace = array( ' ' => '_', '-' => '_' );
				if( move_uploaded_file( $file['tmp_name'], $uploaddir . '/' . strtr( basename( $file['name'] ), $replace ) ) ) {
					$files[] = '<div class="message success">' . $file['name'] . ' ' . $lang->_tr( 'was successfully uploaded', 1 ) . '</div>';
				} else {
					$error = true;
				}
			}
		}
		$data = $error ? array('error' => $errmess ) : array('files' => $files );
		echo json_encode( $data );
    }

// Gets max file upload size
	private function detectMaxUploadFileSize() {
		$normalize = function($size) {
			if (preg_match('/^(-?[\d\.]+)(|[KMG])$/i', $size, $match)) {
				$pos = array_search($match[2], array("", "K", "M", "G"));
				$size = $match[1] * pow(1024, $pos);
			} else {
				throw new Exception("Failed to normalize memory size '{$size}' (unknown format)");
			}
			return $size;
		};
		$limits = array();
		$limits[] = $normalize(ini_get('upload_max_filesize'));
		if (($max_post = $normalize(ini_get('post_max_size'))) != 0) {
			$limits[] = $max_post;
		}
		if (($memory_limit = $normalize(ini_get('memory_limit'))) != -1) {
			$limits[] = $memory_limit;
		}
		$maxFileSize = min($limits);
		return $maxFileSize;
	}

// Returns max file upload size and prevents it to mb
	public function maxUploadSize($maxDecimals = 3, $mbSuffix = " MB") {
		$size = $this->detectMaxUploadFileSize();
		$mbSize = round($size / 1024 / 1024, $maxDecimals);
		return preg_replace("/\\.?0+$/", "", $mbSize) . $mbSuffix;
	}
	
// Scans upload dir and shows the files in the File Picker
	public function scanDirs( $dir ) {
		$config = new Config;
		$files = array_diff( scandir( $dir ), $config->not_files );
		foreach ( $files as $file ) {
			if ( is_dir( "{$dir}{$file}" ) ) {
				$this->scanDirs( "{$dir}{$file}/" );
			} else {
				$dirValue = str_replace( '../', '', $dir );
				echo "<label>
						<span class=\"fmFormat\">{$file}</span>
						<input type=\"radio\" name=\"file\" class=\"fmCheckFile dn\" value=\"{$dirValue}{$file}\" />
					</label>\n";
			}
		}
	}
	
// Downloads a specified file without showing it's location in a browser
	public function fileDownload( $file ) {
		if (file_exists($file)) {
			if (ob_get_level()) {
				ob_end_clean();
			}
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			exit;
		}
	}
}