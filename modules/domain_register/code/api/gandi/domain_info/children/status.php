<?php

class status extends domainNameSpace{
	
	const PREFIX = 'status.';

	/**
	 * [lock description]
	 * @param  string $domain
	 * @return array  DomainStatusUpdateOperationReturn see gandi doc
	 */
	public function lock(string $domain){
		$conn = $this->createConnection(self::PREFIX}});
		$result = $conn->lock(self::API_KEY, $domain);
		return $result;
	}

	public function unLock(string $domain){
		$conn = $this->createConnection(self::PREFIX}});
		$result = $conn->unlock(self::API_KEY, $domain);
		return $result;
	}
	}
}
