<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    private const ENTITIES_PER_PAGE = 15;

    /**
     * @Route("/customers", name="app_customer_index", methods={"GET"})
     * @param CustomerRepository<Customer> $customerRepository
     */
    public function index(Request $request, CustomerRepository $customerRepository, PaginatorInterface $paginator, NormalizerInterface $normalizer): JsonResponse
    {
        $queryBuilder = $customerRepository->customersQueryBuilder();
        $page = $request->query->getInt('page', 1);
        $page = $page > 0 ? $page : 1;
        $pagination = $paginator->paginate(
            $queryBuilder,
            $page,
            self::ENTITIES_PER_PAGE
        );

        $customersResource = $normalizer->normalize($pagination->getItems(), null, ['groups' => 'index']);
        
        return new JsonResponse(['items' => $customersResource, 'page' => $page, 'total' => $pagination->getTotalItemCount()]);
    }
    /**
     * @Route("/customers/{id}", methods={"GET"})
     * @param CustomerRepository<Customer> $customerRepository
     */
    public function show(string $id, CustomerRepository $customerRepository, NormalizerInterface $normalizer): JsonResponse
    {
        $customer = $customerRepository->find($id);
        $customerResource = $normalizer->normalize($customer, null, ['groups' => 'show']);

        return new JsonResponse($customerResource);
    }
}
