<?php defined( '_INCLUDE' ) or die();

function getClasses() {
    $dirName = 'classes/';
    $dir = ADMIN_DIR . '/' . $dirName;
    $openDir = opendir( $dir );	
    while ( $classFile = readdir( $openDir ) ) {
        if ( strripos( $classFile, '.class.php' ) ) {
            $file = $dir . $classFile;
            require_once( $file );
        }
    }
}

getClasses();

function setupURI() {
    $uri = new Redirme\URI;
    $current = $uri->parsingURI();
    return $current['scheme'] . '://' . $_SERVER['HTTP_HOST'] . $current['path'];
}

function setupDomain() {
    $uri = new Redirme\URI;
    $current = $uri->parsingURI();
    return $current['host'];
}

function isCompatable() {
    $config = new Redirme\Config;
    if ( version_compare( phpversion(), $config->min_php_version, '>=' ) ) {
        return true;
    }
    return false;
}

function setLang() {
    if ( ! empty( $_SESSION['setup_language'] ) ) {
        return false;
    }
    $lang = new Redirme\Language;
    $browserLang = $lang->browserLang();
    $_SESSION['setup_language'] = 'en';
    foreach ( $lang->sys_langs as $name => $code ) {
        $posLang = null;
        $posLang = strripos( $browserLang, $code );
        if ( $posLang !== false ) {
            $_SESSION['setup_language'] = $code;
        }
    }
}

setLang();

function _tr( $phrase, $version ) {
    $siteLanguage = $_SESSION['setup_language'];
    $languageFile = ADMIN_DIR . '/files/langs/' . $siteLanguage . '.php';
    if ( ! file_exists( $languageFile ) ) {
        return $phrase;
    }
    include_once( $languageFile );
    foreach ( Redirme\Translation::$translations as $trVersion => $trArr ) {
        if ( $trVersion == $version ) {
            foreach ( $trArr as $originalPhrase => $needlePhrase ) {
                if ( $originalPhrase == $phrase )
                    return $needlePhrase;
            }
        }
    }
    return $phrase;
}

function dbConnect() {
    $uri = new Redirme\URI;
    $host = trim( htmlspecialchars( stripslashes( $_POST['host'] ) ) );
    $user = trim( htmlspecialchars( stripslashes( $_POST['user'] ) ) );
    $name = trim( htmlspecialchars( stripslashes( $_POST['name'] ) ) );
    $password = htmlspecialchars( $_POST['password'], ENT_QUOTES );
    $prefix = trim( htmlspecialchars( stripslashes( $_POST['prefix'] ) ) );
    $mysqli = new mysqli( $host, $user, $password, $name );
    if ( ! $mysqli->connect_errno ) {
        $_SESSION['db_host'] = $host;
        $_SESSION['db_user'] = $user;
        $_SESSION['db_name'] = $name;
        $_SESSION['db_password'] = $password;
        $_SESSION['db_table_prefix'] = $prefix;
        $mysqli->close();
        $uri->redirect( setupURI() . '?step=2' );
        return;
    }
    $mysqli->close();
    $uri->redirect( setupURI() . '?step=1&err=1' );
    return;
}

function isConnected() {
    if ( ! empty( $_SESSION['db_host'] ) &&
        ! empty( $_SESSION['db_user'] ) &&
        ! empty( $_SESSION['db_name'] ) &&
        ! empty( $_SESSION['db_password'] ) &&
        ! empty( $_SESSION['db_table_prefix'] )
    ) {
        return true;
    }
    return false;
}

