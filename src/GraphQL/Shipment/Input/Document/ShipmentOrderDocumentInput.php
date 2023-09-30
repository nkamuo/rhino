<?php
namespace App\GraphQL\Shipment\Input\Document;

use App\Entity\Shipment\Document\ShipmentDocumentType;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input]
class ShipmentOrderDocumentInput{

    #[GQL\Field()]
    public ShipmentDocumentType $type;

    #[GQL\Field()]
    public ?string $label = null;

    /**
     * @var ShipmentOrderAttachmentInput[]
     */
    #[GQL\Field()]
    public array $attachments;
    
    #[GQL\Field(type: 'Json')]
    public array $meta = [];
}