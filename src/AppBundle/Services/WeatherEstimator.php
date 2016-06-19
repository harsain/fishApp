<?php
    /**
     * Created by PhpStorm.
     * User: Harsain
     * Date: 19/06/2016
     * Time: 5:47 PM
     */
    
    namespace AppBundle\Services;
    
    
    class WeatherEstimator
    {


        public function getReadings($idFile, $lat, $lon)
        {
            $fileContents = file_get_contents($idFile);
            $distances = [];
            $stations = json_decode($fileContents, true);
            foreach ($stations as $station) {
                $distances[] = $this->calculateDistance($lat, $lon, $station["location"]["lat"], $station["location"]["lon"]);
            }

            $minDistanceStationIndex = array_keys($distances, min($distances));

            return $stations[ $minDistanceStationIndex[0] ];
        }

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
    }