<?php

namespace App\Command;

use App\Entity\Sensor;
use App\Entity\SensorData;
use App\Entity\Unit;
use App\Repository\SensorRepository;
use App\Repository\UnitRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use karpy47\PhpMqttClient\MQTTClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MqttCommand extends Command
{

// the name of the command (the part after "bin/console")
    protected static $defaultName = 'mqtt:listen';
    /**
     * @var UnitRepository
     */
    private $unitRepository;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var SensorRepository
     */
    private $sensorRepository;

    /**
     * MqttCommand constructor.
     * @param UnitRepository $unitRepository
     * @param SensorRepository $sensorRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UnitRepository $unitRepository,
                                SensorRepository $sensorRepository,
                                EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->unitRepository = $unitRepository;
        $this->entityManager = $entityManager;
        $this->sensorRepository = $sensorRepository;
    }

    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $client = new MQTTClient('test.mosquitto.org', 1883);
        $client->setAuthentication('', '');
        $client->setEncryption('cacerts.pem');
        $success = $client->sendConnect(12345);  // set your client ID
        if ($success) {
            $io->write("<info>Le serveur ecoute</info>");
            while (true) {
                $client->sendSubscribe(['EPSI/DHT11/NDMA/#']);
                $messages = $client->getPublishMessages();
                foreach ($messages as $message) {
                    $data = str_replace("'", '"', $message['message']);
                    $data = json_decode($data, true);
                    $unit = $this->findOrCreateUnit($data['unit'], $io);
                    $sensor = $this->findOrCreateSensor($message['topic'], $unit, $io);
                    $this->createSensorData($data['value'], $sensor, $io);

                    $this->entityManager->flush();

//                    $io->write("<info>" . $message['topic'] . ': ' . $message['message'] . "</info>" . PHP_EOL);
                }
            }
            $client->sendDisconnect();
        } else {
            $output->writeln("<error>Connexion echouée</error>");
        }
        $client->close();

        return 0;
    }

    private function findOrCreateUnit($name, $io): Unit
    {
        $unit = $this->unitRepository->findOneBy(['name' => $name]);
        if (!$unit) {
            $unit = new Unit();
            $unit->setName($name);
            $this->entityManager->persist($unit);
            $io->success("Unité créée: $name");
        }

        return $unit;
    }

    private function findOrCreateSensor($topic, Unit $unit, $io): Sensor
    {
        $name = explode("/", $topic);
        $name = end($name);
        $sensor = $this->sensorRepository->findOneBy(['name' => $name]);
        if (!$sensor) {
            $sensor = new Sensor();
            $sensor->setName($name);
            $sensor->setUnit($unit);
            $this->entityManager->persist($sensor);
            $io->success("Capteur créé: $name");
        }

        return $sensor;
    }

    private function createSensorData($value, Sensor $sensor, $io)
    {
        $sensorData = new SensorData();
        $sensorData->setValue($value);
        $sensorData->setDate(new DateTime());
        $sensorData->setSensor($sensor);

        $this->entityManager->persist($sensorData);
        $io->success("Données enregistrées pour le capteur:" . $sensor->getName());

        return $sensorData;
    }
}
