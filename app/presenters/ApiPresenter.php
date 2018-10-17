<?php
namespace App\Presenters;

use Nette;
use Nette\Utils\Json;
use App\Redis;

class ApiPresenter extends Nette\Application\UI\Presenter
{
	/** @var \App\Redis @inject */
	public $redis;

	private $json;

	const OK = ['jsonrpc' => '2.0', 'result' => 'OK', 'id' => null];
	const RESULT = ['jsonrpc' => '2.0', 'result' => []];
	const ERROR = ['jsonrpc' => '2.0', 'error' => ['code' => -32600, 'message' => 'Invalid Request'], 'id' => null];

	public function actionScoreAdd()
	{
		$params = $this->parseJson();
		if (empty($params['params']) ||
			empty((int)$params['params']['id_hry']) || empty((int)$params['params']['id_usera']) || empty((int)$params['params']['score']))
		{
			$this->sendJson($this->returnError());
		}

		$hra = 'hra:'.(int)$params['params']['id_hry'];
		$user = 'user:'.(int)$params['params']['id_usera'];
		$this->redis->zAdd($hra, (int)$params['params']['score'], $user);

		$this->sendJson($this->returnOK());
	}

	public function actionScoreReport()
	{
		$params = $this->parseJson();
		if (empty($params['params']) || empty((int)$params['params']['id_hry']))
		{
			$this->sendJson($this->returnError());
		}

		$hra = 'hra:'.(int)$params['params']['id_hry'];
		$results = $this->redis->zRevRange($hra, 10);
		$data = [];
		foreach ($results as $user => $score) {
			$data[] = ['user' => $user, 'score' => $score];
		}

		$this->sendJson($this->returnResult($data));
	}

	public function renderDefault()
	{
		$this->sendJson($this->returnError());
	}

	private function parseJson(): array
	{
		if ($this->json) {
			return $this->json;
		}
		try {
			return $this->json = Json::decode($this->getHttpRequest()->getRawBody(), 1);
		} catch (\Exception $e) {
			$this->sendJson(self::ERROR);
		}
	}

	private function returnOK(): array
	{
		$params = $this->parseJson();
		$return = self::OK;
		if (!empty($params['id']) && (int)$params['id'])
		{
			$return['id'] = $params['id'];
		}
		return $return;
	}

	private function returnResult(array $data): array
	{
		$params = $this->parseJson();
		$return = self::RESULT;
		if (!empty($params['id']) && (int)$params['id'])
		{
			$return['id'] = $params['id'];
		}
		$return['result'] = $data;
		return $return;
	}

	private function returnError(): array
	{
		$params = $this->parseJson();
		$return = self::ERROR;
		if (!empty($params['id']) && (int)$params['id'])
		{
			$return['id'] = $params['id'];
		}
		return $return;
	}

}
