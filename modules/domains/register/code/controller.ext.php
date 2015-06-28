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
require_once(__DIR__.'/../../../credits/code/creditRemover.php');

class module_controller extends ctrl_module
{

    static $complete;
    static $error;
    static $writeerror;
    static $nosub;
    static $alreadyexists;
    static $badname;
    static $blank;
    static $ok;
    static $unavailable;
    static $doterror;
    static $notransfer;
    static $transferimpossible;
    static $noregister;
    static $notenough;
    static $proceed;
    static $missingWhoisInfo;
    static $test_mode = false;
    static $disable_credits = true;

    /**
     * The 'worker' methods.
     */
    static function ListTlds()
    {
        try
        {
            if(!self::$test_mode)
            {
            $res = array();
            $tldlist = Transip_DomainService::getAllTldInfos();
            print_r($tldlist);
            foreach ($tldlist as $idx) {
                    array_push($res, $idx->name);
                }
            
            return $res;
            }
            else{
                $res = array('nl','be','com');
                return $res;
            }
        }
        catch(SoapFault $f)
        {
            return FALSE;
        }
        return;
    }

    static function AllocIPv6Addr()
    {
        global $zdbh;
        $sql = "SELECT v6_id_pk FROM x_ipv6 WHERE v6_enabled_in=0 LIMIT 1";
        $res = $zdbh->prepare($sql);
        $res->execute();
        $row = $res->fetch();
        if ($row['v6_id_pk']) {
            $sql = "UPDATE x_ipv6 SET v6_enabled_in=1 WHERE v6_id_pk=:addr";
            $sql = $zdbh->prepare($sql);
            $sql->bindParam(':addr', $row['v6_id_pk']);
            $sql->execute();
            return $row['v6_id_pk'];
        } else {
            $phpmailer = new sys_email();
            $phpmailer->Subject = "WEBSERVER: Out of IPv6";
            $phpmailer->Body = "Er zijn geen beschikbare ipv6 adressen meer";
            $phpmailer->AddAddress("info@deyron.nl");
            $phpmailer->SendEmail();
        }
    }

    static function ListDomainDirs($uid)
    {
        global $controller;
        $currentuser = ctrl_users::GetUserDetail($uid);
        $res = array();
        $handle = @opendir(ctrl_options::GetSystemOption('hosted_dir') . $currentuser['username'] . "/public_html");
        $chkdir = ctrl_options::GetSystemOption('hosted_dir') . $currentuser['username'] . "/public_html/";
        if (!$handle) {
            # Log an error as the folder cannot be opened...
        } else {
            while ($file = @readdir($handle)) {
                if ($file != "." && $file != ".." && $file != "_errorpages") {
                    if (is_dir($chkdir . $file)) {
                        array_push($res, array('domains' => $file));
                    }
                }
            }
            closedir($handle);
        }
        return $res;
    }


    static function CheckDomainAvailability($domain)
    {
        try
        {
            $ok = FALSE;
            $availability = Transip_DomainService::checkAvailability($domain);
            switch($availability)
            {
                case Transip_DomainService::AVAILABILITY_INYOURACCOUNT:
                    self::$unavailable = TRUE;
                    break;

                case Transip_DomainService::AVAILABILITY_UNAVAILABLE:
                    self::$notransfer = TRUE;
                    break;

                case Transip_DomainService::AVAILABILITY_FREE:
                    $ok = TRUE;
                    break;

                case Transip_DomainService::AVAILABILITY_NOTFREE:
                    self::$transferimpossible = TRUE;
                    break;
            }
            return $ok;
        }
        catch(SoapFault $e)
        {
            return FALSE;
        }
    }

