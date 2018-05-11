<?php

namespace KamilDuszynski\TableGeneratorBundle\Helper;

use Doctrine\ORM\QueryBuilder;
use KamilDuszynski\TableGeneratorBundle\Factory\ColumnNameFactory;
use KamilDuszynski\TableGeneratorBundle\Factory\ColumnValueFactory;
use KamilDuszynski\TableGeneratorBundle\Model\Column;
use KamilDuszynski\TableGeneratorBundle\Model\Filter;
use Symfony\Component\HttpFoundation\Request;

class DataHelper
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var Column[]
     */
    private $columns;

    /**
     * @var Filter[]
     */
    private $filters;

    /**
     * @param Request  $request
     * @param string   $tableName
     * @param Column[] $columns
     * @param Filter[] $filters
     */
    public function __construct(Request $request, $tableName, $columns = [], $filters = [])
    {
        $this->request   = $request;
        $this->tableName = $tableName;
        $this->columns   = $columns;
        $this->filters   = $filters;
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function filterData(QueryBuilder $queryBuilder)
    {
        if (true === empty($this->filters)) {
            return;
        }

        $index = 0;

        foreach ($this->filters as $filter) {
            $value          = $filter->getValue();
            $operation      = $filter->getOperation();
            $property       = $filter->getProperty();
            $field          = $filter->getName();
            $rootAlias      = $queryBuilder->getRootAlias();
            $hasAssociation = FilterHelper::hasAssociation($filter, $queryBuilder);

            if (true === $hasAssociation) {
                $join      = sprintf('%s.%s', $rootAlias, $field);
                $rootAlias = $field;

                $queryBuilder->leftJoin($join, $rootAlias);
            }

            if (null !== $property) {
                $field = $property;
            }

            if (true === empty($value)) {
                continue;
            }

            if ('like' === $operation) {
                $value = sprintf('%s%s%s', '%', $value, '%');
            }

            $sql = sprintf(
                '%s.%s %s \'%s\'',
                $rootAlias,
                $field,
                $operation,
                addslashes($value)
            );

            if (0 === $index) {
                $queryBuilder->where($sql);
                $index++;

                continue;
            }

            $queryBuilder->andWhere($sql);
            $index++;
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return bool
     */
    public function searchData(QueryBuilder $queryBuilder)
    {
        parse_str($this->request->get('filters'), $requestFilters);

        if (false === isset($requestFilters['search'])) {
            return false;
        }

        if (true === empty($requestFilters['search'])) {
            return false;
        }

        $search = sprintf(
            '%s%s%s',
            '%',
            $requestFilters['search'],
            '%'
        );

        $index = 0;
        $sql   = '';

        foreach ($this->columns as $column) {
            $field          = $column->getName();
            $property       = $column->getProperty();
            $rootAlias      = $queryBuilder->getRootAlias();
            $hasAssociation = ColumnHelper::hasAssociation($column, $queryBuilder);

            if (true === $hasAssociation) {
                $join      = sprintf('%s.%s', $rootAlias, $field);
                $rootAlias = $field;

                $queryBuilder->leftJoin($join, $rootAlias);
            }

            if (null !== $property) {
                $field = $property;
            }

            $sql .= sprintf(
                '%s %s.%s like \'%s\'',
                0 === $index ? '' : ' OR ',
                $rootAlias,
                $field,
                addslashes($search)
            );

            $index++;
        }

        $queryBuilder->andWhere($sql);

        return true;
    }

    /**
     * @param array        $data
     * @param QueryBuilder $querybuilder
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getRows($data = [], QueryBuilder $querybuilder)
    {
        if (true === empty($data)) {
            return [];
        }

        $rows = [];

        foreach ($data as $row) {
            $filteredRow = [];

            if (false === isset($row['id'])) {
                throw new \Exception(
                    sprintf(
                        'PrimaryKey "id" not defined for data in %s table',
                        $this->tableName
                    )
                );
            }

            $filteredRow['id'] = $row['id'];

            foreach ($this->columns as $column) {
                $field     = $column->getName();
                $fieldName = $field;
                $property  = $column->getProperty();

                if (null !== $property) {
                    $field = $property;
                }

                $columnName = $field;

                if (false !== strpos($field, '.')) {
                    $columnName = ColumnNameFactory::create($querybuilder, $field);
                }

                if (false === in_array($columnName, array_keys($row))) {
                    throw new \Exception(
                        sprintf(
                            'Column "%s" is not defined in data result',
                            $columnName
                        )
                    );
                }

                $mixedContent            = $row[$columnName];
                $filteredRow[$fieldName] = ColumnValueFactory::createValueForColumn($mixedContent, $column);
            }

            $rows[] = $filteredRow;
        }

        return $rows;
    }
}
