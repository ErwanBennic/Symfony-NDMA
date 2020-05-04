<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataController extends AbstractController
{

    /**
     * @Route("/api/sensor/{name}", name="getSensor", methods={"GET"})
     */
    public function getSensorData(string $name)
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK); 
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode([
            'value' => 12,
            'unit' => "température"
        ]));
        return $response;
    }


    /**
     * @Route("/api/sensor", name="saveSensor", methods={"PUT"})
     */
    public function saveSensorData() 
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);    
        $response->prepare();
        return $response;
    }

}
