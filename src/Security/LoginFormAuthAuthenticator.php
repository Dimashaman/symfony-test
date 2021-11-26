<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Cookie\JWTCookieProvider;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LoginFormAuthAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;
    private JWTTokenManagerInterface $jwtTokenManager;
    private JWTCookieProvider $jwtCookieProvider;

    public function __construct(UrlGeneratorInterface $urlGenerator, JWTCookieProvider $jwtCookieProvider, JWTTokenManagerInterface $jwtTokenManager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->jwtTokenManager = $jwtTokenManager;
        $this->jwtCookieProvider = $jwtCookieProvider;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?RedirectResponse
    {
        
        $user = $token->getUser();
        $apiToken = $this->jwtTokenManager->create($user);
        $this->jwtCookieProvider->createCookie($apiToken, "BEARER", 86400);

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
    }
    
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
