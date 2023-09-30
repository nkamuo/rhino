<?php
namespace App\GraphQL\Shipment\Input;

use App\GraphQL\Shipment\Input\Document\ShipmentOrderDocumentInput;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input()]
class ShipmentOrderNodeExecutionInput
{
    /**
     * @var ShipmentOrderDocumentInput[]|null
     */
    #[GQL\Field()]
   public ?array $documents = null;

    #[GQL\Field()]
   public ShipmentOrderDocumentInput $document;
}
