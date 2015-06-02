<?php
/**
 *
 * ZPanel - A Cross-Platform Open-Source Web Hosting Control panel.
 *
 * @package ZPanel
 * @version $Id$
 * @author Bobby Allen - ballen@bobbyallen.me
 * @copyright (c) 2008-2014 ZPanel Group - http://www.zpanelcp.com/
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License v3
 *
 * This program (ZPanel) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

require_once(__DIR__.'/../../../etc/lib/paymentwall-php/lib/paymentwall.php');
require_once(__DIR__.'/creditRemover.php');

class module_controller extends ctrl_module
{
    private static $hasWallet;
    private static $wallet;

    public static function getCurrentCreditBalance(){
        global $zdbh;
        $currentuser = ctrl_users::GetUserDetail();
        $sql = "SELECT id,total FROM x_wallet WHERE user_id=:userid";
        $numrows = $zdbh->prepare($sql);
        $numrows->bindParam(':userid', $currentuser['userid']);

        $display = '';
        if ($numrows->execute()) {
            $result = $numrows->fetchAll();
            if(isset($result)){
                self::$hasWallet = true;
                foreach($result as $res)
                {
                    self::$wallet = $res['id'];
                    $display = "<p>You currently have ".$res['total']." credits</p>";
                }
            }
            else{
                self::$hasWallet = true;
                $display = "<p>You currently do not have any balance.</p>";
            }
        }
        else{
            $display = "<p>Balance never added.</p>";
        }
        return $display;
    }

    public static function getHasWallet(){
        if(self::$hasWallet)
        {
            return true;
        }
        else{
            return false;
        }
    }

    public static function getTransactionLog(){
        global $zdbh;
        $currentuser = ctrl_users::GetUserDetail();
        $sql = "SELECT date,amount,status_id FROM x_credit_transaction WHERE wallet_id=:walletid";
        $numrows = $zdbh->prepare($sql);
        $numrows->bindValue(':walletid', self::$wallet);
        $numrows->execute();
        $result = $numrows->fetchAll();
        $display = "";
        $display = "<tr><th>Amount</th><th>Date</th><th>Status</th></tr>";
        foreach($result as $res)
        {
            $display .= "<tr>";
            $display .= "<td>".$res['amount']."</td>";
            $display .= "<td>".$res['date']."</td>";
            $display .= "<td>".$res['status_id']."</td>";
            $display .= "</tr>";
        }
        return $display;
    }

    public static function doAddFunds($amount,$user){
        self::getPayWall();
    }

    private static function getPayWall(){
    // Paymentwall PHP Library: https://www.paymentwall.com/lib/php

        Paymentwall_Config::getInstance()->set(array(
            'api_type' => Paymentwall_Config::API_VC,
            'public_key' => 't_b33418984f3a03964caa978de9012e',
            'private_key' => 't_a9bd5d122bb9dd392e6f22a118c344'
            ));

        $widget = new Paymentwall_Widget(
            '1', // MUST BE USER ID OF OUR CUSTOMERS NOT PAYMENTWALL ACCOUNTS
            array(), 
            array('email' => 'user@hostname.com')
            );
        print $widget->getHtmlCode();
    }

    private static function pingwall(){
        $pingback = new Paymentwall_Pingback($_GET, $_SERVER['REMOTE_ADDR']);
        if ($pingback->validate()) {
          $virtualCurrency = $pingback->getVirtualCurrencyAmount();
          if ($pingback->isDeliverable()) {
      // deliver the virtual currency
          } else if ($pingback->isCancelable()) {
            creditRemover::$removeCredits();
          } 
          echo 'OK'; 
      } else {
          echo $pingback->getErrorSummary();
      }
    }
}

