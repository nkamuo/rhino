<?php

namespace App\GraphQL\Account\Input;

use App\Entity\Account\User;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
class UserInput
{
    #[GQL\Field()]
    public ?string $firstName = null;

    #[GQL\Field()]
    public ?string $lastName = null;

    #[GQL\Field()]
    public ?string $email = null;

    #[GQL\Field()]
    public ?string $phone = null;

    #[GQL\Field(type: 'UploadFile')]
    public ?UploadedFile $avatar = null;

    #[GQL\Field()]
    public ?string $password = null;

    #[Assert\Choice(options: ['SHIPPER', 'TRUCKER'])]
    #[GQL\Field()]
    public ?string $type = null;

    public function build(User $user): void
    {
        if ($this->firstName)
            $user->setFirstName($this->firstName);

        if ($this->lastName)
            $user->setLastName($this->lastName);

        if ($this->email)
            $user->setEmail($this->email);

        if ($this->phone)
            $user->setPhone($this->phone);
            // ->setTitle($this->title)
            // ->setDescription($this->description)
        ;
    }
}
