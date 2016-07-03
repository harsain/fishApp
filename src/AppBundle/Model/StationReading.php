<?php
    /**
     * Created by PhpStorm.
     * User: Harsain
     * Date: 2/07/2016
     * Time: 10:03 PM
     */
    
    namespace AppBundle\Model;
    
    
    class StationReading
    {
        private $wmoId;

        private $bomId;

        private $tz;

        private $stnName;

        private $stnHeight;

        private $type;

        private $latitude;

        private $longitude;

        private $forecastDistrictId;

        private $description;

        private $timeUTC;

        private $timeLocal;

        private $apparentTmp = [];

        private $deltT = [];

        private $gustKmh = [];

        private $windGustSpd = [];

        private $airTemperature = [];

        private $dewPoint = [];

        private $pressure = [];

        private $mslPres = [];

        private $qnhPres = [];

        private $rainHour = [];

        private $rainTen = [];

        private $relHumidity = [];

        private $visKm = [];

        private $windDir = [];

        private $windDirDeg = [];

        private $windSpdKmh = [];

        private $windSpd = [];

        private $rainfall = [];

        private $rainfall24Hr = [];

        private $maximumAirTemperature = [];

        private $minimumAirTemperature = [];

        private $maximumGustSpd = [];

        private $maximumGustKmh = [];

        private $maximumGustDir = [];

        /**
         * @return mixed
         */
        public function getWmoId()
        {
            return $this->wmoId;
        }

        /**
         * @param mixed $wmoId
         */
        public function setWmoId($wmoId)
        {
            $this->wmoId = $wmoId;
        }

        /**
         * @return mixed
         */
        public function getBomId()
        {
            return $this->bomId;
        }

        /**
         * @param mixed $bomId
         */
        public function setBomId($bomId)
        {
            $this->bomId = $bomId;
        }

        /**
         * @return mixed
         */
        public function getTz()
        {
            return $this->tz;
        }

        /**
         * @param mixed $tz
         */
        public function setTz($tz)
        {
            $this->tz = $tz;
        }

        /**
         * @return mixed
         */
        public function getStnName()
        {
            return $this->stnName;
        }

        /**
         * @param mixed $stnName
         */
        public function setStnName($stnName)
        {
            $this->stnName = $stnName;
        }

        /**
         * @return mixed
         */
        public function getStnHeight()
        {
            return $this->stnHeight;
        }

        /**
         * @param mixed $stnHeight
         */
        public function setStnHeight($stnHeight)
        {
            $this->stnHeight = $stnHeight;
        }

        /**
         * @return mixed
         */
        public function getType()
        {
            return $this->type;
        }

        /**
         * @param mixed $type
         */
        public function setType($type)
        {
            $this->type = $type;
        }

        /**
         * @return mixed
         */
        public function getLatitude()
        {
            return $this->latitude;
        }

        /**
         * @param mixed $latitude
         */
        public function setLatitude($latitude)
        {
            $this->latitude = $latitude;
        }

        /**
         * @return mixed
         */
        public function getLongitude()
        {
            return $this->longitude;
        }

        /**
         * @param mixed $longitude
         */
        public function setLongitude($longitude)
        {
            $this->longitude = $longitude;
        }

        /**
         * @return mixed
         */
        public function getForecastDistrictId()
        {
            return $this->forecastDistrictId;
        }

        /**
         * @param mixed $forecastDistrictId
         */
        public function setForecastDistrictId($forecastDistrictId)
        {
            $this->forecastDistrictId = $forecastDistrictId;
        }

        /**
         * @return mixed
         */
        public function getDescription()
        {
            return $this->description;
        }

        /**
         * @param mixed $description
         */
        public function setDescription($description)
        {
            $this->description = $description;
        }

        /**
         * @return mixed
         */
        public function getTimeUTC()
        {
            return $this->timeUTC;
        }

        /**
         * @param mixed $timeUTC
         */
        public function setTimeUTC($timeUTC)
        {
            $this->timeUTC = $timeUTC;
        }

        /**
         * @return mixed
         */
        public function getTimeLocal()
        {
            return $this->timeLocal;
        }

        /**
         * @param mixed $timeLocal
         */
        public function setTimeLocal($timeLocal)
        {
            $this->timeLocal = $timeLocal;
        }

        /**
         * @return array
         */
        public function getApparentTmp()
        {
            return $this->apparentTmp;
        }

        /**
         * @param array $apparentTmp
         */
        public function setApparentTmp($apparentTmp)
        {
            $this->apparentTmp = $apparentTmp;
        }

        /**
         * @return array
         */
        public function getDeltT()
        {
            return $this->deltT;
        }

        /**
         * @param array $deltT
         */
        public function setDeltT($deltT)
        {
            $this->deltT = $deltT;
        }

        /**
         * @return array
         */
        public function getGustKmh()
        {
            return $this->gustKmh;
        }

        /**
         * @param array $gustKmh
         */
        public function setGustKmh($gustKmh)
        {
            $this->gustKmh = $gustKmh;
        }

        /**
         * @return array
         */
        public function getWindGustSpd()
        {
            return $this->windGustSpd;
        }

        /**
         * @param array $windGustSpd
         */
        public function setWindGustSpd($windGustSpd)
        {
            $this->windGustSpd = $windGustSpd;
        }

        /**
         * @return array
         */
        public function getAirTemperature()
        {
            return $this->airTemperature;
        }

        /**
         * @param array $airTemperature
         */
        public function setAirTemperature($airTemperature)
        {
            $this->airTemperature = $airTemperature;
        }

        /**
         * @return array
         */
        public function getDewPoint()
        {
            return $this->dewPoint;
        }

        /**
         * @param array $dewPoint
         */
        public function setDewPoint($dewPoint)
        {
            $this->dewPoint = $dewPoint;
        }

        /**
         * @return array
         */
        public function getPressure()
        {
            return $this->pressure;
        }

        /**
         * @param array $pressure
         */
        public function setPressure($pressure)
        {
            $this->pressure = $pressure;
        }

        /**
         * @return array
         */
        public function getMslPres()
        {
            return $this->mslPres;
        }

        /**
         * @param array $mslPres
         */
        public function setMslPres($mslPres)
        {
            $this->mslPres = $mslPres;
        }

        /**
         * @return array
         */
        public function getQnhPres()
        {
            return $this->qnhPres;
        }

        /**
         * @param array $qnhPres
         */
        public function setQnhPres($qnhPres)
        {
            $this->qnhPres = $qnhPres;
        }

        /**
         * @return array
         */
        public function getRainHour()
        {
            return $this->rainHour;
        }

        /**
         * @param array $rainHour
         */
        public function setRainHour($rainHour)
        {
            $this->rainHour = $rainHour;
        }

        /**
         * @return array
         */
        public function getRainTen()
        {
            return $this->rainTen;
        }

        /**
         * @param array $rainTen
         */
        public function setRainTen($rainTen)
        {
            $this->rainTen = $rainTen;
        }

        /**
         * @return array
         */
        public function getRelHumidity()
        {
            return $this->relHumidity;
        }

        /**
         * @param array $relHumidity
         */
        public function setRelHumidity($relHumidity)
        {
            $this->relHumidity = $relHumidity;
        }

        /**
         * @return array
         */
        public function getVisKm()
        {
            return $this->visKm;
        }

        /**
         * @param array $visKm
         */
        public function setVisKm($visKm)
        {
            $this->visKm = $visKm;
        }

        /**
         * @return array
         */
        public function getWindDir()
        {
            return $this->windDir;
        }

        /**
         * @param array $windDir
         */
        public function setWindDir($windDir)
        {
            $this->windDir = $windDir;
        }

        /**
         * @return array
         */
        public function getWindDirDeg()
        {
            return $this->windDirDeg;
        }

        /**
         * @param array $windDirDeg
         */
        public function setWindDirDeg($windDirDeg)
        {
            $this->windDirDeg = $windDirDeg;
        }

        /**
         * @return array
         */
        public function getWindSpdKmh()
        {
            return $this->windSpdKmh;
        }

        /**
         * @param array $windSpdKmh
         */
        public function setWindSpdKmh($windSpdKmh)
        {
            $this->windSpdKmh = $windSpdKmh;
        }

        /**
         * @return array
         */
        public function getWindSpd()
        {
            return $this->windSpd;
        }

        /**
         * @param array $windSpd
         */
        public function setWindSpd($windSpd)
        {
            $this->windSpd = $windSpd;
        }

        /**
         * @return array
         */
        public function getRainfall()
        {
            return $this->rainfall;
        }

        /**
         * @param array $rainfall
         */
        public function setRainfall($rainfall)
        {
            $this->rainfall = $rainfall;
        }

        /**
         * @return array
         */
        public function getRainfall24Hr()
        {
            return $this->rainfall24Hr;
        }

        /**
         * @param array $rainfall24Hr
         */
        public function setRainfall24Hr($rainfall24Hr)
        {
            $this->rainfall24Hr = $rainfall24Hr;
        }

        /**
         * @return array
         */
        public function getMaximumAirTemperature()
        {
            return $this->maximumAirTemperature;
        }

        /**
         * @param array $maximumAirTemperature
         */
        public function setMaximumAirTemperature($maximumAirTemperature)
        {
            $this->maximumAirTemperature = $maximumAirTemperature;
        }

        /**
         * @return array
         */
        public function getMinimumAirTemperature()
        {
            return $this->minimumAirTemperature;
        }

        /**
         * @param array $minimumAirTemperature
         */
        public function setMinimumAirTemperature($minimumAirTemperature)
        {
            $this->minimumAirTemperature = $minimumAirTemperature;
        }

        /**
         * @return array
         */
        public function getMaximumGustSpd()
        {
            return $this->maximumGustSpd;
        }

        /**
         * @param array $maximumGustSpd
         */
        public function setMaximumGustSpd($maximumGustSpd)
        {
            $this->maximumGustSpd = $maximumGustSpd;
        }

        /**
         * @return array
         */
        public function getMaximumGustKmh()
        {
            return $this->maximumGustKmh;
        }

        /**
         * @param array $maximumGustKmh
         */
        public function setMaximumGustKmh($maximumGustKmh)
        {
            $this->maximumGustKmh = $maximumGustKmh;
        }

        /**
         * @return array
         */
        public function getMaximumGustDir()
        {
            return $this->maximumGustDir;
        }

        /**
         * @param array $maximumGustDir
         */
        public function setMaximumGustDir($maximumGustDir)
        {
            $this->maximumGustDir = $maximumGustDir;
        }


    }