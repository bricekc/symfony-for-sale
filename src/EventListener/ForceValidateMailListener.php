<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ForceValidateMailListener
{
    private TokenStorageInterface $tokenStorage;
    private UrlGeneratorInterface $router;

    public function __construct(TokenStorageInterface $tokenStorage, UrlGeneratorInterface $router)
    {
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest() || null === $this->tokenStorage->getToken()) {
            return;
        }

        $route = $event->getRequest()->attributes->get('_route');

        $routesToExclude = ['app_validate_email', 'app_logout', 'app_verify_email'];
        if (in_array($route, $routesToExclude)) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        if ($user && !$user->isVerified()) {
            $url = $this->router->generate('app_validate_email', ['id' => $user->getId()]);
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }
    }
}
