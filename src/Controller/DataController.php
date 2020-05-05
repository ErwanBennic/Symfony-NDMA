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
        $sensor = $this->sensorRepository->findOneByName($name);

        $response = new Response();
        if($sensor) {
            $datas = $this->sensorToArray($sensor);
            $response->setStatusCode(Response::HTTP_OK); 
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode($datas));
        }
        else {
            $response->setStatusCode(Response::HTTP_NOT_FOUND); 
        }

        
        return $response;
    }

    /**
     * @Route("/api/sensor", name="getAllSensor", methods={"GET"})
     */
    public function getAllSensor()
    {
        $sensors = $this->sensorRepository->getSensorsLastData();

        $datas = [];

        foreach($sensors as $sensor) {
            //dd($sensor);
            $datas[] = [
                "name" => $sensor[0]->getName(),
                "unit" => $sensor[0]->getUnit()->getName(),
                "value" => $sensor["value"]
            ];
        }

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK); 
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($datas));
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
    

    public function sensorToArray($sensor) {
        $sensorName = $sensor->getName();
        $sensorUnit = $sensor->getUnit()->getName(); 
        $sensorData = $sensor->getSensorData();
        $nbData = count($sensorData);

        $jsonData = ["name" => $sensorName, "unit" => $sensorUnit];
        $datas = [];
        for ($i=0; $i < $nbData; $i++) { 
            $datas[] = [
                "value" => $sensorData[$i]->getValue(),
                "date" => $sensorData[$i]->getDate()
            ];
            
        }
        $jsonData["data"] = $datas;

        return $jsonData;
    }

}
