<?php

namespace App\JsonApi\Validators;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;
use CloudCreativity\LaravelJsonApi\Rules\HasOne;
use Illuminate\Validation\Rule;

class SupplierValidator extends AbstractValidators
{

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'currency',
        'company',
        'company.addresses',
        'pic',
        'supplier-category',
        'user',
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
    protected $allowedFilteringParameters = [
        'companyNameLike',
        'currencyCode',
    ];

    protected $messages = [
        'code.unique' => 'Kode Supplier sudah digunakan',
        'code.required' => 'Kode Supplier harus diisi',
        'currency.required' => 'Currency Supplier harus diisi',
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
            'accounting-number' => 'nullable|string|max:150',
            'information' => 'max:255',
            'plafon' => 'numeric|max:9999999999999',
            'top' => 'numeric|max:9999999999999',
            'currency' => [
                'required',
                new HasOne(),
            ],
        ];

        if (empty($record)) {
            $rules['code'] = [
                'required',
                'max:16',
                Rule::unique('suppliers')
            ];
        } else {
            $rules['code'] = [
                'required',
                'max:16',
                Rule::unique('suppliers')->ignore($record->id)
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
