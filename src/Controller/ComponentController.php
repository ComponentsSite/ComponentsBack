<?php

namespace App\Controller;

use DateTime;
use App\Entity\Component;
use PhpParser\Builder\Method;
use App\Repository\ComponentRepository;
use App\Repository\LibrairieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ComponentController extends AbstractController
{
    /**
     * Renvoie toutes les entrées noms de librairie
     * 
     * @return JSonResponse
     */
    #[Route('/api/component', name: 'component.getAll', methods: ['GET'])]
    public function getAllComponent(ComponentRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $component = $repository->findAll();
        $jsonlibrairies = $serializer->serialize($component, 'json', ['groups' => "getAllWithinName"]);
        return new JsonResponse($jsonlibrairies,200,[], true);
    }

    #[Route('/api/component/{idComponent}', name: 'component.get', methods: ['GET'])]
    #[ParamConverter("component", options : ["id" => "idComponent"])]
    /**
     * Undocumented function
     *
     * @param Component $component
     * @param ComponentRepository $repository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getComponent( Component $component, ComponentRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $jsonlibrairies = $serializer->serialize($component, 'json', ['groups' => "getAllWithinName"]);
        return new JsonResponse($jsonlibrairies,200,[], true);
    }


    /**
     * Post Lirairie entry
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param UrlGeneratorInterface $urlGenerator
     * @return JsonResponse
     */
    #[Route('/api/component', name: 'component.post', methods: ['POST'])]
    public function createComponent(Request $request, SerializerInterface $serializer,EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator): JsonResponse
    {
        $component = $serializer->deserialize($request->getContent(), Component::class,"json");

        $component
        ->setStatus("en cours")
        ->setCreatAt(new DateTime())
        ->setUpdateAt(new DateTime());

        $errors = $validator->validate($component);
        if($errors -> count()>0){
            return new JsonResponse($serializer->serialize($errors,'json'),JsonResponse::HTTP_BAD_REQUEST,[],true);
        }

        $entityManager->persist($component);
        $entityManager->flush();


        $jsonlibrairie = $serializer->serialize($component, 'json', ['groups' => "getAllWithinName"]);
        
        
        $location = $urlGenerator->generate('component.get', ["idComponent" => $component->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonlibrairie,Response::HTTP_CREATED,["Location" =>$location], true);
    }

    #[Route('/api/component/{id}', name: 'component.update', methods: ['PUT','PATCH'])]
   /**
    * Undocumented function
    *
    * @param Request $request
    * @param Component $component
    * @param SerializerInterface $serializer
    * @param EntityManagerInterface $entityManager
    * @return JsonResponse
    */
    public function updateComponent(Request $request, Component $component, SerializerInterface $serializer,EntityManagerInterface $entityManager): JsonResponse
    {
        $updatedComponent = $serializer->deserialize($request->getContent(), Component::class,'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $component]);
        $updatedComponent->setUpdateAt(new DateTime());
        $entityManager->persist($updatedComponent);
        $entityManager->flush();
       

        return new JsonResponse(null,JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/api/component/{id}', name: 'component.delete', methods: ['DELETE'])]
    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Component $component
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function deleteComponent(Request $request,Component $component, EntityManagerInterface $entityManager): JsonResponse
    {
        
        //request Array exist and Not empty 
        if(isset($request->toArray()["force"]) && true === $request->toArray()["force"]){
            // la suoppression est forcée
            $entityManager->remove($component); 
            // meme cho se que le delete
            
        } else {
            // la suppression n'est pas forcée
            // meme chose que le Update
            $component->setStatus("abandonné");
            $component->setUpdateAt(new DateTime());
            $entityManager->persist($component);
        }

        $entityManager->flush();

    //     [
    //         "toto" // => 0
    //     ];
    // $fiche = [
    //     "prenom" => "Alexandre",
    //     "nom"=> "Quilan-Delaistre",
    //     "age"=> "27",
    //    "styleMusique"=> "Jazz"
    // ];   

    // $fiche[0] // === "Alexandre"
    // $fiche["prenom"] // === "Alexandre"

       
        return new JsonResponse(null,JsonResponse::HTTP_NO_CONTENT);
    }

   /*  public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/LibrairieController.php',
        ]);
    } */
}
