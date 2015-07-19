<?php

require_once __DIR__ . '/../../../cnf/db.php';
require_once __DIR__ . '/../../../dryden/db/driver.class.php';
require_once __DIR__ . '/../../../dryden/ctrl/users.class.php';
require_once __DIR__ . '/../../../dryden/debug/logger.class.php';
require_once __DIR__ . '/../../../inc/dbc.inc.php';

/*
All methods must not contain any echo/prints or any output the pingback wel else report failed.
 */

class credits {

	public static function addCredit($amount, $userId) {
		try {
			global $zdbh;
			if (!self::checkWalletExists($userId)) {
				self::createWallet($userId);
			}
			$balance = self::getWalletBalance($userId);
			$newAmount = $balance + $amount;
			$sql = "UPDATE x_wallet SET total = :amount WHERE user_id=:userid AND id=:wallet_id";
			$numrows = $zdbh->prepare($sql);
			$numrows->bindParam(':userid', $userId);
			$numrows->bindValue(':amount', $newAmount);
			$numrows->bindValue(':wallet_id', 0);

			if ($numrows->execute()) {
				self::logTransaction(0, $amount, 2);
				return true;
			} else {
				self::logTransaction(0, $amount, 1);
				return false;
			}
		} catch (PDOException $e) {
			throw $e;
		}
	}

	private static function logTransaction($walletId, $amount, $status) {
		try {
			global $zdbh;
			$sql = "INSERT INTO x_credit_transaction (`wallet_id`,`amount`,`status_id`) VALUES(:walletId,:amount,:status)";
			$numrows = $zdbh->prepare($sql);
			$numrows->bindValue(':walletId', $walletId);
			$numrows->bindValue(':amount', $amount);
			$numrows->bindValue(':status', $status);

			if ($numrows->execute()) {
				return true;
			}
		} catch (PDOException $e) {
			throw $e;
		}
	}

	private static function getWalletBalance($userId) {
		try {
			global $zdbh;
			$sql = "SELECT total FROM x_wallet WHERE user_id=:userid";
			$numrows = $zdbh->prepare($sql);
			$numrows->bindParam(':userid', $userId);

			if ($numrows->execute()) {
				$result = $numrows->fetchAll();
				if (isset($result) && $result != null) {
					foreach ($result as $res) {
						return $res['total'];
					}
				}
			}
		} catch (PDOException $e) {
			throw $e;
		}
	}

	private static function checkWalletExists($userId) {
		try {
			global $zdbh;
			$sql = "SELECT user_id FROM x_wallet WHERE user_id=:userid";
			$numrows = $zdbh->prepare($sql);
			$numrows->bindParam(':userid', $userId);

			if ($numrows->execute()) {
				$result = $numrows->fetch();
				if ($result['user_id'] == $userId) {
					return true;
				} else {
					return false;
				}
			}
		} catch (PDOException $e) {
			throw $e;
		}
	}

	public static function createWallet($userId) {
		try {
			global $zdbh;
			$sql = "INSERT INTO x_wallet (`user_id`) VALUES(:userid)";
			$numrows = $zdbh->prepare($sql);
			$numrows->bindValue(':userid', $userId);

			if ($numrows->execute()) {
				if ($numrows->rowCount() > 0) {
					return true;
				} else {
					//log
				}
			}
		} catch (PDOException $e) {
			throw $e;
		}
	}
}
?>
