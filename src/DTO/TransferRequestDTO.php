<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

final class TransferRequestDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'From account is required')]
        #[SerializedName('from_account_id')]
        public int $fromAccountId,

        #[Assert\NotBlank(message: 'To account is required')]
        #[SerializedName('to_account_id')]
        public int $toAccountId,

        #[Assert\NotBlank(message: 'Amount is required')]
        #[Assert\Positive(message: 'Amount must be positive')]
        public float $amount,

        #[Assert\NotBlank(message: 'Currency is required')]
        public string $currency
    ) {}
}
