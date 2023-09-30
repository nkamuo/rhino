<?php

namespace App\GraphQL\Shipment\Input\Document;

use App\Entity\Shipment\Document\ShipmentDocumentAttachmentType;
use App\Entity\Shipment\Document\ShipmentDocumentType;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[GQL\Input]
class ShipmentOrderAttachmentInput
{

    #[GQL\Field()]
    public ShipmentDocumentAttachmentType $type;

    #[GQL\Field(type: 'UploadFile!')]
    public UploadedFile $src;

    #[GQL\Field()]
    public ?string $caption = null;

    #[GQL\Field(type: 'Json')]
    public array $meta;
}
