<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/api/login_check', name: 'api_login_check', methods: ['POST'])]
    public function loginCheck(): JsonResponse
    {
        throw new \LogicException('This should never be reached!');
    }
}