<?php

namespace algn\test;

use PHPUnit\Framework\TestCase;

require __DIR__ . "../../../config/keys.php";


class DarkSkyTest extends TestCase
{
    public function testToday()
    {
        global $darkSkyKey;
        $darksky = new \algn\DarkSky\DarkSky($darkSkyKey);

        [$lat, $lng] = ["56.1806550", "15.5907000"];

        $result = $darksky->today($lat, $lng);

        $this->assertIsObject($result);
    }
    public function testWeek()
    {
        global $darkSkyKey;
        $darksky = new \algn\DarkSky\DarkSky($darkSkyKey);

        [$lat, $lng] = ["56.1806550", "15.5907000"];

        $result = $darksky->week($lat, $lng);

        $this->assertIsObject($result);
    }
    public function test30Days()
    {
        global $darkSkyKey;
        $darksky = new \algn\DarkSky\DarkSky($darkSkyKey);

        [$lat, $lng] = ["56.1806550", "15.5907000"];

        $result = $darksky->past30Days($lat, $lng);

        $this->assertIsArray(json_decode($result));
    }
}
