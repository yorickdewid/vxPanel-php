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
require_once(__DIR__.'/../../../../etc/lib/api/transip/DomainService.php');

class module_controller extends ctrl_module
{
    static $error;
    static $badname;
    static $blank;
    static $www;
    static $ok;
    static $whois;

    /**
     * The 'worker' methods.
     */
    static function ExecWhoisQuery($domain)
    {
        if (self::CheckCreateForErrors($domain)) {
            try
            {
                self::$whois = nl2br(Transip_DomainService::getWhois($domain));
                return TRUE;
            }
            catch(SoapFault $e)
            {
                self::$whois = 'An error occurred: ' . htmlspecialchars($e->getMessage());
                self::$error = TRUE;
                return FALSE;
            }
        }
        return FALSE;
    }

    static function CheckCreateForErrors($domain)
    {
        // Check for spaces and remove if found...
        $domain = strtolower(str_replace(' ', '', $domain));
        // Check to make sure the domain is not blank before we go any further...
        if ($domain == '') {
            self::$blank = TRUE;
            return FALSE;
        }
        // Check for invalid characters in the domain...
        if (!self::IsValidDomainName($domain)) {
            self::$badname = TRUE;
            return FALSE;
        }
        // Check to make sure the domain is in the correct format before we go any further...
        $wwwclean = stristr($domain, 'www.');
        if ($wwwclean == true) {
            self::$www = TRUE;
            return FALSE;
        }
        return TRUE;
    }

    static function IsValidDomainName($a)
    {
        if (stristr($a, '.')) {
            $part = explode(".", $a);
            foreach ($part as $check) {
                if (!preg_match('/^[a-z\d][a-z\d-]{0,62}$/i', $check) || preg_match('/-$/', $check)) {
                    return false;
                }
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * End 'worker' methods.
     */

    /**
     * Webinterface sudo methods.
     */
    static function doCheckDomain()
    {
        global $controller;
        runtime_csfr::Protect();
        $formvars = $controller->GetAllControllerRequests('FORM');
        if (self::ExecWhoisQuery($formvars['inDomain'])) {
            self::$ok = TRUE;
            return true;
        }
        return false;
    }

    static function getResult()
    {
        if (!fs_director::CheckForEmptyValue(self::$blank)) {
            return ui_sysmessage::shout(ui_language::translate("Your Domain can not be empty. Please enter a valid Domain Name and try again."), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$badname)) {
            return ui_sysmessage::shout(ui_language::translate("Your Domain name is not valid. Please enter a valid Domain Name: i.e. 'domain.com'"), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$error)) {
            return ui_sysmessage::shout(self::$whois, "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$ok)) {
            return ui_sysmessage::shout(self::$whois, "zannounceinfo");
        }
        return;
    }

    /**
     * Webinterface sudo methods.
     */
}
