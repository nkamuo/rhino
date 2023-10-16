<?php

namespace App\Util\Rsql\Operator;

use Oilstone\RsqlParser\Operators\Operator;

class Out extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=out=';

    /**
     * @var string
     */
    protected $sql = 'NOT IN';

    
    /**
     * @var bool
     */
    protected $expectsArray = true;
}
