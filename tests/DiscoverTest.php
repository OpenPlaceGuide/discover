<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * @see \App\Http\Controllers\DiscoverController
 */
class DiscoverTest extends TestCase
{
    public function testEthiopia(): void
    {
        $this->get('/v1/discover?lat=12.78&lng=36.92');
        $this->seeJsonStructure([
            'baseUrl',
            'dataBaseUrl',
        ]);
        $this->seeJson(['area' => 'ethiopia']);
    }

    public function testEthiopiaWithOsmObject(): void
    {
        $this->get('/v1/discover?lat=12.78&lng=36.92&osmType=p&osmId=123456');
        $this->seeJsonStructure([
            'url',
            'dataUrl',
        ]);
        $this->seeJson(['area' => 'ethiopia']);
    }

    public function testOutside(): void
    {
        $this->get('/v1/discover?lat=1&lng=1&osmType=p&osmId=123456');
        $this->assertResponseStatus(204);
    }

}
