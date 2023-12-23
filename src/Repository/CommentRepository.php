<?php

namespace App\Repository;

use App\Entity\Comment;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findOne(int $id): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.publication = :publicationId')
            ->setParameter('publicationId', $id)
            ->orderBy('c.createdAt')
            ->getQuery()
            ->getResult();
    }
    
    public function findLast(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
