<?php

namespace App\GraphQL\Type\Scalar;

use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;
use League\Flysystem\Filesystem;
use Overblog\GraphQLBundle\Annotation\Description;
use Overblog\GraphQLBundle\Annotation\Scalar;
use League\Flysystem\MountManager;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use function Symfony\Component\Clock\now;

// #[Scalar('Time')]
// #[Description('Represents the time instance for marking events')]
class FileType extends ScalarType
{

    public function __construct(
        private MountManager $mountManager,
        private Filesystem $filesystem,
    ) {
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function serialize($value): mixed
    {
        // $anHour = new \DateInterval('PT1H');
        // $expires = now()->add($anHour);
        // if (preg_match('~^\w+:\/\/~i', $value)) {
        //     return $this->mountManager->publicUrl($value);
        //     // return $this->mountManager->temporaryUrl($value, $expires);
        // }
        // return $this->filesystem->publicUrl($value);
        // // return $this->filesystem->temporaryUrl($value, $expires);

        return $value; 
    }

    /**
     * @param UploadedFile $value
     *
     * @return mixed
     */
    public function parseValue($value): void
    {
        // return new \DateTimeImmutable($value);
    }

    /**
     * @param Node $valueNode
     *
     * @return UploadedFile|null
     */
    public function parseLiteral($valueNode, array $variables = null): void
    {
        // return new \DateTimeImmutable($valueNode->value);
    }
}
