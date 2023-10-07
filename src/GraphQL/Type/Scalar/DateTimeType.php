<?php
namespace App\GraphQL\Type\Scalar;

use GraphQL\Language\AST\Node;
use Overblog\GraphQLBundle\Annotation\Description;
use Overblog\GraphQLBundle\Annotation\Scalar;


#[Scalar('DateTime')]
#[Description('Represents the date-time instance for marking events')]
class DateTimeType
{
    
    public const FORMAT = 'D M d Y H:i:s e';



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
        return static::parseDateTime($value);
    }

    /**
     * @param Node $valueNode
     *
     * @return \DateTimeInterface
     */
    public static function parseLiteral(Node $valueNode)
    {
        return static::parseDateTime($valueNode->value);
    }

    
    public static function parseDateTime(string $dateString)
    {
        $dateTime = \DateTimeImmutable::createFromFormat(static::FORMAT, $dateString);
        if ($dateTime) {
            return $dateTime;
        }
        return new \DateTimeImmutable($dateString);
    }
}