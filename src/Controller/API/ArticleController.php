<?php

namespace App\Controller\API;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("api/")]
class ArticleController extends AbstractController
{
    // Get all articles
    #[Route("articles", name: "api_article_readAll", methods: "GET")]
    public function readAll(ArticleRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), 200, [], ["groups" => "article:read"]);
    }

    // Get ONE article from its ID
    #[Route("article/{id}", name: "api_article_readOne", methods: "GET")]
    public function readOne(ArticleRepository $repository, int $id): JsonResponse
    {
        return $this->json($repository->find($id), 200, [], ["groups" => "article:read"]);
    }

    // Create a new article
    #[Route("article", name: "api_article_create", methods: "POST")]
    public function create(Request $request, ArticleRepository $repository, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $jsonReceived = $request->getContent();


        $article = $serializer->deserialize($jsonReceived, Article::class, "json");
        $article->setCreatedAt(new \DateTimeImmutable());
        if ($article->getUrlImageMain() === null) {
            $article->setUrlImageMain("assets/img/news-by-default.jpg");
        }

        $errors = $validator->validate($article);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $repository->add($article, true);

        return $this->json($article, 201, [], ["groups" => "article:read",]);
    }

    // Update one article from its ID
    #[Route("article/{id}", name: "api_article_update", methods: "PUT")]
    public function update(ArticleRepository $repository, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, int $id): JsonResponse
    {
        $jsonReceived = $request->getContent();

        $updatedArticle = $serializer->deserialize($jsonReceived, Article::class, "json");

        $article = $repository->find($id);
        $article->setTitle($updatedArticle->getTitle());
        $article->setContent($updatedArticle->getContent());

        if ($updatedArticle->getUrlImageMain() === null) {
            $article->setUrlImageMain("assets/img/news-by-default.jpg");
        } else {
            $article->setUrlImageMain($updatedArticle->getUrlImageMain());
        }

        $article->setUrlImageAdditional1($updatedArticle->getUrlImageAdditional1());
        $article->setUrlImageAdditional2($updatedArticle->getUrlImageAdditional2());
        $article->setUrlImageAdditional3($updatedArticle->getUrlImageAdditional3());
        $article->setUpdatedAt(new \DateTimeImmutable());

        $errors = $validator->validate($updatedArticle);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $repository->add($article, true);

        return $this->json($article, 201, [], ["groups" => "article:read",]);
    }

    //Delete on article from its ID
    #[Route("article/{id}", name: "api_article_delete", methods: "DELETE")]
    public function delete(ArticleRepository $repository, int $id): JsonResponse
    {
        $repository->remove($repository->find($id), true);
        return $this->json([
            "status" => 201,
            "message" => "The article with the id #$id has been deleted !"
        ], 201, []);
    }
}
