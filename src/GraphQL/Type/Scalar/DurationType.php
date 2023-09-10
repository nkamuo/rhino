<?php
namespace App\GraphQL\Type\Scalar;

use GraphQL\Error\UserError;
use GraphQL\Language\AST\Node;
use Overblog\GraphQLBundle\Annotation\Description;
use Overblog\GraphQLBundle\Annotation\Scalar;


#[Scalar('Duration')]
#[Description('Represents Time duration EG. PT20S')]
class DurationType
{
    /**
     * @param \DateInterval $value
     *
     * @return string
     */
    public static function serialize(\DateInterval $value)
    {
        return $value->format('P%YY%MM%DDT%HH%IM%SS');
    }

    /**
     * @param mixed $value
     *
     * @return \DateInterval
     */
    public static function parseValue($value)
    {
        try {
            return new \DateInterval($value);
        }
        catch(\Throwable $e){
            throw new UserError($e->getMessage());
        }
    }

    /**
     * @param Node $valueNode
     *
     * @return \DateInterval
     */
    public static function parseLiteral(Node $valueNode)
    {
        try {
            return new \DateInterval($valueNode->value);
        }
        catch(\Throwable $e){
            throw new UserError($e->getMessage());
        }
    }
}