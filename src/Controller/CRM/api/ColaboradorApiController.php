<?php

namespace App\Controller\CRM\api;

use App\Functions\crm\ColaboradorFunciones;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api',name: 'api_')]
class ColaboradorApiController extends AbstractController
{
    private $colaboradorFunciones;

    public function __construct(ColaboradorFunciones $colaboradorFunciones)
    {
        $this->colaboradorFunciones = $colaboradorFunciones;
    }

    #[Route('/agregar/colaborador', name: 'agregar_colaborador', methods:['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function agregarColaborador(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $resultado = $this->colaboradorFunciones->guardarColaborador($data, 1);
        return $this->json($resultado, Response::HTTP_OK);
    }

    #[Route('/actualizar/colaborador', name: 'actualizar_colaborador', methods:['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function actualizarColaborador(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $resultado = $this->colaboradorFunciones->guardarColaborador($data, 2);
        return $this->json($resultado, Response::HTTP_OK);
    }

    #[Route('/eliminar/colaborador/{id}', name: 'eliminar_colaborador', methods:['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function eliminarColaborador(int $id): JsonResponse
    {
        $resultado = $this->colaboradorFunciones->eliminarColaborador($id);
        return $this->json($resultado, Response::HTTP_OK);
    }

    #[Route('/obtener/colaborador/{id}', name: 'obtener_colaborador', methods:['GET'])]
    public function obtenerColaborador(int $id): JsonResponse
    {
        $resultado = $this->colaboradorFunciones->obtenerColaboradorPorId($id);
        return $this->json($resultado, Response::HTTP_OK);
    }
}

?>