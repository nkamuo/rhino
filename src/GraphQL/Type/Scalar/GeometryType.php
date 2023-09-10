<?php
namespace App\GraphQL\Type\Scalar;

use Brick\Geo\Geometry;
use Brick\Geo\IO\WKTReader;
use Brick\Geo\IO\WKTWriter;
use GraphQL\Error\UserError;
use GraphQL\Language\AST\Node;
use Overblog\GraphQLBundle\Annotation\Description;
use Overblog\GraphQLBundle\Annotation\Scalar;


#[Scalar('Geometry')]
#[Description('Represents a Well Known Geometry Object value')]
class GeometryType
{

    private static ?WKTReader $reader = null;
    private static ?WKTWriter $writer = null;

    
    /**
     * @param Geometry $geometry
     *
     * @return string
     */
    public static function serialize(Geometry $geometry): string
    {
        // if(!($geometry instanceof Geometry)){
        //     if($geometry instanceof GeometryInterface){
        //         // $geometry = $geometry->
        //     }
        // }
        return self::getWriter()->write($geometry);
    }

    /**
     * @param string $value
     *
     * @return Geometry
     */
    public static function parseValue($value): Geometry
    {
        try {
            return static::getReader()->read($value);
        } catch (\Throwable $e) {
            throw new UserError($e->getMessage());
        }
    }

    /**
     * @param Node $valueNode
     *
     * @return Geometry
     */
    public static function parseLiteral(Node $valueNode): Geometry
    {
        try {
            return static::getReader()->read($valueNode->value);

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