    static function RegisterDomain($domain)
    {
        try
        {
            global $controller;
            print "reached register api";
            $types = array(
                Transip_WhoisContact::TYPE_REGISTRANT,
                Transip_WhoisContact::TYPE_ADMINISTRATIVE,
                Transip_WhoisContact::TYPE_TECHNICAL
            );
            $user = ctrl_users::GetUserProfileDetail();

            if(empty($user['firstname']) || empty($user['lastname']) || empty($user['street']) || empty($user['number']) 
            || empty($user['postcode']) || empty($user['city']) || empty($user['phone']) || empty($user['email']) || empty($user['country'])){
                self::$missingWhoisInfo = true;
                return FALSE;
            }   
            $contacts = array();
            foreach($types AS $type)
            {
                print "foreach";
                $contact = new Transip_WhoisContact();
                $contact->type        = $type;
                $contact->firstName   = $user['firstname']; //verplicht
                $contact->lastName    = $user['lastname']; //verplicht 
                $contact->companyName = '';
                $contact->companyKvk  = '';
                $contact->companyType = '';
                $contact->street      = $user['street']; //verplicht
                $contact->number      = $user['number']; //verplicht
                $contact->postalCode  = $user['postcode'];  
                $contact->city        = $user['city']; //verplicht
                $contact->phoneNumber = $user['phone'];
                $contact->faxNumber   = '';
                $contact->email       = $user['email']; //verplicht
                $contact->country     = $user['country']; //verplicht
                $contacts[] = $contact;
            }

            $arDNS = self::getDefaultDNS();
            $count = 0;
            foreach($arDNS as $dns)
            {
                $data = '';
                switch($dns['type']){
                case Transip_DnsEntry::TYPE_A:
                    $data = self::getIPV4Address();
                    break;
                case Transip_DnsEntry::TYPE_AAAA:
                    break;
                case Transip_DnsEntry::TYPE_CNAME:
                    $data = '@';
                    break;
                case Transip_DnsEntry::TYPE_MX:
                    $data = $dns['prio'].' mail.'.$domain.'.';
                    break;
                case Transip_DnsEntry::TYPE_NS:
                    $count++;
                    $data = 'ns'.$count.'.'.$domain;
                    break;
                case Transip_DnsEntry::TYPE_TXT:
                    break;
                case Transip_DnsEntry::TYPE_SRV:
                    break;
                default:
                    return;
                }
                $dnsEntries[] = new Transip_DnsEntry($dns['name'],$dns['ttl'],strtoupper($dns['type']),$data);
            }
            $reqdomain = new Transip_Domain($domain, $nameservers = null, $contacts,$dnsEntries);
            Transip_DomainService::register($reqdomain);
            return TRUE;
        }
        catch(SoapFault $f)
        {
            if($f->faultcode == 101)
            {
                return TRUE;
            }
	    return FALSE;
        }
    }

   function getDefaultDNS(){
        global $zdbh;
        $sql = "SELECT * FROM x_dns_create WHERE dc_acc_fk = 0";
        $numrows = $zdbh->prepare($sql);
        $numrows->execute();

        $result = array();
        while($row = $numrows->fetch(PDO::FETCH_ASSOC)){
            $result[] = array('type' => $row['dc_type_vc'],
                'name' => $row['dc_host_vc'],
                'ttl' => $row['dc_ttl_in'],
                'prio' => $row['dc_priority_in'],
                'target' => $row['dc_target_vc']);
        }
        return $result;
    }

    function getIPV4Address(){
        global $zdbh;
        $sql = "SELECT so_value_tx FROM x_settings WHERE so_id_pk = 21";
        $numrows = $zdbh->prepare($sql);
        $numrows->execute();
        $result = null;
          while($row = $numrows->fetch(PDO::FETCH_ASSOC)){
            $result = $row['so_value_tx'];
        }
        return $result;
    }

