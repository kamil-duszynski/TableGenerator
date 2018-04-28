<?php

namespace KamilDuszynski\TableGeneratorBundle\Helper;

use KamilDuszynski\TableGeneratorBundle\Model\Export;
use KamilDuszynski\TableGeneratorBundle\Model\Table;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use UserBundle\Entity\User;

class ExportHelper
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var TwigEngine
     */
    private $twigEngine;

    /**
     * @var Table|null
     */
    private $table;

    /**
     * @var User|null
     */
    private $user;

    /**
     * @var Export
     */
    private $export;

    /**
     * @param RequestStack      $requestStack
     * @param TokenStorage      $tokenStorage
     * @param \Twig_Environment $twigEngine
     */
    public function __construct(RequestStack $requestStack, TokenStorage $tokenStorage, \Twig_Environment $twigEngine)
    {
        $this->request    = $requestStack->getMasterRequest();
        $this->twigEngine = $twigEngine;
        $token            = $tokenStorage->getToken();

        if (null !== $token) {
            $this->user = $token->getUser();
        }
    }

    /**
     * @param Table $table
     *
     * @return $this
     */
    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return Export
     */
    public function getExport()
    {
        if (null !== $this->export) {
            return $this->export;
        }

        $this->export = new Export(
            $this->request->get('export', Export::FORMAT_CSV),
            $this->user
        );

        return $this->export;
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function getExportData()
    {
        if (null === $this->table) {
            throw new \Exception('Table for export empty or not set');
        }

        if (null === $this->user) {
            throw new \Exception('User not logged');
        }

        if (Export::FORMAT_XML === $this->export->getActualFormat()) {
            return $this->getXmlData();
        }

        if (Export::FORMAT_CSV === $this->export->getActualFormat()) {
            return $this->getCsvData();
        }

        throw new \Exception(
            sprintf(
                'Export %s format not supported!',
                $this->export->getActualFormat()
            )
        );
    }

    /**
     * @return Response
     */
    private function getXmlData()
    {
        $data = $this->twigEngine->render(
            '@TableGenerator/Table/Export/format.xml.twig',
            [
                'rows' => $this->table->getData(),
                'columns' => $this->table->getColumns(),
                'user' => $this->user
            ]
        );

        return new Response(
            $data,
            200,
            [
                'Content-Type' => 'xml',
            ]
        );
    }

    /**
     * @return Response
     */
    private function getCsvData()
    {
        $data = null;

        if (false == empty($this->table->getData())) {
            ob_start();
            $df = fopen("php://output", 'w');
            $header = [];

            foreach($this->table->getColumns() as $column) {
                if (null !== $column->getLabel()) {
                    $header[] = $column->getLabel();

                    continue;
                }

                $header[] = $column->getName();
            }

            fputcsv($df, $header);

            foreach ($this->table->getData() as $row) {
                $dataRow = [];

                foreach ($this->table->getColumns() as $column) {
                    if (false === isset($row[$column->getName()])) {
                        continue;
                    }

                    $dataRow[] = $row[$column->getName()];
                }

                fputcsv($df, $dataRow);
            }

            fclose($df);
            $data = ob_get_clean();
        }

        return new Response(
            $data,
            200,
            [
                'Content-Type' => 'csv',
            ]
        );
    }
}
