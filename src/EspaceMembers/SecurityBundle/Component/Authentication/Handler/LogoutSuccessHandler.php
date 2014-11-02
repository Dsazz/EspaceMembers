<?php

namespace EspaceMembers\SecurityBundle\Component\Authentication\Handler;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    protected $router;
    protected $security;

    public function __construct(RouterInterface $router, SecurityContext $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function onLogoutSuccess(Request $request)
    {
        $referer_url = $request->headers->get('referer');

        $response = new RedirectResponse($referer_url);

        return $response;
    }

}
