<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\UserNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class addProductCart extends AbstractController
{
   /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ProductRepository
     */
    private $ProductRepository;

    private $tokenStorage;



    public function __construct(
        EntityManagerInterface $em,
        ProductRepository $pr,
        TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->ProductRepository = $pr;
        $this->tokenStorage = $tokenStorage;
       
    }

    public function __invoke(Request $req)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if(is_null($user)){
            return new JsonResponse(null , Response::HTTP_NOT_FOUND);
        }

        $body = json_decode($req->getContent(),true);
        $products = $body['products'];
        if(is_null($products)){
            throw new ParameterNotFoundException("products");
        }

        foreach ($products as $item) {
          $cart = new Cart();
         
          $product = $this->ProductRepository->findOneBy(["id" => $item["id"]]);

          $cart->setProduct($product);
          $cart->setAddedDate(new \DateTime());
          $cart->setBuyer($user);
          $cart->setQuantity($item["quantity"]);

          $totalPrice = $product->getProductPrice() * $item["quantity"];
          $cart -> setTotalPrice($totalPrice);

          $this->em->persist($cart);
        }

        $this->em->flush();

        return new JsonResponse(null , Response::HTTP_CREATED);

    }


}