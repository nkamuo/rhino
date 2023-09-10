<?php
namespace App\GraphQL\Type\Scalar;

use GraphQL\Error\UserError;
use GraphQL\Language\AST\Node;
use Overblog\GraphQLBundle\Annotation\Description;
use Overblog\GraphQLBundle\Annotation\Scalar;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\Ulid;


#[Scalar('Ulid')]
#[Description('A globally unique identifier')]
class UlidType
{
    /**
     * @param Ulid $value
     *
     * @return string
     */
    public static function serialize(Ulid $value)
    {
        return $value->toBase32();
    }

    /**
     * @param mixed $value
     *
     * @return Ulid
     */
    public static function parseValue($value)
    {
        try {
            return Ulid::fromBase32($value);
        } catch (\Throwable $e) {
            throw new UserError($e->getMessage());
        }
    }

    /**
     * @param Node $valueNode
     *
     * @return Ulid
     */
    public static function parseLiteral(Node $valueNode)
    {
        try {
            return Ulid::fromBase32($valueNode->value);
        } catch (\Throwable $e) {
            throw new UserError($e->getMessage());
        }
    }
}