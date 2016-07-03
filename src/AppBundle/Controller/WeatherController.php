<?php

namespace AppBundle\Controller;

use AppBundle\Services\FTPClient;
use AppBundle\Services\WeatherEstimator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class WeatherController extends Controller
{
    /** @var  FTPClient $ftpClient */
    private $ftpClient;

    /**
     * WeatherController constructor.
     */
    public function __construct()
    {
        $this->ftpClient = new FTPClient();
    }

	/**
	* @Route("/weather")
	*/
	public function getWeatherAction() {

        return new JsonResponse($this->ftpClient->connect());
	}

    /**
     * @Route("/weather/stations/readings", name="_listing")
     * @return string
     */
    public function getWeatherReadingsList()
    {
        if ($this->ftpClient->connect() ){
            if ($this->ftpClient->changeDir('/anon/gen/fwo')) {
                $fileList = $this->ftpClient->getFileList('.');
                return $this->render(
                    ':WeatherStationReadingList:list.html.twig',
                    ["fileList" => $fileList]
                );
            } else {
                return new Response(
                    '<html><body><h2>Unable to change the directory</h2></body></html>'
                );
            }
        }
	}


    /**
     * @Route("/weather/stations/live", name="_stationslistinglive")
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public function getWeatherStationsLiveList()
    {
        if ($this->ftpClient->connect()) {
            if ($this->ftpClient->changeDir('/anon/gen/fwo')) {
                $stationsList = $this->ftpClient->getWeatherStationsList('.');

                return JsonResponse::create($stationsList);
//                return $this->render(
//                    ':WeatherStationReadingList:weatherstationslist.html.twig',
//                    ["stations" => $stationsList]
//                );
            } else {
                return new Response(
                    '<html><body><h2>Unable to change the directory</h2></body></html>'
                );
            }
        }
    }


    /**
     * @Route("/weather/stations", name="_stationslisting")
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public function getWeatherStationsListAction()
    {
        $idfile = $this->getParameter('kernel.root_dir') . '/../web/idfile.json';
        $fileContents = file_get_contents($idfile);

//        return JsonResponse::create(json_decode($fileContents, true));
        return $this->render(
            ':WeatherStationReadingList:weatherstationslist.html.twig',
            ["stations" => json_decode($fileContents)]
        );
    }

    /**
     * @Route("/weather/stations/readings/{stationId}", name="_reading")
     * @param $stationId
     *
     * @return string
     */
    public function getWeatherStationReading($stationId)
    {
        if ($this->ftpClient->connect() ) {
            if ($this->ftpClient->changeDir('/anon/gen/fwo')) {
                $readings = $this->ftpClient->readWeather($stationId);
//                return JsonResponse::create($readings);

                return $this->render(
                    ':WeatherStationReadingList:detail.html.twig',
                    ["reading" => json_decode(json_encode($readings), true), "stationId" => $stationId]
                );
            }
        }
    }

    public function getStationLocations($stationsData)
    {

        $stationLocations = [];
        foreach ($stationsData as $item) {
            // var_dump($item);
        }

        return 0;
    }

    /**
     * @Route("/weather/station/distance", name="_distanceEstimate")
     * @return int
     */
    public function estimateDistance()
    {
        $idfile = $this->getParameter('kernel.root_dir') . '/../web/idfile.json';
//        $fileContents = file_get_contents($idfile);
//
//        $stationLocations = array_map('getStationLocations', json_decode($fileContents, true));
////        $this->getStationLocations($fileContents);
        $lat = -38.098971;
        $lon = 145.253902;

        $weatherEstimator = new WeatherEstimator();
        $closestStation = $weatherEstimator->getReadings($idfile, $lat, $lon);

        return JsonResponse::create($closestStation);
    }

//    /**
//     * @Route('/location', name="_location")
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function getMyLocation()
//    {
//        return $this->render(
//            'WeatherStationReadingList/weatherreading.html.twig'
//        );
//    }

    /**
     * @Route("/weather/live/{latitude}/{longitude}", name="_weather")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getMyWeather($latitude, $longitude)
    {
        $idfile = $this->getParameter('kernel.root_dir') . '/../web/idfile.json';

        $weatherEstimator = new WeatherEstimator();
        $closestStation = $weatherEstimator->getReadings($idfile, $latitude, $longitude);

        return JsonResponse::create($closestStation);
    }

    public function calculateDistance($lat, $lon)
    {
        $lat = -38.098971;
        $lon = 145.253902;

        $stnLat = -38.1348;
        $stnLon = 145.2637;
        $R = 6371;  //  km
        $dLat = deg2rad($lat - $stnLat);
        $dLon = deg2rad($lon - $stnLon);

        $stnLatRad = deg2rad($stnLat);
        $userLatRad = deg2rad($stnLat);

        $a = sin($dLat / 2) * sin($dLat / 2) + sin($dLon / 2) * sin($dLon / 2) * cos($stnLatRad) * cos($userLatRad);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $d = $R * $c;

        return JsonResponse::create(["distance" => $d]);
    }
}

?>