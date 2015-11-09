<?php
require_once __DIR__ . '/../etc/lib/paymentwall-php/lib/paymentwall.php';
require_once __DIR__ . '/../modules/credits/code/credits.php';
require_once __DIR__ . '/../modules/credits/code/creditRemover.php';
Paymentwall_Base::setApiType(Paymentwall_Base::API_VC);
Paymentwall_Base::setAppKey(getenv("PWALL_APPKEY"); // available in your Paymentwall mercha$
Paymentwall_Base::setSecretKey(getenv("PWALL_SECRETKEY"); // available in your Paymentwall mer$
$pingback = new Paymentwall_Pingback($_GET, $_SERVER['REMOTE_ADDR']);
if ($pingback->validate()) {
	$virtualCurrency = $pingback->getVirtualCurrencyAmount();
	if ($pingback->isDeliverable()) {
		credits::addCredit($virtualCurrency, $pingback->getUserId(), $pingback->getReferenceId());
	} else if ($pingback->isCancelable()) {
		// withdraw the virtual currency
		creditRemover::doRefund($virtualCurrency, $pingback->getUserId(), $pingback->getReferenceId());
	}
	echo 'OK'; // Paymentwall expects response to be OK, otherwise the pingback will be resent
} else {
	echo $pingback->getErrorSummary();
}
?>
