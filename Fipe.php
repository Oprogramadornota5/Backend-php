<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\FipeRepository")]
class Fipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:50)]
    private ?string $codigoFipe = null;

    #[ORM\Column(type:"string", length:100)]
    private ?string $marca = null;

    #[ORM\Column(type:"string", length:100)]
    private ?string $modelo = null;

    #[ORM\Column(type:"integer")]
    private ?int $anoModelo = null;

    #[ORM\Column(type:"decimal", precision:10, scale:2)]
    private ?string $precoMedio = null;

    #[ORM\Column(type:"string", length:20)]
    private ?string $mesReferencia = null;

    #[ORM\Column(type:"datetime")]
    private \DateTimeInterface $criadoEm;

    #[ORM\Column(type:"datetime", nullable:true)]
    private ?\DateTimeInterface $atualizadoEm = null;

    public function __construct()
    {
        $this->criadoEm = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigoFipe(): ?string
    {
        return $this->codigoFipe;
    }

    public function setCodigoFipe(string $codigoFipe): self
    {
        $this->codigoFipe = $codigoFipe;
        return $this;
    }

    public function getMarca(): ?string
    {
        return $this->marca;
    }

    public function setMarca(string $marca): self
    {
        $this->marca = $marca;
        return $this;
    }

    public function getModelo(): ?string
    {
        return $this->modelo;
    }

    public function setModelo(string $modelo): self
    {
        $this->modelo = $modelo;
        return $this;
    }

    public function getAnoModelo(): ?int
    {
        return $this->anoModelo;
    }

    public function setAnoModelo(int $anoModelo): self
    {
        $this->anoModelo = $anoModelo;
        return $this;
    }

    public function getPrecoMedio(): ?string
    {
        return $this->precoMedio;
    }

    public function setPrecoMedio(string $precoMedio): self
    {
        $this->precoMedio = $precoMedio;
        return $this;
    }

    public function getMesReferencia(): ?string
    {
        return $this->mesReferencia;
    }

    public function setMesReferencia(string $mesReferencia): self
    {
        $this->mesReferencia = $mesReferencia;
        return $this;
    }

    public function getCriadoEm(): \DateTimeInterface
    {
        return $this->criadoEm;
    }

    public function setCriadoEm(\DateTimeInterface $criadoEm): self
    {
        $this->criadoEm = $criadoEm;
        return $this;
    }

    public function getAtualizadoEm(): ?\DateTimeInterface
    {
        return $this->atualizadoEm;
    }

    public function setAtualizadoEm(?\DateTimeInterface $atualizadoEm): self
    {
        $this->atualizadoEm = $atualizadoEm;
        return $this;
    }
}
