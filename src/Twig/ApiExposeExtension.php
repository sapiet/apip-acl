<?php

namespace App\Twig;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

final class ApiExposeExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenManager;

    /**
     * @var Security
     */
    private $security;

    /**
     * ApiExposeExtension constructor.
     */
    public function __construct(
        RouterInterface $router,
        JWTTokenManagerInterface $tokenManager,
        Security $security
    ) {
        $this->router = $router;
        $this->tokenManager = $tokenManager;
        $this->security = $security;
    }

    /**
     * @inheritDoc
     */
    public function getGlobals()
    {
        $user = $this->security->getUser();

        return [
            'jwt' => null !== $user ? $this->tokenManager->create($user) : null,
            'entrypoint' => $this->router->generate('api_entrypoint'),
        ];
    }
}
