<?php

namespace KamilDuszynski\TableGeneratorBundle\Model;

use UserBundle\Entity\User;

class Export
{
    const FORMAT_XML = 'XML';
    const FORMAT_CSV = 'CSV';

    /**
     * @var string
     */
    private $actualFormat;

    /**
     * @var User|null
     */
    private $user;

    /**
     * @var string[]
     */
    private $formats = [
        'CSV', 'XML'
    ];

    /**
     * @param      $actualFormat
     * @param null $user
     */
    public function __construct($actualFormat, $user = null)
    {
        $this->actualFormat = $actualFormat;
        $this->user         = $user;
    }

    /**
     * @return string
     */
    public function getActualFormat()
    {
        return $this->actualFormat;
    }

    /**
     * @return null|User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return \string[]
     */
    public function getFormats()
    {
        return $this->formats;
    }
}