<?php

namespace algn\DarkSky;

class DarkSky
{
    public function __construct($key)
    {
        $this->key = $key;
        $this->url = "https://api.darksky.net/forecast/";
        $this->exclude = "?exclude=minutely,hourly,alerts,flags,daily&units=si";
    }

    public function week($lat, $lng): object
    {
        $response = file_get_contents($this->url . $this->key . "/" . strval($lat) . "," . strval($lng) . "?exclude=minutely,hourly,alerts,flags,currently");

        return json_decode($response);
    }

    public function today($lat, $lng, $time = null)
    {
        $today = date("Y-m-d") . "T" . date("H:i:s");

        if ($time) {
            $today = $time;
        }


        $excludes = $this->exclude;
        $response = file_get_contents($this->url . $this->key . "/" . strval($lat) . "," . strval($lng) . "," . $today . $excludes);

        return json_decode($response);
    }

    public function past30Days($lat, $lng)
    {
        $chAll = [];
        $mhs = curl_multi_init();

        $position = strval($lat) . "," . strval($lng);

        for ($i = 0; $i < 30; $i++) {
            $offset = $i + 1;
            $ourTime = date("Y-m-d", strtotime("-$offset days")) . "T" . date("H:i:s");

            $exclude = $this->exclude;
            $key = $this->key;

            $url = "https://api.darksky.net/forecast/$key/$position,$ourTime$exclude";

            $chs = curl_init($url);
            curl_setopt_array($chs, [CURLOPT_RETURNTRANSFER => true]);
            curl_multi_add_handle($mhs, $chs);

            $chAll[] = $chs;
        }

        $running = null;

        do {
            curl_multi_exec($mhs, $running);
        } while ($running);

        foreach ($chAll as $chs) {
            curl_multi_remove_handle($mhs, $chs);
        }

        curl_multi_close($mhs);

        $response = [];

        foreach ($chAll as $chs) {
            $data = curl_multi_getcontent($chs);
            $response[] = json_decode($data, true);
        }

        return json_encode($response);
    }
}
