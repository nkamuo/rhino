<?php
namespace App\GraphQL\Type\Scalar;

use GraphQL\Error\UserError;
use GraphQL\Language\AST\Node;
use Overblog\GraphQLBundle\Annotation\Description;
use Overblog\GraphQLBundle\Annotation\Scalar;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\Ulid;


#[Scalar('MoneyValue')]
#[Description('Represents a money value input. Takes a float and returns a n integer')]
class MoneyValueType
{

    public const FACTOR = 100;
    
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