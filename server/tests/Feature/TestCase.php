<?php

namespace Tests\Feature;

use CloudCreativity\LaravelJsonApi\Testing\MakesJsonApiRequests;
use Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
	use MakesJsonApiRequests;

	protected $apiNamespace; // string

	protected $apiUrl; // string

	public function setUp()
	{
		parent::setUp();

		$this->apiNamespace = config('json-api-v1.url.namespace'); // '/api/v1'

		$this->apiUrl = config('app.url').$this->apiNamespace; // 'https://web:8080/api/v1'
	}
}
