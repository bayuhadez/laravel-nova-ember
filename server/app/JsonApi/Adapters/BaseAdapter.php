<?php

namespace App\JsonApi\Adapters;

use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use Carbon\Carbon;

abstract class BaseAdapter extends AbstractAdapter
{
    /**
     * @param $value the value submitted by the client.
     * @param string $field the JSON API field name being deserialized.
     * @param Model $record the domain record being filled.
     * @return \DateTime|null
     */
    public function deserializeDate($value, $field, $record)
    {
        return (
            !is_null($value)
            ? (new Carbon($value))->setTimezone(new \DateTimeZone(config('app.timezone')))
            : null
        );
    }
}
