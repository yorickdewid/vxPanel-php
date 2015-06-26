<?php

class creditRemover{

	public static function removeCredit($currentBalance,$minusAmount){
		global $zdbh;
		$currentuser = ctrl_users::GetUserDetail();

        $sql = "UPDATE x_wallet SET total = :newAmount WHERE user_id=:userid;";
        $numrows = $zdbh->prepare($sql);
        $numrows->bindParam(':userid', $currentuser['userid']);
        $newAmount = $currentBalance - $minusAmount;
        $numrows->bindParam(':newAmount', $newAmount);

        if ($numrows->execute()) {
            if ($numrows->rowCount() == 0) {
                $display = "<p>Failed to remove credit</p>";
            } else {
                $display = "<p>Succesfully removed amount of credit</p>";
            }
        }
        return $display;
	}

    /**
    * [getCreditBalance description]
    * @return int amount of balance
    */
    public static function getCreditBalance(){
        global $zdbh;
        $currentuser = ctrl_users::GetUserDetail();
        $sql = "SELECT id,total FROM x_wallet WHERE user_id=:userid";
        $numrows = $zdbh->prepare($sql);
        $numrows->bindParam(':userid', $currentuser['userid']);

        if ($numrows->execute()) {
            $result = $numrows->fetchAll();
            if(isset($result)){
                foreach($result as $res)
                {
                    return $res['total'];
                }
            }
        }
    }

}


?>
