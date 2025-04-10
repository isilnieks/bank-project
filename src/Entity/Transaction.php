<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['transaction:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'outgoingTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['transaction:read'])]
    private Account $fromAccount;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'incomingTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['transaction:read'])]
    private Account $toAccount;

    #[ORM\Column(type: Types::STRING, length: 3)]
    #[Groups(['transaction:read'])]
    private string $fromCurrency;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 6, nullable: true)]
    #[Groups(['transaction:read'])]
    private ?string $exchangeRate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    #[Groups(['transaction:read'])]
    private string $fromAmount;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    #[Groups(['transaction:read'])]
    private string $toAmount;

    #[ORM\Column(type: Types::STRING, length: 3)]
    #[Groups(['transaction:read'])]
    private string $toCurrency;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromAccount(): Account
    {
        return $this->fromAccount;
    }

    public function setFromAccount(Account $fromAccount): self
    {
        $this->fromAccount = $fromAccount;
        return $this;
    }

    public function getToAccount(): Account
    {
        return $this->toAccount;
    }

    public function setToAccount(Account $toAccount): self
    {
        $this->toAccount = $toAccount;
        return $this;
    }

    public function getFromCurrency(): string
    {
        return $this->fromCurrency;
    }

    public function setFromCurrency(string $fromCurrency): self
    {
        $this->fromCurrency = $fromCurrency;
        return $this;
    }

    public function getExchangeRate(): ?float
    {
        return $this->exchangeRate ? (float)$this->exchangeRate : null;
    }
    public function setExchangeRate(?float $exchangeRate): self
    {
        $this->exchangeRate = $exchangeRate ? number_format($exchangeRate, 6, '.', '') : null;
        return $this;
    }

    public function getFromAmount(): float
    {
        return (float)$this->fromAmount;
    }

    public function setFromAmount(float $fromAmount): self
    {
        $this->fromAmount = number_format($fromAmount, 2, '.', '');
        return $this;
    }

    public function getToAmount(): float
    {
        return (float)$this->toAmount;
    }

    public function setToAmount(float $toAmount): self
    {
        $this->toAmount = number_format($toAmount, 2, '.', '');
        return $this;
    }

    public function getToCurrency(): string
    {
        return $this->toCurrency;
    }

    public function setToCurrency(string $toCurrency): self
    {
        $this->toCurrency = $toCurrency;
        return $this;
    }
}
