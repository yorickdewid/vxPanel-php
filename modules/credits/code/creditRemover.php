<?php

class creditRemover {

	public static function removeCredit($currentBalance, $minusAmount) {
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

	public static function doRefund($minusAmount, $ref, $hash) {
		global $zdbh;
		$currentuser = ctrl_users::GetUserDetail();
		$sql = "UPDATE x_wallet SET total = :newAmount WHERE hash=:hash;";
		$numrows = $zdbh->prepare($sql);
		$numrows->bindParam(':hash', $hash);
		$newAmount = $currentBalance - $minusAmount;
		$numrows->bindParam(':newAmount', $newAmount);

		if ($numrows->execute()) {
			if ($numrows->rowCount() == 0) {
				$display = "<p>Failed to remove credit</p>";
			} else {
				self::logTransaction(0, $minusAmount, $ref, 3); // 3 = refund
				$display = "<p>Succesfully removed amount of credit</p>";
			}
		}
		return $display;
	}

	private static function logTransaction($hash, $amount, $ref, $status) {
		try {
			global $zdbh;
			$sql = "SELECT * FROM x_wallet WHERE hash = :hash";
			$numrows = $zdbh->prepare($sql);
			$result = $numrows->fetch();
			$walletId = $result['wallet_id'];
			$sql = "INSERT INTO x_credit_transaction (`wallet_id`,`amount`,`ref_id`,`status_id`) VALUES(:walletId,:amount,:ref,:status)";
			$numrows = $zdbh->prepare($sql);
			$numrows->bindValue(':walletId', $walletId);
			$numrows->bindValue(':amount', $amount);
			$numrows->bindValue(':ref', $ref);
			$numrows->bindValue(':status', $status);

			if ($numrows->execute()) {
				return true;
			}
		} catch (PDOException $e) {
			throw $e;
		}
	}

	/**
	 * [getCreditBalance description]
	 * @return int amount of balance
	 */
	public static function getCreditBalance() {
		global $zdbh;
		$currentuser = ctrl_users::GetUserDetail();
		$sql = "SELECT id,total FROM x_wallet WHERE user_id=:userid";
		$numrows = $zdbh->prepare($sql);
		$numrows->bindParam(':userid', $currentuser['userid']);

		if ($numrows->execute()) {
			$result = $numrows->fetchAll();
			if (isset($result)) {
				foreach ($result as $res) {
					return $res['total'];
				}
			}
		}
	}

}

?>
