<?php

namespace App\Entity\Account;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type()]
#[ORM\Embeddable()]
class ReviewSummary
{

    #[GQL\Field(type:'Float')]
    #[ORM\Column(type: Types::DECIMAL, precision: 2, scale: 1, nullable: true)]
    private ?string $rating = null;

    #[GQL\Field()]
    #[ORM\Column(
        options:[
            'default' => 0
        ]
    )]
    private ?int $count = 0;

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(?string $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): static
    {
        $this->count = $count;

        return $this;
    }
}
