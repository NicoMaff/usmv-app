<?php

namespace App\Controller\API;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("api/")]
class ArticleController extends AbstractController
{
    #[Route("articles", name: "app_article_api_list", methods: "GET")]
    public function list(ArticleRepository $repository): Response
    {
        $articles = $repository->findAll();

        return $this->json($articles, 200, [], ["groups" => "article:read"]);
    }

    #[Route("article/new", name: "app_article_api_new", methods: "POST")]
    public function new(Request $request, ArticleRepository $repository, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        $jsonReceived = $request->getContent();

        try {
            $article = $serializer->deserialize($jsonReceived, Article::class, "json");
            $article->setCreatedAt(new \DateTimeImmutable());

            $errors = $validator->validate($article);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $repository->add($article, true);

            return $this->json($article, 201, [], ["groups" => "article:read",]);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    #[Route("article/update/{id}", name: "app_article_api_update", methods: "PUT")]
    public function replace(ArticleRepository $repository, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, int $id): Response
    {
        $jsonReceived = $request->getContent();

        try {
            $updatedArticle = $serializer->deserialize($jsonReceived, Article::class, "json");

            $article = $repository->find($id);
            $article->setTitle($updatedArticle->getTitle());
            $article->setContent($updatedArticle->getContent());
            $article->setUrlImageMain($updatedArticle->getUrlImageMain());
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
        } catch (NotEncodableValueException $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    #[Route("article/delete/{id}", name: "app_article_api_delete", methods: "DELETE")]
    public function delete(ArticleRepository $repository, Request $request, ValidatorInterface $validator, SerializerInterface $serializer, int $id): Response
    {
        try {

            $repository->remove($repository->find($id), true);

            return $this->json(["status" => 201, "message" => "The article with the id #$id has been deleted !"], 201, []);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }
}
