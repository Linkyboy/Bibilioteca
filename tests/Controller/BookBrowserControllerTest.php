<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookBrowserControllerTest extends WebTestCase
{
    public function testHomePageController()
    {
        $client = static::createClient();
        $client->request('GET', '/bookbrowser/all');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function testBookBrowseByArtistController()
    {
        $client = static::createClient();
        $client->request('GET', '/bookbrowser/artists');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}