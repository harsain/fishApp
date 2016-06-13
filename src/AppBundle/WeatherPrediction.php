<?php
    /**
     * Created by PhpStorm.
     * User: Harsain
     * Date: 4/06/2016
     * Time: 11:57 PM
     */
    
    namespace AppBundle;
    
    
    class WeatherPrediction
    {
        public $amoc;

        public $forecast;

        public $observations;

        /**
         * @return mixed
         */
        public function getAmoc()
        {
            return $this->amoc;
        }

        /**
         * @return mixed
         */
        public function getForecast()
        {
            return $this->forecast;
        }

        /**
         * @return mixed
         */
        public function getObservations()
        {
            return $this->observations;
        }

    }