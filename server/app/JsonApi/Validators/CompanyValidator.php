<?php

namespace App\JsonApi\Validators;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;
use Illuminate\Validation\Rule;

class CompanyValidator extends AbstractValidators
{

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'banners',
        'children-company',
        'customers',
        'company-addresses',
        'company-addresses.address',
        'company-warehouses',
        'company-customers',
        'created-by',
        'parent-company',
        'products',
        'product-categories',
        'services',
        'staffs',
        'staffs.person',
        'stock-divisions',
        'updated-by',
        'warehouses',
    ];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = null;

    /**
     * The filters a client is allowed send.
     *
     * @var string[]|null
     *      the allowed filters, an empty array for none allowed, or null to allow all.
     */
    protected $allowedFilteringParameters = null;

    protected $messages = [
        'name.required' => 'Nama Cabang tidak boleh kosong',
        'code.unique' => 'Kode Cabang sudah digunakan',
        'phone.numeric' => 'Nomor Telephone harus numeric',
        'value-added-tax-type.numeric' => 'Tipe Nomor PPN harus numeric',
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
            'name' => 'required',
            'division' => 'numeric',
            'value-added-tax-type' => 'nullable|numeric',
            'warehouse.type' => 'in:warehouses'
        ];

        if (empty($record)) {
            // create record
            $rules['code'] = 'nullable|unique:companies';
        } else {
            // edit record
            $rules['code'] = [
                'nullable',
                Rule::unique('companies')->ignore($record->id)
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
