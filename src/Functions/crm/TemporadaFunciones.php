<?php

namespace App\Functions\crm;

use App\Entity\CRM\Programa;
use Datetime;
use App\Entity\CRM\Temporada;
use App\Repository\CRM\ProgramaRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CRM\TemporadaRepository;

class TemporadaFunciones
{
    private $entityManager;
    private $temporadaRepository;
    private $programaRepository;
    private $colectivaFunciones;

    private $id;
    private $id_programa;
    private $tem_nombre;
    private $tem_fecha_creacion;
    private $tem_fecha_inicio;
    private $tem_fecha_fin;
    private $programa;

    public function __construct(EntityManagerInterface $entityManager, TemporadaRepository $temporadaRepository, ProgramaRepository $programaRepository, ColectivaFunciones $colectivaFunciones)
    {
        $this->entityManager = $entityManager;
        $this->temporadaRepository = $temporadaRepository;
        $this->programaRepository = $programaRepository;
        $this->colectivaFunciones = $colectivaFunciones;
    }

    public function guardarTemporada(array $datos, int $tipoperacion)
    {
        try{
            if($this->guardarVariablesTemporada($datos)){
                $datoscolectiva = [];
                
                    $temporadaGuardada = $this->almacenarActualizarTemporada($tipoperacion);
                    if($tipoperacion == 2){
                        $datoscolectiva = $this->colectivaFunciones->guardarColectiva($this->maquetarColectiva(),2);
                    }
                
                if ($temporadaGuardada){
                        
                        return [
                            'status' => 'success',
                            'datos de temporada' => $this->obtenerTemporada($temporadaGuardada),
                            'colectiva' => $datoscolectiva
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

    public function eliminarTemporada(int $id){
        try{
            if ($this->deshabilitarTemporada($id)){
            return[
                'status' => 'success',
                'datos_temporadas' => $this->obtenerTemporadas(),
            ];
        }
        }catch(\Exception $e){
            echo 'Ocurrió un error al realizar la operacion : '.$e;
        }
    
    }

    public function getTemporadas(){
        try{
            return[
                'status' => 'success',
                'datos_temporadas' => $this->obtenerTemporadas(),
            ];
        }catch(\Exception $e){
            echo 'Ocurrió un error al realizar la operacion : '.$e;
        }
    }

    private function guardarVariablesTemporada($datostemporada){
        try{
            if(!empty($datostemporada['id'])){
                $this->id = $datostemporada['id'];
            }
            $this->programa = $this->programaRepository->find($datostemporada['id_programa']);
            $this->tem_nombre = $datostemporada['nombre'];
            $this->tem_fecha_creacion = $datostemporada['fecha_creacion'];
            $this->tem_fecha_inicio = $datostemporada['fecha_inicio'];
            $this->tem_fecha_fin = $datostemporada['fecha_fin'];

            if(!empty($this->tem_nombre) && !empty($this->tem_fecha_creacion) && !empty($this->tem_fecha_inicio) && 
                !empty($this->tem_fecha_fin)){
                return true;
            }
            return false;
        }catch(\Exception $e){
            echo 'Ocurrió un error al realizar la operacion : '.$e;
        }
    }

    private function almacenarActualizarTemporada(int $tipo){
        try{
            if($tipo == 1 || $tipo ==2){
                $temporada = new Temporada();
            }else{
                $temporada = $this->temporadaRepository->find($this->id);
            }
            $temporada->setPrograma($this->programa);
            $temporada->setTemNombre($this->tem_nombre);
            $temporada->setTemFechaCreacion($this->tem_fecha_creacion);
            $temporada->setTemFechaInicio($this->tem_fecha_inicio);
            $temporada->setTemFechaFin($this->tem_fecha_fin);
            $temporada->setTemEstadoSys(0);
            if($tipo == 2){
                $temporada->setTemEstadoSys(1);
            }
            $temporada->setTemEstado(true);
            if($tipo == 1 || $tipo == 2){
                $this->entityManager->persist($temporada);
            }
            $this->entityManager->flush();
            if(empty($this->id)){
                $this->id = $temporada->getId();
            }
            return $temporada;

        }catch(\Exception $e){
            echo 'Ocurrió un error al realizar la operacion : '.$e;
        
        }

    }

    private function obtenerTemporada(Temporada $temporada){
        return[
            'id' => $temporada->getId(),
            'Nombre' => $temporada->getTemNombre(),
            'Fecha_creacion' => $temporada->getTemFechaCreacion(),
            'Fecha_inicio' => $temporada->getTemFechaInicio(),
            'Fecha_fin' => $temporada->getTemFechaFin()
        ];
    }

    private function deshabilitarTemporada(int $id){
        try{
            $temporada = $this->temporadaRepository->find($id);
            $temporada->setTemEstado(false);
            $this->entityManager->flush();
            return true;
        }catch(\Exception $e){
            echo 'Ocurrió un error al realizar la operacion : '.$e;
        }
        
    }

    private function obtenerTemporadas(){
        $temporadas = $this->temporadaRepository->findBy([
            'pg_estado' => true
        ]);

        $datosprogramas = [];
        foreach($temporadas as $temporada){
            $datosprogramas [] = $this->obtenerTemporada($temporada);
        }

        return $datosprogramas;

    }

    private function maquetarColectiva(){
        try{
            $datoscolectiva = [
                'id_temporada' => $this->id,
                'nombre' => 'Colectiva de sistema N° '.$this->id,
                'miembros' => 'Miembros del sistema',
                'fecha_creacion' => new Datetime(),
            ];
    
            return $datoscolectiva;

        }catch(\Exception $e){
            echo 'Ocurrió un error al realizar la operacion : '.$e;
        }
        
    }
}