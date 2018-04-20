<?php defined( '_INCLUDE' ) or die();
global $action, $id, $message;
$incder = new RedirMe\Includer;
$config = new Redirme\Config;
$cat = new RedirMe\Category;
$redirect = new RedirMe\Redirect;
$uri = new RedirMe\URI;
$user = new RedirMe\User;
$primary = new RedirMe\Primary;
$lang = new RedirMe\Language;
if ( ! $user->can( 'manage_links' ) ) {
	$user->cant();
}
if ( isset( $_POST['remove_links'] ) ) {
	if ( isset( $_POST['remove'] ) ) {
		$postsRemove = implode( ',', $_POST['remove'] );
		$redirect->remove( $postsRemove );
	} else {
		$uri->redirect( $uri->home() . "?action={$action}&id={$id}" );
	}
}
$pageTitle = 'Search Links';
$incder->getPart( 'header' );
$incder->getPart( 'wrapper' );
// Links Array
$query = ( ! empty( $_GET['s'] ) ) ? $_GET['s'] : null;
$links = $redirect->redirectSearch( $query );
$linkArticles = '';
$pattern = "/((?:^|>)[^<]*)(".$query.")/si";
$replace = '$1<b style="color:black; background:yellow;">$2</b>';
foreach ( $links as $link ) {
	$linkArticles .= "<article class=\"row\">
	<a href=\"{$uri->home()}/?action={$config->actions['link-edit']}&id={$link['link_id']}\" class=\"name\">" . preg_replace( $pattern, $replace, $link['link_title'] ) . "</a>
	<a href=\"{$uri->home()}/?action={$config->actions['cat']}&id={$link['link_category']}\" class=\"category\">{$cat->name( $link['link_category'] )}</a>
	<textarea class=\"target\" id=\"linkURLField_{$link['link_id']}\" onclick=\"$(this).select();\" readonly>{$redirect->uri( $link['link_id'] )}</textarea>
	<section class=\"author\">{$user->name( $link['link_author'] )}</section>
	<a href=\"javascript:void(0);\" class=\"copy\" data-clipboard-target=\"#linkURLField_{$link['link_id']}\">{$lang->_tr( 'Copy', 1 )}</a>
</article>\n";
}
if ( sizeof( $links ) == 0 || empty( $query ) ) {
	$linkArticles = "<div class=\"noItems\">{$lang->_tr( 'No search results', 1 )}</div>\n";
}
$content = array(
	'[name_label]' => $lang->_tr( 'Title', 1 ),
	'[category_label]' => $lang->_tr( 'Category', 1 ),
	'[target_label]' => $lang->_tr( 'Link URL address', 1 ),
	'[author_label]' => $lang->_tr( 'Author', 1 ),
	'[copy_label]' => $lang->_tr( 'Copy', 1 ),
	'[links]' => $linkArticles,
);
$incder->view( 'search', $content );
$incder->getPart( 'footer' ); ?>