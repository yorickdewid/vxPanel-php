<?php
require_once __DIR__ . '/../etc/lib/paymentwall-php/lib/paymentwall.php';
require_once __DIR__ . '/../modules/credits/code/credits.php';
Paymentwall_Base::setApiType(Paymentwall_Base::API_VC);
Paymentwall_Base::setAppKey('db0433611c5f2cade2bbaea512b8fc9b'); // available in your Paymentwall mercha$
Paymentwall_Base::setSecretKey('8fdfbb9b8df26d6368a874bac4ade389'); // available in your Paymentwall mer$
$pingback = new Paymentwall_Pingback($_GET, $_SERVER['REMOTE_ADDR']);
if ($pingback->validate()) {
	$virtualCurrency = $pingback->getVirtualCurrencyAmount();
	if ($pingback->isDeliverable()) {
		credits::addCredit($virtualCurrency, $pingback->getUserId());
	} else if ($pingback->isCancelable()) {
		// withdraw the virtual currency
	}
	echo 'OK'; // Paymentwall expects response to be OK, otherwise the pingback will be resent
} else {
	echo $pingback->getErrorSummary();
}
?>
