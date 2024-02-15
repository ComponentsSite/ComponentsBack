<?php

namespace App\Controller;

use App\Entity\Pictures;
use App\Repository\PicturesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PictureController extends AbstractController
{
    #[Route('/', name: 'app_picture')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PictureController.php',
        ]);
    }

    #[Route('/api/picture/{idPicture}', name:'picture.get', methods:['GET'])]
    public function getPicture(int $idPicture, PicturesRepository $repository, SerializerInterface $serialiser, UrlGeneratorInterface $urlGenerator) : JsonResponse {

        $picture = $repository->find($idPicture);
        $location =  $urlGenerator->generate("app_picture", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $location = $location . str_replace('/public/',"", $picture->getPublicPath())."/".$picture->getRealPath();

        return $picture ?
        new JsonResponse($serialiser->serialize($picture, 'json'), Response::HTTP_OK, ["Location" => $location], true):
        new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/picture', name:'picture.create', methods:['POST'])]
    public function createPicture(Request $request,EntityManagerInterface $entityManager, SerializerInterface $serialiser, UrlGeneratorInterface $urlGenerator) : JsonResponse {

        $picture = new Pictures;
        $file = $request->files->get('file');

        $picture->setFile($file);
        $picture->setMimeType($file->getClientMimeType());
        $picture->setRealName($file->getClientOriginalName());
        $picture->setName($file->getClientOriginalName());
        $picture->setPublicPath('/public/medias/pictures');
        $picture->setStatus("on")
            ->setUpdatedAt(new \DateTime())
            ->setCreatedAt(new \DateTime());
        
        $entityManager->persist($picture);
        $entityManager->flush();

        $jsonPicture = $serialiser->serialize($picture, "json");
        $location = $urlGenerator->generate("picture.get", ['idPicture' => $picture->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonPicture, Response::HTTP_CREATED, ['Location' => $location], true);
    }
}
