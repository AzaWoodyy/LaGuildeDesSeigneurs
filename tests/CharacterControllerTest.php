<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    public function testDisplay(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/character/display');
        $response = $client->getResponse();
        
        $this->assertResponseStatusCodeSame(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }
}
