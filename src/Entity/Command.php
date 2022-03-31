<?php

namespace App\Entity;

use App\Repository\CommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255)]
    private $adresse;

    #[ORM\Column(type: 'string', length: 255)]
    private $nameEnterprise;

    #[ORM\Column(type: 'string', length: 255)]
    private $adresseEnterprise;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $telephoneEnterprise;

    #[ORM\Column(type: 'string', length: 255)]
    private $numeroCommand;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'string', length: 255)]
    private $total;

    #[ORM\Column(type: 'string', length: 255)]
    private $ht;

    #[ORM\Column(type: 'string', length: 255)]
    private $tva;

    #[ORM\OneToMany(mappedBy: 'command', targetEntity: CommandProduct::class)]
    private $commandProducts;

    public function __construct()
    {
        $this->commandProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNameEnterprise(): ?string
    {
        return $this->nameEnterprise;
    }

    public function setNameEnterprise(string $nameEnterprise): self
    {
        $this->nameEnterprise = $nameEnterprise;

        return $this;
    }

    public function getAdresseEnterprise(): ?string
    {
        return $this->adresseEnterprise;
    }

    public function setAdresseEnterprise(string $adresseEnterprise): self
    {
        $this->adresseEnterprise = $adresseEnterprise;

        return $this;
    }

    public function getTelephoneEnterprise(): ?string
    {
        return $this->telephoneEnterprise;
    }

    public function setTelephoneEnterprise(?string $telephoneEnterprise): self
    {
        $this->telephoneEnterprise = $telephoneEnterprise;

        return $this;
    }

    public function getNumeroCommand(): ?string
    {
        return $this->numeroCommand;
    }

    public function setNumeroCommand(string $numeroCommand): self
    {
        $this->numeroCommand = $numeroCommand;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getHt(): ?string
    {
        return $this->ht;
    }

    public function setHt(string $ht): self
    {
        $this->ht = $ht;

        return $this;
    }

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(string $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * @return Collection<int, CommandProduct>
     */
    public function getCommandProducts(): Collection
    {
        return $this->commandProducts;
    }

    public function addCommandProduct(CommandProduct $commandProduct): self
    {
        if (!$this->commandProducts->contains($commandProduct)) {
            $this->commandProducts[] = $commandProduct;
            $commandProduct->setCommand($this);
        }

        return $this;
    }

    public function removeCommandProduct(CommandProduct $commandProduct): self
    {
        if ($this->commandProducts->removeElement($commandProduct)) {
            // set the owning side to null (unless already changed)
            if ($commandProduct->getCommand() === $this) {
                $commandProduct->setCommand(null);
            }
        }

        return $this;
    }
}
