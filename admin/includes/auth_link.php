<?php defined( '_INCLUDE' ) or die();
global $message;
$uri = new RedirMe\URI;
$user = new RedirMe\User;
$primar = new RedirMe\Primary;
$incder = new RedirMe\Includer;
$redirect = new RedirMe\Redirect;
$lang = new RedirMe\Language;
if ( isset( $_POST['password'] ) ) {
	$redirect->processPassword();
}
$messageBlock = null;
if ( $message ) {
	switch ( $message ) {
		case 'errcaptcha':
			$messType = ' fail';
			$messCont = 'Error in calculating';
		break;
		case 'err':
			$messType = ' fail';
			$messCont = 'Wrong Password';
		break;
		default:
			$messCont = 'Non-existent notice';
	}
	$messageBlock = "<div class=\"authMessage fail\">{$lang->_tr( $messCont, 1 )}</div>\n";
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		<title><?= $lang->_tr( 'Processing Redirect Password', 1 ); ?> | <?= $primar->systemName(); ?></title>
		<link href="<?= $uri->home() . '/' . $incder->css_dir; ?>style.css" rel="stylesheet" type="text/css" />
		<link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,400i,700,700i&amp;subset=cyrillic" rel="stylesheet">
		<link rel="icon" href="<?= $uri->home() . '/' . $incder->img_dir; ?>favicon.png" type="image/png"/>
	</head>
	<body>
		<form method="post" action="" id="authForm">
			<div class="authTitle"><?= $lang->_tr( 'Password Required', 1 ); ?></div>
			<div class="authMessage"><?= $lang->_tr( 'Please, enter the password of the redirect to see the target page.', 1 ); ?></div>
			<?= $messageBlock; ?>
			<label class="authLabel"><?= $lang->_tr( 'Password', 1 ); ?>
				<input type="password" name="password" class="authField" maxlength="150" required autofocus />
			</label>
			<label class="authLabel"><?= $user->mathCaptcha(); ?>
				<input type="number" name="math" class="authField" placeholder="<?= $lang->_tr( 'Answer in digits', 1 ); ?>" maxlength="2" required />
			</label>
			<div class="authCheckBlock">
				<button type="submit" class="submit fr"><?= $lang->_tr( 'Submit', 1 ); ?></button>
			</div>
		</form>
	</body>
</html>