<?php

namespace KamilDuszynski\TableGeneratorBundle\Helper;

use Doctrine\ORM\QueryBuilder;
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

        foreach ($this->filters as $key => $filter) {
            $value     = $filter->getValue();
            $operation = $filter->getOperation();

            if (true === empty($value)) {
                continue;
            }

            if ('like' === $operation) {
                $value = '%' . $value . '%';
            }

            $sql = sprintf(
                '%s.%s %s \'%s\'',
                $queryBuilder->getRootAlias(),
                $filter->getName(),
                $operation,
                addslashes($value)
            );

            if (0 === $key) {
                $queryBuilder->where(
                    $sql
                );

                continue;
            }

            $queryBuilder->andWhere(
                $sql
            );
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

        foreach ($this->columns as $key => $column) {
            $sql = sprintf(
                '%s.%s like \'%s\'',
                $queryBuilder->getRootAlias(),
                $column->getName(),
                addslashes($search)
            );

            if (0 === $key) {
                $queryBuilder->where(
                    $sql
                );

                continue;
            }

            $queryBuilder->orWhere(
                $sql
            );
        }

        return true;
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \Exception
     */
    public function getRows($data = [])
    {
        if (true === empty($data)) {
            return [];
        }

        $rows = [];

        foreach($data as $row) {
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

            foreach($this->columns as $column) {
                if (false === in_array($column->getName(), array_keys($row))) {
                    throw new \Exception(
                        sprintf(
                            'Column "%s" is not defined in data result',
                            $column->getName()
                        )
                    );
                }

                if (null !== $column->getValueDecorator()) {
                    $decorator                       = $column->getValueDecorator();
                    $filteredRow[$column->getName()] = $decorator($row[$column->getName()]);

                    continue;
                }

                $filteredRow[$column->getName()] = $row[$column->getName()];
            }

            $rows[] = $filteredRow;
        }

        return $rows;
    }
}