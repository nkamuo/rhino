<?php

namespace App\Message\Chat\Message;


class ChatMessageAttachmentMutationCommand
{

    private ?string $uri = null;          //VERY MUCH REQUIRED

    public function __construct(string $uri)
    {
        $this->uri = $uri;
    }



    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }
}
