<?php

namespace App\Test\Controller\Addressing;

use App\Entity\Addressing\UserAddress;
use App\Repository\Addressing\UserAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserAddressControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserAddressRepository $repository;
    private string $path = '/user/address/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(UserAddress::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('UserAddress index');

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
            'user_address[firstName]' => 'Testing',
            'user_address[lastName]' => 'Testing',
            'user_address[phoneNumber]' => 'Testing',
            'user_address[emailAddress]' => 'Testing',
            'user_address[company]' => 'Testing',
            'user_address[countryCode]' => 'Testing',
            'user_address[provinceCode]' => 'Testing',
            'user_address[provinceName]' => 'Testing',
            'user_address[city]' => 'Testing',
            'user_address[street]' => 'Testing',
            'user_address[postcode]' => 'Testing',
            'user_address[updatedAt]' => 'Testing',
            'user_address[createdAt]' => 'Testing',
            'user_address[owner]' => 'Testing',
        ]);

        self::assertResponseRedirects('/user/address/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new UserAddress();
        $fixture->setFirstName('My Title');
        $fixture->setLastName('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setEmailAddress('My Title');
        $fixture->setCompany('My Title');
        $fixture->setCountryCode('My Title');
        $fixture->setProvinceCode('My Title');
        $fixture->setProvinceName('My Title');
        $fixture->setCity('My Title');
        $fixture->setStreet('My Title');
        $fixture->setPostcode('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setOwner('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('UserAddress');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new UserAddress();
        $fixture->setFirstName('My Title');
        $fixture->setLastName('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setEmailAddress('My Title');
        $fixture->setCompany('My Title');
        $fixture->setCountryCode('My Title');
        $fixture->setProvinceCode('My Title');
        $fixture->setProvinceName('My Title');
        $fixture->setCity('My Title');
        $fixture->setStreet('My Title');
        $fixture->setPostcode('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setOwner('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'user_address[firstName]' => 'Something New',
            'user_address[lastName]' => 'Something New',
            'user_address[phoneNumber]' => 'Something New',
            'user_address[emailAddress]' => 'Something New',
            'user_address[company]' => 'Something New',
            'user_address[countryCode]' => 'Something New',
            'user_address[provinceCode]' => 'Something New',
            'user_address[provinceName]' => 'Something New',
            'user_address[city]' => 'Something New',
            'user_address[street]' => 'Something New',
            'user_address[postcode]' => 'Something New',
            'user_address[updatedAt]' => 'Something New',
            'user_address[createdAt]' => 'Something New',
            'user_address[owner]' => 'Something New',
        ]);

        self::assertResponseRedirects('/user/address/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getFirstName());
        self::assertSame('Something New', $fixture[0]->getLastName());
        self::assertSame('Something New', $fixture[0]->getPhoneNumber());
        self::assertSame('Something New', $fixture[0]->getEmailAddress());
        self::assertSame('Something New', $fixture[0]->getCompany());
        self::assertSame('Something New', $fixture[0]->getCountryCode());
        self::assertSame('Something New', $fixture[0]->getProvinceCode());
        self::assertSame('Something New', $fixture[0]->getProvinceName());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getStreet());
        self::assertSame('Something New', $fixture[0]->getPostcode());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getOwner());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new UserAddress();
        $fixture->setFirstName('My Title');
        $fixture->setLastName('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setEmailAddress('My Title');
        $fixture->setCompany('My Title');
        $fixture->setCountryCode('My Title');
        $fixture->setProvinceCode('My Title');
        $fixture->setProvinceName('My Title');
        $fixture->setCity('My Title');
        $fixture->setStreet('My Title');
        $fixture->setPostcode('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setOwner('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/user/address/');
    }
}
