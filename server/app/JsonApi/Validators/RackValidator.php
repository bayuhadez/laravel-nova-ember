<?php

namespace App\JsonApi\Validators;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;
use Illuminate\Validation\Rule;
use App\Rules\UniqueNameInWarehouse;

class RackValidator extends AbstractValidators
{

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = ['warehouse'];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [];

    /**
     * The filters a client is allowed send.
     *
     * @var string[]|null
     *      the allowed filters, an empty array for none allowed, or null to allow all.
     */
    protected $allowedFilteringParameters = ['codeExist', 'nameExist', 'inWarehouse'];

    protected $messages = [
        'code.unique' => 'Kode Rak sudah digunakan',
        'name.string' => 'Nama Rak harus string',
        'quantity.number' => 'Quantity harus numeric',
    ];

    /**
     * Get resource validation rules.
     *
     * @param mixed|null $record
     *      the record being updated, or null if creating a resource.
     * @return mixed
     */
    protected function rules($record = null): array
    {
        $rules = [
            'quantity' => 'required|numeric'
        ];
        
        if (empty($record)) {
            // create record
            $warehouseId = request()
                ->get('data')
                    ['relationships']
                    ['warehouse']
                    ['data']
                    ['id'];
            $rules['code'] = 'unique:racks';
            $rules['name'] = [
                'required',
                'string',
                new UniqueNameInWarehouse($warehouseId)
            ];
        } else {
            // edit record
            $rules['code'] = [
                Rule::unique('racks')->ignore($record->id)
            ];
            $rules['name'] = [
                'required',
                'string',
                new UniqueNameInWarehouse($record->warehouse_id)
            ];
        }
        return $rules;
    }

    /**
     * Get query parameter validation rules.
     *
     * @return array
     */
    protected function queryRules(): array
    {
        return [
            //
        ];
    }

}