    static function ExecuteAddDomain($uid, $domain, $destination, $autohome, $status)
    {
        global $zdbh;
        $retval = FALSE;
        $active = TRUE;
        $domaintype = 1;
        runtime_hook::Execute('OnBeforeAddDomain');
        $currentuser = ctrl_users::GetUserDetail($uid);
        $domain = strtolower(str_replace(' ', '', $domain));
        if (!fs_director::CheckForEmptyValue(self::CheckCreateForErrors($domain))) {
            if (!self::RegisterDomain($domain)) {
                self::$noregister = TRUE;
                return $retval;
            }
            // Do we need to activate the domain //
            if ($status == 1) {
            //** New Home Directory **//
            if ($autohome == 1) {
                $destination = "/" . str_replace(".", "_", $domain);
                $vhost_path = ctrl_options::GetSystemOption('hosted_dir') . $currentuser['username'] . "/public_html/" . $destination . "/";
                fs_director::CreateDirectory($vhost_path);
                fs_director::SetFileSystemPermissions($vhost_path, 0777);
                //** Existing Home Directory **//
            } else {
                $destination = "/" . $destination;
                $vhost_path = ctrl_options::GetSystemOption('hosted_dir') . $currentuser['username'] . "/public_html/" . $destination . "/";
            }
            // Error documents:- Error pages are added automatically if they are found in the _errorpages directory
            // and if they are a valid error code, and saved in the proper format, i.e. <error_number>.html
            fs_director::CreateDirectory($vhost_path . "/_errorpages/");
            $errorpages = ctrl_options::GetSystemOption('static_dir') . "/errorpages/";
            if (is_dir($errorpages)) {
                if ($handle = @opendir($errorpages)) {
                    while (($file = @readdir($handle)) !== false) {
                        if ($file != "." && $file != "..") {
                            $page = explode(".", $file);
                            if (!fs_director::CheckForEmptyValue(self::CheckErrorDocument($page[0]))) {
                                fs_filehandler::CopyFile($errorpages . $file, $vhost_path . '/_errorpages/' . $file);
                            }
                        }
                    }
                    closedir($handle);
                }
            }
            // Lets copy the default welcome page across...
            if ((!file_exists($vhost_path . "/index.html")) && (!file_exists($vhost_path . "/index.php")) && (!file_exists($vhost_path . "/index.htm"))) {
                fs_filehandler::CopyFileSafe(ctrl_options::GetSystemOption('static_dir') . "pages/welcome.html", $vhost_path . "/index.html");
            }
            }else{
                $destination = "";
                $domaintype = 3;
            }
            // Request ipv6 address
            //$addr6 = self::AllocIPv6Addr();
            // If all has gone well we need to now create the domain in the database...
            $sql = $zdbh->prepare("INSERT INTO x_vhosts (vh_acc_fk,
														 vh_name_vc,
														 vh_directory_vc,
														 vh_type_in,
														 vh_created_ts) VALUES (
														 :userid,
														 :domain,
														 :destination,
														 :type,
														 :time)");
														 //:addr6)"); //CLEANER FUNCTION ON $domain and $homedirectory_to_use (Think I got it?)
                                                         // //vh_ipv6_fk) VALUES (
            $time = time();
            $sql->bindParam(':time', $time);
            $sql->bindParam(':userid', $currentuser['userid']);
            $sql->bindParam(':domain', $domain);
            $sql->bindParam(':destination', $destination);
            $sql->bindParam(':type', $domaintype);
            //$sql->bindParam(':addr6', $addr6);
            $sql->execute();
            self::SetWriteApacheConfigTrue();
            $retval = TRUE;
            runtime_hook::Execute('OnAfterAddDomain');
            return $retval;
        }
    }

    static function CheckCreateForErrors($domain)
    {
        global $zdbh;
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
            self::$error = TRUE;
            return FALSE;
        }
        // Check to see if the domain already exists in ZPanel somewhere and redirect if it does....
        $sql = "SELECT COUNT(*) FROM x_vhosts WHERE vh_name_vc=:domain AND vh_deleted_ts IS NULL";
        $numrows = $zdbh->prepare($sql);
        $numrows->bindParam(':domain', $domain);

