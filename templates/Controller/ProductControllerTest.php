<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\InMemoryUser;

class ProductControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();

        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/product/new');

        $buttonCrawlerNode = $crawler->selectButton('Save');

        $form = $buttonCrawlerNode->form();
        $form['product[category]']->select('Chaussure');

        $client->submit($form, [
            'product[title]' => 'basket',
            'product[description]' => 'voici ma description',
            'product[price]' => 20,
        ]);

        $this->assertResponseRedirects('/admin/product');
    }
}

