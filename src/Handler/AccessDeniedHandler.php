<?php

namespace App\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {

        return  new JsonResponse([
            'status'=>false,
            'from'=>'AccessDeniedHandler',
            'message'=>$accessDeniedException->getMessage(),
            'meta'=>['url'=>$request->getUri()]],$accessDeniedException->getCode());
    }
}
