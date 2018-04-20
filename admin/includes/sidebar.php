<?php defined( '_INCLUDE' ) or die();
global $mainMenuLinks, $createMenuLinks;
$config = new RedirMe\Config;
$primar = new RedirMe\Primary;
$uri = new RedirMe\URI;
$user = new RedirMe\User;
$lang = new RedirMe\Language;
$cat = new RedirMe\Category;
?>
		<aside id="leftBar">
			<div class="leftBarTitle"><?= $lang->_tr( 'Main menu', 1 ); ?></div>
			<nav class="leftBarNav">
<?= $primar->sidebarMenu( $mainMenuLinks ); ?>
			</nav>
			<div class="leftBarTitle"><?= $lang->_tr( 'Create', 1 ); ?></div>
			<nav class="leftBarNav">
<?= $primar->sidebarMenu( $createMenuLinks ); ?>
			</nav>
<?php if ( $user->can( 'manage_links' ) ) : ?>
			<div class="leftBarTitle"><?= $lang->_tr( 'Categories', 1 ); ?></div>
			<nav class="leftBarNav">
<?= $cat->getCategoriesByLinks( null, null ); ?>
			</nav>
<?php endif; ?>
		</aside>
		<section id="mainContent"><?= "\n"; ?>