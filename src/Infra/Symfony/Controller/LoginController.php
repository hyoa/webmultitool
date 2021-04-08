<?php

namespace WebMultiTool\Infra\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;

class LoginController extends AbstractController
{
    /**
     * @Route("/login")
     */
    public function login(): Response
    {
      return $this->render('base.html.twig', ['component' => 'login']);
    }
}