        if ($numrows->execute()) {
            if ($numrows->fetchColumn() > 0) {
                self::$alreadyexists = TRUE;                return FALSE;
            }
        }
        // Check to make sure user not adding a subdomain and blocks stealing of subdomains....
        // Get shared domain list
        $SharedDomains = array();
        $a = explode(',', ctrl_options::GetSystemOption('shared_domains'));
        foreach ($a as $b) {
            $SharedDomains[] = $b;
        }
        if (substr_count($domain, ".") > 1) {
            $part = explode('.', $domain);
            foreach ($part as $check) {
                if (!in_array($check, $SharedDomains)) {
                    if (strlen($check) > 3) {
                        $sql = $zdbh->prepare("SELECT * FROM x_vhosts WHERE vh_name_vc LIKE :check AND vh_type_in !=2 AND vh_deleted_ts IS NULL");
                        $checkSql = '%' . $check . '%';
                        $sql->bindParam(':check', $checkSql);
                        $sql->execute();
                        while ($rowcheckdomains = $sql->fetch()) {
                            $subpart = explode('.', $rowcheckdomains['vh_name_vc']);
                            foreach ($subpart as $subcheck) {
                                if (strlen($subcheck) > 3) {
                                    if ($subcheck == $check) {
                                        if (substr($domain, -7) == substr($rowcheckdomains['vh_name_vc'], -7)) {
                                            self::$nosub = TRUE;
                                            return FALSE;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        // Check to see if domain is available
        if (!self::CheckDomainAvailability($domain)) {
            return FALSE;
        }
        return TRUE;
    }

    static function CheckErrorDocument($error)
    {
        $errordocs = array(100, 101, 102, 200, 201, 202, 203, 204, 205, 206, 207,
            300, 301, 302, 303, 304, 305, 306, 307, 400, 401, 402,
            403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413,
            414, 415, 416, 417, 418, 419, 420, 421, 422, 423, 424,
            425, 426, 500, 501, 502, 503, 504, 505, 506, 507, 508,
            509, 510);
        return in_array($error, $errordocs);
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

    static function IsValidEmail($email)
    {
        return preg_match('/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i', $email) == 1;
    }

    static function SetWriteApacheConfigTrue()
    {
        global $zdbh;
        $sql = $zdbh->prepare("UPDATE x_settings
								SET so_value_tx='true'
								WHERE so_name_vc='apache_changed'");
        $sql->execute();
    }

    /**
     * End 'worker' methods.
     */
    /**
     * Webinterface sudo methods.
     */
    static function getTldList()
    {
        $tld = self::ListTlds();
        $res = array();
        foreach ($tld as $row) {
            $res[] = array('name' => $row);
        }
        return $res;
    }

    static function getDomainDirsList()
    {
        $currentuser = ctrl_users::GetUserDetail();
        $domaindirectories = self::ListDomainDirs($currentuser['userid']);
        if (!fs_director::CheckForEmptyValue($domaindirectories)) {
            return $domaindirectories;
        } else {
            return false;
        }
    }

    static function getCreateDomain()
    {
        $currentuser = ctrl_users::GetUserDetail();
        return ($currentuser['domainquota'] < 0) or //-1 = unlimited
                ($currentuser['domainquota'] > ctrl_users::GetQuotaUsages('domains', $currentuser['userid']));
    }

    static function doCreateDomain($price = null)
    {
        global $controller;
        runtime_csfr::Protect();
        $formvars = $controller->GetAllControllerRequests('FORM');
        $balance = creditRemover::getCreditBalance();
        $tld = $formvars['inTld'];
        $object = Transip_DomainService::getTldInfo($tld);
        $amount = $object->price; //test value
        if(!self::$disable_credits)
        {
            if(($balance-$amount) >= 0){
                creditRemover::removeCredit($balance,$amount);
            }
            else{
                self::$notenough = TRUE;
                return FALSE; // turn off for testing
            }
        }
        $currentuser = ctrl_users::GetUserDetail();

        $domain = $formvars['inDomain'].$tld;
        // Check if there are dot in the domainname...
        if (strpos($formvars['inDomain'], ".") !== false) {
            self::$doterror = TRUE;
            return FALSE;
        }

        if (self::ExecuteAddDomain($currentuser['userid'], $domain, $formvars['inDestination'], $formvars['inAutoHome'], $formvars['inActive'])) {
            self::$ok = TRUE;
            return true;
        } else {
            return false;
        }
        return;
    }

    static function getCurrentID()
    {
        global $controller;
        $id = $controller->GetControllerRequest('URL', 'id');
        return ($id) ? $id : '';
    }

    static function getCurrentDomain()
    {
        global $controller;
        $domain = $controller->GetControllerRequest('URL', 'domain');
        return ($domain) ? $domain : '';
    }

    static function getDomainUsagepChart()
    {
        $currentuser = ctrl_users::GetUserDetail();
        $maximum = $currentuser['domainquota'];
        if ($maximum < 0) { //-1 = unlimited
            return '<img src="' . ui_tpl_assetfolderpath::Template() . 'images/unlimited.png" alt="' . ui_language::translate('Unlimited') . '"/>';
        } else {
            $used = ctrl_users::GetQuotaUsages('domains', $currentuser['userid']);
            $free = max($maximum - $used, 0);
            return '<img src="etc/lib/pChart2/zpanel/z3DPie.php?score=' . $free . '::' . $used
                    . '&labels=Free: ' . $free . '::Used: ' . $used
                    . '&legendfont=verdana&legendfontsize=8&imagesize=240::190&chartsize=120::90&radius=100&legendsize=150::160"'
                    . ' alt="' . ui_language::translate('Pie chart') . '"/>';
        }
    }

    static function getDomainStatusHTML($int, $id)
    {
        global $controller;
        if ($int == 1) {
            return '<td><font color="green">' . ui_language::translate('Live') . '</font></td>'
                    . '<td></td>';
        } else {
            return '<td><font color="orange">' . ui_language::translate('Pending') . '</font></td>'
                    . '<td><a href="#" class="help_small" id="help_small_' . $id . '_a"'
                    . 'title="' . ui_language::translate('Your domain will become active at the next scheduled update.  This can take up to one hour.') . '">'
                    . '<img src="/modules/' . $controller->GetControllerRequest('URL', 'module') . '/assets/help_small.png" border="0" /></a>';
        }
    }

    static function getDebug(){
        $currentuser = ctrl_users::GetUserDetail();
        print_r($currentuser);
    }
    static function getResult()
    {
        if (!fs_director::CheckForEmptyValue(self::$blank)) {
            return ui_sysmessage::shout(ui_language::translate("Your Domain can not be empty. Please enter a valid Domain Name and try again."), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$badname)) {
            return ui_sysmessage::shout(ui_language::translate("Your Domain name is not valid. Please enter a valid Domain Name: i.e. 'domain.com'"), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$alreadyexists)) {
            return ui_sysmessage::shout(ui_language::translate("The domain already appears to exist on this server."), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$nosub)) {
            return ui_sysmessage::shout(ui_language::translate("You cannot add a Sub-Domain here. Please use the Subdomain manager to add Sub-Domains."), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$unavailable)) {
            return ui_sysmessage::shout(ui_language::translate("The domain is not available for registration."), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$notransfer)) {
            return ui_sysmessage::shout(ui_language::translate("The domain cannot be transferred"), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$transferimpossible)) {
            return ui_sysmessage::shout(ui_language::translate("The domain is not available, but can be transferred if you are the owner"), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$error)) {
            return ui_sysmessage::shout(ui_language::translate("Please remove 'www'. The 'www' will automatically work with all Domains / Subdomains."), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$doterror)) {
            return ui_sysmessage::shout(ui_language::translate("You cannot specify the extension in the domain name. Please remove any dot from the domain name."), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$writeerror)) {
            return ui_sysmessage::shout(ui_language::translate("There was a problem writting to the virtual host container file. Please contact your administrator and report this error. Your domain will not function until this error is corrected."), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$missingWhoisInfo)) {
            return ui_sysmessage::shout(ui_language::translate("Missing Whois information,registration cannot continue. You can add missing details under your account."), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$noregister)) {
            return ui_sysmessage::shout(ui_language::translate("The domain could not be registered"), "zannounceerror");
        }
        if (!fs_director::CheckForEmptyValue(self::$ok)) {
            return ui_sysmessage::shout(ui_language::translate("The domain has been requested and will be available in a few hours."), "zannounceok");
        }
        if (!fs_director::CheckForEmptyValue(self::$notenough)) {
            return ui_sysmessage::shout(ui_language::translate("Not enough credits to proceed with registration."), "zannounceerror");
        }
        return;
    }
}
