<?php

namespace KamilDuszynski\TableGeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/kd/table-generator")
 */
class TableController extends Controller
{
    /**
     * @Route("/generate/{page}", name="table_generator.generate")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function generateAction(Request $request)
    {
        $error = null;
        $html  = null;

        try {
            $tableType  = $request->request->get('class');
            $parameters = [
                'name' => $request->request->get('name'),
            ];

            $tableGenerator = $this->get('table_generator');
            $tableGenerator->init($tableType, $parameters);

            $html = $this->renderView('@TableGenerator/Table/table.html.twig', [
                'table' => $tableGenerator->createView(),
            ]);
        } catch (\Exception $exception) {
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
     * @Route("/export/", name="table_generator.export")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function exportAction(Request $request)
    {
        $tableType  = $request->request->get('class');
        $parameters = [
            'name' => $request->request->get('name'),
        ];

        $tableGenerator = $this->get('table_generator');
        $tableGenerator->init($tableType, $parameters);

        return $tableGenerator->createExport();
    }
}
