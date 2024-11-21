<?php

namespace App\Functions\crm;

use App\Entity\CRM\Colaborador;
use App\Entity\CRM\ColaboradorTaller;
use App\Repository\CRM\TallerRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CRM\UsuarioRepository;
use App\Repository\CRM\ColaboradorRepository;

class ColaboradorFunciones
{
    private $entityManager;
    private $colaboradorRepository;
    private $tallerRepository;
    private $usuarioRepository;

    private $id;
    private $usuarioId;
    private $c_nombres;
    private $c_apellido_paterno;
    private $c_apellido_materno;
    private $c_fecha_nacimiento;
    private $c_telefono;
    private $c_correo;
    private $c_foto;
    private $c_genero;
    private $c_fecha_creacion;
    private $c_estado;

    public function __construct(EntityManagerInterface $entityManager, ColaboradorRepository $colaboradorRepository, TallerRepository $tallerRepository, UsuarioRepository $usuarioRepository)
    {
        $this->entityManager = $entityManager;
        $this->colaboradorRepository = $colaboradorRepository;
        $this->tallerRepository = $tallerRepository;
        $this->usuarioRepository = $usuarioRepository;
    }

    public function guardarColaborador(array $datos, int $tipoperacion)
    {
        try {
            if ($this->guardarVariablesColaborador($datos)) {
                if ($tipoperacion == 1) {
                    $colaboradorGuardado = $this->almacenarActualizarColaborador(1);
                } else {
                    $colaboradorGuardado = $this->almacenarActualizarColaborador(2);
                }
                if ($colaboradorGuardado) {
                    if (!empty($datos['talleres']) && is_array($datos['talleres'])) {
                        $this->guardarTalleresColaborador($colaboradorGuardado, $datos['talleres']);
                    }
                    return [
                        'status' => 'success',
                        'colaborador' => $this->obtenerColaborador($colaboradorGuardado),
                    ];
                } else {
                    return [
                        'status' => 'failed'
                    ];
                }
            }
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operacion : ' . $e;
        }
    }

    public function eliminarColaborador(int $id)
    {
        try {
            if ($this->deshabilitarColaborador($id)) {
                return [
                    'status' => 'success'
                ];
            }
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operacion : ' . $e;
        }
    }

    public function obtenerColaboradorPorId(int $id)
    {
        try {
            $colaborador = $this->colaboradorRepository->find($id);

            if (!$colaborador || !$colaborador->isCEstado()) {
                return [
                    'status' => 'error',
                    'message' => 'Colaborador no encontrado'
                ];
            }

            return [
                'status' => 'success',
                'colaborador' => $this->obtenerColaborador($colaborador)
            ];
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operacion : ' . $e;
        }
    }

    private function guardarVariablesColaborador($datosColaborador)
    {
        try {
            if (!empty($datosColaborador['id'])) {
                $this->id = $datosColaborador['id'];
            }
            $this->usuarioId = $datosColaborador['usuario_id'] ?? null;
            $this->c_nombres = $datosColaborador['c_nombres'] ?? null;
            $this->c_apellido_paterno = $datosColaborador['c_apellido_paterno'] ?? null;
            $this->c_apellido_materno = $datosColaborador['c_apellido_materno'] ?? null;
            $this->c_fecha_nacimiento = isset($datosColaborador['c_fecha_nacimiento']) ? new \DateTime($datosColaborador['c_fecha_nacimiento']) : null;
            $this->c_telefono = $datosColaborador['c_telefono'] ?? null;
            $this->c_correo = $datosColaborador['c_correo'] ?? null;
            $this->c_foto = $datosColaborador['c_foto'] ?? null;
            $this->c_genero = $datosColaborador['c_genero'] ?? null;
            $this->c_fecha_creacion = new \DateTime();
            $this->c_estado = true;

            if (!empty($this->c_nombres) && !empty($this->c_apellido_paterno) && !empty($this->usuarioId)) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operacion : ' . $e;
        }
    }

    private function almacenarActualizarColaborador(int $tipo)
    {
        try {
            if ($tipo == 1) {
                $colaborador = new Colaborador();
                $usuario = $this->usuarioRepository->find($this->usuarioId);
                if (!$usuario) {
                    throw new \Exception('Usuario no encontrado');
                }
                $colaborador->setUsuario($usuario);
            } else {
                $colaborador = $this->colaboradorRepository->find($this->id);
            }

            $colaborador->setCNombres($this->c_nombres);
            $colaborador->setCApellidoPaterno($this->c_apellido_paterno);
            $colaborador->setCApellidoMaterno($this->c_apellido_materno);
            $colaborador->setCFechaNacimiento($this->c_fecha_nacimiento);
            $colaborador->setCTelefono($this->c_telefono);
            $colaborador->setCCorreo($this->c_correo);
            $colaborador->setCFoto($this->c_foto);
            $colaborador->setCGenero($this->c_genero);
            $colaborador->setCFechaCreacion($this->c_fecha_creacion);
            $colaborador->setCEstado($this->c_estado);

            if ($tipo == 1) {
                $this->entityManager->persist($colaborador);
            }
            $this->entityManager->flush();
            return $colaborador;
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operacion : ' . $e;
        }
    }

    private function guardarTalleresColaborador(Colaborador $colaborador, array $talleres)
    {
        try {
            foreach ($talleres as $tallerData) {
                if (isset($tallerData['taller_id'])) {
                    $taller = $this->tallerRepository->find($tallerData['taller_id']);
                    if ($taller) {
                        $colaboradorTaller = new ColaboradorTaller();
                        $colaboradorTaller->setColaboradores($colaborador);
                        $colaboradorTaller->setTalleres($taller);
                        $colaboradorTaller->setCtTipoColaborador($tallerData['ct_tipo_colaborador'] ?? null);
                        $colaboradorTaller->setCtAsistencia($tallerData['ct_asistencia'] ?? null);
                        $colaboradorTaller->setCtJustificacion($tallerData['ct_justificacion'] ?? null);
                        $colaboradorTaller->setCtEstado(true);
                        $this->entityManager->persist($colaboradorTaller);
                    }
                }
            }
            $this->entityManager->flush();
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operacion : ' . $e;
        }
    }

    private function obtenerColaborador(Colaborador $colaborador)
    {
        return [
            'id' => $colaborador->getId(),
            'usuario_id' => $colaborador->getUsuario() ? $colaborador->getUsuario()->getId() : null,
            'c_nombres' => $colaborador->getCNombres(),
            'c_apellido_paterno' => $colaborador->getCApellidoPaterno(),
            'c_apellido_materno' => $colaborador->getCApellidoMaterno(),
            'c_fecha_nacimiento' => $colaborador->getCFechaNacimiento() ? $colaborador->getCFechaNacimiento()->format('Y-m-d') : null,
            'c_telefono' => $colaborador->getCTelefono(),
            'c_correo' => $colaborador->getCCorreo(),
            'c_genero' => $colaborador->getCGenero(),
        ];
    }

    private function deshabilitarColaborador(int $id)
    {
        try {
            $colaborador = $this->colaboradorRepository->find($id);
            if ($colaborador) {
                $colaborador->setCEstado(false);
                $this->entityManager->flush();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            echo 'Ocurrió un error al realizar la operacion : ' . $e;
        }
    }
}
?>
