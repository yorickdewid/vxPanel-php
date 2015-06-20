<?php

require_once(__DIR__.'/../etc/lib/paymentwall-php/lib/paymentwall.php');

if(!isset($_GET['r']))     
{     
echo "<script language=\"JavaScript\">     
<!--      
document.location=\"$PHP_SELF?r=1&width=\"+window.innerWidth+\"&Height=\"+window.innerHeight;     
//-->     
</script>";     
}     
else {         

// Code to be displayed if resolutoin is detected     
     if(isset($_GET['width']) && isset($_GET['Height'])) {     
               // Resolution  detected     
     }     
     else {     
               // Resolution not detected     
     }     
}     
    function getShowPayWall(){
    // Paymentwall PHP Library: https://www.paymentwall.com/lib/php

        Paymentwall_Config::getInstance()->set(array(
            'api_type' => Paymentwall_Config::API_VC,
            'public_key' => 't_b33418984f3a03964caa978de9012e',
            'private_key' => 't_a9bd5d122bb9dd392e6f22a118c344'
            ));

        $widget = new Paymentwall_Widget(
            '1', // MUST BE USER ID OF OUR CUSTOMERS NOT PAYMENTWALL ACCOUNTS
            'p1',
            array(), 
            array('email' => 'user@hostname.com')
            );
        echo $widget->getHtmlCode(array('width'=>$_GET['width']-20,'height'=>$_GET['Height']));
    }

    getShowPayWall();

?>