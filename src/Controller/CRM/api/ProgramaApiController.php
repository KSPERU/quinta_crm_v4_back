<?php

namespace App\Controller\CRM\api;


use App\Functions\crm\ProgramaFunciones;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api',name: 'api_')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ProgramaApiController extends AbstractController
{
    private $programaFunciones;

    public function __construct(ProgramaFunciones $programaFunciones)
    {
        $this->programaFunciones = $programaFunciones;
    }

    #[Route('/agregar/programa', name: 'agregar_programa',methods:['POST'])]
    public function agregarPrograma(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        $resultado = $this->programaFunciones->guardarPrograma($data, 1);
        return $this->json($resultado, Response::HTTP_OK);
    }

    #[Route('/actualizar/programa', name: 'actualizar_programa',methods:['POST'])]
    public function actualizarPrograma(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        $resultado = $this->programaFunciones->guardarPrograma($data, 2);

        return $this->json($resultado, Response::HTTP_OK);
    }

    #[Route('/eliminar/programa/{id}', name: 'eliminar_programa',methods:['GET'])]
    public function eliminarPrograma(int $id): JsonResponse
    {
        $resultado = $this->programaFunciones->eliminarPrograma($id);
        return $this->json($resultado, Response::HTTP_OK);
    }

    #[Route('/obtener/programas', name: 'obtener_programas',methods:['GET'])]
    public function obtenerProgramas(): JsonResponse
    {
        $resultado = $this->programaFunciones->getProgramas();
        return $this->json($resultado, Response::HTTP_OK);
    }


}
