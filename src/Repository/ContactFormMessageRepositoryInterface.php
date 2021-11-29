<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin\Repository;

use Doctrine\ORM\QueryBuilder;

interface ContactFormMessageRepositoryInterface
{
    public function findAllByCustomerId(int $customerId): QueryBuilder;
}
