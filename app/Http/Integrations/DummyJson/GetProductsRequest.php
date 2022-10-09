<?php

namespace App\Http\Integrations\DummyJson;

use App\Http\Integrations\DummyJson\DummyJsonConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonConnector;
use Sammyjo20\Saloon\Http\SaloonRequest;

class GetProductsRequest extends SaloonRequest
{
	protected ?string $connector = SaloonConnector::class;

	protected ?string $method = Saloon::GET;

	public function defineEndpoint(): string
	{
		return '/products';
	}

	public function __construct()
	{

	}
}
