<?php defined( '_INCLUDE' ) or die();
global $message;
$uri = new RedirMe\URI;
$user = new RedirMe\User;
$primar = new RedirMe\Primary;
$incder = new RedirMe\Includer;
$redirect = new RedirMe\Redirect;
$lang = new RedirMe\Language;
$link = $uri->process_GET();
$an = $redirect->an( $link );
$delayTime = $redirect->metaValue( 'delay_time', $an->link_id );
$delayPage = $an->link_delay_page;
$linkCode = ( $an->link_code === '1001' ) ? '301' : $an->link_code;
$delayHeaderTime = $delayTime + 3;
header( "Refresh: {$delayHeaderTime}; url={$an->link_target}", true, $linkCode );
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		<title><?= $lang->_tr( 'Redirecting...', 1 ); ?> | <?= $primar->systemName(); ?></title>
		<script type="text/javascript" src="<?= $uri->home() . '/' . $incder->js_dir; ?>jquery.min.js"></script>
		<link rel="icon" href="<?= $uri->home() . '/' . $incder->img_dir; ?>favicon.png" type="image/png"/>
	</head>
	<body>
		<header id="redirectTimer">
			<?= $lang->_tr( 'You will be redirected after', 1 ); ?>
			&nbsp;<span id="countdown"><?= $delayTime; ?></span>&nbsp;
			<?= $lang->_tr( 'sec', 1 ); ?>.&nbsp;
			<a href="<?= $delayPage; ?>" id="visitPageBelow" target="_blank"><?= $lang->_tr( 'Visit the page below', 1 ); ?>&nbsp;&darr;</a>
		</header>
		<script type="text/javascript">
			var countdown = $('#countdown'),
			startFrom = <?= $delayTime; ?>;
			function startCountdown(){
				countdown.show();
				timer = setInterval(function(){
					countdown.text(startFrom--);
					if(startFrom < 0) {
						clearInterval(timer);
						countdown.text('<?= $lang->_tr( 'wait...', 1 ); ?>');
					}
				},1000);
			}
			$(document).ready ( function(){
				startCountdown();
				$('#frame').html('<iframe src="<?= $delayPage; ?>" id="delayPageFrame"></iframe>');
			});
		</script>
		<div id="frame"></div>
		<style type="text/css">
			html, body, input, textarea, button {font-family: 'Verdana', sans-serif;font-weight: 400;outline: none;}html, input, textarea {font-size: 13px;}b, strong {font-weight: 700;}html {background: #eee;}html, body {height: 100%;margin: 0;padding: 0;width: 100%;}#redirectTimer {background: black;color: #fff;float: left;font-size: 17px;height: 50px;line-height: 50px;text-align: center;width: 100%;}#redirectTimer #countdown {color: red;}#redirectTimer #visitPageBelow {color: #0BDA51;text-decoration: underline;}#redirectTimer #visitPageBelow:hover {color: orange;}#delayPageFrame {border: none;bottom: 0;height: -webkit-calc(100% - 50px);height: -moz-calc(100% - 50px);height: calc(100% - 50px);left: 0;overflow: auto;position: fixed;right: 0;top: 50px;width: 100%;}
		</style>
	</body>
</html>