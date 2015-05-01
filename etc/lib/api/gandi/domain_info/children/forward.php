<?php

class forward extends domainNameSpace{

	const PREFIX = 'forward.'

	/**
	 * [getCount description]
	 * @param  string $domain
	 * @param  array $opts ForwardListOptions
	 * @return int number of forwards
	 */
	public function getCount($domain,$opts = null){
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
	 * @param  string $domain
	 * @param  string $source
	 * @param  array of strings
	 * @return array forwardReturn
	 */
	public function create($domain,$source,array $destinations){
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->create(self::API_KEY,$domain,$destinations);
		return $result;
	}

	/**
	 * [delete description]
	 * @param  string $domain
	 * @param  string $source
	 * @return boolean 
	 */
	public function delete($domain,$source){
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->delete(self::API_KEY,$domain,$destinations);
		return $result;
	}

	/**
	 * [getList description]
	 * @param  string $domain
	 * @param  array $opts
	 * @return array forwardReturn	 
	 */
	public function getList($domain,$opts = null){
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
	 * 
	 * @param  string $domain
	 * @param  string $source
	 * @param  array of strings
	 * @return array forwardReturn
	 */
	public function update($domain,$source,array $destinations)
	{
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->create(self::API_KEY,$domain,$destinations);
		return $result;
	}
}

?>