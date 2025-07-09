<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Vehicle
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:100)]
    private string $marca;

    #[ORM\Column(type:"string", length:100)]
    private string $modelo;

    #[ORM\Column(type:"integer")]
    private int $ano;

    #[ORM\Column(type:"float")]
    private float $preco;

    #[ORM\Column(type:"text", nullable:true)]
    private ?string $descricao;

    #[ORM\Column(type:"boolean")]
    private bool $disponivel;

    // getters e setters ...
    public function getId(): ?int { return $this->id; }
    public function getMarca(): string { return $this->marca; }
    public function setMarca(string $marca): self { $this->marca = $marca; return $this; }
    public function getModelo(): string { return $this->modelo; }
    public function setModelo(string $modelo): self { $this->modelo = $modelo; return $this; }
    public function getAno(): int { return $this->ano; }
    public function setAno(int $ano): self { $this->ano = $ano; return $this; }
    public function getPreco(): float { return $this->preco; }
    public function setPreco(float $preco): self { $this->preco = $preco; return $this; }
    public function getDescricao(): ?string { return $this->descricao; }
    public function setDescricao(?string $descricao): self { $this->descricao = $descricao; return $this; }
    public function isDisponivel(): bool { return $this->disponivel; }
    public function setDisponivel(bool $disponivel): self { $this->disponivel = $disponivel; return $this; }
}
