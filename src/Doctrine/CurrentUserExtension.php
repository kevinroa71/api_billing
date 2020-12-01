<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Billing;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

final class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    protected $security;

    /**
     * Construction function
     *
     * @param Security $security Service Security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Conditions to apply on collections
     *
     * @param QueryBuilder                $queryBuilder       Query Builder
     * @param QueryNameGeneratorInterface $queryNameGenerator Name generator query
     * @param string                      $resourceClass      Class
     * @param string                      $operationName      Operation
     *
     * @return void
     */
    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ): void {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    /**
     * Conditions to apply on items
     *
     * @param QueryBuilder                $queryBuilder       Query Builder
     * @param QueryNameGeneratorInterface $queryNameGenerator Name generator query
     * @param string                      $resourceClass      Class
     * @param array                       $identifiers        Items ids
     * @param string                      $operationName      Operation
     * @param array                       $context            Settings
     *
     * @return void
     */
    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        string $operationName = null,
        array $context = []
    ): void {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    /**
     * Conditions to apply on items and collections
     *
     * @param QueryBuilder $queryBuilder  Query Builder
     * @param string       $resourceClass Class
     *
     * @return void
     */
    protected function addWhere(
        QueryBuilder $queryBuilder,
        string $resourceClass
    ): void {
        $user = $this->security->getUser();

        if (Billing::class !== $resourceClass || !$user instanceof User) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.user = :current_user', $rootAlias));
        $queryBuilder->setParameter('current_user', $user->getId());
    }
}