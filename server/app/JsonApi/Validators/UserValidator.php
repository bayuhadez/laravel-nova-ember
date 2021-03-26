<?php

namespace App\JsonApi\Validators;

use App\Rules\Password;
use CloudCreativity\LaravelJsonApi\Contracts\Validation\ValidatorInterface;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;
use Illuminate\Validation\Rule;

class UserValidator extends AbstractValidators
{

    protected $allowedFilteringParameters = ['username'];

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = null;

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [];

    /**
     * @inheritDoc
     */
    public function update($record, array $document): ValidatorInterface
    {
        $validator = parent::update($record, $document);

        $validator->sometimes('password-confirmation', 'required_with:password|same:password', function ($input) {
            return isset($input['password']);
        });

        return $validator;
    }

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
            'email' => [
                'required',
                'email',
                'max:191',
                Rule::unique('users', 'email')->ignore($record->id ?? null),
            ],
            'name' => 'nullable|string|max:191',
            'username' => [
                'nullable',
                'string',
                'max:191',
                Rule::unique('users', 'username')->ignore($record->id ?? null),
            ],
            /*
            'password' => [
                $record ? 'nullable' : 'required',
                'min:6',
                'string',
                new Password(),
            ],
            */
        ];

        if (!$record) {
            $rules['password-confirmation'] = 'required_with:password|same:password';
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
