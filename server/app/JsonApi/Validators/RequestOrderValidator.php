<?php

namespace App\JsonApi\Validators;

use CloudCreativity\LaravelJsonApi\Rules\HasOne;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;
use Illuminate\Validation\Rule;

class RequestOrderValidator extends AbstractValidators
{

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'created-by'
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
        'inCompany',
        'inPreOrder',
        'status',
        'numberExist',
        'companyNameLike',
        'numberLike',
        'createdAtLike',
        'companyDivisionFilter',
        'statusFilter',
        'exclude',
    ];

    protected $messages = [
        'number.unique' => 'Nomor RO sudah digunakan',
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
            'memo' => 'max:191',
            'company' => [
                'required',
                new HasOne()
            ],
        ];
        
        if (empty($record)) {
            // create record
            $rules['number'] = 'unique:request_orders';
        } else {
            // edit record
            $rules['number'] = [
                Rule::unique('request_orders')->ignore($record->id)
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
            'filter.inPreOrder' => 'nullable|integer',
            'filter.status' => 'in:0,1,2',
        ];
    }

}
