<?php
namespace App\GraphQL\Type\Scalar;

use GraphQL\Error\UserError;
use GraphQL\Language\AST\Node;
use Overblog\GraphQLBundle\Annotation\Description;
use Overblog\GraphQLBundle\Annotation\Scalar;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\Ulid;


#[Scalar('Mass')]
#[Description('Represents a distance; length, width, height, depth and so on. Takes a float and returns a n integer')]
class MassType
{

    public const FACTOR = 1000;
    
    /**
     * @param int $value
     *
     * @return string
     */
    public static function serialize(int $value)
    {
        return number_format($value / self::FACTOR, 2,'.','');
    }

    /**
     * @param mixed $value
     *
     * @return int
     */
    public static function parseValue($value)
    {
        try {

            // if(is_numeric($value)){
            //     //Asume a value is provided and is in the standard unit, meters
            //     $value = ((int) $value);
            //     return new Length($value,'m');
            // }

            // $parts = MeasureUtil::parseQuantity($value);

            // return new Length($parts['value'],$parts['unit']);
            
            return (int) (((float) $value) * self::FACTOR);
        } catch (\Throwable $e) {
            throw new UserError($e->getMessage());
        }
    }

    /**
     * @param Node $valueNode
     *
     * @return int
     */
    public static function parseLiteral(Node $valueNode)
    {
        try {
            return (int) (((float) $valueNode->value) * self::FACTOR);
        } catch (\Throwable $e) {
            throw new UserError($e->getMessage());
        }
    }
}