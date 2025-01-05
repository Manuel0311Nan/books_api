<?php

namespace App\Controller;
use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private BookRepository $bookRepository;

    public function __construct(EntityManagerInterface $entityManager, BookRepository $bookRepository)
    {
        $this->entityManager = $entityManager;
        $this->bookRepository = $bookRepository;
    }
    #[Route('/api/book', name: 'showBooks',methods:['GET'])]
    public function index(): Response
    {
        $books = $this->bookRepository->findAll();

        return $this->json($books, 200);
    }
    #[Route('/api/book/{id}', name: 'showID',methods:['GET'])]
    public function show(int $id): JsonResponse
    {
        $book = $this->bookRepository->find($id);

        if(!$book){
            return $this -> json(['error' => 'Book not found'], 404);
        }

        return $this->json($book, 200);
    }
    #[Route('/api/book', name: 'create',methods:['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!isset($data['title']) || !isset($data['desciption']))
        {
            return $this->json(['error' => 'Invalid Data'], 400);
        }

        $book = new Book();
        $book->setTitle($data['title']);
        $book->setDescription($data['description']);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $this->json(['success' =>'Libro creado'], 201);
    }
    #[Route('/api/book/{id}',name:'update_book',methods:['PUT'])]
    public function update_book(int $id, Request $request): JsonResponse
    {
        $book = $this -> bookRepository->find($id);

        if(!$book){
            return $this -> json(['error' => 'Book not found'], 404);
        }

        $data = json_decode($request -> getContent(), true);

        if(isset($data['title'])){
            $book->setTitle($data['title']);
        }
        if(isset($data['description'])){
            $book->setDescription($data['description']);
        }

        $this->entityManager->flush();

        return $this->json(['success' =>'Libro actualizado'], 201);
    }
    #[Route('/api/book/{id}', name:'delete_book', methods:['DELETE'])]
    public function delete_BookA(int $id):JsonResponse
    {
        $book = $this->bookRepository->find($id);

        if(!$book){
            return $this->json(['error' => 'Book not found'], 404);
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();

        return $this->json(['success' => 'Libro eliminado'], 200);
    }
}
