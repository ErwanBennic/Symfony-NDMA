<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        $response = $this->forward('App\Controller\DataController::getAllSensor');
        $data = $response->getContent();
        $data = json_decode($data);
        return $this->render('profile/profile.html.twig', [
            'data' => $data[0]->value,
        ]);
    }
}