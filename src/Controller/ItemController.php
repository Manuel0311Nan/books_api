<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ItemController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ItemRepository $itemRepository;

    public function __construct(EntityManagerInterface $entityManager, ItemRepository $itemRepository)
    {
        $this->entityManager = $entityManager;
        $this->itemRepository = $itemRepository;
    }
    #[Route('/api/item', name: 'showItems',methods:['GET'])]
    public function index(): Response
    {
         $items = $this->itemRepository->findAll();

        return $this->json($items, 200);
    }

    #[Route('/api/item/{id}', name: 'showID',methods:['GET'])]
    public function show(int $id): JsonResponse
    {
        $item = $this->itemRepository->find($id);

        if(!$item){
            return $this -> json(['error' => 'Item not found'], 404);
        }

        return $this->json($item, 200);
    }
    #[Route('/api/item', name: 'create',methods:['POST'])]
    public function create_item(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!isset($data['type']) || !isset($data['name']))
        {
            return $this->json(['error' => 'Invalid Data'], 400);
        }

        $item = new Item();
        $item->setType($data['type']);
        $item->setName($data['name']);
        $item->setPower($data['power']);
        $item->SetImage($data['image']);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        return $this->json(['success' =>'Item Creado'], 201);
    }

    #[Route('/api/item/{id}',name:'update_item',methods:['PUT'])]
    public function update_item(int $id, Request $request): JsonResponse
    {
        $item = $this -> itemRepository->find($id);

        if(!$item){
            return $this -> json(['error' => 'Item not found'], 404);
        }

        $data = json_decode($request -> getContent(), true);

        if(isset($data['title'])){
            $item->setTitle($data['title']);
        }
        if(isset($data['description'])){
            $item->setDescription($data['description']);
        }

        $this->entityManager->flush();

        return $this->json(['success' =>'Item actualizado'], 201);
    }
    #[Route('/api/item/{id}', name:'delete_item', methods:['DELETE'])]
    public function delete_item(int $id):JsonResponse
    {
        $item = $this->itemRepository->find($id);

        if(!$item){
            return $this->json(['error' => 'Item not found'], 404);
        }

        $this->entityManager->remove($item);
        $this->entityManager->flush();

        return $this->json(['success' => 'Item eliminado'], 200);
    }
}
