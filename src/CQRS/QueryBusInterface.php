<?php
namespace App\CQRS;


interface QueryBusInterface extends MessageBusInterface{
    public function query(object $query, array $stamps = []): mixed;
}