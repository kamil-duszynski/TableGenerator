<?php

namespace KamilDuszynski\TableGeneratorBundle\Helper;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use KamilDuszynski\TableGeneratorBundle\Model\Pagination;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationHelper
{
    /**
     * @var Paginator
     */
    private $doctrinePagination;

    /**
     * @var Pagination
     */
    private $tablePagination;

    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getMasterRequest();
    }

    /**
     * @param Query $query
     */
    public function setQuery(Query $query)
    {
        $this->doctrinePagination = new Paginator($query);
    }

    /**
     * @return Query
     */
    public function getQuery()
    {
        return $this->doctrinePagination->getQuery();
    }

    /**
     * @return Pagination
     * @throws \Exception
     */
    public function getPagination()
    {
        if (null !== $this->tablePagination) {
            return $this->tablePagination;
        }

        if (null === $this->doctrinePagination) {
            throw new \Exception('Pagination not initialized');
        }

        $actualPage = $this->request->get('page', 1);
        $items      = $this->request->get('items', 10);
        $totalCount = count($this->doctrinePagination);
        $pagesCount = ceil($totalCount / $items);
        $firstPage  = $actualPage > 1 ? 1 : null;
        $prevPage   = $actualPage > 1 ? ($actualPage - 1) : null;
        $nextPage   = $actualPage < $pagesCount ? ($actualPage + 1) : null;
        $lastPage   = $actualPage < $pagesCount ? $pagesCount : null;

        $this->tablePagination = new Pagination(
            $totalCount,
            $items,
            $pagesCount,
            $firstPage,
            $actualPage,
            $nextPage,
            $lastPage,
            $prevPage
        );

        return $this->tablePagination;
    }

    /**
     * @return Pagination
     */
    public function getExportPagination()
    {
        return new Pagination(999999999, 999999999, 1);
    }
}