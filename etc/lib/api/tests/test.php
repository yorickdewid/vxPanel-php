<?php

function testPingBack(){
	/* Accept-encoding: gzip, deflate
	User-Agent: Paymentwall API */
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,'localhost/external/pingback.php?uid=1245646&currency=10&type=0&ref=3353&is_test=1&sign_version=2&sig=0f3b5a389a369f9e34408920b205bee2');
	$output = curl_exec($ch);
	curl_close($ch);
	print $output;
}

testPingBack();

?>
