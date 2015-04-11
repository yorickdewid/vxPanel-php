<?php

require_once __DIR__ .'/../../provider/genericNameSpace.php';

class operation extends genericNameSpace {
	
	public function __construct(){
		$this->setPrefix('operation.');
	}

	public function getInfo(array $ids)
	{
		$conn = $this->createConnection();
		$result = $conn->info(self::API_KEY,$ids);
		return $result;
	}

}

?>