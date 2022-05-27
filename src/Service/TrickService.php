<?php

namespace App\Service;

use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;

class TrickService extends AbstractRestService
{
    /**
     * @param TrickRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        TrickRepository $repository,
        EntityManagerInterface $entityManager,
    )
    {
        parent::__construct($repository, $entityManager);
    }
}
