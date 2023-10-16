<?php

namespace App\Util\Rsql;

use App\Entity\Shipment\ShipmentOrderStatus;
use App\Entity\Shipment\ShipmentStatus;
use App\Util\Rsql\Operator\EqualTo;
use App\Util\Rsql\Operator\GreaterThan;
use App\Util\Rsql\Operator\GreaterThanOrEqualTo;
use App\Util\Rsql\Operator\LessThan;
use App\Util\Rsql\Operator\LessThanOrEqualTo;
use App\Util\Rsql\Operator\NotIn;
use App\Util\Rsql\Operator\NotLike;
use App\Util\Rsql\Operator\Out;
use App\Util\Rsql\Operator\SameWeek;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
// use Prettus\FIQLParser\Parser;
use GraphQL\Error\UserError;
use Oilstone\RsqlParser\Condition;
use Oilstone\RsqlParser\Expression;
use Oilstone\RsqlParser\Operators;
use Oilstone\RsqlParser\Operators\Operator;
use Symfony\Component\VarDumper\VarDumper;
use Oilstone\RsqlParser\Parser;



Operators::custom(LessThanOrEqualTo::class);
Operators::custom(GreaterThanOrEqualTo::class); // ADD THESE FIRST SO THAT >,<,= are not parsed as seperate operators
Operators::custom(NotLike::class);
Operators::custom(NotIn::class);
Operators::custom(Out::class);
Operators::custom(EqualTo::class);
Operators::custom(LessThan::class);
Operators::custom(GreaterThan::class);
Operators::custom(SameWeek::class);


class ExceptionH extends \Exception
{
}

class RSQLHelper
{

    public function __construct(
        // private EntityManagerInterface $entityManager
    )
    {
    }

    private ?DoctrineQueryBuilder $doctrineQueryBuilder = null;

    private $fieldTransformers = [];

    public function applyFilter(QueryBuilder $qb, string $rootName, ?string $filter = null)
    {
        if (null === $filter)
            return;

        try {
            // echo $filter;
            $expression = Parser::parse($filter);
            // VarDumper::dump($expression);
            // die;
            $criteria = $this->getBuilder()->buildExpressionNode($qb, $expression, $rootName);
            if ($criteria)
                $qb->andWhere($criteria);

            // die($qb->getDQL());

            // die($criteria);

        } catch (\Throwable $e) {
            throw $e;
            // throw new UserError($e->getMessage(), $e->getCode(), $e);
        }

        // $parser = new Parser();

        // // $parser->

        // VarDumper::dump($result);
        // die;

    }



    public function addFieldTransformer(string $field, callable $transformer)
    {
        // $this->getBuilder()->addFieldTransformer($field, $transformer);
        $this->fieldTransformers[$field] = $transformer;
    }




    private function getBuilder(): DoctrineQueryBuilder
    {
        $builder = /*$this->doctrineQueryBuilder ??=*/ new DoctrineQueryBuilder();
        foreach ($this->fieldTransformers as $field => $transformer) {
            $builder->addFieldTransformer($field, $transformer);
        }
        return $builder;
    }
}




/**
 * @internal description
 */
class DoctrineQueryBuilder
{

    private array $operatorBuilders = [];

    private $fieldTransformers = [];


    private array $typeMappings = [];


    public function __construct(
        // private EntityManagerInterface $entityManager
    )
    {
        $this->setup();
    }

    /**
     * 
     */
    public function addOperator(string $name, callable $operatorBuilder)
    {
        $this->operatorBuilders[$name] = $operatorBuilder;
    }


    public function addFieldTransformer(string $field, callable $transformer)
    {
        $this->fieldTransformers[$field] = $transformer;
    }




    public function buildExpressionNode(QueryBuilder $qb, Expression $expression, string $root): string
    {

        $result = '';

        foreach ($expression as $childNode) {

            $operator = $childNode['operator'];
            $constraint = $childNode['constraint'];
            $nodeResult = $this->buildNode($qb, $constraint, $root);

            if ($result) {
                switch ($operator) {
                    case 'OR':
                        $result = $qb->expr()->orX($result, $nodeResult)->__toString();
                        break;

                    case 'AND':
                        $result = $qb->expr()->andX($result, $nodeResult)->__toString();
                        break;

                    default:
                        throw new \InvalidArgumentException("Unkown operator {$operator}");
                }
            } else {
                $result = $nodeResult;
            }
        }

        return $result;
    }

