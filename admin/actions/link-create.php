<?php defined( '_INCLUDE' ) or die();
global $action, $message;
$incder = new RedirMe\Includer;
$config = new Redirme\Config;
$opts = new RedirMe\Options;
$uri = new RedirMe\URI;
$user = new RedirMe\User;
$lang = new RedirMe\Language;
$cat = new RedirMe\Category;
$redirect = new RedirMe\Redirect;
if ( ! $user->can( 'manage_links' ) ) {
	$user->cant();
}
if ( isset( $_POST['link_do'] ) ) {
	$redirect->add();
	die();
}
$pageTitle = 'Create Link';
$incder->getPart( 'header' );
$incder->getPart( 'wrapper' );
$messageBlock = null;
if ( $message ) {
	switch ( $message ) {
		case 'emptytitle':
			$messType = ' fail';
			$messCont = 'Enter the title';
		break;
		case 'nolatinalias':
			$messType = ' fail';
			$messCont = 'The alias should consist only of Latin characters, numbers, dashes and underscores.';
		break;
		case 'aliasexists':
			$messType = ' fail';
			$messCont = 'The link with such alias already exists in the system';
		break;
		case 'emptypass':
			$messType = ' fail';
			$messCont = 'Set the password';
		break;
		case 'emptytarget':
			$messType = ' fail';
			$messCont = 'Enter the target page URL';
		break;
		case 'emptydpage':
			$messType = ' fail';
			$messCont = 'Enter the delay page URL';
		break;
		case 'err':
			$messType = ' fail';
			$messCont = 'Error creating the link';
		break;
		default:
			$messCont = 'Non-existent notice';
	}
	$messageBlock = "<div class=\"message{$messType}\">{$lang->_tr( $messCont, 1 )}</div>\n";
}
$content = array(
	'[message]' => $messageBlock,
	'[name_label]' => $lang->_tr( 'Title', 1 ),
	'[name_value]' => null,
	'[required]' => $lang->_tr( 'Required!', 1 ),
	'[max_input_length]' => $config->max_input_length,
	'[alias_label]' => $lang->_tr( 'Alias', 1 ),
	'[alias_value]' => null,
	'[alias_help]' => $lang->_tr( 'Leave this field empty, if you want to use the ID as alias or write some text and people will see this text in their browsers. Use only lowercase English letters and numbers!', 1 ),
	'[extra_features_label]' => $lang->_tr( 'Extra Features', 1 ),
	'[extra_features]' => $redirect->features(),
	'[delay_page_label]' => $lang->_tr( 'Delay Page', 1 ),
	'[delay_page_help]' => $lang->_tr( 'Specify the page that will be displayed while the timer counts. It can be a page advertising any of your projects or products. If a person will likes the content of the page, he can visit this page by clicking special link, or he will be redirected to the target page when the timer will end the count.', 1 ),
	'[delay_page_value]' => null,
	'[delay_time_label]' => $lang->_tr( 'Delay time on the page (in seconds)', 1 ),
	'[delay_time_value]' => $config->min_redirect_delay_time,
	'[delay_time_min]' => $config->min_redirect_delay_time,
	'[delay_time_max]' => $config->max_redirect_delay_time,
	'[files_url]' => $uri->home() . '/?action=file-picker&insert=targetField',
	'[file_label]' => $lang->_tr( 'Add a File', 1 ),
	'[target_label]' => $lang->_tr( 'Target Page', 1 ),
	'[target_value]' => null,
	'[target_help]' => $lang->_tr( 'Specify the target page to be redirected to.', 1 ) . '<br />' . $lang->_tr( 'For example', 1 ) . ':<br />http://example.com/example-page.html',
	'[category_label]' => $lang->_tr( 'Category', 1 ),
	'[no_category_option]' => $lang->_tr( 'Without Category', 1 ),
	'[categories]' => $cat->getCategoriesByOpts(),
	'[type_label]' => $lang->_tr( 'Type of redirect', 1 ),
	'[types]' => $redirect->types(),
	'[set_password_label]' => $lang->_tr( 'Protect by Password', 1 ),
	'[checked_attr]' => null,
	'[dn_class]' => 'dn',
	'[password_label]' => $lang->_tr( 'Password', 1 ),
	'[password_holder]' => null,
	'[max_pass_length]' => $config->pass_max_length,
	'[submit_button]' => $lang->_tr( 'Create', 1 ),
);
$incder->view( 'link-do', $content );
$incder->getPart( 'footer' ); ?>