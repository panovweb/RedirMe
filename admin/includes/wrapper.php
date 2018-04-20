<?php defined( '_INCLUDE' ) or die();
$incder = new RedirMe\Includer;
$primary = new RedirMe\Primary;
$uri = new Redirme\URI;
$opts = new RedirMe\Options;
$lang = new RedirMe\Language;
$query = ( ! empty( $_GET['s'] ) ) ? $_GET['s'] : null;
?>
		<header id="sysHead">
			<a href="<?= $uri->home(); ?>" id="sysNameDesc">
				<section>
					<div id="sysName"><?= $primary->systemName(); ?></div>
				</section>
			</a>
			<h1 id="sectionTitle">
				<?= $primar->sectionTitle(); ?>
			</h1>
			<a href="javascript:void(0);" id="openMobileMenu"><img src="<?= $uri->home() . '/' . $incder->img_dir; ?>menu.png" /></a>
		</header><?= "\n"; ?>
<?php $incder->getPart( 'sidebar' ); ?>
		<form method="get" action="<?= $uri->home(); ?>">
			<input type="hidden" name="action" value="search" />
			<input type="text" name="s" class="globalSearchField" value="<?= $query; ?>" placeholder="<?= $lang->_tr( 'Search links in all categories', 1 ); ?>" maxlength="300" required />
		</form>