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
}

?>