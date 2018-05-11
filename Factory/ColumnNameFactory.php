<?php

namespace KamilDuszynski\TableGeneratorBundle\Factory;

use Doctrine\ORM\QueryBuilder;

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
        $fieldName      = null;
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

        var_dump($aliases);
        die();

        foreach ($aliases as $aliasName => $alias) {
            if ($aliasName !== $fieldAlias) {
                continue;
            }
        }

        return $fieldName;
    }
}
