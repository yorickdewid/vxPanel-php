<?php

class mailbox extends domainNameSpace{

	const PREFIX = 'mailbox.'

	/**
	 * [getCount description]
	 * @param  string $domain
	 * @param  array $opts MailboxListOptions
	 * @return int number of mailbox
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
	 * @param  string $domain
	 * @param  string $login
	 * @param  string $password
	 * @param  string $fallback_email optional
	 * @param  int $quota optional
	 * @return array Mailboxreturn
	 */
	public function create($domain,$login,$password,$fallback_email = null,quota = null){
		$params = array(
			'password'=>$password)
		if($fallback_email != null)
		{
			$params['fallback_email'] = $fallback_email;
		}
		if($quota != null)
		{
			$params['quota'] = $quota;
		}
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->create(self::API_KEY,$domain,$login,$params);
		return $result;
	}

	/**
	 * [delete description]
	 * @param  string $domain
	 * @param  string $login
	 * @return boolean 1,0
	 */
	public function delete($domain,$login)
	{
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->delete(self::API_KEY,$domain,$login);
		return $result;
	}

	/**
	 * [getInfo description]
	 * @param  string $domain
	 * @param  string $login
	 * @return array mailboxreturn
	 */
	public function getInfo($domain,$login)
	{
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->info(self::API_KEY,$domain,$login);
		return $result;
	}

	/**
	 * [getCount description]
	 * @param  string $domain
	 * @param  array $opts MailboxListOptions
	 * @return array MailboxListReturn
	 */
	public function getList($domain,$opts = null)
	{
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
	 * @param  string $login
	 * @return array operationreturn
	 */
	public function purge($domain,$login){
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->purge(self::API_KEY,$domain,$login);
		return $result;
	}

	/**
	 * [update description]
	 * @param  string $domain
	 * @param  string $login
	 * @param  string $password opt
	 * @param  string $fallback_email opt
	 * @param  int $quota opt
	 * @return array mailboxreturn
	 */
	public function update($domain,$login,$password = null,$fallback_email = null,$quota = nul)
	{
		$params = array();
		if($password != null)
		{
			$params['password']=$password;
		}
		if($fallback_email != null)
		{
			$params['fallback_email'] = $fallback_email;
		}
		if($quota != null)
		{
			$params['quota'] = $quota;
		}
		$conn = $this->createConnection(self::PREFIX);
		if(!empty($params))
		{
			$result = $conn->update(self::API_KEY,$domain,$login,$params);
		}
		else{
			$result = $conn->update(self::API_KEY,$domain,$login);
		}
		return $result;
	}

	/**
	 * [setAlias description]
	 * @param string $domain
	 * @param string $login 
	 * @param array $aliases e.g array('stephanie')
	 */
	public function setAlias(string $domain,string $login,array $aliases){
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->alias.set(self::API_KEY,$domain,$login,$aliases);
		return $result;
	}

	/**
	 * [activateResponder description]
	 * @param  string $domain
	 * @param  string $login
	 * @param  string $content
	 * @param  string $date
	 * @return array operationreturn
	 */
	public function activateResponder($domain,$login,$content,$date = null){
		$params = array(
			'content'=>$content);
		if($date!=null)
		{
			$params['date'] = $date;
		}
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->responder.activate(self::API_KEY,$domain,$login,$params);
		return $result;
	}
	
	/**
	 * [deactivateResponder description]
	 * @param  string $domain
	 * @param  string $login
	 * @param  string $date
	 * @return array operationreturn
	 */
	public function deactivateResponder($domain,$login,$date =null){
		if($date!=null)
		{
			$params = array(
			'date'=>$date);
		}
		if($params != null)
		{
			$result = $conn->update(self::API_KEY,$domain,$login,$params);
		}
		else{
			$result = $conn->update(self::API_KEY,$domain,$login);
		}
		$conn = $this->createConnection(self::PREFIX);
		$result = $conn->responder.deactivate(self::API_KEY,$domain,$login,$aliases);
		return $result;
	}
}

?>