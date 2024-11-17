<?php

namespace App\Functions\crm;

use App\Entity\CRM\Programa;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CRM\TemporadaRepository;

class TemporadaFunciones
{
    private $entityManager;
    private $temporadaRepository;

    private $id;
    private $id_programa;
    private $tem_nombre;
    private $tem_fecha_creacion;
    private $tem_fecha_inicio;
    private $tem_fecha_fin;

    public function __construct(EntityManagerInterface $entityManager, TemporadaRepository $temporadaRepository)
    {
        $this->entityManager = $entityManager;
        $this->temporadaRepository = $temporadaRepository;
    }

    
}