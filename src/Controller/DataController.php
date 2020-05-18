<?php

namespace App\Controller;

use App\Entity\Sensor;
use App\Repository\SensorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $sensorData = $sensor->getSensorData();
        $response = new Response();
        if($sensor) {
            $datas = $this->sensorToArray($sensor);
            $response->setStatusCode(Response::HTTP_OK);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->setContent(json_encode($datas));

        } else {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }


        return $response;
    }

    /**
     * @Route("/api/sensor", name="getAllSensor", methods={"GET"})
     */
    public function getAllSensor()
    {
//        $sensors = $this->sensorRepository->getSensorsLastData();
        $sensors = $this->sensorRepository->findAll();
        $datas = [];

        foreach($sensors as $sensor) {
//            dd();
            $datas[] = [
                "name" => $sensor->getName(),
                "unit" => $sensor->getUnit()->getName(),
                "value" => $sensor->getSensorData()->get($sensor->getSensorData()->count() - 1)->getValue()
            ];
        }

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
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


    /**
     * @Route("/api/getchart/{name}", name="getChart", methods={"GET"})
     */
    public function getChart(string $name)
    {
        $sensor = $this->sensorRepository->findOneByName($name);
        $sensorData = $sensor->getSensorData();
        //echo $sensorData[0]->getValue();

        $values = [];
        $labels = [];

        foreach($sensorData as $data) {
            //$date = date_format($data->getDate(), 'Y-m-d H:i:s');
            $values[] = $data->getValue();
            $labels[] = $data->getDate()->format("m-Y à H:i");
        }

        $data = [
            "labels" => $labels,
            "values" => $values
        ];

        $response = new Response();
        if($sensor) {
            $response->setStatusCode(Response::HTTP_OK);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->setContent(json_encode($data));

        } else {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }


        return $response;
    }


    public function sensorToArray($sensor) {
        $sensorName = $sensor->getName();
        $sensorUnit = $sensor->getUnit()->getName();
        $sensorData = $sensor->getSensorData();
        $nbData = count($sensorData);
        $jsonData = ["name" => $sensorName, "unit" => $sensorUnit];
        $datas = [];
        if ($nbData < 20){
            for ($i = 0; $i < $nbData; $i++) {
                $datas[] = [
                    "value" => $sensorData[$i]->getValue(),
                    "date" => $sensorData[$i]->getDate()
                ];
            }
        } else {
            for ($i = $nbData - 20; $i < $nbData; $i++) {
                $datas[] = [
                    "value" => $sensorData[$i]->getValue(),
                    "date" => $sensorData[$i]->getDate()
                ];

            }
        }

        $jsonData["data"] = $datas;

        return $jsonData;
    }
}
