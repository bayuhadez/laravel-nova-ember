<?php

namespace App\JsonApi\Authorizers;

use CloudCreativity\LaravelJsonApi\Auth\AbstractAuthorizer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class OrderAuthorizer extends AbstractAuthorizer
{

	/**
	 * Authorize a resource index request.
	 *
	 * @param string $type
	 *      the domain record type.
	 * @param Request $request
	 *      the inbound request.
	 * @return void
	 * @throws AuthenticationException|AuthorizationException
	 *      if the request is not authorized.
	 */
	public function index($type, $request)
	{
		$this->authenticate();
	}

	/**
	 * Authorize a resource create request.
	 *
	 * @param string $type
	 *      the domain record type.
	 * @param Request $request
	 *      the inbound request.
	 * @return void
	 * @throws AuthenticationException|AuthorizationException
	 *      if the request is not authorized.
	 */
	public function create($type, $request)
	{
		// TODO: Implement create() method.
		$this->can('create');
	}

	/**
	 * Authorize a resource read request.
	 *
	 * @param object $record
	 *      the domain record.
	 * @param Request $request
	 *      the inbound request.
	 * @return void
	 * @throws AuthenticationException|AuthorizationException
	 *      if the request is not authorized.
	 */
	public function read($record, $request)
	{
		$this->can('view', $record);
	}

	/**
	 * Authorize a resource update request.
	 *
	 * @param object $record
	 *      the domain record.
	 * @param Request $request
	 *      the inbound request.
	 * @return void
	 * @throws AuthenticationException|AuthorizationException
	 *      if the request is not authorized.
	 */
	public function update($record, $request)
	{
		// TODO: Implement update() method.
		$this->can('update');
	}

	/**
	 * Authorize a resource read request.
	 *
	 * @param object $record
	 *      the domain record.
	 * @param Request $request
	 *      the inbound request.
	 * @return void
	 * @throws AuthenticationException|AuthorizationException
	 *      if the request is not authorized.
	 */
	public function delete($record, $request)
	{
		$this->can('delete', $record);
	}

}
