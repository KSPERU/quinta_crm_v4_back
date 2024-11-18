<?php

namespace App\Functions\crm;

use App\Entity\CRM\Programa;
use Datetime;
use App\Entity\CRM\Colectiva;
use App\Repository\CRM\ColectivaRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CRM\TemporadaRepository;

class ColectivaFunciones
{
    private $entityManager;
    private $temporadaRepository;
    private $colectivaRepository;
    private $tallerFunciones;

    private $id;
    private $c_nombre;
    private $c_miembros;
    private $c_fecha_creacion;
    private $temporada;

    public function __construct(EntityManagerInterface $entityManager, TemporadaRepository $temporadaRepository, ColectivaRepository $colectivaRepository, TallerFunciones $tallerFunciones)
    {
        $this->entityManager = $entityManager;
        $this->temporadaRepository = $temporadaRepository;
        $this->colectivaRepository = $colectivaRepository;
        $this->tallerFunciones = $tallerFunciones;
    }

    public function guardarColectiva(array $datos, int $tipoperacion)
    {
        try {
            if ($this->guardarVariablesColectiva($datos)) {
                $datosTaller = [];
                    $colectivaGuardada = $this->almacenarActualizarColectiva($tipoperacion);
                    if ($tipoperacion == 2) {
                        $datosTaller = $this->tallerFunciones->guardarTaller($this->maquetarTaller(),2);
                    }
                
                if ($colectivaGuardada) {
                    return [
                        'status' => 'success',
                        'datos_colectiva' => $this->obtenerColectiva($colectivaGuardada),
                        'taller' => $datosTaller
                    ];
                } else {
                    return [
                        'status' => 'failed'
                    ];
                }
            }
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operación: ' . $e->getMessage();
        }
    }

    public function eliminarColectiva(int $id)
    {
        try {
            if ($this->deshabilitarColectiva($id)) {
                return [
                    'status' => 'success',
                    'datos_colectivas' => $this->obtenerColectivas(),
                ];
            }
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operación: ' . $e->getMessage();
        }
    }

    public function getColectivas()
    {
        try {
            return [
                'status' => 'success',
                'datos_colectivas' => $this->obtenerColectivas(),
            ];
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operación: ' . $e->getMessage();
        }
    }

    private function guardarVariablesColectiva($datosColectiva)
    {
        try {
            if (!empty($datosColectiva['id'])) {
                $this->id = $datosColectiva['id'];
            }
            $this->temporada = $this->temporadaRepository->find($datosColectiva['id_temporada']);
            $this->c_nombre = $datosColectiva['nombre'];
            $this->c_miembros = $datosColectiva['miembros'];
            $this->c_fecha_creacion = $datosColectiva['fecha_creacion'];

            if (!empty($this->c_nombre) && !empty($this->c_miembros) && !empty($this->c_fecha_creacion)) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operación: ' . $e->getMessage();
        }
    }

    private function almacenarActualizarColectiva(int $tipo)
    {
        try {
            if ($tipo == 1 || $tipo == 2) {
                $colectiva = new Colectiva();
            } else {
                $colectiva = $this->colectivaRepository->find($this->id);
            }

            $colectiva->setTemporada($this->temporada);
            $colectiva->setCNombre($this->c_nombre);
            $colectiva->setCMiembros($this->c_miembros);
            $colectiva->setCFechaCreacion($this->c_fecha_creacion);
            $colectiva->setCEstado(true);
            $colectiva->setCEstadoSys(0);
            if($tipo == 2){
                $colectiva->setCEstadoSys(1);
            }
            if ($tipo == 1 || $tipo == 2) {
                $this->entityManager->persist($colectiva);
            }
            $this->entityManager->flush();

            if (empty($this->id)) {
                $this->id = $colectiva->getId();
            }
            return $colectiva;
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operación: ' . $e->getMessage();
        }
    }

    private function obtenerColectiva(Colectiva $colectiva)
    {
        return [
            'id' => $colectiva->getId(),
            'nombre' => $colectiva->getCNombre(),
            'miembros' => $colectiva->getCMiembros(),
            'fecha_creacion' => $colectiva->getCFechaCreacion(),
        ];
    }

    private function deshabilitarColectiva(int $id)
    {
        try {
            $colectiva = $this->colectivaRepository->find($id);
            $colectiva->setCEstado(false);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operación: ' . $e->getMessage();
        }
    }

    private function obtenerColectivas()
    {
        $colectivas = $this->colectivaRepository->findBy([
            'c_estado' => true
        ]);

        $datosColectivas = [];
        foreach ($colectivas as $colectiva) {
            $datosColectivas[] = $this->obtenerColectiva($colectiva);
        }

        return $datosColectivas;
    }

    private function maquetarTaller()
    {
        return [
            'id_colectiva' => $this->id,
            'nombre' => 'Taller de sistema N° ' . $this->id,
            'fecha_hora' => new \DateTime(),
            'modalidad' => 0, // Modalidad predeterminada
            'tipo_taller' => 'Taller de sistema', // Tipo predeterminado
        ];
    }
}