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
        $response = $this->forward('App\Controller\DataController::getAllSensor');
        $data = $response->getContent();
        $data = json_decode($data);
        return $this->render('home/home.html.twig', [
            'datas' => $data,
        ]);
    }
}
