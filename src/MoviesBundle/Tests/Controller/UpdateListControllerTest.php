<?php

namespace MoviesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateListControllerTest extends WebTestCase
{
    public function testSelectfolder()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/selectFolder');
    }

}
