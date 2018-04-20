<?php defined( '_INCLUDE' ) or die();
$uri = new RedirMe\URI;
$primar = new RedirMe\Primary;
$incder = new RedirMe\Includer;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Downloading File | <?= $primar->systemName(); ?></title>
		<link href="<?= $uri->home() . '/' . $incder->css_dir; ?>style.css" rel="stylesheet" type="text/css" />
		<link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,400i,700,700i&amp;subset=cyrillic" rel="stylesheet">
		<link rel="icon" href="<?= $uri->home() . '/' . $incder->img_dir; ?>favicon.png" type="image/png"/>
	</head>
	<body>
		<div class="message success">Downloading the file started!</div>
	</body>
</html>