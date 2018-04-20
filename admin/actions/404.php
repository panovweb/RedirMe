<?php defined( '_INCLUDE' ) or die();
header( "HTTP/1.0 404 Not Found" );
$incder = new RedirMe\Includer;
$lang = new RedirMe\Language;
$pageTitle = 'Page not found';
$incder->getPart( 'header' );
$incder->getPart( 'wrapper' );
?>
<div class="e404">
	<span class="smile">:(</span>
	<span class="text"><?= $lang->_tr( 'Sorry, but the requested page was not found. Use the navigation menu.', 1 ); ?></span>
</div>
<?php $incder->getPart( 'footer' ); ?>