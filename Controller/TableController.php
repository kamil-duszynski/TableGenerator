<?php

namespace KamilDuszynski\TableGeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TableController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generateAction(Request $request)
    {
        $error = null;
        $html  = null;

        try {
            $tableType = $request->request->get('class');
            $parameters = [
                'name' => $request->request->get('name')
            ];

            $tableGenerator = $this->get('table_generator');
            $tableGenerator->init(new $tableType(), $parameters);

            $html = $this->renderView('@TableGenerator/Table/table.html.twig', [
                'table' => $tableGenerator->createView(),
            ]);
        }
        catch(\Exception $exception) {
            $error = $exception->getMessage();
        }

        return new JsonResponse(
            [
                'error' => $error,
                'html'  => $html,
            ]
        );
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportAction(Request $request)
    {
        $tableType = $request->request->get('class');
        $parameters = [
            'name' => $request->request->get('name')
        ];

        $tableGenerator = $this->get('table_generator');
        $tableGenerator->init(new $tableType(), $parameters);

        return $tableGenerator->createExport();
    }
}
