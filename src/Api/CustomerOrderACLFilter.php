<?php

namespace App\Api;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Customer;
use App\Entity\Order;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

final class CustomerOrderACLFilter implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * CustomerOrderACLFilter constructor.
     *
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @inheritDoc
     */
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if (Order::class !== $resourceClass) {
            return;
        }
        $user = $this->security->getUser();
        if (!$user instanceof Customer) {
            return;
        }
        $this->restrict($queryBuilder, $user);
    }

    /**
     * @inheritDoc
     */
    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        if (Order::class !== $resourceClass) {
            return;
        }
        $user = $this->security->getUser();
        if (!$user instanceof Customer) {
            return;
        }
        $this->restrict($queryBuilder, $user);
    }

    /**
     * Restrict query to a specific customer.
     */
    private function restrict(QueryBuilder $queryBuilder, Customer $customer)
    {
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.customer = :customer', $rootAlias));
        $queryBuilder->setParameter('customer', $customer);
    }
}
