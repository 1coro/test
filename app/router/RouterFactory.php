<?php
namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;
use Ublaboo\ApiRouter\ApiRoute;

final class RouterFactory
{
	/**
	 * @return Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList;

		$router[] = new ApiRoute('/api/score', 'Api', [
			'methods' => ['POST' => 'scoreAdd']
		]);

		$router[] = new ApiRoute('/api/top', 'Api', [
			'methods' => ['POST' => 'scoreReport']
		]);

		$router[] = new Route('', 'Api:default');

		return $router;
	}

}