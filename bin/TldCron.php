<?php

require_once('/etc/zpanel/panel/etc/lib/api/transip/DomainService.php');
require_once('/etc/zpanel/panel/dryden/db/driver.class.php');
require_once('/etc/zpanel/panel/cnf/db.php');

$zdbh = new db_driver("mysql:host=$host;dbname=$dbname", $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
$zdbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


function getTldsForTable()
{
	try
	{
		$res = array();
		$tldlist = Transip_DomainService::getAllTldInfos();
		foreach ($tldlist as $idx) {
			array_push($res, $idx->name);
		}
		return $res;
	} catch (SoapFault $f) {
		return FALSE;
	}
	return;
}

function compareTldsToDatabase($tldsRetrieved){
	try{
		global $zdbh;
		$sql = "SELECT * FROM x_tld";
		$res = $zdbh->prepare($sql);
		$res->execute();
		$result = $res->fetchAll(PDO::FETCH_ASSOC);
		$newList = array();
		for($i = 0;$i < count($tldsRetrieved);$i++)
		{
			$tldApi = $tldsRetrieved[$i];
			$newList[$i] = array('tld'=>$tldApi);
			foreach($result as $tld)
			{
				if($tldApi == $tld['tld'])
				{
					$newList[$i]['skip'] = 1;
				}
			}
		}
		return $newList;
	}
	catch(Exception $e)
	{
		throw $e;
	}
}

function checkForLocalTld(){
	global $zdbh;
        $sql = "SELECT count(*) as count FROM x_tld WHERE tld = '.local'";
        $res = $zdbh->prepare($sql);
        $res->execute();
        $result = $res->fetch(PDO::FETCH_ASSOC);
	if($result['count'] == 1){
		echo 'local tld present';
		$sql = "DELETE FROM x_tld WHERE tld=:tld";
		$sql = $zdbh->prepare($sql);
		$tld = ".local";
		$sql->bindParam(':tld', $tld);
		$result = $sql->execute();
		if($result == 1){
			echo "Local tld  deleted";
		}
	}
}

function saveTldsToDatabase($tldList){
	try{
		global $zdbh;
		foreach($tldList as $row)
		{
			try{
			if(!isset($row['skip']))
			{
				$sql = "INSERT INTO x_tld (tld) VALUES (:tld)";
				$prepared = $zdbh->prepare($sql);
				$prepared->bindParam(':tld', $row['tld']);
				$prepared->execute();
			}
			}
			catch(PDOException $e){
				if($e->getCode() == 23000){
					echo "Duplicate entry, ignore";
				}
			}
		}
	}
	catch(Exception $e)
	{
		throw $e;
	}
}

$res = getTldsForTable();
if(!empty($res)){
	$res = compareTldsToDatabase($res);
	checkForLocalTld();
}
else{
	echo "empty";
	$res[0] = ['tld' => '.local'];
}
saveTldsToDatabase($res);
?>
