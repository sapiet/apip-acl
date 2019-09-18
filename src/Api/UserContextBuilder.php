<?php

namespace App\Api;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use App\Entity\Customer;
use App\Entity\Employee;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserContextBuilder implements SerializerContextBuilderInterface
{
    private const PREFIXES = [
        Customer::class => 'customer',
        Employee::class => 'employee',
    ];

    /**
     * @var SerializerContextBuilderInterface
     */
    private $decorated;

    /**
     * @var Security
     */
    private $security;

    /**
     * UserContextBuilder constructor.
     */
    public function __construct(SerializerContextBuilderInterface $decorated, Security $security)
    {
        $this->decorated = $decorated;
        $this->security = $security;
    }

    /**
     * @inheritDoc
     */
    public function createFromRequest(Request $request, bool $normalization, array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $prefix = $this->getPrefix($this->security->getUser());

        if (null === $prefix) {
            return $context;
        }

        $context['groups'] = $context['groups'] ?? [];
        foreach ($context['groups'] as $group) {
            $context['groups'][] = sprintf('%s:%s', $prefix, $group);
        }

        return $context;
    }


    private function getPrefix(?UserInterface $user): ?string
    {
        if (null === $user) {
            return null;
        }

        return self::PREFIXES[\get_class($user)] ?? null;
    }
}
