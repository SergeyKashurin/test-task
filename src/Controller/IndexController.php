<?php

namespace App\Controller;

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
//        $students = $this->getDoctrine()
//            ->getRepository('AppBundle:Student')
//            ->findAll();

        if ($request->isXmlHttpRequest()) {
            $jsonData = array();
            $idx = 0;
            //foreach($students as $student) {
                $temp = array(
                    'source' => $request->query->get('source'),
                    'to'     => $request->query->get('to'),
                    'size'   => $request->query->get('size'),
                );
                $jsonData[$idx++] = $temp;
            //}
            return new JsonResponse($jsonData);
        } else {
            return $this->render('app/index/index.html.twig');
        }
    }
}
