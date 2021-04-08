<?php

namespace WebMultiTool\Infra\Symfony\Controller;

use WebMultiTool\Domain\Jwt\UseCases\AddSecret\AddSecret;
use WebMultiTool\Domain\Jwt\UseCases\AddSecret\AddSecretRequest;
use WebMultiTool\Domain\Jwt\UseCases\GetSecrets\GetSecrets;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/jwt", name="jwt_")
 */
class JwtController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('base.html.twig', ['component' => 'jwt']);
    }

    /**
     * @Route("/secret", methods={"POST"})
     * @return JsonResponse
     */
    public function addSecret(Request $request, AddSecret $addSecret): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $response = $addSecret->execute(new AddSecretRequest($content['secret'], $content['label']));
        return new JsonResponse($response->getSecret()->toArray());
    }

    /**
     * @Route("/secrets", methods={"GET"})
     * @return JsonResponse
     */
    public function getSecrets(GetSecrets $getSecrets): JsonResponse
    {
        return new JsonResponse(
            $getSecrets->execute()->getSecrets()
        );
    }
}
