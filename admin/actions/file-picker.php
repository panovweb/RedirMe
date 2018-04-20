<?php defined( '_INCLUDE' ) or die();
global $action, $do;
$insert = ( isset( $_GET['insert'] ) ) ? $_GET['insert'] : null;
$file = new Redirme\File;
$incder = new Redirme\Includer;
$config = new Redirme\Config;
$uri = new Redirme\URI;
$lang = new Redirme\Language;
$dir = $incder->uploads_dir;
$product = null;
$getProduct = null;
$uploadButtonID = 'fmUploadAjax';
if ( isset( $_GET['upload'] ) ) {
	$file->upload( $product );
	die();
}
if ( isset( $_GET['delete'] ) ) {
	unlink( '../' . $_POST['file'] );
	die( '<div class="message success">' . $lang->_tr( 'The file was successfully removed. Expect...', 1 ) . '</div>' );
}
?>
<form method="post" action="" id="filePicker" onsubmit="return false;">
	<div class="title"><?= $lang->_tr( 'File Picker', 1 ); ?>
		<a href="javascript:void(0);" class="close" title="<?= $lang->_tr( 'Close', 1 ); ?>">&times;</a>
	</div>
	<div class="tabs">
		<a href="javascript:void(0);" rel="tab-1"><?= $lang->_tr( 'Upload', 1 ); ?></a>
		<a href="javascript:void(0);" rel="tab-2" class="defaulttab selected" onclick="$('#fileManager').empty().load('<?= $uri->_URI(); ?>');"><?= $lang->_tr( 'Library', 1 ); ?></a>
	</div>
	<section class="tab-content dn" id="tab-1">
		<label id="fmUploadLabel"><?= $lang->_tr( 'Upload file(s). Max file size:', 1 ) . ' ' . $file->maxUploadSize($maxDecimals = 2, $mbSuffix = " MB"); ?>
			<input type="file" class="dn" id="<?= $uploadButtonID; ?>" multiple="multiple">
		</label>
		<div class="ajax-respond"></div>
	</section>
	<section class="tab-content" id="tab-2">
		<section class="content">
<?php $file->scanDirs( '../' . $dir ); ?>
		</section>
		<aside class="aside">
			<section id="fmAsideContent">
			</section>
			<button type="button" class="remove long" id="fmDeletePerm" onclick="ajax_form_post( 'fmAsideContent', 'filePicker', '<?= $uri->home(); ?>/?action=<?= $action; ?>&delete=1' );setTimeout('$(\'#fileManager\').empty().load(\'<?= $uri->_URI(); ?>\')', 2000 );" disabled><?= $lang->_tr( 'Delete Permanently', 1 ); ?></button>
			<button type="button" class="button" id="fmInsertHere" onclick="$('#<?= $insert; ?>').empty();var valuex = $('.fmCheckFile:checked').val();var content = '<?= $uri->base(); ?>/' + valuex;$('#<?= $insert; ?>').val(content);$('#fileManager').fadeOut(200);$('body').css('overflow', 'auto');" disabled><?= $lang->_tr( 'Insert', 1 ); ?></button>
		</aside>
	</section>
</form>