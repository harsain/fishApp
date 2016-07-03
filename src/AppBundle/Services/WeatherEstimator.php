<?php
    /**
     * Created by PhpStorm.
     * User: Harsain
     * Date: 19/06/2016
     * Time: 5:47 PM
     */
    
    namespace AppBundle\Services;


    use AppBundle\Model\StationReading;
    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
    use Symfony\Component\Serializer\Serializer;

    class WeatherEstimator
    {


        public function getReadings($idFile, $lat, $lon)
        {
            $toReturn = [];
            $fileContents = file_get_contents($idFile);
            $distances = [];
            $stations = json_decode($fileContents, true);
            foreach ($stations as $station) {
                $distances[] = $this->calculateDistance($lat, $lon, $station["location"]["lat"], $station["location"]["lon"]);
            }

            $minDistanceStationIndex = array_keys($distances, min($distances));

            $station = $stations[ $minDistanceStationIndex[0] ];
            $ftpClient = new FTPClient();
            if ($ftpClient->connect()) {
                if ($ftpClient->changeDir('/anon/gen/fwo')) {
                    $readings = $ftpClient->readStationWeather($station["identifier"] . ".xml", $station["bom_id"]);
                    $toReturn = $this->parseStationReadings($readings);
                }
            }

            $encoders = [new JsonEncoder()];
            $normalizer = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizer, $encoders);

            return json_decode($serializer->serialize($toReturn, 'json'), true);
        }

        /**
         * @param $lat
         * @param $lon
         * @param $stnLat
         * @param $stnLon
         *
         * @return int
         */
        public function calculateDistance($lat, $lon, $stnLat, $stnLon)
        {
            $R = 6371;  //  km
            $dLat = deg2rad($lat - $stnLat);
            $dLon = deg2rad($lon - $stnLon);

            $stnLatRad = deg2rad($stnLat);
            $userLatRad = deg2rad($stnLat);

            $a = sin($dLat / 2) * sin($dLat / 2) + sin($dLon / 2) * sin($dLon / 2) * cos($stnLatRad) * cos($userLatRad);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            $d = $R * $c;

            return $d;
        }

        public function parseStationReadings($stationReadingArr)
        {
            $reading = new StationReading();
            if (!empty($stationReadingArr)) {
                $stationReading = $stationReadingArr[0];
                $reading->setWmoId($stationReading['@wmo-id']);
                $reading->setBomId($stationReading['@bom-id']);
                $reading->setTz($stationReading['@tz']);
                $reading->setStnName($stationReading['@stn-name']);
                $reading->setStnHeight($stationReading['@stn-height']);
                $reading->setLatitude($stationReading['@lat']);
                $reading->setLongitude($stationReading['@lon']);
                $reading->setForecastDistrictId($stationReading['@forecast-district-id']);
                $reading->setDescription($stationReading['@description']);
                $reading->setType($stationReading['@type']);
                if ($stationReading['period']) {
                    $stationPeriod = $stationReading['period'];
                    $reading->setTimeUTC($stationPeriod['@time-utc']);
                    $reading->setTimeLocal($stationPeriod['@time-local']);

                    if ($stationPeriod['level']) {
                        $stationPeriodLevel = $stationPeriod['level'];

                        if ($stationPeriodLevel['element']) {
                            $stationPeriodLevelElement = $stationPeriodLevel['element'];

                            foreach ($stationPeriodLevelElement as $element) {
                                $this->stationPeriodLevelElementParser($element, $reading);
                            }
                        }
                    }
                }
            }

            return $reading;
        }

        public function stationPeriodLevelElementParser($stationPeriodLevelElement, StationReading &$reading)
        {
            $type = $stationPeriodLevelElement['@type'];
            switch ($type) {
                case 'apparent_temp':
                    $apparentTemp = [
                        'value' => $stationPeriodLevelElement['#'],
                        'unit'  => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setApparentTmp($apparentTemp);
                    break;
                case 'delta_t':
                    $deltaT = [
                        'value' => $stationPeriodLevelElement['#'],
                        'unit'  => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setDeltT($deltaT);
                    break;
                case 'gust_kmh':
                    $gustKmh = [
                        'value' => $stationPeriodLevelElement['#'],
                        'unit'  => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setGustKmh($gustKmh);
                    break;
                case 'wind_gust_spd':
                    $windGustSpd = [
                        'value' => $stationPeriodLevelElement['#'],
                        'unit'  => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setWindGustSpd($windGustSpd);
                    break;
                case 'air_temperature':
                    $airTemp = [
                        'value' => $stationPeriodLevelElement['#'],
                        'unit'  => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setAirTemperature($airTemp);
                    break;
                case 'dew_point':
                    $dewPoint = [
                        'value' => $stationPeriodLevelElement['#'],
                        'unit'  => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setDewPoint($dewPoint);
                    break;
                case 'pres':
                    $pres = [
                        'value' => $stationPeriodLevelElement['#'],
                        'units' => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setPressure($pres);
                    break;
                case 'msl_pres':
                    $mslPres = [
                        'value' => $stationPeriodLevelElement['#'],
                        'unit'  => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setMslPres($mslPres);
                    break;
                case 'qnh_pres':
                    $qnhPres = [
                        'value' => $stationPeriodLevelElement['#'],
                        'units' => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setQnhPres($qnhPres);
                    break;
                case 'rain_hour':
                    $rainHour = [
                        'value' => $stationPeriodLevelElement['#'],
                        'unit'  => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setRainHour($rainHour);
                    break;
                case 'rel-humidity':
                    $relHumidity = [
                        'value' => $stationPeriodLevelElement['#'],
                        'unit'  => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setRelHumidity($relHumidity);
                    break;
                case 'vis_km':
                    $visKm = [
                        'value' => $stationPeriodLevelElement['#'],
                        'unit'  => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setVisKm($visKm);
                    break;
                case 'wind_dir':
                    $windDir = [
                        'value' => $stationPeriodLevelElement['#']
                    ];
                    $reading->setWindDir($windDir);
                    break;
                case 'deg':
                    $deg = [
                        'value' => $stationPeriodLevelElement['#'],
                        'unit'  => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setWindDirDeg($deg);
                    break;
                case 'wind_spd_kmh':
                    $windSpdKmh = [
                        'value' => $stationPeriodLevelElement['#'],
                        'unit'  => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setWindSpdKmh($windSpdKmh);
                    break;
                case 'wind_spd':
                    $windSpd = [
                        'value' => $stationPeriodLevelElement['#'],
                        'unit'  => $stationPeriodLevelElement['@units']
                    ];
                    $reading->setWindSpd($windSpd);
                    break;
                case 'rainfall':
                    $rainfall = [
                        'value'          => $stationPeriodLevelElement['#'],
                        'unit'           => $stationPeriodLevelElement['@units'],
                        'duration'       => $stationPeriodLevelElement['@duration'],
                        'startTimeLocal' => $stationPeriodLevelElement['@start-time-local'],
                        'endTimeLocal'   => $stationPeriodLevelElement['@end-time-local'],
                        'startTimeUTC'   => $stationPeriodLevelElement['@start-time-utc'],
                        'endTimeUTC'     => $stationPeriodLevelElement['@end-time-utc']
                    ];
                    $reading->setRainfall($rainfall);
                    break;
                case 'rainfall_24hr':
                    $rainfall24Hr = [
                        'value'          => $stationPeriodLevelElement['#'],
                        'unit'           => $stationPeriodLevelElement['@units'],
                        'duration'       => $stationPeriodLevelElement['@duration'],
                        'startTimeLocal' => $stationPeriodLevelElement['@start-time-local'],
                        'endTimeLocal'   => $stationPeriodLevelElement['@end-time-local'],
                        'startTimeUTC'   => $stationPeriodLevelElement['@start-time-utc'],
                        'endTimeUTC'     => $stationPeriodLevelElement['@end-time-utc']
                    ];
                    $reading->setRainfall24Hr($rainfall24Hr);
                    break;
                case 'maximum_air_temperature':
                    $maxAirTemp = [
                        'value'          => $stationPeriodLevelElement['#'],
                        'unit'           => $stationPeriodLevelElement['@units'],
                        'startTimeLocal' => $stationPeriodLevelElement['@start-time-local'],
                        'endTimeLocal'   => $stationPeriodLevelElement['@end-time-local'],
                        'startTimeUTC'   => $stationPeriodLevelElement['@start-time-utc'],
                        'endTimeUTC'     => $stationPeriodLevelElement['@end-time-utc'],
                        'timeUTC'        => $stationPeriodLevelElement['@time-utc'],
                        'timeLocal'      => $stationPeriodLevelElement['@time-local']
                    ];
                    $reading->setMaximumAirTemperature($maxAirTemp);
                    break;
                case 'minimum_air_temperature':
                    $minAirTemp = [
                        'value'          => $stationPeriodLevelElement['#'],
                        'unit'           => $stationPeriodLevelElement['@units'],
                        'instance'       => $stationPeriodLevelElement['@instance'],
                        'startTimeLocal' => $stationPeriodLevelElement['@start-time-local'],
                        'endTimeLocal'   => $stationPeriodLevelElement['@end-time-local'],
                        'startTimeUTC'   => $stationPeriodLevelElement['@start-time-utc'],
                        'endTimeUTC'     => $stationPeriodLevelElement['@end-time-utc'],
                        'timeUTC'        => $stationPeriodLevelElement['@time-utc'],
                        'timeLocal'      => $stationPeriodLevelElement['@time-local']
                    ];
                    $reading->setMinimumAirTemperature($minAirTemp);
                    break;
                case 'maximum_gust_spd':
                    $maxGustSpd = [
                        'value'          => $stationPeriodLevelElement['#'],
                        'unit'           => $stationPeriodLevelElement['@units'],
                        'instance'       => $stationPeriodLevelElement['@instance'],
                        'startTimeLocal' => $stationPeriodLevelElement['@start-time-local'],
                        'endTimeLocal'   => $stationPeriodLevelElement['@end-time-local'],
                        'startTimeUTC'   => $stationPeriodLevelElement['@start-time-utc'],
                        'endTimeUTC'     => $stationPeriodLevelElement['@end-time-utc'],
                        'timeUTC'        => $stationPeriodLevelElement['@time-utc'],
                        'timeLocal'      => $stationPeriodLevelElement['@time-local']
                    ];
                    $reading->setMaximumGustSpd($maxGustSpd);
                    break;
                case 'maximum_gust_kmh':
                    $maxGustKmh = [
                        'value'          => $stationPeriodLevelElement['#'],
                        'unit'           => $stationPeriodLevelElement['@units'],
                        'instance'       => $stationPeriodLevelElement['@instance'],
                        'startTimeLocal' => $stationPeriodLevelElement['@start-time-local'],
                        'endTimeLocal'   => $stationPeriodLevelElement['@end-time-local'],
                        'startTimeUTC'   => $stationPeriodLevelElement['@start-time-utc'],
                        'endTimeUTC'     => $stationPeriodLevelElement['@end-time-utc'],
                        'timeUTC'        => $stationPeriodLevelElement['@time-utc'],
                        'timeLocal'      => $stationPeriodLevelElement['@time-local']
                    ];
                    $reading->setMaximumGustKmh($maxGustKmh);
                    break;
                case 'maximum_gust_dir':
                    $maxGustDir = [
                        'value'          => $stationPeriodLevelElement['#'],
                        'instance'       => $stationPeriodLevelElement['@instance'],
                        'startTimeLocal' => $stationPeriodLevelElement['@start-time-local'],
                        'endTimeLocal'   => $stationPeriodLevelElement['@end-time-local'],
                        'startTimeUTC'   => $stationPeriodLevelElement['@start-time-utc'],
                        'endTimeUTC'     => $stationPeriodLevelElement['@end-time-utc'],
                        'timeUTC'        => $stationPeriodLevelElement['@time-utc'],
                        'timeLocal'      => $stationPeriodLevelElement['@time-local']
                    ];
                    $reading->setMaximumGustDir($maxGustDir);
                    break;
                default:
                    break;
            }
        }
    }