<?php

require_once __DIR__ .'/../../provider/genericNameSpace.php';

class contact extends genericNameSpace {

	public function __construct(){
		$this->setPrefix('contact.');
	}

	public function checkCanAssociateDomainContact($domain,$contactHandle)
	{
		try{
			$conn = $this->createConnection();
			$association_spec = array(
			'domain' => $domain,
			'owner' => true,
			'admin' => true,
			'bill' => true,
			'tech' => true);
			$result = $conn->can_associate_domain(self::API_KEY, 
			$contactHandle,
			$association_spec);
			return $result;
		}
		catch(XML_RPC2_FaultException $e)
		{
			echo $e->getMessage() . "\n\n";
		}
	}

	public function getCount($opts =  null)
	{
		try{
			$conn = $this->createConnection();
			if($opts != null)
			{
				$result = $conn->__call('count',array(self::API_KEY,$opts));
			}
			else{
				$result = $conn->__call('count',array(self::API_KEY));
			}
			return $result;
		}
		catch(XML_RPC2_FaultException $e)
		{
			echo $e->getMessage() . "\n\n";
		}
	}

	public function create($params){
		try{
			$conn = $this->createConnection();
			$result = $conn->__call('create',array(self::API_KEY,$params));
			return $result;
		}
		catch(XML_RPC2_FaultException $e)
		{
			echo $e->getMessage() . "\n\n";
		}
	}

	public function getList(){
		try{
			$conn = $this->createConnection();
			$result = $conn->__call('list',array(self::API_KEY));
			return $result;
		}
		catch(XML_RPC2_FaultException $e)
		{
			echo $e->getMessage() . "\n\n";
		}
	}

	public function info(){
		try{
			$conn = $this->createConnection();
			$result = $conn->__call('info',array(self::API_KEY));
			return $result;
		}
		catch(XML_RPC2_FaultException $e)
		{
			echo $e->getMessage() . "\n\n";
		}
	}

	public function update($contactHandle,$params)
	{
		try{
			$conn = $this->createConnection();
			$result = $conn->__call('update',array(self::API_KEY,$contactHandle,$params));
			return $result;
		}
		catch(XML_RPC2_FaultException $e)
		{
			echo $e->getMessage() . "\n\n";
		}
	}
}

?>