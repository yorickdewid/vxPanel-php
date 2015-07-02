<?php

require_once(__DIR__.'/../../../etc/lib/api/transip/DomainService.php');
require_once(__DIR__.'/../../../etc/lib/api/transip/WhoisContact.php');

class module_controller extends ctrl_module
{
    //security that someone only edits their domains>
    static $editwhois = null;

    static function getDisplayDomains()
    {
        global $zdbh;
        global $controller;
        $currentuser = ctrl_users::GetUserDetail();
        $line = '<div class="zgrid_wrapper">';
        $line .= "<h2>" . ui_language::translate("Manage Domains") . "</h2>";
        $line .= "" . ui_language::translate("Choose fom the list of domains below") . "";
        $line .= "<form name=DisplayDNS action=\"./?module=manage_whois&action=DisplayWhois\" method=\"post\">";
        $line .= "<br><br>";
        $line .= "<table class=\"zform\">";
        $line .= "<tr>";
        $line .= "<td><select name=\"inDomain\" id=\"inDomain\">";
        $line .= "<option value=\"\" selected=\"selected\">-- " . ui_language::translate("Select a domain") . " --</option>";
        $sql = $zdbh->prepare("SELECT * FROM x_vhosts WHERE vh_acc_fk=:userid AND vh_type_in !=2 AND vh_deleted_ts IS NULL");
        $sql->bindParam(':userid', $currentuser['userid']);
        $sql->execute();
        while ($rowdomains = $sql->fetch()) {
            $line .= "<option value=\"" . $rowdomains['vh_id_pk'] . "\">" . $rowdomains['vh_name_vc'] . "</option>";
        }
        $line .= "</select></td>";
        $line .= "<td>";
        $line .= '<button type="submit" class="btn btn-large btn-primary" name="inSelect" value="' . $rowdomains['vh_id_pk'] . '"><i class="glyphicon glyphicon-pencil"></i>  ' . ui_language::translate("Edit") . '</button>';
        $line .= '</td>';
        $line .= '</tr>';
        $line .= '</table>';
        $line .= self::getCSFR_Tag();
        $line .= '</form>';
        $line .= '<p>&nbsp;</p>';
        $line .= '</div>';
        return $line;
    }

    static function getDisplayWhois(){
        if(self::$editwhois != null)
        {
        $s = self::getWhoisForDomain('restaurantoeverbos.nl');
        $line = '<!-- testing !-->';
        $line .= '<div><form action="./?module=my_account&action=UpdateWhoisInfoRemote" method="post">';
        $line .= '<table class="table table-striped">';
        $line .= '<tr>';
        $line .= '<th>* First Name :</th>';
        $line .= '<td><input name="inFirstName" type="text" id="inFirstName" size="40" value="'.$s['firstname'].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* Last Name :</th>';
        $line .= '<td><input name="inLastName" type="text" id="inLastName" size="40" value="'.$s['lastname'].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* Street :</th>';
        $line .= '<td><input name="inStreet" type="text" id="inStreet" size="20" value="'.$s['street'].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* Number :</th>';
        $line .= '<td><input name="inNumber" type="text" id="inNumber" size="20" value="'.$s['number'].'"/></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* City :</th>';
        $line .= '<td><input name="inCity" type="text" id="inCity" size="15" value="'.$s['city'].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* Postal Code :</th>';
        $line .= '<td><input name="inPostalCode" type="text" id="inPostalCode" size="15" value="'.$s['postcode'].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* Choose Country :</th>';
        $countries = self::getCountryList();
        $line .= '<td>';
        $current = 'gb';
        $line .= '<select name="inCountry" id="inCountry" style="">';
        foreach ($countries as $short => $country) {
            if(strtolower($short) == strtolower($current)) {
               $selected = "SELECTED";
            }
            else {
                $selected = "";
            }
            $line .= '<option value="'.$short.'" '.$selected.'>'.$country.'</option>';
        }
        $line .= '</select>';
        $line .= '</td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* Phone Number :</th>';
        $line .= '<td><input name="inPhone" type="text" id="inPhone" size="20" value="'.$s['phone'].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* Email Address :</th>';
        $line .= '<td><input name="inEmail" type="text" id="inEmail" size="40" value="'.$s['email'].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th> Company Name :</th>';
        $line .= '<td><input name="inCompanyName" type="text" id="inCompanyName" size="40" value="'.$s['companyname'].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th> Company KVK :</th>';
        $line .= '<td><input name="inCompanyKVK" type="text" id="inCompanyKVK" size="40" value="'.$s['companykvk'].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th> Company Type :</th>';
        $line .= '<td><input name="inCompanyType" type="text" id="inCompanyType" size="40" value="'.$s['companytype'].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th> Fax Number :</th>';
        $line .= '<td><input name="inFax" type="text" id="inFax" size="40" value="'.$s['fax'].'" /></td>';
        $line .= '</tr>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>&nbsp;</th>';
        $line .= '<td align="right"><@ CSFR_Tag @><button class="button-loader btn btn-primary" id="button" type="submit" > Update Whois </button</td>';
        $line .= '</tr>';
        $line .= '</table>';
        $line .= '</form></div>';
        return $line;
        }
    }

    static function getWhoisForDomain($domain){
        try{
        // the received domain must be one from a list in this case.
        $result = Transip_DomainService::getInfo($domain);
        echo $result;
        // $result = Transip_DomainService::getWhois($domain);
        // echo $result;
        $whoiscontacts = $result->Transip_WhoisContact;
        }
        catch(Exception $e)
        {
            echo $e->faultcode;
        }   
    }

    static function getCountryList()
    {
        $countryList = Transip_WhoisContact::$possibleCountryCodes;
        return $countryList;
    }

static function doDisplayWhois()
{
    global $zdbh;
    global $controller;
    runtime_csfr::Protect();
    self::$editwhois = $controller->GetControllerRequest('FORM', 'inDomain');
    return;
}

static function getEditWhois(){
    if(self::$editwhois != null)
    {
        return true;
    }
    else{
        return false;
    }
}
}

?>