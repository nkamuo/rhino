<?php
namespace App\GraphQL\Type\Scalar;

use GraphQL\Language\AST\Node;
use Overblog\GraphQLBundle\Annotation\Description;
use Overblog\GraphQLBundle\Annotation\Scalar;


#[Scalar('DateTime')]
#[Description('Represents the date-time instance for marking events')]
class DateTimeType
{
    /**
     * @param \DateTimeInterface $value
     *
     * @return string
     */
    public static function serialize(\DateTimeInterface $value)
    {
        return $value->format('Y-m-d H:i:s');
    }

    /**
     * @param mixed $value
     *
     * @return \DateTimeInterface
     */
    public static function parseValue($value)
    {
        return new \DateTimeImmutable($value);
    }

    /**
     * @param Node $valueNode
     *
     * @return \DateTimeInterface
     */
    public static function parseLiteral(Node $valueNode)
    {
        return new \DateTimeImmutable($valueNode->value);
    }
}