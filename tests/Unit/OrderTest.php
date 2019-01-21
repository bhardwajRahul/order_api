<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;

class OrderTest extends TestCase {

    /**
     * This is set up method for setting the primary need to tests
     */
    protected function setUp() {
        // ifconfig result provide this IP address(docker ip)
        $queue = [new Response(500)];
        $this->client = new Client([
            'base_uri' => 'http://172.17.0.1:8080',
            'handler' => MockHandler::createWithMiddleware($queue),
            'headers' => [
                'Accept' => 'application/json; charset=utf-8'
            ]
        ]);
    }

    /**
     * this will test create new order
     */
    public function testOrderPost() {
        $response = $this->client->request('POST', '/api/orders', [
            'json' => [
                "origin" => ["29.2321646", "77.0114754"],
                "destination" => ["28.6688444", "77.1602896"]
            ]
                ]
        );
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody(), true);
        $this->assertEquals('UNASSIGNED', $body['status']);
    }

    /**
     * this will test Order update
     */
    public function testOrderUpdate() {
        $response = $this->client->request('PATCH', '/api/orders/1', [
            'json' => [
                "status" => "TAKEN"
            ]
                ]
        );
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody(), true);
        $this->assertEquals('SUCCESS', $body['status']);
    }

    /**
     * test to fetch orders(pagination)
     */
    public function testOrders() {
        $response = $this->client->request('GET', '/api/orders?page=1&limit=10');
        $this->assertEquals(200, $response->getStatusCode());
    }

}