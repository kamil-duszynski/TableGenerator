<?php

namespace KamilDuszynski\TableGeneratorBundle\Model;

class Pagination
{
    /**
     * @var int
     */
    private $totalCount;

    /**
     * @var int
     */
    private $perPage;

    /**
     * @var int
     */
    private $pagesCount;

    /**
     * @var int[]
     */
    private $items = [
        10, 20, 50, 100
    ];

    /**
     * @var int
     */
    private $firstPage;

    /**
     * @var int|null
     */
    private $actualPage;

    /**
     * @var int|null
     */
    private $nextPage;

    /**
     * @var int|null
     */
    private $lastPage;

    /**
     * @var int|null
     */
    private $prevPage;

    /**
     * @param int      $totalCount
     * @param int      $perPage
     * @param int      $pagesCount
     * @param int|null $firstPage
     * @param int|null $actualPage
     * @param int|null $nextPage
     * @param int|null $lastPage
     * @param int|null $prevPage
     */
    public function __construct($totalCount, $perPage, $pagesCount, $firstPage = null, $actualPage = null, $nextPage = null, $lastPage = null, $prevPage = null)
    {
        $this->totalCount = $totalCount;
        $this->perPage    = $perPage;
        $this->pagesCount = $pagesCount;
        $this->firstPage  = $firstPage;
        $this->actualPage = $actualPage;
        $this->nextPage   = $nextPage;
        $this->lastPage   = $lastPage;
        $this->prevPage   = $prevPage;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @return int
     */
    public function getPagesCount()
    {
        return $this->pagesCount;
    }

    /**
     * @return int
     */
    public function getFirstPage()
    {
        return $this->firstPage;
    }

    /**
     * @return int|null
     */
    public function getActualPage()
    {
        return $this->actualPage;
    }

    /**
     * @return int|null
     */
    public function getNextPage()
    {
        return $this->nextPage;
    }

    /**
     * @return int|null
     */
    public function getLastPage()
    {
        return $this->lastPage;
    }

    /**
     * @return int|null
     */
    public function getPrevPage()
    {
        return $this->prevPage;
    }

    /**
     * @return int[]
     */
    public function getItems()
    {
        return $this->items;
    }
}