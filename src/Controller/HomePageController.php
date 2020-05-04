<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomePageController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        $response = $this->forward('App\Controller\DataController::getAllSensorData');
        $data = $response->getContent();
        echo $data;
        $data = json_decode($data);
        return $this->render('home/home.html.twig', [
            'data' => $data[0]->value,
        ]);
    }
}
