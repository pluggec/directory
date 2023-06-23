<?php
ob_start();
session_start();
error_reporting(0);
if(file_exists("./install.php")) {
	header("Location: ./install.php");
} 
include("includes/config.php");
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
$db->set_charset("utf8");
$settingsQuery = $db->query("SELECT * FROM bit_settings ORDER BY id DESC LIMIT 1");
$settings = $settingsQuery->fetch_assoc();
include("includes/functions.php");
include(getLanguage($settings['url'],null,null));

if(isset($_GET['refid'])) {
	$_SESSION['refid'] = protect($_GET['refid']);
	header("Location: $settings[url]");
}
check_unpayed();
if(checkSession()) {
	check_user_verify_status();
}

include("sources/header.php");
$a = protect($_GET['a']);
switch($a) {
	case "account": include("sources/account.php"); break;
	case "login": include("sources/login.php"); break;
	case "register": include("sources/register.php"); break;
	case "track": include("sources/track.php"); break;
	case "testimonials": include("sources/testimonials.php"); break;
	case "affiliate": include("sources/affiliate.php"); break;
	case "contact": include("sources/contact.php"); break;
	case "about": include("sources/about.php"); break;
	case "faq": include("sources/faq.php"); break;
	case "page": include("sources/page.php"); break;
	case "exchange": include("sources/exchange.php"); break;
	case "search": include("sources/search.php"); break;
	case "password": include("sources/password.php"); break;
	case "email-verify": include("sources/email-verify.php"); break;
	case "logout": 
		unset($_SESSION['bit_uid']);
		unset($_COOKIE['bitexchanger_uid']);
		setcookie("bitexchanger_uid", "", time() - (86400 * 30), '/'); // 86400 = 1 day
		session_unset();
		session_destroy();
		header("Location: $settings[url]");
	break;
	default: include("sources/homepage.php");
}
include("sources/footer.php");
mysqli_close($db);
?>