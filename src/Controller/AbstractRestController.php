<?php

namespace App\Controller;

use App\Service\AbstractRestService;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Abstract REST controller to map HTTP requests to REST service.
 *
 * @package App\Controller\Rest
 */
abstract class AbstractRestController extends AbstractController
{
    /**
     * @var AbstractRestService
     */
    protected $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * @Route("", methods={"OPTIONS", "GET"}, name="_find_all")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function findAll()
    {
        $rows = $this->service->findAll();
        return new JsonResponse($this->serialize($rows));
    }

    /**
     * @Route("/{id}", methods={"OPTIONS", "GET"}, name="_find_one")
     *
     * @param         $id
     * @return JsonResponse
     */
    public function findOne($id): JsonResponse
    {
        dd($id);
        try {
            $res = null;
            $row = $this->service->findOne($id);
            $res = $this->serialize($row);

            return new JsonResponse($res);
        } catch (EntityNotFoundException $exception) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("", methods={"OPTIONS", "POST"}, name="_create")
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->request->all();
        if ($request->getContentType() === 'json') {
            $data = json_decode($request->getContent(), true);
        }
        $row = $this->service->create($data);
        return new JsonResponse($this->serialize($row));
    }

    /**
     * @Route("/{id}", methods={"OPTIONS", "PUT"}, name="_update")
     *
     * @param         $id
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function update($id, Request $request): JsonResponse
    {
        $data = $request->request->all();
        if ($request->getContentType() === 'json') {
            $data = json_decode($request->getContent(), true);
        }

        try {
            $row = $this->service->update($id, $data, true);
            return new JsonResponse($this->serialize($row));
        } catch (EntityNotFoundException $exception) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/{id}", methods={"OPTIONS", "DELETE"}, name="_delete")
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        try {
            $this->service->delete($id);
            return new JsonResponse();
        } catch (EntityNotFoundException $exception) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/by", methods={"OPTIONS", "POST"}, name="_find_by")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function findBy(Request $request): JsonResponse
    {
        $data = $request->request->all();
        if ($request->getContentType() === 'json') {
            $data = json_decode($request->getContent(), true);
        }

        $fulltext = $data['fulltext'] ?? [];
        $criteria = $data['criteria'] ?? [];
        $orderBy = $data['orderBy'] ?? null;
        $limit = $data['limit'] ?? null;
        $offset = $data['offset'] ?? null;

        if ($fulltext) {
            $rows = $this->service->findByFulltext($fulltext, $criteria, $orderBy, $limit, $offset);
        } else {
            $rows = $this->service->findBy($criteria, $orderBy, $limit, $offset);
        }

        $response = new JsonResponse($this->serialize($rows));

        if ($offset !== null && $limit !== null) {
            if ($fulltext) {
                $total = sizeOf($this->service->findByFulltext($fulltext, $criteria, $orderBy));
            } else {
                $total = sizeOf($this->service->findBy($criteria, $orderBy));
            }

            $response->headers->set('X-Pager-Total', $total);
        }

        return $response;
    }


    /**
     * Serialize row/rows to array
     *
     * @param       $rows
     * @return array
     */
    public function serialize($rows): array
    {
        return $this->service->serialize($rows);
    }

}
