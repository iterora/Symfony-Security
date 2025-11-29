<?php

namespace App\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?JsonResponse
    {
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Login berhasil',
            'user' => $token->getUser()->getUserIdentifier(),
            'roles' => $token->getRoleNames(),
        ], 200);
    }
}
