<?php

namespace App;

use Nette;

class Redis
{
	use Nette\SmartObject;

	/** @var string $url */
	private $url;

	/** @var Redis */
	private $connection;

	public function __construct(string $url = '127.0.0.1')
	{
		$this->url = $url;
	}

	protected function getConnection(): \Redis
	{
		if(!$this->connection instanceof \Redis)
		{
			$this->connection = new \Redis();
			$this->connection->connect($this->url);
		}
		return $this->connection;
	}

	public function zAdd(string $key, int $addScore, $value)
	{
		return $this->getConnection()->zAdd($key, ['CH', 'INCR'], $addScore, $value);
	}

	public function zRevRange(string $key, $limit = 10)
	{
		return $this->getConnection()->zRevRange($key, 0, $limit, true);
	}

}