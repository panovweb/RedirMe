<?php defined( '_INCLUDE' ) or die();
global $message;
$incder = new RedirMe\Includer;
$stat = new RedirMe\Stat;
$user = new RedirMe\User;
$lang = new RedirMe\Language;
if ( ! $user->can( 'manage_profile' ) ) {
	$user->cant();
}
$pageTitle = 'Dashboard';
$incder->getPart( 'header' );
$incder->getPart( 'wrapper' );
// Messages
$messageBlock = null;
if ( $message ) {
	switch ( $message ) {
		case 'cat_removed':
			$messType = ' success';
			$messCont = 'The category was successfully removed';
		break;
		default:
			$messCont = 'Non-existent notice';
	}
	$messageBlock = "<div class=\"message{$messType}\">{$lang->_tr( $messCont, 1 )}</div>\n";
}
$usersAmountRow = null;
if ( $user->can( 'manage_users' ) ) {
	$usersAmountRow = "<article class=\"row\">
		<section class=\"name\">{$lang->_tr( 'Users', 1 )}</section>
		<section class=\"value\">{$stat->countUsers()}</section>
	</article>\n";
}
// Content
$content = array(
	'[message]' => $messageBlock,
	'[stats_title]' => $lang->_tr( 'General Statistics', 1 ),
	'[name_label]' => $lang->_tr( 'Title', 1 ),
	'[value_label]' => $lang->_tr( 'Amount', 1 ),
	'[categories_label]' => $lang->_tr( 'Categories', 1 ),
	'[categories_value]' => $stat->countCats(),
	'[links_label]' => $lang->_tr( 'Links', 1 ),
	'[links_value]' => $stat->countLinks(),
	'[users_row]' => $usersAmountRow,
	'[chart_clicks_title]' => $lang->_tr( 'The ratio of the number of clicks by each link', 1 ),
	'[chart_link_label]' => $lang->_tr( 'Title', 1 ),
	'[chart_clicks_amount_label]' => $lang->_tr( 'Clicks Amount', 1 ),
	'[chart_clicks_data]' => $stat->linkHitCompare(),
	'[chart_title]' => $lang->_tr( 'The ratio of the number of links by category', 1 ),
	'[chart_cat_label]' => $lang->_tr( 'Category', 1 ),
	'[chart_amount_label]' => $lang->_tr( 'Links Amount', 1 ),
	'[chart_data]' => $stat->catCompare(),
);
$incder->view( 'index', $content );
$incder->getPart( 'footer' ); ?>