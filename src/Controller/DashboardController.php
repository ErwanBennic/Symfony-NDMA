<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
        $response = $this->forward('App\Controller\DataController::getAllSensor');
        $data = $response->getContent();
        $data = json_decode($data);
        return $this->render('dashboard/dash.html.twig', [
            'data' => $data[0]->value,
        ]);
    }
}