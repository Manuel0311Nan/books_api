<?php

namespace App\Controller;

use App\Entity\Page;
use App\Repository\PageRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private BookRepository $bookRepository;
    private PageRepository $pageRepository;
    public function __construct(
        EntityManagerInterface $entityManager,
        BookRepository $bookRepository,
        PageRepository $pageRepository
    ) {
        $this->entityManager = $entityManager;
        $this->bookRepository = $bookRepository;
        $this->pageRepository = $pageRepository;
    }
    #[Route('/api/book/{bookId}/pages', name: 'showPages',methods:['GET'])]
    public function index(int $bookId): Response
    {
        $book = $this->bookRepository->find($bookId);

        if(!$book){
            return $this ->json(['error' => 'Book not found'], 404);
        }

        $pages = $this->pageRepository->findBy(['bookId' => $book]);

        return $this->json($pages, 200);
    }
    #[Route('/api/book/{bookId}/pages', name: 'createPage',methods:['POST'])]
    public function createPage(int $bookId, Request $request): JsonResponse
    {
        $book = $this->bookRepository->find($bookId);

        if (!$book){
            return $this->json(['error' => 'Book not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['content']) || !isset($data['pageNumber'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $page = new Page();
        $page->setContent($data['content']);
        $page->setPageNumber($data['pageNumber']);
        $page->setNextOptions($data['nextOptions'] ?? []);

        $this->entityManager->persist($page);
        $this->entityManager->flush();

        return $this->json($page,201);
    }

    #[Route('/api/books/{bookId}/pages/{pageNumber}',name:'showPage',methods:['GET'])]
    public function showPage(int $bookId, int $pageNumber): JsonResponse
    {
        $book = $this->bookRepository->find($bookId);

        if(!$book){
            return $this->json(['error' => 'Book not found'], 404);
        }

        $page = $this->pageRepository->find($pageNumber);

        if (!$page || $page->getBook()->getId() !== $bookId) {
            return $this->json(['error' => 'Page not found'], 404);
        }

        return $this->json($page,200);
    }
}
