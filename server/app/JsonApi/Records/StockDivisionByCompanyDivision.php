<?php

namespace App\JsonApi\Records;

use App\Models\Company;

class StockDivisionByCompanyDivision
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $companyName;

    /**
     * @var string
     */
    public $division;

    /**
     * @var int
     */
    public $stockDivisionCount;

    public function __construct(
        int $id,
        string $companyName,
        string $division,
        int $stockDivisionCount
    ) {
        $this->id = $id;
        $this->companyName = $companyName;
        $this->division = $division;
        $this->stockDivisionCount = $stockDivisionCount;
    }

    public static function get($parameters)
    {
        /**
         * @var array $data
         *
         *  [
         *      {company name} => [
         *          {division text (retail/wholesale)} => {stock division count}
         *      ]
         *  ]
         */
        $data = [];

        $id = 1;

        $qb = new Company;

        if (
            !empty($parameters['page'])
            && !empty($parameters['page']['number'])
            && !empty($parameters['page']['size'])
        ) {
            $offset = ($parameters['page']['number']-1) * $parameters['page']['size'];
            $id = $offset + 1;
            $qb = $qb->limit($parameters['page']['size'])
                ->offset($offset);
        }

        $companies = $qb->get();

        foreach ($companies as $company) {
            if (!isset($data[$company->name])) {
                $data[$company->name] = [];
            }

            foreach ($company->stockDivisions as $stockDivision) {

                if (!isset($data[$company->name][$stockDivision->divisionText])) {
                    $data[$company->name][$stockDivision->divisionText] = 0;
                }

                $data[$company->name][$stockDivision->divisionText]++;
            }
        }

        $records = [];

        foreach ($data as $companyName => $datum) {

            foreach ($datum as $division => $stockDivisionCount) {
                $records[] = new StockDivisionByCompanyDivision(
                    $id,
                    $companyName,
                    $division,
                    $stockDivisionCount
                );

                $id++;
            }
        }

        return collect($records);
    }
}
