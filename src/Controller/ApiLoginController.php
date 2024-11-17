<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiLoginController extends AbstractController
{
    #[Route('/api/auth/user', name: 'app_api_login')]
    public function index(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json([
                'status' => 'error', 
                'message' => 'Usuario no autenticado',
                'user' => [
                    'id' => null,
                    'email' => null,
                ]
            ]);
        }

        return $this->json([
            'status' => 'success',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail()
            ]
        ]);
    }
}
