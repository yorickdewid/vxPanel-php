<?php

require_once __DIR__ . '/../etc/lib/paymentwall-php/lib/paymentwall.php';

if (!isset($_GET['r'])) {
	echo "<script language=\"JavaScript\">
<!--
document.location=\"$PHP_SELF?r=1&width=\"+window.innerWidth+\"&Height=\"+window.innerHeight;
//-->
</script>";
} else {

// Code to be displayed if resolutoin is detected
	if (isset($_GET['width']) && isset($_GET['Height'])) {
		// Resolution  detected
	} else {
		// Resolution not detected
	}
}
static $testValues = array('public' => 'db0433611c5f2cade2bbaea512b8fc9b', 'private' => '8fdfbb9b8df26d6368a874bac4ade389');
static $testValuesDef = array('public' => 't_b33418984f3a03964caa978de9012e', 'private' => 't_a9bd5d122bb9dd392e6f22a118c344');
function getShowPayWall() {
	// Paymentwall PHP Library: https://www.paymentwall.com/lib/php
	global $controller;
	global $testValues;
	Paymentwall_Config::getInstance()->set(array(
		'api_type' => Paymentwall_Config::API_VC,
		'public_key' => $testValues['public'],
		'private_key' => $testValues['private'],
	));
	$widget = new Paymentwall_Widget(
		$_GET['uid'], // MUST BE USER ID OF OUR CUSTOMERS NOT PAYMENTWALL ACCOUNTS
		'p10_1',
		array(),
		array('email' => 'user@hostname.com')
	);
	echo $widget->getHtmlCode(array('width' => $_GET['width'] - 20, 'height' => $_GET['Height']));
}

getShowPayWall();
