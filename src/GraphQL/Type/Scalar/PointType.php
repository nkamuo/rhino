<?php
namespace App\GraphQL\Type\Scalar;

use Brick\Geo\Geometry;
use Brick\Geo\IO\WKTReader;
use Brick\Geo\IO\WKTWriter;
use Brick\Geo\Point;
use GraphQL\Error\UserError;
use GraphQL\Language\AST\Node;
use Overblog\GraphQLBundle\Annotation\Description;
use Overblog\GraphQLBundle\Annotation\Scalar;
use App\Util\Str;


#[Scalar('Point')]
#[Description('Represents a Well Known Point Object value')]
class PointType
{

    private static ?WKTReader $reader = null;
    private static ?WKTWriter $writer = null;

    
    /**
     * @param Point $point
     *
     * @return string
     */
    public static function serialize(Point $point): string
    {
        return self::getWriter()->write($point);
    }

    /**
     * @param string $value
     *
     * @return Point
     */
    public static function parseValue($value): Point
    {
        try {
            $result = static::getReader()->read($value);
            if(!($result instanceof Point))
                throw new \InvalidArgumentException("Expected type Point; ".Str::getShortClassName($result::class) . ' Found');
            return $result;
        } catch (\Throwable $e) {
            throw new UserError($e->getMessage(), 0, $e);
        }
    }

    /**
     * @param Node $valueNode
     *
     * @return Point
     */
    public static function parseLiteral(Node $valueNode): Point
    {
        try {
            $result = static::getReader()->read($valueNode->value);
            if(!($result instanceof Point))
                throw new \InvalidArgumentException("Expected type Point; ".Str::getShortClassName($result::class) . ' Found');
            return $result;

        } catch (\Throwable $e) {
            throw new UserError($e->getMessage());
        }
    }




    private static function getReader(): WKTReader{
        return self::$reader ??= new WKTReader();
    }
    private static function getWriter(): WKTWriter{
        return self::$writer ??= new WKTWriter();
    }
}