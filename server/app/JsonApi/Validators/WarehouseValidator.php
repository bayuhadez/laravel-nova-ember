<?php

namespace App\JsonApi\Validators;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;
use Illuminate\Validation\Rule;

class WarehouseValidator extends AbstractValidators
{

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'companies',
        'company-warehouses',
        'warehouseCategories',
        'racks'
    ];

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
    protected $allowedFilteringParameters = ['codeExist', 'nameExist', 'inCompany'];
    
    protected $messages = [
        'name.required' => 'Nama Lokasi tidak boleh kosong',
        'name.unique' => 'Nama Lokasi sudah digunakan',
        'name.max' => 'Nama Lokasi terlalu panjang, maksimal 20 karakter',
        'code.unique' => 'Kode Lokasi sudah digunakan',
        'code.max' => 'Kode Lokasi terlalu panjang, maksimal 10 karakter',
        'phone.numeric' => 'Nomor Telephone harus numeric',
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
            'phone' => 'nullable|numeric'
        ];

        if (empty($record)) {
            // create record
            $rules['name'] = 'required|unique:warehouses|max:20';
            $rules['code'] = 'unique:warehouses|max:10';
        } else {
            // edit record
            $rules['name'] = [
                'required',
                'max:20',
                Rule::unique('warehouses')->ignore($record->id)
            ];
            $rules['code'] = [
                Rule::unique('warehouses')->ignore($record->id)
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
