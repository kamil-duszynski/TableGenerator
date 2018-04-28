<?php

namespace KamilDuszynski\TableGeneratorBundle\Helper;

use Doctrine\ORM\QueryBuilder;
use KamilDuszynski\TableGeneratorBundle\Model\Filter;

class FilterHelper
{
    /**
     * @param Filter       $column
     * @param QueryBuilder $builder
     *
     * @return bool
     */
    public static function hasAssociation(Filter $column, QueryBuilder $builder)
    {
        $rootEntities = $builder->getRootEntities();
        $entityName   = array_pop($rootEntities);
        $columnName   = $column->getName();

        return $builder
            ->getEntityManager()
            ->getClassMetadata($entityName)
            ->hasAssociation($columnName)
            ;
    }
}
