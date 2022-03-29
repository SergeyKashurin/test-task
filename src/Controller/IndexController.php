<?php

namespace App\Controller;

use App\AbstractIntegrator;
use App\Integrations\FreeSoft;
use App\Integrations\Mdek;
use App\Integrations\MyWay;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(): Response
    {
        return $this->render('app/index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/calc-information/ajax")
     */
    public function ajaxAction(Request $request) {

        $properties = [
            'source'   => (string)$request->query->get('source'),
            'to'       => (string)$request->query->get('to'),
            'weight'   => (float)$request->query->get('weight'),
            'delivery' => (string)$request->query->get('delivery'),
        ];

        $deliviersArray = [
            new Mdek(),
            new MyWay(),
            new FreeSoft(),
        ];

        foreach ($deliviersArray as $deliverClass) {
            $deliversData[] = AbstractIntegrator::getIntegration($deliverClass, $properties);
        }

        if ($request->isXmlHttpRequest()) {
            $jsonData = [];

            foreach ($deliversData as $deliverData) {
                if (!$deliverData) {
                    continue;
                }

                $temp = [
                    'price' => $deliverData['price'],
                    'date'  => $deliverData['date'],
                    'error' => $deliverData['error'],
                ];
                $jsonData[] = $temp;
            }

            return new JsonResponse($jsonData);
        } else {
            return $this->render('app/index/index.html.twig');
        }
    }
}
