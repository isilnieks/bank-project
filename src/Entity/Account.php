<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AccountRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private Client $client;

    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'fromAccount')]
    private Collection $outgoingTransactions;

    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'toAccount')]
    private Collection $incomingTransactions;

    #[ORM\Column(type: Types::DECIMAL, precision: 19, scale: 4)]
    private string $balance = '0.0000';

    #[ORM\Column(type: Types::STRING, length: 3)]
    private string $currency;

    public function __construct()
    {
        $this->outgoingTransactions = new ArrayCollection();
        $this->incomingTransactions = new ArrayCollection();
    }

    #[Groups(['account:read'])]
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalance(): float
    {
        return (float)$this->balance;
    }

    public function setBalance(float $amount): self
    {
        $this->balance = number_format($amount, 4, '.', '');
        return $this;
    }

    public function addToBalance(float $amount): self
    {
        $newBalance = $this->getBalance() + $amount;
        $this->balance = number_format($newBalance, 4, '.', '');
        return $this;
    }

    public function subtractFromBalance(float $amount): self
    {
        $newBalance = $this->getBalance() - $amount;
        $this->balance = number_format($newBalance, 4, '.', '');
        return $this;
    }

    public function hasSufficientBalance(float $amount): bool
    {
        return $this->getBalance() >= $amount;
    }

    #[Groups(['account:read'])]
    #[SerializedName('balance')]
    public function getFormattedBalance(): string
    {
        return number_format($this->getBalance(), 2, '.', '');
    }

    #[Groups(['account:read'])]
    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getTransactions(): Collection
    {
        return new ArrayCollection(
            array_merge(
                $this->outgoingTransactions->toArray(),
                $this->incomingTransactions->toArray()
            )
        );
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }
}
