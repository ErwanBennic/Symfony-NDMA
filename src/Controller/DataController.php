<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SensorRepository;

class DataController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SensorRepository
     */
    private $sensorRepository;

    public function __construct(EntityManagerInterface $entityManager, SensorRepository $sensorRepository)
    {
        $this->entityManager = $entityManager;
        $this->sensorRepository = $sensorRepository;
    }

    /**
     * @Route("/api/sensor/{name}", name="getSensor", methods={"GET"})
     */
    public function getSensor(string $name)
    {
        $sensor =  $this->sensorRepository->findByName($name);
        $sensorData = $sensor->getSensorData();
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK); 
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode([
            'azd' => 44949,
            'unit' => "température"
        ]));
        
        return $response;
    }

    /**
     * @Route("/api/sensor/all", name="getAllSensor", methods={"GET"})
     */
    public function getAllSensor()
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK); 
        $response->headers->set('Content-Type', 'application/json');
        $sensorsData = [
            ['value' => '12', 'unit' => 'Thermomètre', 'type' => 'Humidité'], 
            ['value' => 'Fermée', 'unit'=> 'Porte', 'type' => 'Chépa']];
        $response->setContent(json_encode($sensorsData));
        return $response;
    }


    /**
     * @Route("/api/sensor", name="saveSensor", methods={"PUT"})
     */
    public function saveSensorData() 
    {
        $sensorData = new SensorData();
        $sensorData->setData('Keyboard');
        $sensorData->setTimestamp();
        $sensorData->setSensorID('Ergonomic and stylish!');

        $this->entityManager->persist($sensorData);
        $this->entityManager->flush();

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);    
        return $response;
    }
    

}
