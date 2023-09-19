<?php

namespace App\Entity\Addressing;

use App\Entity\Account\User;
use App\Repository\Addressing\UserAddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use GraphQL\Type\Definition\Type;
use Overblog\GraphQLBundle\Resolver\TypeResolver;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: UserAddressRepository::class)]
class UserAddress extends Address
{
    
    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'addressess')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
    public function resolveGQLType(TypeResolver $typeResolver): Type
    {
        return $typeResolver->resolve('UserAddress');
    }
}
