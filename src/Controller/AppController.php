<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(ProductRepository $productRepository) : Response
    {
        $data = $productRepository->findPaginate(10);

        return $this->render('home.html.twig', [
            'products' => $data['products']
        ]);
    }

    #[Route('/catalog', name: 'app_product_catalog', methods: ['GET'])]
    public function catalogProduct(Request $request, ProductRepository $productRepository) : Response
    {
        // $params = $request->query->all();

        $size = isset($request->query->all()['size']) ? $request->query->get('size') : 10;
        $page = isset($request->query->all()['page']) ? $request->query->get('page') : 10;


        //$size = $request->query->get("size");
        //$page = $request->query->get("page");

        $data = $productRepository->findPaginate($size, $page);

        return $this->render('product/catalog.html.twig', [
            'products' => $data['products'],
            'count' => $data['count'],
        ]);
    }




    #[Route('/admin/dashboard', name: 'app_admin_dashboard', methods: ['GET'])]
    public function admin(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/product/{id}', name: 'app_public_product_show', methods: ['GET'])]
    public function showProduct(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

}
