<?php
namespace RedirMe;
/**
* System Configuration
* This class consists important configuration variables.
* DO NOT RECOMMENDED TO CHANGE SOMETHING HERE!
*/
class Config {

	public $actions = array(
		'link-new' => 'link-create',
		'link-edit' => 'link-edit',
		'cat-new' => 'category-create',
		'cat' => 'category',
		'cat-edit' => 'category-edit',
		'user-new' => 'user-create',
		'user-list' => 'user-list',
		'user-edit' => 'user-edit',
		'options' => 'settings',
		'my-profile' => 'profile',
		'copyrights' => 'copyrights',
	);
	
	public $not_files = array(
		'.',
		'..',
		'index.php',
		'.DS_Store'
	);
	
	private $image_formats = array(
		'jpg' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpe' => 'image/jpeg',
		'gif' => 'image/gif',
		'png' => 'image/png',
		'bmp' => 'image/bmp',
		'tif' => 'image/tiff',
		'tiff' => 'image/tiff',
		'ico' => 'image/x-icon'
	);
	private $video_formats = array(
		'asf' => 'video/x-ms-asf',
		'asx' => 'video/x-ms-asf',
		'wmv' => 'video/x-ms-wmv',
		'wmx' => 'video/x-ms-wmx',
		'wm' => 'video/x-ms-wm',
		'avi' => 'video/avi',
		'divx' => 'video/divx',
		'flv' => 'video/x-flv',
		'mov' => 'video/quicktime',
		'qt' => 'video/quicktime',
		'mpeg' => 'video/mpeg',
		'mpg' => 'video/mpeg',
		'mpe' => 'video/mpeg',
		'mp4' => 'video/mp4',
		'm4v' => 'video/mp4',
		'ogv' => 'video/ogg',
		'webm' => 'video/webm',
		'mkv' => 'video/x-matroska'
	);
	private $audio_formats = array(
		'mp3' => 'audio/mpeg',
		'm4a' => 'audio/mpeg',
		'm4b' => 'audio/mpeg',
		'ra' => 'audio/x-realaudio',
		'ram' => 'audio/x-realaudio',
		'wav' => 'audio/wav',
		'ogg' => 'audio/ogg',
		'oga' => 'audio/ogg',
		'mid' => 'audio/midi',
		'midi' => 'audio/midi',
		'wma' => 'audio/x-ms-wma',
		'wax' => 'audio/x-ms-wax',
		'mka' => 'audio/x-matroska'
	);
	private $misc_formats = array(
		'rtf' => 'application/rtf',
		'js' => 'application/javascript',
		'pdf' => 'application/pdf',
		'swf' => 'application/x-shockwave-flash',
		'class' => 'application/java',
		'tar' => 'application/x-tar',
		'zip' => 'application/zip',
		'gz' => 'application/x-gzip',
		'gzip' => 'application/x-gzip',
		'rar' => 'application/rar',
		'7z' => 'application/x-7z-compressed',
		'exe' => 'application/x-msdownload',
	);
	private $document_formats = array(
		'txt' => 'text/plain',
		'asc' => 'text/plain',
		'c' => 'text/plain',
		'cc' => 'text/plain',
		'h' => 'text/plain',
		'csv' => 'text/csv',
		'tsv' => 'text/tab-separated-values',
		'ics' => 'text/calendar',
		'rtx' => 'text/richtext',
		'css' => 'text/css',
		'htm' => 'text/html',
		'html' => 'text/html',
		'doc' => 'application/msword',
		'pot' => 'application/vnd.ms-powerpoint',
		'pps' => 'application/vnd.ms-powerpoint',
		'ppt' => 'application/vnd.ms-powerpoint',
		'wri' => 'application/vnd.ms-write',
		'xla' => 'application/vnd.ms-excel',
		'xls' => 'application/vnd.ms-excel',
		'xlt' => 'application/vnd.ms-excel',
		'xlw' => 'application/vnd.ms-excel',
		'mdb' => 'application/vnd.ms-access',
		'mpp' => 'application/vnd.ms-project',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
		'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
		'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
		'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
		'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
		'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
		'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		'ppsm' => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
		'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
		'potm' => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
		'ppam' => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
		'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
		'sldm' => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
		'onetoc' => 'application/onenote',
		'onetoc2' => 'application/onenote',
		'onetmp' => 'application/onenote',
		'onepkg' => 'application/onenote',
		'odt' => 'application/vnd.oasis.opendocument.text',
		'odp' => 'application/vnd.oasis.opendocument.presentation',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		'odg' => 'application/vnd.oasis.opendocument.graphics',
		'odc' => 'application/vnd.oasis.opendocument.chart',
		'odb' => 'application/vnd.oasis.opendocument.database',
		'odf' => 'application/vnd.oasis.opendocument.formula',
		'wp' => 'application/wordperfect',
		'wpd' => 'application/wordperfect',
		'key' => 'application/vnd.apple.keynote',
		'numbers' => 'application/vnd.apple.numbers',
		'pages' => 'application/vnd.apple.pages'
	);
	
	public $min_php_version = '5.6.0';
	public $product_version = '1.1.0';
	public $product_code_name = 'redirme';
	public $sys_name = 'RedirMe';
	public $company_uri = 'https://github.com/panovweb';
	public $company_name = 'PanovWeb';
	public $num_cookie_days = '90';
	public $pass_min_length = '6';
	public $pass_max_length = '150';
	public $max_input_length = '300';
	public $links_per_page = '10';
	public $users_per_page = '10';
	public $min_redirect_delay_time = '5';
	public $max_redirect_delay_time = '60';
	
	public function allMimes() {
		return array_merge( $this->image_formats, $this->video_formats, $this->audio_formats, $this->misc_formats, $this->document_formats );
	}

}