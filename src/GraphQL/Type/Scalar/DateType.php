<?php

namespace App\GraphQL\Type\Scalar;

use GraphQL\Language\AST\Node;
use Overblog\GraphQLBundle\Annotation\Description;
use Overblog\GraphQLBundle\Annotation\Scalar;


#[Scalar('Date')]
#[Description('Represents the date-time instance for marking events')]
class DateType
{

    /**
     * @param \DateTimeInterface $value
     *
     * @return string
     */
    public static function serialize(\DateTimeInterface $value)
    {
        return $value->format('Y-m-d');
    }

    /**
     * @param mixed $value
     *
     * @return \DateTimeInterface
     */
    public static function parseValue($value)
    {
        return DateTimeType::parseDateTime($value);
    }

    /**
     * @param Node $valueNode
     *
     * @return \DateTimeInterface
     */
    public static function parseLiteral(Node $valueNode)
    {
        return DateTimeType::parseDateTime($valueNode->value);
    }
}
