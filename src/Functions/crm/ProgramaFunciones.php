<?php

namespace App\Functions\crm;

use App\Entity\CRM\Programa;
use App\Repository\CRM\ProgramaRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ProgramaFunciones
{
    private $entityManager;
    private $programaRepository;

    private $id;
    private $pg_nombre;
    private $pg_imagen;
    private $pg_organizacion;
    private $pg_fecha_creacion;
    private $pg_fecha_inicio;
    private $pg_fecha_fin;

    private $temporadaFunciones;

    public function __construct(EntityManagerInterface $entityManager, ProgramaRepository $programaRepository, TemporadaFunciones $temporadaFunciones)
    {
        $this->entityManager = $entityManager;
        $this->programaRepository = $programaRepository;
        $this->temporadaFunciones = $temporadaFunciones;
    }

    public function guardarPrograma(array $datos, int $tipoperacion)
    {
        try{
            if($this->guardarVariablesPrograma($datos)){
                $datostemporada = [];
                    $programaGuardado = $this->almacenarActualizarPrograma($tipoperacion);
                    if($tipoperacion == 2){
                        $datostemporada = $this->temporadaFunciones->guardarTemporada($this->maquetarTemporada(),2);
                    }
                
                if ($programaGuardado){
                        
                        return [
                            'status' => 'success',
                            'datos del programa' => $this->obtenerPrograma($programaGuardado),
                            'temporada' => $datostemporada
                        ];
                    }else{
                    return [
                        'status' => 'failed'
                    ];
                }
            }

        }catch(\Exception $e){
            echo 'Ocurrió un error al realizar la operacion : '.$e;
        }

       
    }

    public function eliminarPrograma(int $id){
        try{
             if ($this->deshabilitarPrograma($id)){
            return[
                'status' => 'success',
                'datos_programas' => $this->obtenerProgramas(),
            ];
        }
        }catch(\Exception $e){
            echo 'Ocurrió un error al realizar la operacion : '.$e;
        }
       
    }

    public function getProgramas(){
        try{
            return[
                'status' => 'success',
                'datos_programas' => $this->obtenerProgramas(),
            ];
        }catch(\Exception $e){
            echo 'Ocurrió un error al realizar la operacion : '.$e;
        }
    }

    private function guardarVariablesPrograma($datosprograma){
        try{
            if(!empty($datosprograma['id'])){
                $this->id = $datosprograma['id'];
            }
            $this->pg_nombre = $datosprograma['nombre'];
            $this->pg_imagen = $datosprograma['imagen'];
            $this->pg_organizacion = $datosprograma['organizacion'];
            $this->pg_fecha_creacion = new \DateTime($datosprograma['fecha_creacion']);
            $this->pg_fecha_inicio = new \DateTime($datosprograma['fecha_inicio']);
            $this->pg_fecha_fin = new \DateTime($datosprograma['fecha_fin']);

            if(!empty($this->pg_nombre) && !empty($this->pg_imagen) && !empty($this->pg_organizacion) && 
                !empty($this->pg_fecha_creacion) && !empty($this->pg_fecha_inicio) && !empty($this->pg_fecha_fin)){
                return true;
            }
            return false;
        }catch(\Exception $e){
            echo 'Ocurrió un error al realizar la operacion : '.$e;
        }
    }

    private function almacenarActualizarPrograma(int $tipo){
        try{
            if($tipo == 1 || $tipo == 2){
                $programa = new Programa();
            }else{
                $programa = $this->programaRepository->find($this->id);
            }
            
            $programa->setPgNombre($this->pg_nombre);
            $programa->setPgImagen($this->pg_imagen);
            $programa->setPgOrganizacion($this->pg_organizacion);
            $programa->setPgFechaCreacion($this->pg_fecha_creacion);
            $programa->setPgFechaInicio($this->pg_fecha_inicio);
            $programa->setPgFechaFin($this->pg_fecha_fin);
            $programa->setPgEstado(true);
            if($tipo == 1 || $tipo ==2){
                $this->entityManager->persist($programa);
            }
            $this->entityManager->flush();
            if(empty($this->id)){
                $this->id = $programa->getId();
            }
            return $programa;

        }catch(\Exception $e){
            echo 'Ocurrió un error al realizar la operacion : '.$e;
        
        }

    }

    private function obtenerPrograma(Programa $programa){
        return[
            'id' => $programa->getId(),
            'Nombre' => $programa->getPgNombre(),
            'Imagen' => $programa->getPgImagen(),
            'Organizacion'=> $programa->getPgOrganizacion(),
            'Fecha_creacion' => $programa->getPgFechaCreacion(),
            'Fecha_inicio' => $programa->getPgFechaInicio(),
            'Fecha_fin' => $programa->getPgFechaFin(),
        ];
    }

    private function deshabilitarPrograma(int $id){
        try{
            $programa = $this->programaRepository->find($id);
            $programa->setPgEstado(false);
            $this->entityManager->flush();
            return true;
        }catch(\Exception $e){
            echo 'Ocurrió un error al realizar la operacion : '.$e;
        }
        
    }

    private function obtenerProgramas(){
        $programas = $this->programaRepository->findBy([
            'pg_estado' => true
        ]);

        $datosprogramas = [];
        foreach($programas as $programa){
            $datosprogramas [] = $this->obtenerPrograma($programa);
        }

        return $datosprogramas;

    }

    private function maquetarTemporada(){
        try{
            $datostemporada = [
                'id_programa' => $this->id,
                'nombre' => 'temporada de sistema N° '.$this->id,
                'fecha_creacion' => new DateTime(),
                'fecha_inicio' => new Datetime(),
                'fecha_fin' => (new DateTime())->modify('+1 month'), 
            ];
    
            return $datostemporada;

        }catch(\Exception $e){
            echo 'Ocurrió un error al realizar la operacion : '.$e;
        }
        
    }
}