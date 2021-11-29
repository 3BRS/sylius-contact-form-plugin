<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class ContactFormMessageRepository extends EntityRepository implements ContactFormMessageRepositoryInterface
{
    public function findAllByCustomerId(int $customerId): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->join('o.customer', 'customer')
            ->andWhere('customer.id = :customerId')
            ->setParameter('customerId', $customerId);
    }
}