function setUP() {
    $uri = new Redirme\URI;
    $user = new Redirme\User;
    $login = mb_strtolower( trim( htmlspecialchars( stripslashes( $_POST['login'] ) ) ) );
    if ( empty( $login ) ) {
        $uri->redirect( setupURI() . '?step=2&err=emptylogin' );
        return;
    }
    if ( ! preg_match( '|^[A-Z0-9]+$|i', $login ) ) {
        $uri->redirect( setupURI() . '?step=2&err=nolatinlogin' );
        return;
    }
    $email = trim( htmlspecialchars( stripslashes( $_POST['email'] ) ) );
    if ( empty( $email ) ) {
        $uri->redirect( setupURI() . '?step=2&err=emptymail' );
        return;
    }
    if ( ! $user->isCorrectEmail( $email ) ) {
        $uri->redirect( setupURI() . '?step=2&err=errmail' );
        return;
    }
    $password = htmlspecialchars( $_POST['password'], ENT_QUOTES );
    $salt = $user->salt();
    $password = $user->toCrypt( $password, $salt );
    if ( empty( $password ) ) {
        $uri->redirect( setupURI() . '?step=2&err=emptypass' );
        return;
    }
    $date = date( 'Y-m-d H:i:s' );
    $homeURI = ( substr( setupURI(), strlen( setupURI() )-1 ) == '/' ) ? substr( setupURI(), 0, strlen( setupURI() )-1 ) : setupURI();
    $language = trim( htmlspecialchars( stripslashes( $_POST['lang'] ) ) );
    $prefix = $_SESSION['db_table_prefix'];
    $db['connect'] = new mysqli( $_SESSION['db_host'], $_SESSION['db_user'], $_SESSION['db_password'], $_SESSION['db_name'] );
    $createUserTable = $db['connect']->query( "CREATE TABLE IF NOT EXISTS `{$prefix}users` (
        `user_id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `user_login` varchar(300) DEFAULT NULL,
        `user_email` varchar(300) DEFAULT NULL,
        `user_salt` varchar(300) DEFAULT NULL,
        `user_password` varchar(500) DEFAULT NULL,
        `user_firstname` varchar(300) DEFAULT NULL,
        `user_lastname` varchar(300) DEFAULT NULL,
        `user_registered` datetime DEFAULT NULL,
        `user_permissions` text DEFAULT NULL
      ) ENGINE=MyISAM;" );
    $createLinkTable = $db['connect']->query( "CREATE TABLE IF NOT EXISTS `{$prefix}links` (
        `link_id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `link_title` varchar(300) DEFAULT NULL,
        `link_alias` varchar(300) DEFAULT NULL,
        `link_target` varchar(300) DEFAULT NULL,
        `link_delay_page` varchar(300) DEFAULT NULL,
        `link_category` int NOT NULL DEFAULT '0',
        `link_code` int NOT NULL DEFAULT '301',
        `link_hits` int NOT NULL DEFAULT '0',
        `link_author` int NOT NULL DEFAULT '1',
        `link_salt` varchar(300) DEFAULT NULL,
        `link_password` varchar(300) DEFAULT NULL
      ) ENGINE=MyISAM;" );
    $createLinkMetaTable = $db['connect']->query( "CREATE TABLE IF NOT EXISTS `{$prefix}link_meta` (
        `meta_key` varchar(300) DEFAULT NULL,
        `meta_value` text DEFAULT NULL,
        `link_id` int NOT NULL DEFAULT '0'
      ) ENGINE=MyISAM;" );
    $createCategoryTable = $db['connect']->query( "CREATE TABLE IF NOT EXISTS `{$prefix}categories` (
        `category_id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `category_name` varchar(300) DEFAULT NULL,
        `category_author` int NOT NULL DEFAULT '1',
        `parent_id` int NOT NULL DEFAULT '0'
      ) ENGINE=MyISAM;" );
    $createOptionTable = $db['connect']->query( "CREATE TABLE IF NOT EXISTS `{$prefix}options` (
        `option_id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `option_name` varchar(300) DEFAULT NULL,
        `option_value` text DEFAULT NULL
      ) ENGINE=MyISAM;" );
    $insertAdmin = $db['connect']->query( "INSERT INTO `{$prefix}users` SET
        user_login = '$login',
        user_email = '$email',
        user_salt = '$salt',
        user_password = '$password',
        user_registered = '$date',
        user_permissions = 'all'
    " );
    $insertOptions = $db['connect']->query( "INSERT INTO `{$prefix}options` (option_name, option_value) VALUES
    	('home_uri', '$homeURI'),
    	('sys_language', '$language'),
    	('browser_language', 'off'),
    	('date_format', 'Y-m-d'),
    	('time_format', 'H:i'),
    	('test_mode', 'off'),
    	('demo_mode', 'off')
    " );
    $createConfigFile = fopen( 'config.php', 'w' );
    $configData = "<?php\n";
    $configData .= 'namespace RedirMe;
class Confire {
    public $db_host = \'' . $_SESSION['db_host'] . '\';
    public $db_name = \'' . $_SESSION['db_name'] . '\';
    public $db_user = \'' . $_SESSION['db_user'] . '\';
    public $db_password = \'' . $_SESSION['db_password'] . '\';
    public $table_prefix = \'' . $prefix . '\';
}
?>';
    fwrite( $createConfigFile, $configData );
    fclose( $createConfigFile );
    $current = $uri->parsingURI();
    $createHtAccessFile = fopen( '.htaccess', 'w' );
    $HtAccessFileData = "<IfModule mod_rewrite.c>\n";
    $HtAccessFileData .= "RewriteEngine On
RewriteBase {$current['path']}
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule . index.php [L]
</IfModule>
Options All -Indexes
ErrorDocument 404 /index.php
php_flag magic_quotes_gpc off
php_value default_charset utf-8
AddType 'text/html; charset=utf-8' .html .htm .shtml";
	fwrite( $createHtAccessFile, $HtAccessFileData );
	fclose( $HtAccessFileData );
    session_destroy();
    if ( $createUserTable == true &&
        $createLinkTable == true &&
        $createLinkMetaTable == true &&
        $createCategoryTable == true &&
        $createOptionTable == true &&
        $insertAdmin == true &&
        $insertOptions == true
    ) {
        $uri->redirect( $homeURI . '/' . ADMIN_DIR );
        return;
    }
    $uri->redirect( setupURI() . '?step=2&err=1' );
    return;

}
?>