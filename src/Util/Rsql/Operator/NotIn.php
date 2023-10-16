<?php
namespace App\Util\Rsql\Operator;
use Oilstone\RsqlParser\Operators\Operator;

class NotIn extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=notin=';

    /**
     * @var string
     */
    protected $sql = 'NOT IN';

    
    /**
     * @var bool
     */
    protected $expectsArray = true;
}