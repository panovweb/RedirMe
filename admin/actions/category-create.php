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
if ( ! $user->can( 'manage_cats' ) ) {
	$user->cant();
}
if ( isset( $_POST['cat_do'] ) ) {
	$cat->add();
	die();
}
// Messages
$messageBlock = null;
if ( $message ) {
	switch ( $message ) {
		case 'emptytitle':
			$messType = ' fail';
			$messCont = 'Enter the title';
		break;
		case 'numparent':
			$messType = ' fail';
			$messCont = 'Parent category must be a number';
		break;
		case 'err':
			$messType = ' fail';
			$messCont = 'Error creating the category';
		break;
		default:
			$messCont = 'Non-existent notice';
	}
	$messageBlock = "<div class=\"message{$messType}\">{$lang->_tr( $messCont, 1 )}</div>\n";
}
$pageTitle = 'Create Category';
$incder->getPart( 'header' );
$incder->getPart( 'wrapper' );
$content = array(
	'[message]' => $messageBlock,
	'[required]' => $lang->_tr( 'Required!', 1 ),
	'[max_input_length]' => $config->max_input_length,
	'[name_label]' => $lang->_tr( 'Title', 1 ),
	'[name_value]' => null,
	'[paren_label]' => $lang->_tr( 'Parent Category', 1 ),
	'[no_parent]' => $lang->_tr( 'None', 1 ),
	'[parents]' => $cat->getCategoriesByOpts(),
	'[submit]' => $lang->_tr( 'Create', 1 ),
	'[remove]' => null,
);
$incder->view( 'category-do', $content );
$incder->getPart( 'footer' );
?>