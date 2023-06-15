<?php 

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BeerDispenserControllerTest extends WebTestCase
{
    public function testCreateBeerDispenser(): void
    {
        $client = static::createClient();
        $client->request(
            "POST",
            '/beerDispensers',
            ['flow_volume' => 0.2],
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCreateBeerDispenserWithInvalidFlowVolume(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/beerDispensers',
            ['flow_volume' => 0, 'price' => 2]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testListBeerDispensers(): void
    {
        $client = static::createClient();
        $client->request('GET', '/beerDispensers');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetBeerDispenser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/beerDispensers/1');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetBeerDispenserNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/beerDispensers/999');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testUpdateBeerDispenser(): void
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            '/beerDispensers/1',
            ['flow_volume' => 8.5, "price"=> 4.99]
        );

        $this->assertEquals(Response::HTTP_ACCEPTED, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }


    public function testUpdateBeerDispenserNotFound(): void
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            '/beerDispensers/999',
            ['flow_volume'=> 8.5, 'price'=> 4.99]
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testOpenBeerTap(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/beerDispensers/1/tap');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testOpenBeerTapAlreadyOpen(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/beerDispensers/1/tap');

        $this->assertEquals(Response::HTTP_CONFLICT, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCloseBeerTap(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/beerDispensers/1/tap');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCloseBeerTapAlreadyClosed(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/beerDispensers/1/tap');

        $this->assertEquals(Response::HTTP_CONFLICT, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCloseBeerTapNotFound(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/beerDispensers/999/tap');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }
}
