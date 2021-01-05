<?php

namespace App\Serializer;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AdminRoleContextBuilder implements SerializerContextBuilderInterface
{

    private $decorated;
    private $authorizedChecker;

    public function __construct(  
        SerializerContextBuilderInterface $decorated,
        AuthorizationCheckerInterface $authorizedChecker)
    {
        $this -> decorated = $decorated;
        $this -> authorizedChecker = $authorizedChecker;
    }


    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request , $normalization , $extractedAttributes);
        $resourceClass = $context['resource_class'] ??  null;

        if(
            $resourceClass === User::class && 
            isset($context['groups']) &&
            $this->authorizedChecker->isGranted("ROLE_ADMIN")
            ) {

                $context['groups'][] = $normalization ? "admin:read" : "admin:write";
            }

            return $context;
    }
}