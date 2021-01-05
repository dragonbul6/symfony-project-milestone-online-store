<?php

namespace App\Controller;

use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class checkoutCart
{

    /** @var EntityManagerInterface */
    private $em;

    /** @var CartRepository */
    private $CartRepository;


    public function __construct(EntityManagerInterface $em , CartRepository $cartRepository)
    {
        $this->em = $em;
        $this->CartRepository = $cartRepository;
    }

    public function __invoke(Request $req)
    {
        $body = json_decode($req->getContent(),true);

        if(is_null($body)){
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $carts = $body["carts"];
        if(count($carts) <= 0){
            return new JsonResponse(["msg" => "You need to sent request with carts id"], Response::HTTP_BAD_REQUEST);
        }

        foreach($carts as $cart){
            $target = $this->CartRepository->findOneBy(["id" => $cart]);

            $this->em->remove($target);
            
        }
        $this->em->flush();

        return new JsonResponse(null,Response::HTTP_GONE);
        

    }
}