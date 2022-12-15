<?php

namespace App\Controller\API;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[IsGranted("ROLE_ADMIN")]
#[Route("api/admin")]
class ApiArticleController extends AbstractController
{
    /**
     * CREATE
     * An ADMIN can create a new article.
     * If no file is uploaded, a default image for the thirdAddFile will be added to the article.
     */
    #[Route("article", name: "api_article_createArticle", methods: "POST")]
    public function createArticle(Request $request, ArticleRepository $repository, SerializerInterface $serializer, ValidatorInterface $validator, SluggerInterface $slugger): JsonResponse
    {
        // Request using multipart/form-data
        if ($request->request->get("data")) {
            $jsonReceived = $request->request->get("data");
        } else {
            // Request using raw Body
            $jsonReceived = $request->getContent();
        }

        // Request using multipart/form-data
        if ($request->files->get("mainFile")) {
            $uploadedFile = $request->files->get("mainFile");
        }
        if ($request->files->get("firstAddFile")) {
            $uploadedFile1 = $request->files->get("firstAddFile");
        }
        if ($request->files->get("secondAddFile")) {
            $uploadedFile2 = $request->files->get("secondAddFile");
        }
        if ($request->files->get("thirdAddFile")) {
            $uploadedFile3 = $request->files->get("thirdAddFile");
        }

        $article = $serializer->deserialize($jsonReceived, Event::class, "json");

        if (isset($uploadedFile)) {
            // File settings
            $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $destination = $this->getParameter("kernel.project_dir") . "/public/assets/img/articles/";
            $newFileName = $safeFileName . "-" . uniqid() . "." . $uploadedFile->guessExtension();

            try {
                $uploadedFile->move($destination, $newFileName);
            } catch (FileException $e) {
                echo $e->getMessage();
            }

            $article->setMainImageName($newFileName);
            $article->setMainImageUrl($destination . $newFileName);
        } else {
            $article->setMainImageName("article-default-image.png");
            $article->setMainImageUrl($this->getParameter("kernel.project_dir") . "/public/assets/img/articles/article-default-image.png");
        }

        if (isset($uploadedFile1)) {
            // File settings
            $originalFileName = pathinfo($uploadedFile1->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $destination = $this->getParameter("kernel.project_dir") . "/public/assets/img/articles/";
            $newFileName = $safeFileName . "-" . uniqid() . "." . $uploadedFile->guessExtension();

            try {
                $uploadedFile1->move($destination, $newFileName);
            } catch (FileException $e) {
                echo $e->getMessage();
            }

            $article->setFirstAdditionalImageName($newFileName);
            $article->setFirstAdditionalImageUrl($destination . $newFileName);
        }

        if (isset($uploadedFile2)) {
            // File settings
            $originalFileName = pathinfo($uploadedFile2->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $destination = $this->getParameter("kernel.project_dir") . "/public/assets/img/articles/";
            $newFileName = $safeFileName . "-" . uniqid() . "." . $uploadedFile->guessExtension();

            try {
                $uploadedFile2->move($destination, $newFileName);
            } catch (FileException $e) {
                echo $e->getMessage();
            }

            $article->setSecondAdditionalImageName($newFileName);
            $article->setSecondAdditionalImageUrl($destination . $newFileName);
        }

        if (isset($uploadedFile3)) {
            // File settings
            $originalFileName = pathinfo($uploadedFile3->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $destination = $this->getParameter("kernel.project_dir") . "/public/assets/img/articles/";
            $newFileName = $safeFileName . "-" . uniqid() . "." . $uploadedFile->guessExtension();

            try {
                $uploadedFile3->move($destination, $newFileName);
            } catch (FileException $e) {
                echo $e->getMessage();
            }

            $article->setThirdAdditionalImageName($newFileName);
            $article->setThirdAdditionalImageUrl($destination . $newFileName);
        }

        $errors = $validator->validate($article);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $repository->add($article, true);
        return $this->json($article, 201, context: ["groups" => "article:read"]);
    }

    /**
     * READ
     * An ADMIN can get an article from its ID.
     */
    #[Route("article/{id}", name: "api_article_readArticle", methods: "GET")]
    public function readArticle(ArticleRepository $repository, int $id): JsonResponse
    {
        return $this->json($repository->find($id), 200, context: ["groups" => "article:read"]);
    }

    /**
     * READ
     * An ADMIN can get all articles' details.
     */
    #[Route("articles", name: "api_article_readAllArticles", methods: "GET")]
    public function readAllArticles(ArticleRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), 200, context: ["groups" => "article:read"]);
    }

    /**
     * UPDATE
     * An admin can update an article from its ID.
     * Only one file by property can be stored.
     * If a new image is uploaded, it will replace the older.
     */
    #[Route("article/{id}", name: "api_article_update", methods: "PATCH")]
    public function update(ArticleRepository $repository, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, int $id, SluggerInterface $slugger): JsonResponse
    {

        // Request using multipart/form-data
        if ($request->request->get("data")) {
            $jsonReceived = $request->request->get("data");
        } else {
            // Request using raw Body
            $jsonReceived = $request->getContent();
        }

        // Request using multipart/form-data
        if ($request->files->get("mainFile")) {
            $uploadedFile = $request->files->get("mainFile");
        }
        if ($request->files->get("firstAddFile")) {
            $uploadedFile1 = $request->files->get("firstAddFile");
        }
        if ($request->files->get("secondAddFile")) {
            $uploadedFile2 = $request->files->get("secondAddFile");
        }
        if ($request->files->get("thirdAddFile")) {
            $uploadedFile3 = $request->files->get("thirdAddFile");
        }

        if ($request->request->get("deleteFirstAddFile")) {
            $deleteFirstAddFile = true;
        } else {
            $deleteFirstAddFile = false;
        }
        if ($request->request->get("deleteSecondAddFile")) {
            $deleteSecondAddFile = true;
        } else {
            $deleteSecondAddFile = false;
        }
        if ($request->request->get("deleteThirdAddFile")) {
            $deleteThirdAddFile = true;
        } else {
            $deleteThirdAddFile = false;
        }

        $article = $repository->find($id);
        $updatedArticle = $serializer->deserialize($jsonReceived, Article::class, "json");

        if ($updatedArticle->getTitle()) {
            $article->setTitle($updatedArticle->getTitle());
        }
        if ($updatedArticle->getContent()) {
            $article->setContent($updatedArticle->getContent());
        }
        if ($updatedArticle->isVisible()) {
            $article->setVisible($updatedArticle->isVisible());
        }

        if (isset($uploadedFile)) {
            // File settings
            $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $destination = $this->getParameter("kernel.project_dir") . "/public/assets/img/articles/";
            $newFileName = $safeFileName . "-" . uniqid() . "." . $uploadedFile->guessExtension();

            if ($article->getMainImageName() && file_exists($article->getMainImageUrl())) {
                unlink($article->getMainImageUrl());
            }

            try {
                $uploadedFile->move($destination, $newFileName);
            } catch (FileException $e) {
                echo $e->getMessage();
            }

            $article->setMainImageName($newFileName);
            $article->setMainImageUrl($destination . $newFileName);
        }

        if (isset($uploadedFile1)) {
            // File settings
            $originalFileName = pathinfo($uploadedFile1->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $destination = $this->getParameter("kernel.project_dir") . "/public/assets/img/articles/";
            $newFileName = $safeFileName . "-" . uniqid() . "." . $uploadedFile->guessExtension();

            if ($article->getFirstAdditionalImageName() && file_exists($article->getFirstAdditionalImageUrl())) {
                unlink($article->getFirstAdditionalImageUrl());
            }

            try {
                $uploadedFile1->move($destination, $newFileName);
            } catch (FileException $e) {
                echo $e->getMessage();
            }

            $article->setFirstAdditionalImageName($newFileName);
            $article->setFirstAdditionalImageUrl($destination . $newFileName);
        }

        if (isset($uploadedFile2)) {
            // File settings
            $originalFileName = pathinfo($uploadedFile2->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $destination = $this->getParameter("kernel.project_dir") . "/public/assets/img/articles/";
            $newFileName = $safeFileName . "-" . uniqid() . "." . $uploadedFile->guessExtension();

            if ($article->getSecondAdditionalImageName() && file_exists($article->getSecondAdditionalImageUrl())) {
                unlink($article->getSecondAdditionalImageUrl());
            }

            try {
                $uploadedFile2->move($destination, $newFileName);
            } catch (FileException $e) {
                echo $e->getMessage();
            }

            $article->setSecondAdditionalImageName($newFileName);
            $article->setSecondAdditionalImageUrl($destination . $newFileName);
        }

        if (isset($uploadedFile3)) {
            // File settings
            $originalFileName = pathinfo($uploadedFile3->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $slugger->slug($originalFileName);
            $destination = $this->getParameter("kernel.project_dir") . "/public/assets/img/articles/";
            $newFileName = $safeFileName . "-" . uniqid() . "." . $uploadedFile->guessExtension();

            if ($article->getThirdAdditionalImageName() && file_exists($article->getThirdAdditionalImageUrl())) {
                unlink($article->getThirdAdditionalImageUrl());
            }

            try {
                $uploadedFile3->move($destination, $newFileName);
            } catch (FileException $e) {
                echo $e->getMessage();
            }

            $article->setThirdAdditionalImageName($newFileName);
            $article->setThirdAdditionalImageUrl($destination . $newFileName);
        }

        if ($deleteFirstAddFile && $article->getFirstAdditionalImageName() && file_exists($article->getFirstAdditionalImageUrl())) {
            unlink($article->getFirstAdditionalImageUrl());
            $article->setFirstAdditionalImageName(null);
            $article->setFirstAdditionalImageUrl(null);
        } else {
            throw new Exception("There is no file to delete.");
        }
        if ($deleteSecondAddFile && $article->getSecondAdditionalImageName() && file_exists($article->getSecondAdditionalImageUrl())) {
            unlink($article->getSecondAdditionalImageUrl());
            $article->setSecondAdditionalImageName(null);
            $article->setSecondAdditionalImageUrl(null);
        } else {
            throw new Exception("There is no file to delete.");
        }
        if ($deleteThirdAddFile && $article->getThirdAdditionalImageName() && file_exists($article->getThirdAdditionalImageUrl())) {
            unlink($article->getThirdAdditionalImageUrl());
            $article->setThirdAdditionalImageName(null);
            $article->setThirdAdditionalImageUrl(null);
        } else {
            throw new Exception("There is no file to delete.");
        }

        $article->setUpdatedAt(new \DateTimeImmutable());

        $errors = $validator->validate($article);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $repository->add($article, true);
        return $this->json($article, 201, context: ["groups" => "article:read"]);
    }

    /**
     * DELETE
     * An ADMIN can delete an article from its ID.
     * If an article is deleted, all of its files will be removed from the server.
     */
    #[Route("article/{id}", name: "api_article_deleteArticle", methods: "DELETE")]
    public function deleteArticle(ArticleRepository $repository, int $id): JsonResponse
    {
        $article = $repository->find($id);
        if ($article->getMainImageName() && file_exists($article->getMainImageUrl())) {
            unlink($article->getMainImageUrl());
        }
        if ($article->getFirstAdditionalImageName() && file_exists($article->getFirstAdditionalImageUrl())) {
            unlink($article->getFirstAdditionalImageUrl());
        }
        if ($article->getSecondAdditionalImageName() && file_exists($article->getSecondAdditionalImageUrl())) {
            unlink($article->getSecondAdditionalImageUrl());
        }
        if ($article->getThirdAdditionalImageName() && file_exists($article->getThirdAdditionalImageUrl())) {
            unlink($article->getThirdAdditionalImageUrl());
        }
        $repository->remove($article, true);
        return $this->json([
            "status" => 200,
            "message" => "The article with the id #$id has been correctly deleted."
        ], 200);
    }
}
