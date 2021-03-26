<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use CloudCreativity\LaravelJsonApi\Document\Error;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
use CloudCreativity\LaravelJsonApi\Http\Requests\FetchResource;

class UsersController extends JsonApiController
{
    /**
     * Return Auth user
     *
     * @return Illuminate\Http\Response
     */
    protected function current()
    {
        return $this->reply()->content(Auth::user());
    }
}
