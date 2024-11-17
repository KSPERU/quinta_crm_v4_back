<?php

namespace App\Controller\CRM\api;

use App\Functions\crm\ColaboradorFunciones;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ColaboradorApiController extends AbstractController
{
    private $colaboradorFunciones;

    public function __construct(ColaboradorFunciones $colaboradorFunciones)
    {
        $this->colaboradorFunciones = $colaboradorFunciones;
    }

    #[Route('/api/agregar/colaborador', name: 'api_agregar_colaborador', methods:['POST'])]
    public function agregarColaborador(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $resultado = $this->colaboradorFunciones->guardarColaborador($data, 1);
        return $this->json($resultado, Response::HTTP_OK);
    }

    #[Route('/api/actualizar/colaborador', name: 'api_actualizar_colaborador', methods:['POST'])]
    public function actualizarColaborador(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $resultado = $this->colaboradorFunciones->guardarColaborador($data, 2);
        return $this->json($resultado, Response::HTTP_OK);
    }

    #[Route('/api/eliminar/colaborador/{id}', name: 'api_eliminar_colaborador', methods:['GET'])]
    public function eliminarColaborador(int $id): JsonResponse
    {
        $resultado = $this->colaboradorFunciones->eliminarColaborador($id);
        return $this->json($resultado, Response::HTTP_OK);
    }

    #[Route('/api/obtener/colaborador/{id}', name: 'api_obtener_colaborador', methods:['GET'])]
    public function obtenerColaborador(int $id): JsonResponse
    {
        $resultado = $this->colaboradorFunciones->obtenerColaboradorPorId($id);
        return $this->json($resultado, Response::HTTP_OK);
    }
}

?>