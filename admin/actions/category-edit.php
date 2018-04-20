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
if ( ! $user->can( 'manage_cats' ) ) {
	$user->cant();
}
if ( isset( $_POST['remove'] ) ) {
	$cat->remove();
	die();
}
if ( isset( $_POST['cat_do'] ) ) {
	$cat->update();
	die();
}
$an = $cat->an();
if ( empty( $an ) ) {
	$primary->e404();
}
// Messages
$messageBlock = null;
if ( $message ) {
	switch ( $message ) {
		case 'created':
			$messType = ' success';
			$messCont = 'The category was created successfully';
		break;
		case 'emptytitle':
			$messType = ' fail';
			$messCont = 'Enter the title';
		break;
		case 'numparent':
			$messType = ' fail';
			$messCont = 'Parent category must be a number';
		break;
		case 'updated':
			$messType = ' success';
			$messCont = 'The category was successfully updated';
		break;
		case 'err':
			$messType = ' fail';
			$messCont = 'Error updating the category';
		break;
		case 'noperm':
			$messType = ' fail';
			$messCont = 'You do not have permission to perform this operation';
		break;
		case 'errremoving':
			$messType = ' fail';
			$messCont = 'Error removing the category';
		break;
		default:
			$messCont = 'Non-existent notice';
	}
	$messageBlock = "<div class=\"message{$messType}\">{$lang->_tr( $messCont, 1 )}</div>\n";
}
$pageTitle = 'Edit Category';
$incder->getPart( 'header' );
$incder->getPart( 'wrapper' );
$content = array(
	'[message]' => $messageBlock,
	'[required]' => $lang->_tr( 'Required!', 1 ),
	'[max_input_length]' => $config->max_input_length,
	'[name_label]' => $lang->_tr( 'Title', 1 ),
	'[name_value]' => $an->category_name,
	'[paren_label]' => $lang->_tr( 'Parent Category', 1 ),
	'[no_parent]' => $lang->_tr( 'None', 1 ),
	'[parents]' => $cat->catsOptsParents( $id, $an->parent_id, null, null ),
	'[submit]' => $lang->_tr( 'Update', 1 ),
	'[remove]' => "<button type=\"submit\" name=\"remove\" class=\"submit red\">{$lang->_tr( 'Remove Permanently', 1 )}</button>\n",
);
$incder->view( 'category-do', $content );
$incder->getPart( 'footer' );
?>