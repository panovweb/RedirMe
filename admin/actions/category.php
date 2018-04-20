<?php defined( '_INCLUDE' ) or die();
global $action, $id, $message;
$incder = new RedirMe\Includer;
$config = new Redirme\Config;
$cat = new RedirMe\Category( $id );
$redirect = new RedirMe\Redirect;
$uri = new RedirMe\URI;
$user = new RedirMe\User;
$primary = new RedirMe\Primary;
$lang = new RedirMe\Language;
if ( ! $user->can( 'manage_links' ) ) {
	$user->cant();
}
$an = $cat->an();
if ( empty( $an ) && $id !== '0' ) {
	$primary->e404();
}
if ( isset( $_POST['remove_links'] ) ) {
	if ( isset( $_POST['remove'] ) ) {
		$postsRemove = implode( ',', $_POST['remove'] );
		$redirect->remove( $postsRemove );
	} else {
		$uri->redirect( $uri->home() . "?action={$action}&id={$id}" );
	}
}
if ( $id == '0' ) {
	$pageTitle = 'Without Category';
} else {
	$pageTitle = $an->category_name;
}
$incder->getPart( 'header' );
$incder->getPart( 'wrapper' );
// Links Array
$query = ( ! empty( $_POST['s'] ) ) ? $_POST['s'] : null;
if ( ! empty( $query ) ) {
	$links = $cat->redirectSearch( $query );
} else {
	$links = $cat->redirects();
}
$linkArticles = '';
$pattern = "/((?:^|>)[^<]*)(".$query.")/si";
$replace = '$1<b style="color:black; background:yellow;">$2</b>';
foreach ( $links as $link ) {
	$linkArticles .= "<article class=\"row\">
	<section class=\"check\">
		<input type=\"checkbox\" name=\"remove[]\" class=\"autoCheck\" value=\"{$link['link_id']}\" />
	</section>
	<a href=\"{$uri->home()}/?action={$config->actions['link-edit']}&id={$link['link_id']}\" class=\"name\">" . preg_replace( $pattern, $replace, $link['link_title'] ) . "</a>
	<textarea class=\"target\" id=\"linkURLField_{$link['link_id']}\" onclick=\"$(this).select();\" readonly>{$redirect->uri( $link['link_id'] )}</textarea>
	<section class=\"author\">{$user->name( $link['link_author'] )}</section>
	<section class=\"hits\">{$link['link_hits']}</section>
	<a href=\"javascript:void(0);\" class=\"copy\" data-clipboard-target=\"#linkURLField_{$link['link_id']}\">{$lang->_tr( 'Copy', 1 )}</a>
</article>\n";
}
if ( sizeof( $links ) == 0 ) {
	$noText = 'No links in the category';
	if ( $query ) {
		$noText = 'No search results';
	}
	$linkArticles = "<div class=\"noItems\">{$lang->_tr( $noText, 1 )}</div>\n";
}
// Disabled Remove Button
$disabled = ' disabled';
if ( $user->can( 'remove_links' ) ) {
	$disabled = null;
}
// Edit Cat Link
$editLink = null;
if ( $id !== '0' && $user->can( 'manage_cats' ) ) {
	$editLink = "<a href=\"{$uri->home()}/?action={$config->actions['cat-edit']}&id={$id}\" class=\"submit yellow\">{$lang->_tr( 'Edit Category', 1 )}</a>\n";
}
// Messages
$messageBlock = null;
if ( $message ) {
	switch ( $message ) {
		case 'unselected':
			$messType = ' fail';
			$messCont = 'Please, select links to remove';
		break;
		case 'noperm':
			$messType = ' fail';
			$messCont = 'You do not have permission to perform this operation';
		break;
		case 'removed':
			$messType = ' success';
			$messCont = 'Selected links were successfully removed';
		break;
		case 'err':
			$messType = ' fail';
			$messCont = 'Error removing links';
		break;
		default:
			$messCont = 'Non-existent notice';
	}
	$messageBlock = "<div class=\"message{$messType}\">{$lang->_tr( $messCont, 1 )}</div>\n";
}
$content = array(
	'[message]' => $messageBlock,
	'[%EDIT_CAT_URL%]' => $uri->home() . "/?action={$config->actions['cat-edit']}&id={$id}",
	'[%EDIT_CAT_TEXT%]' => $lang->_tr( 'Edit Category', 1 ),
	'[edit_cat_link]' => $editLink,
	'[remove]' => $lang->_tr( 'Remove Selected', 4 ),
	'[disabled]' => $disabled,
	'[search_placeholder]' => $lang->_tr( 'Search...', 1 ),
	'[query]' => $query,
	'[max_input_length]' => $config->max_input_length,
	'[name_label]' => $lang->_tr( 'Title', 1 ),
	'[target_label]' => $lang->_tr( 'Link URL address', 1 ),
	'[author_label]' => $lang->_tr( 'Author', 1 ),
	'[hits_label]' => $lang->_tr( 'Clicks', 1 ),
	'[copy_label]' => $lang->_tr( 'Copy', 1 ),
	'[links]' => $linkArticles,
);
$incder->view( 'category', $content );
$cat->paginator( $uri->home() . "/?action={$action}&id={$id}", $cat->redirectCount() );
$incder->getPart( 'footer' ); ?>