    public function buildNode(QueryBuilder $qb, Expression|Condition $node, string $root): string
    {
        if ($node instanceof Expression)
            return $this->buildExpressionNode($qb, $node, $root);
        else
            return $this->buildConditionNode($qb, $node, $root);
    }



    public function buildConditionNode(QueryBuilder $qb, Condition $condition, string $rootName): ?string
    {
        // string $attributePath;
        $operator = $condition->getOperator();
        $operatorName = $operator->toUri();
        $attrName = $condition->getColumn();
        $value = $condition->getValue();

        if (!array_key_exists($operatorName, $this->operatorBuilders))
            throw new \InvalidArgumentException("Unknown operator \"{$operatorName}\" given");

        // $data = $qb->();
        // var_dump($data);
        // die;


        $type = 'string';
        if (array_key_exists($attrName, $this->fieldTransformers)) {
            $transformer = $this->fieldTransformers[$attrName];
            $attrName = $transformer($qb, $rootName, $attrName);
            $rootName = '';
        } else {
            // $this->getOrJoinField($qb, $attrName);
            list($rootName, $attrName, $type, $rValue) = $this->getOrJoinField($qb, $attrName, $value);
            $rootName .= '.';
            if ($rValue) {
                $value = $rValue;
            }
        }

        if (null !== $type) {
            if (is_array($value)) {
                $value = array_map(fn ($data) => $this->normalizeValue($data, $type), $value);
            } else
                $value = $this->normalizeValue($value, $type);
        }

        $operator = $this->operatorBuilders[$operatorName];
        return $operator($qb, $rootName, $attrName, $type, $value);
    }


    public function normalizeValue(mixed $value, string $type)
    {
        if ('boolean' === $type) {
            if (in_array(strtolower($value), ['false', 'f', 0, '0', 'no', 'nah']))
                $value = false;

            $value = (bool) $value;
        }


        if (in_array($type, ['date', 'time', 'datetime', Types::DATETIME_MUTABLE,])) {
            return  new \DateTime($value);
        }
        if (in_array($type, [Types::DATE_IMMUTABLE, Types::TIME_IMMUTABLE, Types::DATETIME_IMMUTABLE,])) {
            return new \DateTimeImmutable($value);
        }



        return $value;
    }



    private function getOrJoinField(QueryBuilder $qb, string $path, mixed $value): mixed
    {

        $em = $qb->getEntityManager();
        $rootEntity = $qb->getRootEntities()[0];

        $segments = explode('.', $path);
        $size = count($segments);

        $rootEntityMetadata = $em->getClassMetadata($rootEntity);
        $levelMetadata = $rootEntityMetadata;

        $rootAlias = $qb->getRootAliases()[0];
        $parentAlias = $rootAlias;
        $subPath = '';

        for ($index = 0; $index < $size; $index++) {
            // echo $index, "\n\n";
            $segment = $segments[$index];

            if ($subPath) {
                $subPath .= '.';
            }
            $subPath .= $segment;



            if ($levelMetadata->hasAssociation($segment)) {

                if ($index >= ($size - 1)) {
                    throw new \InvalidArgumentException("Cannot use an association \"{$path}\" as a query field");
                    // $type = null;
                    // return [$parentAlias, $segment, $type];
                }

                $associationMapping = $levelMetadata->getAssociationMapping($segment);
                $targetEntity = ((array) $associationMapping)['targetEntity'];
                $levelMetadata = $em->getClassMetadata($targetEntity);

                if (!array_key_exists($subPath, $this->typeMappings)) {
                    $alias = uniqid('assoc_' . $segment . '_');
                    $qb->leftJoin("{$parentAlias}.{$segment}", $alias);
                    $this->typeMappings[$subPath] = $alias; //$segment;
                }
                $parentAlias = $this->typeMappings[$subPath];
            } else {

                $propPath = implode('.', array_slice($segments, $index));

                // if (!$levelMetadata->hasField($segment)) {
                if ($levelMetadata->hasField($propPath)) {
                    $segment = $propPath;
                }
                // }


                if ($levelMetadata->hasField($segment)) {
                    // if (($size - 1) !== $index)
                    //     throw new \InvalidArgumentException("Invalid path \"{$path}\"");
                    $fieldMapping = $levelMetadata->getFieldMapping($segment);
                    // VarDumper::dump($fieldMapping);
                    $fieldMapping = ((array) $fieldMapping);

                    $enumValue = [];
                    if (isset($fieldMapping['enumType'])) {
                        $enumType = $fieldMapping['enumType'];

                        $mCases = ShipmentOrderStatus::cases();

                        $case = null;
                        $values = $value;
                        $isArray = is_array($values);
                        if (!$isArray) {
                            $values = [$values];
                        }

                        foreach ($values as $value) {
                            try {
                                $case = $enumType::from($value);
                            } catch (\Throwable $e) {
                                $cases = [];
                                foreach ($enumType::cases() as $v) {
                                    $cases[$v->name] = $v;
                                }

                                if (isset($cases[$value])) {
                                    $case = $cases[$value];
                                } else {
                                    throw $e;
                                }
                            }
                            $enumValue[] = $case->value;
                        }

                        if(!$isArray){
                            $enumValue = $enumValue[0]?? null;
                        }

                    }

                    $type = ((array) $fieldMapping)['type'];

                    return [$parentAlias, $segment, $type, $enumValue];
                } else {
                    $targetEntity = $levelMetadata->getName();
                    throw new \InvalidArgumentException("RSQL-ERROR: \"{$targetEntity}\" has not field or association \"{$segment}\"");
                }
            }

            // $index--;
        }


        $parentAlias = $segment;
        return false;
    }





