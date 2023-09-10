<?php
namespace App\GraphQL\Type\Scalar;

abstract class MeasureUtil{

    
    // Regular expressions to extract value and unit
    public const PATTERN = '/^(\d+(\.\d+)?)(\s+)?([a-zA-Z]+)?$/';

    /**
     * @return array[value => string] //array<(unit,value),(string,int,float)>
     */
public static function parseQuantity(string $input)
{

    $input = trim($input);
    // Extract value and unit using regex
    if (preg_match(self::PATTERN, $input, $matches)) {
        $value = (float) $matches[1];
        $unit = isset($matches[4]) ? strtolower($matches[4]) : null;

        return [
            'value' => $value,
            'unit' => $unit
        ];
    }

    return null; // Invalid input
}
}