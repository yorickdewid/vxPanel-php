<?php

class host extends domainNameSpace{

	const PREFIX = 'host';

	/**
	 * [geCount description]
	 * @param  string $domain
	 * @param  array $opts HostListOptions
	 * @return int glue records
	 */
	public function getCount(string $domain,array $opts = null){
		$conn = $this->createConnection(self::PREFIX);
		if($opts != null)
		{
			$result = $conn->count(self::API_KEY,$domain,$opts);
		}
		else{
			$result = $conn->count(self::API_KEY,$domain);
		}
		return $result;
	}
	/**
	 * [create description]
	 * @param  string $fqdn 
	 * @param  array $ips adresses
	 * @return array HostCreateOperationReturn
	 */
	public function create(string $fqdn,array $ips){
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->create(self::API_KEY,$fqdn,$ips);
		return $result;
	}

	/**
	 * [delete description]
	 * @param  string $fqdn
	 * @return array HostDeleteOperationReturn
	 */
	public function delete(string $fqdn){
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->delete(self::API_KEY,$fqdn);
		return $result;
	}

	/**
	 * [getInfo description]
	 * @param  string $fqdn
	 * @return array (domain,ips,name)
	 */
	public function getInfo(string $fqdn){
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->info(self::API_KEY,$fqdn);
		return $result;
	}

	/**
	 * [getList description]
	 * @param  string $domain
	 * @param  array $opts HostListOptions
	 * @return array HostListOptions
	 */
	public function getList(string $domain,array $opts = null){
		$conn = $this->createConnection(self::PREFIX);
		if($opts != null)
		{
			$result = $conn->list(self::API_KEY,$domain,$opts);
		}
		else{
			$result = $conn->list(self::API_KEY,$domain);
		}
		return $result;
	}

	/**
	 * [update description]
	 * @param  string $fqdn
	 * @param  array $ips 
	 * @return array HostUpdateOperationReturn
	 */
	public function update(string $fqdn,array $ips)
	{
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->update(self::API_KEY,$fqdn,$ips);
		return $result;
	}

}
?>