    private function getUniqueParamName()
    {
        return 'PRAMA_' . uniqid();
    }



    private function setup()
    {

        $equalTo = function (QueryBuilder $qb, string $rootName, string $alias, string $type, mixed $value) {
            // list($rootName, $alias, $type) = $this->getOrJoinField($qb, $attrName);
            $param = $this->getUniqueParamName();
            $qb->setParameter($param, $value, $type);
            return $qb->expr()->eq("{$rootName}{$alias}", ":{$param}");
        };

        $lessThan = function (QueryBuilder $qb, string $rootName, string $alias, string $type, string $value) {
            // list($rootName, $alias, $type) = $this->getOrJoinField($qb, $attrName);
            $param = $this->getUniqueParamName();
            $qb->setParameter($param, $value, $type);
            return $qb->expr()->lt("{$rootName}{$alias}", ":{$param}");
        };

        $lessThanOrEqual = function (QueryBuilder $qb, string $rootName, string $alias, string $type, string $value) {
            // list($rootName, $alias, $type) = $this->getOrJoinField($qb, $attrName);
            $param = $this->getUniqueParamName();
            $qb->setParameter($param, $value, $type);
            return $qb->expr()->lte("{$rootName}{$alias}", ":{$param}");
        };

        $greaterThan = function (QueryBuilder $qb, string $rootName, string $alias, string $type, string $value) {
            // list($rootName, $alias, $type) = $this->getOrJoinField($qb, $attrName);
            $param = $this->getUniqueParamName();
            $qb->setParameter($param, $value, $type);
            return $qb->expr()->gt("{$rootName}{$alias}", ":{$param}");
        };

        $greaterOrEqual = function (QueryBuilder $qb, string $rootName, string $alias, string $type, string $value) {
            // list($rootName, $alias, $type) = $this->getOrJoinField($qb, $attrName);
            $param = $this->getUniqueParamName();
            $qb->setParameter($param, $value, $type);
            return $qb->expr()->gte("{$rootName}{$alias}", ":{$param}");
        };

        $notIn = function (QueryBuilder $qb, string $rootName, string $alias, string $type, array $values) {
            $querySegment = 'NOT IN(';
            $params = [];
            foreach ($values as $value) {
                $param = uniqid(':param_');
                $params[] = $param;
                $qb->setParameter($param, $value, $type);
            }
            $querySegment .= implode(',', $params) . ')';
            return "{$rootName}{$alias} " . $querySegment;
        };


        $this->addOperator('==', $equalTo);
        $this->addOperator('=eq=', $equalTo);

        $this->addOperator('<', $lessThan);
        $this->addOperator('=lt=', $lessThan);
        $this->addOperator('<=', $lessThanOrEqual);
        $this->addOperator('=lte=', $lessThanOrEqual);

        $this->addOperator('=', $greaterOrEqual);
        $this->addOperator('>', $greaterThan);
        $this->addOperator('>=', $greaterOrEqual);
        $this->addOperator('=gte=', $greaterOrEqual);



        $this->addOperator('=like=', function (QueryBuilder $qb, string $rootName, string $alias, string $type, string $value) {
            // list($rootName, $alias, $type) = $this->getOrJoinField($qb, $attrName);
            $param = $this->getUniqueParamName();
            $qb->setParameter($param, "%{$value}%", $type);
            return $qb->expr()->like("{$rootName}{$alias}", ":{$param}");
        });

        $this->addOperator('=notlike=', function (QueryBuilder $qb, string $rootName, string $alias, string $type, string $value) {
            // list($rootName, $alias, $type) = $this->getOrJoinField($qb, $attrName);
            $param = $this->getUniqueParamName();
            $qb->setParameter($param, "%{$value}%", $type);
            return $qb->expr()->notLike("{$rootName}{$alias}", ":{$param}");
        });

        $this->addOperator('=between=', function (QueryBuilder $qb, string $rootName, string $alias, string $type, array $values) {
            $querySegment = 'BETWEEN';
            if (($count = count($values)) !== 2)
                throw new \InvalidArgumentException("=between= operator accepts only and array of 2 values. {$count} given");

            for ($i = 0; $i < $count; $i++) {
                $value = $values[$i];
                $param = uniqid(':param_');
                $qb->setParameter($param, $value, $type);
                $querySegment .= ' ' . $param;

                if ($i == 0) {
                    $querySegment .= ' AND ';
                }
            }
            // $querySegment .= ')';
            return "{$rootName}{$alias} " . $querySegment;
        });

        $this->addOperator('=in=', function (QueryBuilder $qb, string $rootName, string $alias, string $type, array $values) {
            $querySegment = 'IN(';
            $params = [];
            foreach ($values as $value) {
                $param = uniqid(':param_');
                $params[] = $param;
                $qb->setParameter($param, $value, $type);
            }
            $querySegment .= implode(',', $params) . ')';
            return "{$rootName}{$alias} " . $querySegment;
        });

        $this->addOperator('=not-in=', $notIn);
        $this->addOperator('=notin=', $notIn);
        $this->addOperator('=out=', $notIn);

        $this->addOperator('=null=', function (QueryBuilder $qb, string $rootName, string $alias, ?string $type, ?string $value) {
            // list($rootName, $alias, $type) = $this->getOrJoinField($qb, $attrName);
            return $qb->expr()->isNull("{$rootName}{$alias}");
        });

        $this->addOperator('=not-null=', function (QueryBuilder $qb, string $rootName, string $alias, ?string $type, ?string $value) {
            // list($rootName, $alias, $type) = $this->getOrJoinField($qb, $attrName);
            return $qb->expr()->isNotNull("{$rootName}{$alias}");
        });


        $this->addOperator('=sameweek=', function (QueryBuilder $qb, string $rootName, string $alias, ?string $type, string|\DateTimeInterface $value) {
            $param = uniqid(':param_');
            $qb->setParameter($param, $value);
            //
            $field = "{$rootName}{$alias}";
            return "WEEK($field) = WEEK($param) AND YEAR($field) = YEAR($param)"; //
        });


        $this->addOperator('=sameday=', function (QueryBuilder $qb, string $rootName, string $alias, ?string $type, string|\DateTimeInterface $value) {
            $param = uniqid(':param_');
            $qb->setParameter($param, $value);
            //
            $field = "{$rootName}{$alias}";
            return "DATE($field) = DATE($param)"; //
        });



        $this->addOperator('=year=', function (QueryBuilder $qb, string $rootName, string $alias, ?string $type, string|\DateTimeInterface $value) {
            $param = uniqid(':param_');
            $qb->setParameter($param, $value);
            //
            $field = "{$rootName}{$alias}";
            return "YEAR($field) = YEAR($param)"; //
        });
    }
}
