<?php

require_once __DIR__.'/../../../cnf/db.php';
require_once __DIR__.'/../../../dryden/db/driver.class.php';
require_once __DIR__.'/../../../dryden/ctrl/users.class.php';
require_once __DIR__.'/../../../dryden/debug/logger.class.php';
require_once __DIR__.'/../../../inc/dbc.inc.php';

class credits{
	

	public static function addCredit($amount,$userId){
		global $zdbh;
        //self::createWallet($userId);
        $balance = self::getWalletBalance($userId);
        $newAmount = $balance + $amount;
        $sql = "UPDATE credits.wallet SET total = :amount WHERE user_id=:userid AND id=:wallet_id";
        $numrows = $zdbh->prepare($sql);
        $numrows->bindParam(':userid', $userId);
        $numrows->bindValue(':amount', $newAmount);
        $numrows->bindValue(':wallet_id',0);

        if ($numrows->execute()) {
        	self::logTransaction(0,$amount,2);
            return true;
        }
        else{
        	self::logTransaction(0,$amount,1);
        	return false;
        }
    }

    private static function logTransaction($walletId,$amount,$status){
    	global $zdbh;
        $sql = "INSERT INTO credits.credit_transaction (`wallet_id`,`amount`,`status_id`) VALUES(:walletId,:amount,:status)";
        $numrows = $zdbh->prepare($sql);
        $numrows->bindValue(':walletId', $walletId);
        $numrows->bindValue(':amount', $amount);
        $numrows->bindValue(':status', $status);

        if ($numrows->execute()) {
        	print "logged transaction";
        }
    }

    private static function getWalletBalance($userId){
    	global $zdbh;
        $sql = "SELECT total FROM credits.wallet WHERE user_id=:userid";
        $numrows = $zdbh->prepare($sql);
        $numrows->bindParam(':userid', $userId);

        if ($numrows->execute()) {
        	$result=$numrows->fetchAll();
        	print_r($result);
        	if(isset($result) && $result != null)
        	{
        		foreach($result as $res){
        			return $res['total'];
        		}
        	}
        }
    }

    /**
     * Should be executed just after user registration?
     * @return [type] [description]
     */
    private static function createWallet($userId){
    	global $zdbh;
        $sql = "INSERT INTO credits.wallet (`user_id`) VALUES(:userid)";
        $numrows = $zdbh->prepare($sql);
        $numrows->bindValue(':userid', $userId);

        if ($numrows->execute()) {
            $result = $numrows->fetch();
            print $result;
        }
    }

}


?>