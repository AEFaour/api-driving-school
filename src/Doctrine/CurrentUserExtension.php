<?php


namespace App\Doctrine;


use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Invoice;
use App\Entity\Learner;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    /**
     * @var Security
     */

    private $security;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $auth;

    /**
     * CurrentUserExtension constructor.
     * @param Security $security
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(Security $security, AuthorizationCheckerInterface $checker)
    {
        $this->security = $security;
        $this->auth = $checker;
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass){
        $user = $this->security->getUser();

        if($resourceClass === Learner::class || $resourceClass === Invoice::class && !$this->auth->isGranted('ROLE_ADMIN')
            && $user instanceof User){
            $rootAlias = $queryBuilder->getRootAliases()[0];

            if($resourceClass === Learner::class){
                $queryBuilder->andWhere("$rootAlias.user = :user");
            }else if ($resourceClass === Invoice::class){
                $queryBuilder->join("$rootAlias.learner", "l")
                    ->andWhere("l.user = :user");
            }

            $queryBuilder->setParameter("user", $user);
        }
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }


    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

}