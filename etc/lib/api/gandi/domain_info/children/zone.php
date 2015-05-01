<?php

class zone{
	
	const PREFIX = 'zone,'
	const PREFIX_VERSION = 'zone.version.';
	const PREFIX_RECORD  = 'zone.record';
	
	/**
	 * [clone description]
	 * @param  integer $zoneId
	 * @param  integer $versionId
	 * @param  string $name opt
	 * @return array ZoneReturn
	 */
	public function clone($zoneId,$versionId = 0,$name = null){

		$conn = $this->createConnection(self::PREFIX);
		if($name != null)
		{
			$params = array('name'=>$name);
			$result = $conn->clone(self::API_KEY,$zoneId,$versionId,$params);
		}
		else{
			$result = $conn->clone(self::API_KEY,$zoneId,$versionId);
		}
		return $result;
	}

	/**
	 * [getCount description]
	 * @param  array $opts ZoneListOptions
	 * @return [int number of zones
	 */
	public function getCount($opts = null){
		$conn = $this->createConnection(self::PREFIX);
		if($opts != null)
		{
			$result = $conn->count(self::API_KEY,$opts);
		}
		else{
			$result = $conn->count(self::API_KEY);
		}
		return $result;
	}

	/**
	 * [create description]
	 * @param  string $name
	 * @return array ZoneReturn
	 */
	public function create($name){
		$params = array(
			'name' => $name);
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->create(self::API_KEY,$params);
		return $result;
	}

	/**
	 * [delete description]
	 * @param  integer $zone_id
	 * @return boolean
	 */
	public function delete($zone_id){
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->create(self::API_KEY,$zone_id);
		return $result;
	}

	/**
	 * [getInfo description]
	 * @param  integer $zone_id
	 * @return array ZoneReturn
	 */
	public function getInfo($zone_id){
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->info(self::API_KEY,$zone_id);
		return $result;
	}

	/**
	 * [setZone description]
	 * @param string $domain
	 * @param integer $zoneId
	 * @return array DomainReturn
	 */
	public function setZone($domain,$zoneId){
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->set(self::API_KEY,$domain,$zone_id);
		return $result;
	}

	/**
	 * [updateZone description]
	 * @param  integer $zone_id
	 * @param  string $name
	 * @return array ZoneReturn
	 */
	public function updateZone($zone_id,$name = null)
	{
		$conn = $this->createConnection(self::PREFIX);
		if($name != null)
		{
			$params = array(
			'name' => $name);
			$result = $conn->update(self::API_KEY,$zone_id,$params);
		}
		else{
			$result = $conn->update(self::API_KEY,$zone_id);
		}
		return $result;
	}

	/**
	 * [countVersion description]
	 * @param  integer $zoneId
	 * @param  integer $versionId
	 * @param  array $opts RecordListOptions
	 * @return integer count versions of zone
	 */
	public function countVersion($zoneId,$versionId,$opts = null)
	{
		$conn = $this->createConnection(self::PREFIX_VERSION);
		if($opts != null)
		{
			$result = $conn->count(self::API_KEY,$zoneId,$versionId,$opts);
		}
		else{
			$result = $conn->count(self::API_KEY,$zoneId,$versionId);
		}
		return $result;
	}

	/**
	 * [deleteVersion description]
	 * @param  integer $zoneId
	 * @param  integer $versionid
	 * @return boolean
	 */
	public function deleteVersion($zoneId,$versionId)
	{
		$conn = $this->createConnection(self::PREFIX_VERSION);
		$result = $conn->delete(self::API_KEY,$zoneId,$versionId);
		return $result;
	}

	/**
	 * [listVersion description]
	 * @param  integer $zoneId
	 * @param  integer $versionId optional
	 * @return array ZoneVersionReturn
	 */
	public function listVersion($zoneId,$versionId = null)
	{
		$conn = $this->createConnection(self::PREFIX_VERSION);
		if($versionId != null)
		{
			$result = $conn->list(self::API_KEY,$zoneId,$versionId);
		}
		else{
			$result = $conn->list(self::API_KEY,$zoneId);
		}
		return $result;
	}

