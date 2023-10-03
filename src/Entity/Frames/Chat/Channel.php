<?php

namespace App\Entity\Frames\Chat;

use App\Repository\Frames\Chat\ChannelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:'frames_channels')]
#[ORM\Entity(repositoryClass: ChannelRepository::class)]
class Channel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
