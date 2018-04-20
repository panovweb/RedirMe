<?php defined( '_INCLUDE' ) or die();
require_once( 'core.php' );
$step = ( isset( $_GET['step'] ) ) ? $_GET['step'] : null;
$err = ( isset( $_GET['err'] ) ) ? $_GET['err'] : null;
$config = new Redirme\Config;
$lang = new Redirme\Language;
if ( $step === '1' & isset( $_POST['db_conn'] ) && isCompatable() ) {
    dbConnect();
    die();
}
if ( $step === '2' & isset( $_POST['set'] ) && isCompatable() ) {
    setUP();
    die();
}
?>
<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,400i,700,700i" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?= setupURI() . INSTALL_DIR; ?>/style.css" />
		<title><?= _tr( 'Installation', 1 ); ?> | <?= $config->sys_name; ?></title>
    </head>
    <body>
        <form method="post" action="" id="installForm">
<?php if ( ! $step ) { ?>
            <section class="title"><?= _tr( 'Beginning Installation', 1 ); ?></section>
            <h1 class="welcome"><?= _tr( 'Welcome to the Installation Master!', 1 ); ?></h1>
<?php if ( isCompatable() ) { ?>
            <p class="text success"><?= _tr( 'The server configuration is fine. Now we can proceed with the installation. To do this, click the button below.', 1 ); ?></p>
            <a href="<?= setupURI(); ?>?step=1" class="next">&#8594;</a>
<?php } else { ?>
            <p class="text fail"><?= _tr( 'Your version of PHP should be at least', 1 ) . ' ' . $config->min_php_version; ?></p>
<?php } ?>

<?php } // ! step
if ( $step === '1' && isCompatable() ) { ?>
            <section class="title"><?= _tr( 'Step 1 - Connecting to a database', 1 ); ?></section>
            <input type="hidden" name="db_conn" value="1" />
            <p class="text"><?= _tr( 'Now you need to enter the data to connect to the MySQL database.', 1 ); ?></p>
<?php if ( $err ) { ?>
            <p class="text fail"><?= _tr( 'Error connection with the database!', 1 ); ?></p>
<?php } ?>
            <label class="label"><?= _tr( 'Database Host', 1 ); ?>
                <input type="text" name="host" class="field" placeholder="localhost" value="localhost" maxlength="300" required autofocus />
            </label>
            <label class="label"><?= _tr( 'Database User', 1 ); ?>
                <input type="text" name="user" class="field" placeholder="root" maxlength="300" required />
            </label>
            <label class="label"><?= _tr( 'Database Name', 1 ); ?>
                <input type="text" name="name" class="field" placeholder="<?= mb_strtolower( $config->sys_name ); ?>" maxlength="300" required />
            </label>
            <label class="label"><?= _tr( 'Database Password', 1 ); ?>
                <input type="password" name="password" class="field" maxlength="500" />
            </label>
            <label class="label"><?= _tr( 'Table Prefix (optional)', 1 ); ?>
                <input type="text" name="prefix" class="field" value="<?= mb_strtolower( $config->sys_name ); ?>_" maxlength="300" />
            </label>
            <a href="<?= setupURI(); ?>" class="back">&#8592;</a>
            <button type="submit" class="next">&#8594;</button>
<?php } // step 1
if ( $step === '2' && isCompatable() && isConnected() ) {
    $message = null;
    switch( $err ) {
        case 'emptylogin':
            $message = 'Enter Your Username';
        break;
        case 'nolatinlogin':
            $message = 'Username must consist only of Latin symbols and digits';
        break;
        case 'emptymail':
            $message = 'Enter Your E-mail';
        break;
        case 'errmail':
            $message = 'Incorrect E-mail';
        break;
        case 'emptypass':
            $message = 'Enter Your Password';
        break;
        case '1':
            $message = 'Error in the installation process';
        break;
        default:
            $message = 'Non-existent notice';
    }
?>
            <section class="title"><?= _tr( 'Step 2 - Setting up parameters', 1 ); ?></section>
            <input type="hidden" name="set" value="1" />
            <p class="text success"><?= _tr( 'Congratulations! We managed to connect to the database. Now it remains to configure several parameters.', 1 ); ?></p>
<?php if ( $err ) { ?>
            <p class="text fail"><?= _tr( $message, 1 ); ?></p>
<?php } ?>
            <label class="label"><?= _tr( 'Admin Username', 1 ); ?>
                <input type="text" name="login" class="field" id="loginField" placeholder="admin" maxlength="150" required autofocus />
            </label>
            <label class="label"><?= _tr( 'Admin E-mail', 1 ); ?>
                <input type="text" name="email" class="field" placeholder="admin@<?= setupDomain(); ?>" maxlength="150" required />
            </label>
            <label class="label"><?= _tr( 'Admin Password', 1 ); ?>
                <input type="password" name="password" class="field"  maxlength="500" required />
            </label>
            <div class="checks">
                <span class="checkLabel"><?= _tr( 'System Language', 1 ); ?></span>
                <select name="lang">
                    <?= $lang->selects(); ?>
                </select>
            </div>
            <a href="<?= setupURI(); ?>?step=1" class="back">&#8592;</a>
            <button type="submit" class="next"><?= _tr( 'Install', 1 ); ?></button>
<?php } // step 2 ?>
        </form>
    </body>
</html>