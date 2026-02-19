<?php

namespace App\Tests\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\InMemoryUser;

class ProductControllerTest extends WebTestCase
{
    private static ?int $id = null;

    public function testIndex(): void
    {
        $client = self::createClient();
        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $client->request('GET', '/admin/product');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testNewProduct(): void
    {
        $client = self::createClient();
        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/product/new');
        $buttonCrawlerNode = $crawler->selectButton('Save');

        $form = $buttonCrawlerNode->form();

        $form['product[category]']->select('Chaussure');
        $client->submit($form, [
            'product[title]' => 'basket',
            'product[description]' => 'voici ma description',
            'product[price]' => '20'

        ]);
        $container = self::getContainer();
        $product = $container->get(ProductRepository::class)->findOneBy(['title' => 'basket']);

        self::$id = $product->getId();

        $this->assertResponseRedirects('/admin/product');
    }

    public function testEditProduct(): void
    {
        $client = self::createClient();

        $user = new InMemoryUser('admin', 'password', ['ROLE_ADMIN']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/product/' . self::$id . '/edit');
        $buttonCrawlerNode = $crawler->selectButton('Update');

        $form = $buttonCrawlerNode->form();

        $form['product[category]']->select('Chaussure');
        $client->submit($form, [
            'product[title]' => 'basket',
            'product[description]' => 'voici ma description',
            'product[price]' => '20'

        ]);

        $this->assertResponseRedirects('/admin/product');
    }
}
