<?php
namespace App\Lib;

use Illuminate\Support\Facades\DB;

class Functions
{
    /**
     * handle for generate code if code null
     *
     * @param int $digit
     * @param bool $unique
     *   true = generate and check if code exists
     *   false = just generate code
     * @param string $table
     *
     * @return string ramdomNumber
     */
    public static function generateCode(
        int $digit = 10,
        bool $unique = false,
        $table = null,
        string $columnName = 'code'
    ): string {
        $randomNumber = self::randomNumber($digit);

        if ($table != null && $unique == true) {
            if (DB::table($table)->where($columnName, $randomNumber)->exists()) {
                $randomNumber = self::generateCode($digit, $unique, $table, $columnName);
            }
        }
        return $randomNumber;
    }

    /**
     * Generate random number
     *
     * @paramm int $length
     * @return string
     */
    public static function randomNumber(int $length = 1): string
    {
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }

        return $result;
    }

    /**
     * Return true if string is bcrypted
     *
     * @param string $value
     * @return bool
     */
    public static function isBcrypted($value): bool
    {
        return preg_match('/^\$2[ayb]\$.{56}$/', $value) === 1;
    }

    /**
     * if term is "< 100"
     * this will return an array ['operator' => '<', 'term' => 100]
     * if term has no spaces ex "100", operator will be defaulted to '='
     *
     * @param string $term
     * @return array
     */
    public static function translateNumericFilterTerm($term)
    {
        $params = explode(' ', $term);

        // determine the operator
        $operator = '=';

        if (in_array($params[0], ['<', '>', '<=', '>='])) {
            $operator = $params[0];
            $term = $params[1];
        } else {
            $term = $params[0];
        }

        return [
            'operator' => $operator,
            'term' => $term,
        ];
    }

    /**
     * Return discounts from provided number
     * @return float
     */
    public static function countDiscountFromNumber($discount, $number): float
    {
        $finalDiscount = 0;

        if (empty($discount) || empty($number)) {
            return 0;
        }

        $discounts = explode(' ', trim($discount));

        foreach ($discounts as $disc) {
            $calculatedDiscount = 0;

            if (preg_match('/^[0-9]*$/', $disc)) {
                // discount is a number
                $calculatedDiscount = $disc;
            } elseif (preg_match('/^[0-9]*%$/', $disc)) {
                // discount is percentage
                $disc = (int) str_replace('%', '', $disc);
                $calculatedDiscount = $number * $disc / 100;
            }

            $finalDiscount += (float) $calculatedDiscount;

            $number -= (float) $calculatedDiscount;

            if ($number <= 0) break;
        }

        return (float) $finalDiscount;
    }

    /**
     * Return Ppn 10% from number
     * @return float
     */
    public static function countPpnFromNumber($number): float
    {
        return $number * 10 / 100;
    }

}
