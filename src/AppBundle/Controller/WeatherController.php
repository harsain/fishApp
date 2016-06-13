<?php

namespace AppBundle\Controller;

use AppBundle\Services\FTPClient;
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
    public function getWeatherReadingsListAction()
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
    public function getWeatherStationsLiveListAction()
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
    public function getWeatherStationReadingAction($stationId)
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
}

?>