	/**
 	* [newVersion description]
	* @param  integer $zoneId
 	* @param  integer $versionId
 	* @return integer created version number
 	*/
	public function newVersion($zoneId,$versionId = 0)
	{
		$conn = $this->createConnection(self::PREFIX_VERSION);
		$result = $conn->new(self::API_KEY,$zoneId,$versionId);
		return $result;
	}

	/**
 	* It can take up to 20 minutes for a new zone 
 	* or version to be loaded on Gandi’s nameservers
	* @param  integer $zoneId
 	* @param  integer $versionId
 	*/
	public function setVersion($zoneId,$versionId)
	{
		$conn = $this->createConnection(self::PREFIX_VERSION);
		$result = $conn->set(self::API_KEY,$zoneId,$versionId);
		return $result;
	}



	/* RECORDS */

	/**
	 * [addRecord description]
	 * @param  integer $zoneId
 	 * @param  integer $versionId
	 * @param array $params ZoneRecord mandatory(name,type,value)
	 * @return array ZoneRecordReturn
	 */
	public function addRecord($zoneId,$versionId,$params)
	{
		$conn = $this->createConnection(self::PREFIX_RECORD);
		$result = $conn->add(self::API_KEY,$zoneId,$versionId,$params);
		return $result;
	}

	/**
	 * [countRecord description]
	 * @param  integer $zoneId
 	 * @param  integer $versionId
	 * @param  array $opts RecordListOptions
	 * @return integer records
	 */
	public function countRecord($zoneId,$versionId,$opts = null)
	{
		$conn = $this->createConnection(self::PREFIX_RECORD);
		if($opts != null)
		{
			$result = $conn->count(self::API_KEY,$zoneId,$versionId,$opts);
		}
		else{
			$result = $conn->count(self::API_KEY,$zoneId,$versionId);
		}
		return $result;
	}

	/**
	 * [deleteRecord description]
	 * @param  integer $zoneId
	 * @param  integer $versionId
	 * @param  array $opts RecordDeleteOptions
	 * @return integer number of records deleted
	 */
	public function deleteRecord($zoneId,$versionId,$opts =null)
	{
		$conn = $this->createConnection(self::PREFIX_RECORD);
		if($opts != null)
		{
			$result = $conn->delete(self::API_KEY,$zoneId,$versionId,$opts);
		}
		else{
			$result = $conn->delete(self::API_KEY,$zoneId,$versionId);
		}
		return $result;
	}

	/**
	 * [listRecord description]
	 * @param  integer $zoneId
 	 * @param  integer $versionId
	 * @param  array $opts RecordListOptions
	 * @return array 
	 */
	public function listRecord($zoneId,$versionId,$opts =null)
	{
		$conn = $this->createConnection(self::PREFIX_RECORD);
		if($opts != null)
		{
			$result = $conn->list(self::API_KEY,$zoneId,$versionId,$opts);
		}
		else{
			$result = $conn->list(self::API_KEY,$zoneId,$versionId);
		}
		return $result;
	}

	/**
	 * [setRecord description]
	 * @param  integer $zoneId
 	 * @param  integer $versionId
	 * @param array $params a Bind zone as string (see RFC 1035) or a list of ZoneRecord
	 * @return array ZoneRecordReturn
	 */
	public function setRecord($zoneId,$versionId,$params)
	{
		$conn = $this->createConnection(self::PREFIX_RECORD);
		$result = $conn->set(self::API_KEY,$zoneId,$versionId,$params);
		return $result;
	}
	/**
	 * [updateRecord description]
	 * @param  integer $zoneId
 	 * @param  integer $versionId
	 * @param  array $opts RecordUpdateOptions
	 * @param  array $params  ZoneRecord
	 * @return array ZoneRecordReturn updated record
	 */
	public function updateRecord($zoneId,$versionId,$opts,$params)
	{
		$conn = $this->createConnection(self::PREFIX_RECORD);
		$result = $conn->update(self::API_KEY,$zoneId,$versionId,$opts,$params);
		return $result;
	}

}
?>