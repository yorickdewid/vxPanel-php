<?php

class webredir extends domainNameSpace{

	const PREFIX = 'webredir.';

	/**
	 * [getCount description]
	 * @param  string $domain
	 * @param  array $options gandi doc WebredirListOptions
	 * @return int count
	 */
	public function getCount(string $domain,$opts = null){
		$conn = $this->createConnection(self::PREFIX}});
		if($opts != null)
		{
			$result = $conn->count(self::API_KEY, $domain,$opts);
		}
		else{
			$result = $conn->count(self::API_KEY, $domain);
		}
		return $result;
	}

	/**
	 * [create description]
	 * @param  string $domain
	 * @param  string $host source
	 * @param  string $url target
	 * @param  string $type (cloak,http302,http301)
	 * @return array WebredirReturn
	 */
	public function create($domain,$host,$url,$type = null){
		$conn = $this->createConnection(self::PREFIX);
		if($type != null)
		{
			$result = $conn->create(self::API_KEY,$domain,$host,$url,$type);
		}
		else{
			$result = $conn->create(self::API_KEY,$domain,$host,$url);
		}
		return $result;
	}

	/**
	 * [delete description]
	 * @param  string $domain
	 * @param  string $host
	 * @return boolean
	 */
	public function delete($domain,$host){
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->delete(self::API_KEY,$domain,$host);
		return $result;
	}

	/**
	 * [getCount description]
	 * @param  string $domain
	 * @param  array $options gandi doc WebredirListOptions
	 * @return array WebRedirReturn
	 */
	public function getList(string $domain,$opts = null){
		$conn = $this->createConnection(self::PREFIX}});
		if($opts != null)
		{
			$result = $conn->list(self::API_KEY, $domain,$opts);
		}
		else{
			$result = $conn->list(self::API_KEY, $domain);
		}
		return $result;
	}

	/**
	 * [update description]
	 * @param  string $domain
	 * @param  string $host
	 * @param  array $params WebRedirUpdate
	 * @return array WebRedirReturn
	 */
	public function update($domain,$host,$params = null)
	{
		$conn = $this->createConnection(self::PREFIX);
		if($params != null)
		{
			$result = $conn->update(self::API_KEY,$domain,$host,$params);
		}
		else{
			$result = $conn->update(self::API_KEY,$domain,$host);
		}
		return $result;
	}

}

?>