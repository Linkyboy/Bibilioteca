<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomePageController()
    {
        $client = static::createClient();
        $client->request('GET', '/home');
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }
}