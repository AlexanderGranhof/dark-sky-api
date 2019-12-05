<?php

namespace Anax\DarkSky;

class DarkSky {
    public function __construct() {
        require __DIR__ . "../../../config/keys.php";

        $this->key = $DARK_SKY_KEY;
        $this->url = "https://api.darksky.net/forecast/";
        $this->exclude = "?exclude=minutely,hourly,alerts,flags,daily&units=si";
    }

    public function week($lat, $lng): object {
        $response = file_get_contents($this->url . $this->key . "/" . strval($lat) . "," . strval($lng) . "?exclude=minutely,hourly,alerts,flags,currently");

        return json_decode($response);
    }

    public function today($lat, $lng, $time = null) {
        $today = date("Y-m-d") . "T" . date("H:i:s");

        if ($time) {
            $today = $time;
        }


        $excludes = $this->exclude;
        $response = file_get_contents($this->url . $this->key . "/" . strval($lat) . "," . strval($lng) . "," . $today . $excludes);

        return json_decode($response);
    }

    public function past_30_days($lat, $lng) {
        $chAll = [];
        $mh = curl_multi_init();

        $position = strval($lat) . "," . strval($lng);

        for ($i = 0; $i < 30; $i++) {
            $offset = $i + 1;
            $ourTime = date("Y-m-d", strtotime("-$offset days")) . "T" . date("H:i:s");

            $exclude = $this->exclude;
            $key = $this->key;

            $url = "https://api.darksky.net/forecast/$key/$position,$ourTime$exclude";

            $ch = curl_init($url);
            curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true]);
            curl_multi_add_handle($mh, $ch);

            $chAll[] = $ch;
        }

        $running = null;

        do {
            curl_multi_exec($mh, $running);
        } while($running);

        foreach($chAll as $ch) {
            curl_multi_remove_handle($mh, $ch);
        }

        curl_multi_close($mh);

        $response = [];

        foreach($chAll as $ch) {
            $data = curl_multi_getcontent($ch);
            $response[] = json_decode($data, true);
        }

        return json_encode($response);
    }
}