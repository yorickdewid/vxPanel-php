<?php

require_once __DIR__ . '/../etc/lib/paymentwall-php/lib/paymentwall.php';

function getShowPayWall() {
	// Paymentwall PHP Library: https://www.paymentwall.com/lib/php
	global $controller;
	global $testValues;
	Paymentwall_Config::getInstance()->set(array(
		'api_type' => Paymentwall_Config::API_VC,
		'public_key' => getenv("PWALL_APPKEY"),
		'private_key' => getenv("PWALL_SECRETKEY"),
	));
	$widget = new Paymentwall_Widget(
		$_POST['inHash'], // MUST BE USER ID OF OUR CUSTOMERS NOT PAYMENTWALL ACCOUNTS
		'p10_1'
		, array(),
		array('email' => $_POST['inEmail'], 'succes_url' => '/?module=credits')
	);
	echo $widget->getHtmlCode();
}

getShowPayWall();
