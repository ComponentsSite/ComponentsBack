<?php

namespace App\Controller;

use DateTime;
use App\Entity\Component;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use PhpParser\Builder\Method;
use App\Repository\ComponentRepository;
use App\Repository\LibrairieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ComponentController extends AbstractController
{
    /**
     * Renvoit tous les composants
     *
     * @param ComponentRepository $repository
     * @param SerializerInterface $serializer
     * @param TagAwareCacheInterface $cache
     * @return JsonResponse
     */
    #[OA\Response(
        response:200,
        description: "Retourne la liste des composants",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: new Model(type:Component::class)),
        ),
    )]
    #[Route('/api/component', name: 'component.getAll', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function getAllComponent(ComponentRepository $repository, SerializerInterface $serializer,  TagAwareCacheInterface $cache): JsonResponse
    {
        $idCache = "getAllComponent";
        $cache->invalidateTags(["componentCache"]);
        
        $jsonComponent = $cache->get($idCache, function(ItemInterface $item) use ($repository, $serializer){
            $item->tag("componentCache");
            $components = $repository->findAll();
            return $serializer->serialize($components, 'json', ['groups' => 'getAllWithinName']);
            
        });
        return new JsonResponse($jsonComponent,200,[], true);
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
    public function createComponent(Request $request, SerializerInterface $serializer,EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator,TagAwareCacheInterface $cache): JsonResponse
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

        $cache->invalidateTags(["componentCache"]);

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
    public function updateComponent(Request $request, Component $component, SerializerInterface $serializer,EntityManagerInterface $entityManager,TagAwareCacheInterface $cache): JsonResponse
    {
        $updatedComponent = $serializer->deserialize($request->getContent(), Component::class,'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $component]);
        $updatedComponent->setUpdateAt(new DateTime());
        $entityManager->persist($updatedComponent);
        $entityManager->flush();
       
        $cache->invalidateTags(["componentCache"]);

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
    public function deleteComponent(Request $request,Component $component, EntityManagerInterface $entityManager,TagAwareCacheInterface $cache): JsonResponse
    {
        if(isset($request->toArray()["force"]) && true === $request->toArray()["force"]){
            $entityManager->remove($component);   
        } else {
            $component->setStatus("abandonné");
            $component->setUpdateAt(new DateTime());
            $entityManager->persist($component);
        }

        $entityManager->flush();

        $cache->invalidateTags(["componentCache"]);
        return new JsonResponse(null,JsonResponse::HTTP_NO_CONTENT);
    }
}
