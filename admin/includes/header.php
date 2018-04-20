<?php defined( '_INCLUDE' ) or die();
$config = new RedirMe\Config;
$uri = new RedirMe\URI;
$primar = new RedirMe\Primary;
$incder = new RedirMe\Includer;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		<title><?= $primar->pageTitle(); ?></title>
		<link href="<?= $uri->home() . '/' . $incder->css_dir; ?>style.css?version=<?= $config->product_version; ?>" rel="stylesheet" type="text/css" />
		<link href="<?= $uri->home() . '/' . $incder->js_dir; ?>chosen/chosen.css" rel="stylesheet" type="text/css" />
		<link href="<?= $uri->home() . '/' . $incder->js_dir; ?>icheck/skins/square/blue.css" rel="stylesheet" type="text/css" />
		<link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,400i,700,700i&amp;subset=cyrillic" rel="stylesheet">
		<script type="text/javascript" src="<?= $uri->home() . '/' . $incder->js_dir; ?>jquery.min.js"></script>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript" src="<?= $uri->home() . '/' . $incder->js_dir; ?>chosen/chosen.jquery.min.js"></script>
		<script type="text/javascript" src="<?= $uri->home() . '/' . $incder->js_dir; ?>icheck/icheck.min.js"></script>
		<script type="text/javascript" src="<?= $uri->home() . '/' . $incder->js_dir; ?>clipboard.min.js"></script>
		<link rel="icon" href="<?= $uri->home() . '/' . $incder->img_dir; ?>favicon.png" type="image/png"/>
	</head>
	<body><?= "\n"; ?>