<?php

namespace App\Service;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentService extends AbstractRestService
{
    /**
     * @param CommentRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        CommentRepository $repository,
        EntityManagerInterface $entityManager,
    )
    {
        parent::__construct($repository, $entityManager);
    }
}
