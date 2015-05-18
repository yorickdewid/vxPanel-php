<?php

class creditRemover{

	public static function removeCredit($currentBalance,$minusAmount){
		global $zdbh;
		$currentuser = ctrl_users::GetUserDetail();

        $sql = "UPDATE x_wallet SET total = :newAmount WHERE user_id=:userid;";
        $numrows = $zdbh->prepare($sql);
        $numrows->bindParam(':userid', $currentuser['userid']);
        $numrows->bindParam(':newAmount', $currentBalance - $minusAmount);

        if ($numrows->execute()) {
            if ($numrows->returnResult == 0) {
                $display = "<p>Failed to remove credit</p>";
            } else {
                $display = "<p>Succesfully removed amount of credit</p>";
            }
        }
        return $display;
	}

}


?>
