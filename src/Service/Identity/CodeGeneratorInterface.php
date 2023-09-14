<?php

namespace App\Service\Identity;

interface CodeGeneratorInterface
{
    public function generateCode(?string $prefix = null, ?int $length = null): string;
}
