<?php

namespace App\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class CustomAuthentication extends AbstractAuthenticator
{

    /**
     * barer pertama, mengecek request apakah ada authorization header
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $authHeader = $request->headers->get('Authorization');

        if (!str_starts_with($authHeader, 'Bearer ')) {
            throw new AuthenticationException('Missing bearer token');
        }

        // userBadge seperti user detail service di spring
        // mengambil data user dari sumber berdasarkan indetifier (email, username, etc)
        // self validating passport??? kemungkinan authenticator
        // mengecek password dan data terkait
        return new SelfValidatingPassport(new UserBadge("admin"));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return  new JsonResponse(
            [
                'status'=>false,
                'from'=>'CustomAuthentication',
                'error'=>["no authorization header"],
                'meta'=>['url'=>$request->getUri()]
            ]);
    }
}
