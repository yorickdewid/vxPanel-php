<?php

require_once(__DIR__.'/../../../etc/lib/api/transip/DomainService.php');
require_once(__DIR__.'/../../../etc/lib/api/transip/WhoisContact.php');

class module_controller extends ctrl_module
{

    const ADMIN_SUFFIX = 'Adm';
    const TECHNICAL_SUFFIX = 'Tec';
    const REGISTRANT_SUFFIX = 'Reg';
//security that someone only edits their domains>
    static $editwhois = null;
    static $test = true;
    static $updated;
    static $whoisContacts = array(
        Transip_WhoisContact::TYPE_REGISTRANT => self::REGISTRANT_SUFFIX,
        Transip_WhoisContact::TYPE_ADMINISTRATIVE => self::ADMIN_SUFFIX,
        Transip_WhoisContact::TYPE_TECHNICAL => self::TECHNICAL_SUFFIX
        );

    static function getDisplayDomains()
    {
        global $zdbh;
        global $controller;
        $currentuser = ctrl_users::GetUserDetail();
        $line = '<div class="zgrid_wrapper">';
        $line .= "<h2>" . ui_language::translate("Manage Domains") . "</h2>";
        $line .= "" . ui_language::translate("Choose from the list of domains below") . "";
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
        global $controller;
    if(self::$editwhois != null) // still executed if this statement is not present... though html is not shown
    {
        $domain = ''; // test
        $line = '<!-- DNS FORM -->';
        $line .= '<div>';
        $line .= '<form action="./?module=manage_whois&action=UpdateWhois" method="post">';
        $line .= '<input id="domainId" name="domainId" value="' . $controller->GetControllerRequest('FORM', 'inDomain'). '" type="hidden">';
        $line .= '<!-- TABS -->';
        $line .= '<div id="dnsRecords">';
        $line .= '<ul class="nav nav-tabs">';
        if (self::IsTypeAllowed(Transip_WhoisContact::TYPE_REGISTRANT)) {
            $line .= '    <li class="active"><a href="#typeReg" data-toggle="tab">Registrant(owner) contact</a></li>';
        }
        if (self::IsTypeAllowed(Transip_WhoisContact::TYPE_ADMINISTRATIVE)) {
            $line .= '    <li><a href="#typeAdm" data-toggle="tab">Adminstrative contact</a></li>';
        }
        if (self::IsTypeAllowed(Transip_WhoisContact::TYPE_TECHNICAL)) {
            $line .= '    <li><a href="#typeTec" data-toggle="tab">Technical contact</a></li>';
        }
        $line .= '</ul>';
        $line .= '<!-- END TABS -->';
        $line .= '<div class="tab-content">';
        //test
        if(self::$test)
        {
            $whoisTest = array(self::createFakeWhoisContacts(Transip_WhoisContact::TYPE_REGISTRANT),self::createFakeWhoisContacts(Transip_WhoisContact::TYPE_ADMINISTRATIVE),self::createFakeWhoisContacts(Transip_WhoisContact::TYPE_TECHNICAL));
            $data = self::rewriteObjectForForm($whoisTest);
        }
        else{
            $data = self::getExistingWhoisContacts($domain);
        }
        $line .= self::getTabContact('Registrant Contact',$whoisContacts['registrant'],self::REGISTRANT_SUFFIX,Transip_WhoisContact::TYPE_REGISTRANT,$data[0]);
        $line .= self::getTabContact('Administrative Contact',$whoisContacts['administrative'],self::ADMIN_SUFFIX,Transip_WhoisContact::TYPE_ADMINISTRATIVE,$data[1]);
        $line .= self::getTabContact('Technical Contact',$whoisContacts['technical'],self::TECHNICAL_SUFFIX,Transip_WhoisContact::TYPE_TECHNICAL,$data[2]);
        $line .= '<input name="newRecords" value="0" type="hidden">';
        $line .= '</div> <!-- END TABS CONTENT -->';
        /* END TABS SECTION */
        $line .= '</div> <!-- END TABS -->';
        $line .= self::getCSFR_Tag();
        $line .= "</form>";
        $line .= '</div>';
        return $line;
    }
}

static function getExistingWhoisContacts($domain){
    try{
        //getinfo
        $domainObject = Transip_DomainService::getInfo($domain);
        $whoisContacts = $domainObject->Transip_WhoisContact;
        $contacts = self::rewriteObjectForForm($whoisContacts);
        return $contacts;
    }
    catch(Exception $e)
    {
        throw $e;
    }
}

static function rewriteObjectForForm($whoisContacts){
    $contacts = array();
    foreach($whoisContacts as $whoisContact)
    {
        $type = $whoisContact->type;
        $formContact['Type'.self::$whoisContacts[$type]] = $type;
        $formContact['FirstName'.self::$whoisContacts[$type]] = $whoisContact->firstName;
        $formContact['LastName'.self::$whoisContacts[$type]] = $whoisContact->lastName;
        $formContact['CompanyName'.self::$whoisContacts[$type]] = $whoisContact->companyName;
        $formContact['CompanyKvk'.self::$whoisContacts[$type]] = $whoisContact->companyKvk;
        $formContact['CompanyType'.self::$whoisContacts[$type]] = $whoisContact->companyType;
        $formContact['Street'.self::$whoisContacts[$type]] = $whoisContact->street;
        $formContact['Number'.self::$whoisContacts[$type]] = $whoisContact->number;
        $formContact['PostalCode'.self::$whoisContacts[$type]] = $whoisContact->postalCode;
        $formContact['City'.self::$whoisContacts[$type]] = $whoisContact->city;
        $formContact['Phone'.self::$whoisContacts[$type]] = $whoisContact->phoneNumber;
        $formContact['Fax'.self::$whoisContacts[$type]] = $whoisContact->faxNumber;
        $formContact['Email'.self::$whoisContacts[$type]] = $whoisContact->email;
        $formContact['Country'.self::$whoisContacts[$type]]  = $whoisContact->country;
        $contacts[] = $formContact;
    }
    return $contacts;
}

static function getTabContact($name,$whoisContact,$idSuffix,$type,$data)
{
    if (self::IsTypeAllowed($type)) {
        if ($type === Transip_WhoisContact::TYPE_REGISTRANT ) {
            $activeCss = 'active';
        } else {
            $activeCss = '';
        }
        $line = '<!-- ' . $type . ' RECORDS -->';
        $line .= '<div class="tab-pane ' . $activeCss . '" id="type' . $idSuffix. '">';
        $line .= '<div><p>Edit your whois information for the '.$type.' contact here.</p></div>';
        $line .= '<table class="table table-striped">';
        $line .= '<tr>';
        $line .= '<th>* First Name :</th>';
        $line .= '<td><input name="inFirstName'.$idSuffix.'" type="text" id="inFirstName'.$idSuffix.'" size="40" value="'.$data['FirstName'.$idSuffix].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* Last Name :</th>';
        $line .= '<td><input name="inLastName'.$idSuffix.'" type="text" id="inLastName'.$idSuffix.'" size="40" value="'.$data['LastName'.$idSuffix].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* Street :</th>';
        $line .= '<td><input name="inStreet'.$idSuffix.'" type="text" id="inStreet'.$idSuffix.'" size="20" value="'.$data['Street'.$idSuffix].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* Number :</th>';
        $line .= '<td><input name="inNumber'.$idSuffix.'" type="text" id="inNumber'.$idSuffix.'" size="20" value="'.$data['Number'.$idSuffix].'"/></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* City :</th>';
        $line .= '<td><input name="inCity'.$idSuffix.'" type="text" id="inCity'.$idSuffix.'" size="15" value="'.$data['City'.$idSuffix].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* Postal Code :</th>';
        $line .= '<td><input name="inPostalCode'.$idSuffix.'" type="text" id="inPostalCode'.$idSuffix.'" size="15" value="'.$data['PostalCode'.$idSuffix].'" /></td>';
        $line .= '</tr>';
        $line .= '<tr>';
        $line .= '<th>* Country :</th>';
        $countries = self::getCountryList();
        $line .= '<td>';
        $current = $data['Country'.$idSuffix];
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
    $line .= '<td><input name="inPhone'.$idSuffix.'" type="text" id="inPhone'.$idSuffix.'" size="20" value="'.$data['Phone'.$idSuffix].'" /></td>';
    $line .= '</tr>';
    $line .= '<tr>';
    $line .= '<th>* Email Address :</th>';
    $line .= '<td><input name="inEmail'.$idSuffix.'" type="text" id="inEmail'.$idSuffix.'" size="40" value="'.$data['Email'.$idSuffix].'" /></td>';
    $line .= '</tr>';
    $line .= '<tr>';
    $line .= '<th> Company Name :</th>';
    $line .= '<td><input name="inCompanyName'.$idSuffix.'" type="text" id="inCompanyName'.$idSuffix.'" size="40" value="'.$data['CompanyName'.$idSuffix].'" /></td>';
    $line .= '</tr>';
    $line .= '<tr>';
    $line .= '<th> Company KVK :</th>';
    $line .= '<td><input name="inCompanyKVK'.$idSuffix.'" type="text" id="inCompanyKVK'.$idSuffix.'" size="40" value="'.$data['CompanyKvk'.$idSuffix].'" /></td>';
    $line .= '</tr>';
    $line .= '<tr>';
    $line .= '<th> Company Type :</th>';
    $line .= '<td>';
    $line .= '<select name="inCompanyType'.$idSuffix.'" id="inCompanyType'.$idSuffix.'" style="">';
    $companyList = self::getCompanyTypeList();
    $curCompany = $data['CompanyType'.$idSuffix];
    foreach ($companyList as $short => $companyname) {
        if(strtolower($short) == strtolower($curCompany)) {
            $selected = "SELECTED";
        }
        else {
            $selected = "";
        }
        $line .= '<option value="'.$short.'" '.$selected.'>'.$companyname.'</option>';
    }
    $line .= '</select>';
    $line .= '</td>';
    $line .= '</tr>';
    $line .= '<tr>';
    $line .= '<th> Fax Number :</th>';
    $line .= '<td><input name="inFax'.$idSuffix.'" type="text" id="inFax'.$idSuffix.'" size="40" value="'.$data['Fax'.$idSuffix].'" /></td>';
    $line .= '</tr>';
    $line .= '</tr>';
    $line .= '<tr>';
    $line .= '<th>&nbsp;</th>';
    $line .= '<td align="right"><button class="button-loader btn btn-primary" id="button" type="submit" > Update Whois </button</td>';
    $line .= '</tr>';
    $line .= '</table>';
    $line .= '</div>';
    return $line;
}
}

private static function createFakeWhoisContacts($type){
    $contact  = new Transip_WhoisContact();
    $contact->type = $type;
    $contact->firstName = 'arie';
    $contact->lastName = 'kaas';
    $contact->companyName = 'ariekaas productions';
    $contact->companyKvk = 122425535;
    $contact->companyType = 'BV';
    $contact->street = 'kaasfabriekstraat';
    $contact->number = 117;
    $contact->postalCode = '4437ll';
    $contact->city = 'Kaaszilla';
    $contact->phoneNumber = "+31020300302";
    $contact->faxNumber = '2-10-240-2-40-1';
    $contact->email = 'nopeeee@hotmail.com';
    $contact->country = 'nl';
    return $contact;
}

static function IsTypeAllowed($type)
{
    global $zdbh;
    $record_types = array(Transip_WhoisContact::TYPE_REGISTRANT,Transip_WhoisContact::TYPE_ADMINISTRATIVE,Transip_WhoisContact::TYPE_TECHNICAL);
    if (in_array($type, $record_types)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

static function getCountryList()
{
    $countryList = Transip_WhoisContact::$possibleCountryCodes;
    //$countryList = array('nl'=>'nederland');
    return $countryList;
}

static function getCompanyTypeList()
{
    $companyList = Transip_WhoisContact::$possibleCompanyTypes;
    return $companyList;
}

static function doDisplayWhois()
{
    global $zdbh;
    global $controller;
    runtime_csfr::Protect();
    self::$editwhois = $controller->GetControllerRequest('FORM', 'inDomain');
    return;
}

static function doUpdateWhois(){
    try{
        global $controller;
        global $zdbh;
        $contacts = array();
        foreach(self::$whoisContacts as $type => $suffix)
        {
            $contact = new Transip_WhoisContact();
            $contact->type        = $type;
            $contact->firstName   = $controller->GetControllerRequest('FORM', 'inFirstName'.$suffix); //verplicht
            $contact->lastName    = $controller->GetControllerRequest('FORM', 'inLastName'.$suffix);
            $contact->companyName = $controller->GetControllerRequest('FORM', 'inCompanyName'.$suffix);
            $contact->companyKvk  = $controller->GetControllerRequest('FORM', 'inCompanyKVK'.$suffix);
            $contact->companyType = $controller->GetControllerRequest('FORM', 'inCompanyType'.$suffix);
            $contact->street      = $controller->GetControllerRequest('FORM', 'inStreet'.$suffix);
            $contact->number      = $controller->GetControllerRequest('FORM', 'inNumber'.$suffix);
            $contact->postalCode  = $controller->GetControllerRequest('FORM', 'inPostalCode'.$suffix); 
            $contact->city        = $controller->GetControllerRequest('FORM', 'inCity'.$suffix);
            $contact->phoneNumber = $controller->GetControllerRequest('FORM', 'inPhone'.$suffix);
            $contact->faxNumber   = $controller->GetControllerRequest('FORM', 'inFax'.$suffix);
            $contact->email       = $controller->GetControllerRequest('FORM', 'inEmail'.$suffix);
            $contact->country     = $controller->GetControllerRequest('FORM', 'inCountry'.$suffix);
            $contacts[] = $contact;
        }
        echo "\n\n";
        $domainId = $controller->GetControllerRequest('FORM', 'domainId');
        $sql = $zdbh->prepare("SELECT * FROM x_vhosts WHERE vh_id_pk = :domainid");
        $sql->bindParam(':domainid', $domainId);
        $sql->execute();
        while ($rowdomains = $sql->fetch()) {
           $domainname = $rowdomains['vh_name_vc'];
        }
        Transip_DomainService::setContacts($domainname,$contacts);
        self::$updated = true;
        return true;
    }
    catch(Exception $e)
    {
        if($e->faultcode == 101)
        {
            self::$updated = true;
            return true;
        }
    }
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

static function getResult(){
     if (!fs_director::CheckForEmptyValue(self::$updated)) {
        return ui_sysmessage::shout(ui_language::translate("Whois contact information succesfully updated"), "zannounceok");
    }
    return;
}
}

?>