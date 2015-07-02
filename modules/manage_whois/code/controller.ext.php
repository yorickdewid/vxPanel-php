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
        if(self::$editwhois != null) // still executed if this statement is not present... though html is not shown
        {
        $s = self::getWhoisForDomain('restaurantoeverbos.nl');
        echo 'owkee';
        $line = '<!-- DNS FORM -->';
        $line .= '<div id="dnsTitle" class="account accountTitle">';
        $line .= '</div>';
        $line .= '</div>';
        $line .= '<form action="./?module=manage_whois&action=UpdateWhoisInfoRemote" method="post">';
        // $line .= '<input id="domainName" name="domainName" value="' . $domain['vh_name_vc'] . '" type="hidden">';
        // $line .= '<input id="domainID" name="domainID" value="' . $domain['vh_id_pk'] . '" type="hidden">';
        $line .= '<!-- TABS -->';
        $line .= '<div id="dnsRecords">';
        $line .= '<ul class="nav nav-tabs">';
        echo 'type allowed';
        if (self::IsTypeAllowed(Transip_WhoisContact::TYPE_REGISTRANT,'Reg')) {
            $line .= '    <li class="active"><a href="#typeA" data-toggle="tab">Registrant(owner) contact</a></li>';
        }
        if (self::IsTypeAllowed(Transip_WhoisContact::TYPE_ADMINISTRATIVE,'Adm')) {
            $line .= '    <li><a href="#typeAAAA" data-toggle="tab">Adminstrative contact</a></li>';
        }
        if (self::IsTypeAllowed(Transip_WhoisContact::TYPE_TECHNICAL,'Tec')) {
            $line .= '    <li><a href="#typeCNAME" data-toggle="tab">Technical contac</a></li>';
        }
        $line .= '</ul>';
        $line .= '<!-- END TABS -->';
        $line .= '<div class="tab-content">';
        echo 'tab contacts';
        $line .= self::getTabContact('Registrant Contact',$whoisContacts['registrant']);
        $line .= self::getTabContact('Administrative Contact',$whoisContacts['administrative']);
        $line .= self::getTabContact('Technical Contact',$whoisContacts['technical']);
        $line .= '<input name="newRecords" value="0" type="hidden">';
        $line .= '</div> <!-- END TABS CONTENT -->';
        /* END TABS SECTION */
        $line .= '</div> <!-- END TABS -->';
        // Bottom Edit buttons
        $line .= "<div id=\"dnsTitle\" class=\"account accountTitle\">";
        $line .= "<div class=\"content\">";
        echo "csfr tag";
        $line .= self::getCSFR_Tag();
        $line .= "</form>";
        $line .= '</div>';
        $line .='
<div id="dns-modal" class="modal fade in" tabindex="-1" role="dialog" style="display: none;">
   <div class="modal-dialog">
       <div class="alert alert-block alert-error fade in">
           <h4 class="alert-heading">Oh snap! You got an error!</h4>
           <p>Change this and that and try again. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
           <p>
           <a class="btn btn-danger" href="#" data-dismiss="modal">Ok</a>
           </p>
       </div><!-- /.modal-content -->
   </div><!-- /.modal-dalog -->
</div>';
        return $line;
        }
    }

    static function getTabContact($name,$whoisContact,$idSuffix)
    {
          $line = '<!-- testing !-->';
            $line .= '<table class="table table-striped">';
            $line .= '<tr>';
            $line .= '<th>* First Name :</th>';
            $line .= '<td><input name="inFirstName'.$idSuffix.'" type="text" id="inFirstName'.$idSuffix.'" size="40" value="'.$s['firstname'].'" /></td>';
            $line .= '</tr>';
            $line .= '<tr>';
            $line .= '<th>* Last Name :</th>';
            $line .= '<td><input name="inLastName'.$idSuffix.' type="text" id="inLastName'.$idSuffix.'" size="40" value="'.$s['lastname'].'" /></td>';
            $line .= '</tr>';
            $line .= '<tr>';
            $line .= '<th>* Street :</th>';
            $line .= '<td><input name="inStreet'.$idSuffix.'" type="text" id="inStreet'.$idSuffix.'" size="20" value="'.$s['street'].'" /></td>';
            $line .= '</tr>';
            $line .= '<tr>';
            $line .= '<th>* Number :</th>';
            $line .= '<td><input name="inNumber'.$idSuffix.'" type="text" id="inNumber'.$idSuffix.'" size="20" value="'.$s['number'].'"/></td>';
            $line .= '</tr>';
            $line .= '<tr>';
            $line .= '<th>* City :</th>';
            $line .= '<td><input name="inCity'.$idSuffix.'" type="text" id="inCity'.$idSuffix.'" size="15" value="'.$s['city'].'" /></td>';
            $line .= '</tr>';
            $line .= '<tr>';
            $line .= '<th>* Postal Code :</th>';
            $line .= '<td><input name="inPostalCode'.$idSuffix.'" type="text" id="inPostalCode'.$idSuffix.'" size="15" value="'.$s['postcode'].'" /></td>';
            $line .= '</tr>';
            $line .= '<tr>';
            $line .= '<th>* Country :</th>';
            $countries = self::getCountryList();
            $line .= '<td>';
            $current = 'gb';
            $line .= '<select name="inCountry'.$idSuffix.'" id="inCountry'.$idSuffix.'" style="">';
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
            $line .= '<td><input name="inPhone'.$idSuffix.'" type="text" id="inPhone'.$idSuffix.'" size="20" value="'.$s['phone'].'" /></td>';
            $line .= '</tr>';
            $line .= '<tr>';
            $line .= '<th>* Email Address :</th>';
            $line .= '<td><input name="inEmail'.$idSuffix.'" type="text" id="inEmail'.$idSuffix.'" size="40" value="'.$s['email'].'" /></td>';
            $line .= '</tr>';
            $line .= '<tr>';
            $line .= '<th> Company Name :</th>';
            $line .= '<td><input name="inCompanyName'.$idSuffix.'" type="text" id="inCompanyName'.$idSuffix.'" size="40" value="'.$s['companyname'].'" /></td>';
            $line .= '</tr>';
            $line .= '<tr>';
            $line .= '<th> Company KVK :</th>';
            $line .= '<td><input name="inCompanyKVK'.$idSuffix.'" type="text" id="inCompanyKVK'.$idSuffix.'" size="40" value="'.$s['companykvk'].'" /></td>';
            $line .= '</tr>';
            $line .= '<tr>';
            $line .= '<th> Company Type :</th>';
            $line .= '<td><input name="inCompanyType'.$idSuffix.'" type="text" id="inCompanyType'.$idSuffix.'" size="40" value="'.$s['companytype'].'" /></td>';
            $line .= '</tr>';
            $line .= '<tr>';
            $line .= '<th> Fax Number :</th>';
            $line .= '<td><input name="inFax'.$idSuffix.'" type="text" id="inFax'.$idSuffix.'" size="40" value="'.$s['fax'].'" /></td>';
            $line .= '</tr>';
            $line .= '</tr>';
            $line .= '<tr>';
            $line .= '<th>&nbsp;</th>';
            $line .= '<td align="right"><button class="button-loader btn btn-primary" id="button" type="submit" > Update Whois </button</td>';
            $line .= '</tr>';
            $line .= '</table>';
            return $line;
    }

    static function temp()
    {
       
    }

    static function IsTypeAllowed($type)
    {
        global $zdbh;
        $record_types = array(Transip_WhoisContact::TYPE_REGISTRANT,Transip_WhoisContact::TYPE_ADMINISTRATIVE,Transip_WhoisContact::TYPE_TECHNICAL);
        if (in_array($type, $record_types)) {
            echo 'true';
            return TRUE;
        } else {
            return FALSE;
        }
    }


    /**
    * 3 types of whois contacts
    *   TYPE_REGISTRANT
    *   TYPE_ADMINISTRATIVE
    *   TYPE_TECHNICAL
    */
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