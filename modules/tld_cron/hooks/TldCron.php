<?php

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
		print_r($result);
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

function saveTldsToDatabase($tldList){
	try{
		global $zdbh;
		print_r($tldList);
		foreach($tldList as $row)
		{
			if(!isset($row['skip']))
			{
				$sql = "INSERT INTO x_tld (tld) VALUES (:tld)";
				$prepared = $zdbh->prepare($sql);
				$prepared->bindParam(':tld', $row['tld']);
				$prepared->execute();
			}
		}
	}
	catch(Exception $e)
	{
		throw $e;
	}
}

$res = getTldsForTable();
$res = compareTldsToDatabase($res);
saveTldsToDatabase($res);
?>
