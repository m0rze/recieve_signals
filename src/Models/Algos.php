<?php

namespace App\Models;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class Algos
{

    protected $entityManager;
    protected $repo;
    protected $kernel;

    public function __construct(KernelInterface $kernel, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
    }

    abstract public function goAlgo();
}