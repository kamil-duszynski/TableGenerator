<?php

namespace KamilDuszynski\TableGeneratorBundle\Factory;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;

class ColumnNameFactory
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $field
     *
     * @return string
     *
     * @throws \Exception
     */
    public static function create(QueryBuilder $queryBuilder, $field)
    {
        $fieldName      = $field;
        $root           = null;
        $fieldNameParts = explode('.', $field);
        $fieldAlias     = $fieldNameParts[0];

        $rootAliases = $queryBuilder->getRootAliases();
        $joinAliases = $queryBuilder->getDQLPart('join');

        if (true === empty($rootAliases)) {
            throw new \Exception('Root alias not found');
        }

        $root = $rootAliases[0];

        if (false === array_key_exists($root, $joinAliases)) {
            throw new \Exception('Root alias not found in join closure');
        }

        $aliases = $joinAliases[$root];

        /** @var Join $joinAlias */
        foreach ($aliases as $joinAlias) {
            $join  = explode('.', $joinAlias->getJoin());
            $alias = $joinAlias->getAlias();

            if ($alias !== $fieldAlias) {
                continue;
            }

            $fieldName = $join[1]; //bo index 0 to alias
        }

        return $fieldName;
    }
}
