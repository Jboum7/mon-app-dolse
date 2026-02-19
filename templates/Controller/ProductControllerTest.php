<?php

namespace App\Tests\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\InMemoryUser;

class ProductControllerTest extends WebTestCase
{
    private static ?int $id = null;

    public function testNewProduct(): void
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
        $client->submit($form);

        $container = self::getContainer();
        $product = $container->get(ProductRepository::class)->findOneby(['title' => 'basket']);
        self::$id= $product->getId();

        $this->assertResponseRedirects('/admin/product');
    }

    public function testEditProduct(): void
    {
        $client = static::createClient();

        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/product/' . self::$id . '/edit');

        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();
        $form['product[category]']->select('Chaussure');

        $client->submit($form, [
            'product[title]' => 'basket',
            'product[description]' => 'voici ma description',
            'product[price]' => 20,
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/admin/product');
    }
}


