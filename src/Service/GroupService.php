<?php

namespace App\Service;

use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class GroupService extends AbstractRestService
{
    /**
     * @param GroupRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        GroupRepository $repository,
        EntityManagerInterface $entityManager,
    )
    {
        parent::__construct($repository, $entityManager);
    }
}
