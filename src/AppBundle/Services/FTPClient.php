<?php

    namespace AppBundle\Services;

    use AppBundle\WeatherPrediction;
    use Symfony\Component\Serializer\Encoder\XmlEncoder;
    use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
    use Symfony\Component\Serializer\Serializer;

    /**
     * Created by PhpStorm.
     * User: Harsain
     * Date: 4/06/2016
     * Time: 9:22 PM
     */
    class FTPClient
    {
        private $ftpServer = "ftp.bom.gov.au";
        private $connectionID;

        /**
         *
         */
        public function connect()
        {
            $this->connectionID = ftp_connect($this->ftpServer) or die("Couldn't connect to $this->ftpServer");
            return ftp_login($this->connectionID, 'anonymous', 'anonymous@domain.com');

//            if (ftp_chdir($this->connectionID, '/anon/gen/fwo')) {
//                $remotePath = "IDN10064.xml";
//                $tempPath = tempnam(sys_get_temp_dir(), 'ftp');
//
//                ftp_get($this->connectionID, $tempPath, $remotePath, FTP_BINARY);
//                $contents = file_get_contents($tempPath);
//                $encoders = [new XmlEncoder()];
//                $normalizer = [new ObjectNormalizer()];
//                $serializer = new Serializer($normalizer, $encoders);
//
//                $deserialized = $serializer->deserialize($contents, 'AppBundle\WeatherPrediction', 'xml');
//                unlink($tempPath);
//                return $deserialized;
//                echo "<pre>" .$contents . "</pre>";
//                die();
//                $contents = ftp_nlist($this->connectionID, ".");
//                foreach ($contents as $file) {
//                    echo $file . PHP_EOL;
//                    if ($file == '.' || $file == '..') {
//                        continue;
//                    }
//
//                    ftp_get($this->connectionID, $file, $file, FTP_BINARY);
//                }
//            }
        }

        /**
         * @param $dir
         *
         * @return bool
         */
        public function changeDir($dir)
        {
            return ftp_chdir($this->connectionID, $dir);
        }

        /**
         * @param string $dir
         *
         * @return array
         */
        public function getFileList($dir = '.')
        {
            $fileList = ftp_nlist($this->connectionID, $dir);
            return $fileList;
        }

        /**
         * @param string $dir
         *
         * @return array
         */
        public function getWeatherStationsList($dir = '.')
        {
            $fileList = ftp_nlist($this->connectionID, $dir);
            $idfile = "idfile_with_key.json";
            $fh = fopen($idfile, "w") or die("ERROR -- Unable to open the file " . $idfile);
            $data = [];

            foreach ($fileList as $file) {

                if (preg_match("/^[a-zA-Z0-9]+\.xml/", $file)) {
                    try {
                        $tempFile = tempnam(sys_get_temp_dir(), 'ftp');
                        ftp_get($this->connectionID, $tempFile, $file, FTP_BINARY);
                        $contents = file_get_contents($tempFile);

                        $encoders = [new XmlEncoder()];
                        $normalizer = [new ObjectNormalizer()];
                        $serializer = new Serializer($normalizer, $encoders);

                        /** @var WeatherPrediction $deserialized */
                        $deserialized = $serializer->deserialize($contents, 'AppBundle\WeatherPrediction', 'xml');

                        unlink($tempFile);

                        if (!empty($deserialized->getObservations())) {

                            foreach ($deserialized->getObservations() as $stations) {
                                foreach ($stations as $station) {
                                    $eachData[ $deserialized->getAmoc()['identifier'] ] = [];
                                    //$eachData['identifier'] = $deserialized->getAmoc()['identifier'];
                                    $eachData[ $deserialized->getAmoc()['identifier'] ]['bom_id'] = $station['@bom-id'];
                                    $eachData[ $deserialized->getAmoc()['identifier'] ]['stn_name'] = $station['@stn-name'];
                                    $eachData[ $deserialized->getAmoc()['identifier'] ]['description'] = $station['@description'];
                                    $eachData[ $deserialized->getAmoc()['identifier'] ]['timezone'] = $station['@tz'];
                                    $eachData[ $deserialized->getAmoc()['identifier'] ]['location'] = [
                                        'lat' => $station['@lat'],
                                        'lon' => $station['@lon']
                                    ];

                                    $data[] = $eachData;
                                }
                            }
                        }
                    } catch (\Exception $ex) {
                        continue;
                    }
                }
            }
            fwrite($fh, json_encode($data));
            fclose($fh);

            return $data;
        }

        /**
         * @param $stationId
         *
         * @return object
         */
        public function readWeather($stationId)
        {
            $tempFile = tempnam(sys_get_temp_dir(), 'ftp');
            ftp_get($this->connectionID, $tempFile, $stationId, FTP_BINARY);
            $contents = file_get_contents($tempFile);

            $encoders = [new XmlEncoder()];
            $normalizer = [new ObjectNormalizer()];

            $serializer = new Serializer($normalizer, $encoders);

            $deserialized = $serializer->deserialize($contents, 'AppBundle\WeatherPrediction', 'xml');
            unlink($tempFile);
            return $deserialized;
        }
    }