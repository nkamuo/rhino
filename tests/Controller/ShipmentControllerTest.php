<?php

namespace App\Test\Controller\Shipment;

use App\Entity\Shipment\Shipment;
use App\Repository\Shipment\ShipmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShipmentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ShipmentRepository $repository;
    private string $path = '/shipment/shipment/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Shipment::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Shipment index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'shipment[owner]' => 'Testing',
            'shipment[billingAddress]' => 'Testing',
            'shipment[originAddress]' => 'Testing',
            'shipment[destinationAddress]' => 'Testing',
        ]);

        self::assertResponseRedirects('/shipment/shipment/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Shipment();
        $fixture->setOwner('My Title');
        $fixture->setBillingAddress('My Title');
        $fixture->setOriginAddress('My Title');
        $fixture->setDestinationAddress('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Shipment');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Shipment();
        $fixture->setOwner('My Title');
        $fixture->setBillingAddress('My Title');
        $fixture->setOriginAddress('My Title');
        $fixture->setDestinationAddress('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'shipment[owner]' => 'Something New',
            'shipment[billingAddress]' => 'Something New',
            'shipment[originAddress]' => 'Something New',
            'shipment[destinationAddress]' => 'Something New',
        ]);

        self::assertResponseRedirects('/shipment/shipment/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getOwner());
        self::assertSame('Something New', $fixture[0]->getBillingAddress());
        self::assertSame('Something New', $fixture[0]->getOriginAddress());
        self::assertSame('Something New', $fixture[0]->getDestinationAddress());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Shipment();
        $fixture->setOwner('My Title');
        $fixture->setBillingAddress('My Title');
        $fixture->setOriginAddress('My Title');
        $fixture->setDestinationAddress('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/shipment/shipment/');
    }
}
