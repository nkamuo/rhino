<?php
namespace App\GraphQL\Type\Scalar;

use GraphQL\Error\UserError;
use GraphQL\Language\AST\Node;
use Overblog\GraphQLBundle\Annotation\Description;
use Overblog\GraphQLBundle\Annotation\Scalar;

#[Scalar('Json')]
#[Description('Represents a json value')]
class JsonType
{

    
    /**
     * @param mixed $value
     *
     * @return string
     */
    public static function serialize(mixed $value)
    {
        return json_encode($value);
    }

    /**
     * @param string $value
     *
     * @return mixed
     */
    public static function parseValue($value)
    {
        try {
            return json_decode($value,true);
        } catch (\Throwable $e) {
            throw new UserError($e->getMessage());
        }
    }

    /**
     * @param Node $valueNode
     *
     * @return mixed
     */
    public static function parseLiteral(Node $valueNode)
    {
        try {
            return json_decode($valueNode->value,true);

        } catch (\Throwable $e) {
            throw new UserError($e->getMessage());
        }
    }
}