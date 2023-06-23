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
//include(getLanguage($settings['url'],null,null));

$b = protect($_GET['b']);
$c = protect($_GET['c']);
$d = protect($_GET['d']);
$eid = protect($_GET['eid']);

if($b == "check") {
	if($c == "paypal") { include("sources/payment/PayPal.php"); }
	elseif($c == "skrill") { include("sources/payment/Skrill.php"); }
	elseif($c == "webmoney") { include("sources/payment/WebMoney.php"); }
	elseif($c == "payeer") { include("sources/payment/Payeer.php"); }
	elseif($c == "perfectmoney") { include("sources/payment/PerfectMoney.php"); }
	elseif($c == "advcash") { include("sources/payment/AdvCash.php"); }
	elseif($c == "okpay") { include("sources/payment/OKPay.php"); }
	elseif($c == "entromoney") { include("sources/payment/Entromoney.php"); }
	elseif($c == "payza") { include("sources/payment/Payza.php"); }
	elseif($c == "solidtrustpay") { include("sources/payment/SolidTrustPay.php"); }
	else { 
		echo 'Unknown payment method.';
	}
} else {
	echo 'Unknown page.';
}
mysqli_close($db);
?>