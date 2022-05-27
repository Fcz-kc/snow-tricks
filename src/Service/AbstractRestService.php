<?php

namespace App\Service;

use ArrayObject;
use Countable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Traversable;

/**
 * Abstract service used to manage CRUD for simple Doctrine entities.
 *
 * @package App\Service\Rest
 */
 abstract class AbstractRestService
{
    /**
     * @var ServiceEntityRepository
     */
    protected $repository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var string
     */
    protected $className;

    /**
     * AbstractRestService constructor.
     *
     * @param ServiceEntityRepository $repository
     * @param EntityManagerInterface $entityManager
     * @param Serializer $serializer
     */
    public function __construct(ServiceEntityRepository $repository,
                                EntityManagerInterface $entityManager
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->serializer = new Serializer([new ObjectNormalizer()]);
        $this->className = $this->repository->getClassName();
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function findBy(
        array $criteria,
        array $orderBy = null,
        int $limit = null,
        int $offset = null): array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     * @return object
     */
    public function findOneBy(array $criteria): ?object
    {
        return $this->repository->findOneBy($criteria);
    }

     /**
      * @param array $fulltext
      * @param array|null $criteria
      * @param array|null $orderBy
      * @param int|null $limit
      * @param int|null $offset
      *
      * @return array
      */
     public function findByFulltext(
         array $fulltext,
         array $criteria = null,
         array $orderBy = null,
         int $limit = null,
         int $offset = null
     ): array {
         $queryBuilder = $this->repository->createQueryBuilder('o');
         $queryBuilder->select('o')
             ->setMaxResults($limit)
             ->setFirstResult($offset);

         foreach ($fulltext as $property => $value) {
             $queryBuilder->orWhere('o.' . $property . ' LIKE :' . $property);
             $queryBuilder->setParameter($property, '%' . $value . '%');
         }

         if ($criteria) {
             foreach ($criteria as $property => $value) {
                 if (is_array($value)) {
                     $queryBuilder->andWhere('o.' . $property . ' IN (:' . $property . ')');
                 } else {
                     $queryBuilder->andWhere('o.' . $property . ' = :' . $property);
                 }
                 $queryBuilder->setParameter($property, $value);
             }
         }

         if ($orderBy) {
             foreach ($orderBy as $property => $order) {
                 $queryBuilder->addOrderBy('o.' . $property, $order);
             }
         }

         return $queryBuilder->getQuery()->getResult();
     }


     /**
     * @param int $id
     *
     * @return object
     * @throws EntityNotFoundException
     */
    public function findOne(int $id): object
    {
        $row = $this->repository->find($id);
        if (!$row) {
            throw new EntityNotFoundException();
        }
        return $row;
    }

    /**
     * @param $data
     * @return object
     * @throws ExceptionInterface
     */
    public function create($data): object
    {
        $row = $this->serializer->denormalize($data, 'json');

        $this->entityManager->persist($row);
        $this->entityManager->flush();

        return $row;
    }

     /**
      * @param int $id
      * @param $data
      * @param bool $flush
      * @return object
      * @throws EntityNotFoundException
      * @throws ExceptionInterface
      */
     public function update(int $id, $data, bool $flush = true): object
     {
        $row = $this->findOne($id);
        $this->denormalize($row, $data);

        if ($flush) {
            $this->entityManager->flush();
        }
        return $row;
    }

     /**
      * @param int $id
      * @return array
      * @throws EntityNotFoundException
      */
     public function delete(int $id): array
     {
        $row = $this->findOne($id);
        $this->entityManager->remove($row);
        $this->entityManager->flush();
        return [];
    }

    /**
     * @param $rows
     * @return array|mixed
     */
    public function serialize($rows)
    {
        $json = [];
        $isCollection = is_array($rows);
        if (!$isCollection) {
            $rows = [$rows];
        }
        foreach ($rows as $row) {
            $interface = method_exists($row, 'jsonSerialize') ? $row->jsonSerialize() : [];
            $json[] = $interface;
        }
        return $isCollection ? $json : $json[0];
    }

     /**
      * @param $row
      * @param array $attributes
      *
      * @return array|ArrayObject|bool|Countable|float|int|string|Traversable|null
      * @throws ExceptionInterface
      */
     public function normalize(
         $row,
         array $attributes = []
     ) {
         return $this->serializer->normalize($row, null, [
             ObjectNormalizer::ATTRIBUTES => $attributes,
             ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($reference) {
                 return $reference->getId();
             }
         ]);
     }

    /**
     * @param $data
     * @param null $row
     *
     * @return object
     * @throws ExceptionInterface
     */
    protected function denormalize($data, $row = null): object
    {
        if (!$row) {
            $row = $this->serializer->denormalize($data, $this->className, null, [
                ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true
            ]);
        } else {
            $this->serializer->denormalize($data, $this->className, null, [
                ObjectNormalizer::OBJECT_TO_POPULATE => $row,
                ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true
            ]);
        }
        return $row;
    }
}
