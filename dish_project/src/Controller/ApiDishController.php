<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Repository\DishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ApiDishController extends AbstractController
{
    #[Route('/api/dish', name: 'post_dish', methods: ['POST'])]
    public function createDish(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['price'])) {
            return new JsonResponse([
                'error' => 'Missing name or price'
            ], Response::HTTP_BAD_REQUEST
            );
        }

        if ( !is_string($data['name']) || !is_numeric($data['price'])) {
            return new JsonResponse([
                'error' => 'Invalid data type'
            ], Response::HTTP_BAD_REQUEST);
        }

        $dish = new Dish();
        $dish->setName((string)$data['name']);
        $dish->setPrice((float)$data['price']);

        try {
            $em->persist($dish);
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'Failed to create dish'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }


        return new JsonResponse([
            'id' => $dish->getId(),
            'name' => $dish->getName(),
            'price' => $dish->getPrice()
        ], Response::HTTP_CREATED);
    }

    #[Route('/api/dish/{id}', name: 'put_dish', methods:['PUT'])]
    public function updateDish(int $id, Request $request, EntityManagerInterface $em, DishRepository $dishRepository): JsonResponse {

        $dish = $dishRepository->find($id);
        if (!$dish) {
            return new JsonResponse(
                ['error'=> 'Dish not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($request->getContent(), true);
        if (json_last_error() !==JSON_ERROR_NONE) {
            return new JsonResponse(
                ['error'=> 'Innvalid JSON'],
                Response::HTTP_BAD_REQUEST
            );
        }

       if (isset($data['name'])) {
           if (!is_string($data['name'])) {
               return new JsonResponse(
                   ['error'=> 'Must be a string type'],
                   Response::HTTP_BAD_REQUEST
               );
           }
           $dish->setName(trim($data['name']));
       }


        if (isset($data['price'])) {
            if (!is_numeric($data['price'])) {
                return new JsonResponse(
                    ['error'=> 'Must be a numeric type'],
                    Response::HTTP_BAD_REQUEST
                );
            }
            $dish->setPrice((float) $data['price']);
        }

        try{
            $em->flush();

            return new JsonResponse([
                'id' => $dish->getId(),
                'name' => $dish->getName(),
                'price' => $dish->getPrice()
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'Failed to update dish'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        };
    }
    #[Route('/api/dish/{id}', name: 'get_dish_by_id', methods:['GET'])]
    public function getDishById(int $id, EntityManagerInterface $em, DishRepository $dishRepository): JsonResponse {
        $dish = $dishRepository->find($id);
        if (!$dish) {
            return new JsonResponse(
                ['error'=> 'Dish not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json([
            'id' => $dish->getId(),
            'name' => $dish->getName(),
            'price' => $dish->getPrice()
        ]);
    }
    #[Route('/api/dishes', name:'get_all_dishes', methods:['GET'])]
public function getAllDishes(DishRepository $dishRepository): JsonResponse {
        $dishes = $dishRepository->findAll();

        $dishesArray = array_map(function($dish) {
            return [
                'id' => $dish->getId(),
                'name' => $dish->getName(),
                'price' => $dish->getPrice()
            ];
        }, $dishes);
        return $this->json($dishesArray);
    }
    #[Route('/api/dish/{id}', name: 'delete_dish', methods:['DELETE'])]
public function deleteDish(int $id, EntityManagerInterface $em, DishRepository $dishRepository): JsonResponse {
    $dish = $dishRepository->find($id);
    if (!$dish) {
        return new JsonResponse(
            ['error'=> 'Dish not found'],
            Response::HTTP_NOT_FOUND
        );
    }

    try {
        $em->remove($dish);
        $em->flush();
    } catch (\Exception $e) {
        return new JsonResponse(
            ['error' => 'Failed to delete dish'],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    return new JsonResponse([
        'message' => 'Dish deleted successfully'
    ]);
}
}
