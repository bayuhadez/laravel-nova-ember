<?php

namespace App\Services;

use App\Models\Phone;
use Illuminate\Database\Eloquent\Model;

class PhoneService
{
    /**
     * Create Phone based on relation attributes
     * then set Model's defined property that related with phone
     *
     * @param array $attributes contains [
     *   string 'number'
     *   int 'country-id'
     * ]
     * @param Model $model
     * @param string $relationName is Model's relation
     *
     * @return void
     */
    public function createBelongsToRelationFromAttributes(
        array $attributes,
        Model &$model,
        string $relationName
    ): void
    {
        // make sure number is not empty
        if (!empty($attributes['number'])) {

            // then create Phone
            $phone = Phone::create([
                'number' => $attributes['number'],
                'country_id' => $attributes['country-id'],
                'type' => $attributes['type'] ?? Phone::TYPE_PHONE,
            ]);

            // update model property
            $model->$relationName()->associate($phone);
        }
    }

    /**
     * - Update Phone relation's attributes if phone is exists
     * - or create Phone if it is not exists before
     * - or delete PHone if attribute's `number` is empty
     *
     * @param array $attributes contains [
     *   string 'number'
     *   int 'country-id'
     * ]
     * @param Model $model
     * @param string $relationName is Model's relation
     *
     * @return void
     */
    public function updateBelongsToRelationFromAttributes(
        array $attributes,
        Model &$model,
        string $relationName
    ): void
    {
        // if phone is exists
        if (!empty($attributes['id'])) {

            if (empty($attributes['number'])) {
                // delete Phone
                $phone = $model->$relationName;
                $phone->delete();

            } else {
                // update phone
                $phone = $model->$relationName;

                if ($phone->id == $attributes['id']) {
                    $phone->update([
                        'number' => $attributes['number'],
                        'country_id' => $attributes['country-id'],
                    ]);
                }
            }

        } else {
            // else phone is not exists
            // then create for relation
            $this->createBelongsToRelationFromAttributes(
                $attributes,
                $model,
                $relationName
            );
        }
    }

}
