<?php

namespace App\Functions\crm;

use App\Entity\CRM\Colectiva;
use Datetime;
use App\Entity\CRM\Taller;
use App\Repository\CRM\ColectivaRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CRM\TallerRepository;

class TallerFunciones
{
    private $entityManager;
    private $tallerRepository;
    private $colectivaRepository;

    private $id;
    private $t_nombre;
    private $t_fecha_hora;
    private $t_modalidad;
    private $t_tipo_taller;
    private $colectiva;

    public function __construct(EntityManagerInterface $entityManager, TallerRepository $tallerRepository, ColectivaRepository $colectivaRepository)
    {
        $this->entityManager = $entityManager;
        $this->tallerRepository = $tallerRepository;
        $this->colectivaRepository = $colectivaRepository;
    }

    public function guardarTaller(array $datos, int $tipoperacion)
    {
        try {
            if ($this->guardarVariablesTaller($datos)) {
                
                $tallerGuardado = $this->almacenarActualizarTaller($tipoperacion);
                if ($tallerGuardado) {
                    return [
                        'status' => 'success',
                        'datos_taller' => $this->obtenerTaller($tallerGuardado)
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

    public function eliminarTaller(int $id)
    {
        try {
            if ($this->deshabilitarTaller($id)) {
                return [
                    'status' => 'success',
                    'datos_talleres' => $this->obtenerTalleres(),
                ];
            }
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operación: ' . $e->getMessage();
        }
    }

    public function getTalleres()
    {
        try {
            return [
                'status' => 'success',
                'datos_talleres' => $this->obtenerTalleres(),
            ];
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operación: ' . $e->getMessage();
        }
    }

    private function guardarVariablesTaller(array $datosTaller)
    {
        try {
            if (!empty($datosTaller['id'])) {
                $this->id = $datosTaller['id'];
            }
            $this->colectiva = $this->colectivaRepository->find($datosTaller['id_colectiva']);
            $this->t_nombre = $datosTaller['nombre'];
            $this->t_fecha_hora = $datosTaller['fecha_hora'];
            $this->t_modalidad = $datosTaller['modalidad'];
            $this->t_tipo_taller = $datosTaller['tipo_taller'];

            if (!empty($this->t_nombre) && !empty($this->t_fecha_hora) && $this->t_modalidad !== null) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            echo 'Ocurrió un error al guardar los datos del taller: ' . $e->getMessage();
        }
    }

    private function almacenarActualizarTaller(int $tipo)
    {
        try {
            if ($tipo == 1 || $tipo == 2) {
                $taller = new Taller();
            } else {
                $taller = $this->tallerRepository->find($this->id);
            }

            $taller->setColectiva($this->colectiva);
            $taller->setTNombre($this->t_nombre);
            $taller->setTFechaHora($this->t_fecha_hora);
            $taller->setTModalidad($this->t_modalidad);
            $taller->setTTipoTaller($this->t_tipo_taller);
            $taller->setTEstado(true);
            $taller->setTEstadoSys(0);
            if($tipo == 2){
                $taller->setTEstadoSys(1);
            }
            if ($tipo == 1 || $tipo == 2) {
                $this->entityManager->persist($taller);
            }
            $this->entityManager->flush();

            if (empty($this->id)) {
                $this->id = $taller->getId();
            }
            return $taller;
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operación: ' . $e->getMessage();
        }
    }

    private function obtenerTaller(Taller $taller)
    {
        return [
            'id' => $taller->getId(),
            'nombre' => $taller->getTNombre(),
            'fecha_hora' => $taller->getTFechaHora()->format('Y-m-d H:i:s'),
            'modalidad' => $taller->getTModalidad(),
            'tipo_taller' => $taller->getTTipoTaller(),
            'colectiva' => [
                'id' => $taller->getColectiva()->getId(),
                'nombre' => $taller->getColectiva()->getCNombre()
            ]
        ];
    }

    private function deshabilitarTaller(int $id)
    {
        try {
            $taller = $this->tallerRepository->find($id);
            $taller->setTEstado(false);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operación: ' . $e->getMessage();
        }
    }

    private function obtenerTalleres()
    {
        $talleres = $this->tallerRepository->findBy([
            't_estado' => true
        ]);

        $datosTalleres = [];
        foreach ($talleres as $taller) {
            $datosTalleres[] = $this->obtenerTaller($taller);
        }

        return $datosTalleres;
    }
}