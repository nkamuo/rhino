<?php

namespace App\GraphQL\Account\Input;

use App\Entity\Account\Gender;
use App\Entity\Document\DriverLicense;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
class DriverLicenseInput
{

    #[Assert\Length(min: 3)]
    #[GQL\Field()]
    public string $fullName;


    #[Assert\Length(min: 3)]
    #[GQL\Field()]
    public string $licenseNumber;

    #[Assert\Length(min: 3)]
    #[GQL\Field()]
    public ?string $class = null;

    // #[Assert\Date()]
    #[GQL\Field(type: 'Date')]
    public ?\DateTimeInterface $dob = null;
    
    // #[Assert\Date()]
    #[GQL\Field(type: 'Date!')]
    public ?\DateTimeInterface $expiryDate = null;

    // #[Assert\Date()]
    #[GQL\Field(type: 'Date!')]
    public ?\DateTimeInterface $issuanceDate = null;


    #[Assert\Length(min: 2)]
    #[GQL\Field()]
    public ?string $issuanceState = null;

    
    #[Assert\Length(exactly: 2)]
    #[GQL\Field()]
    public string $countryCode;


    #[GQL\Field()]
    public ?Gender $gender;

    #[GQL\Field(type: 'UploadFile')]
    public UploadedFile $file;

    public function build(DriverLicense $doc): void
    {
        if ($this->dob)
            $doc->setDob($this->dob);
        // if ($this->gender)
        //     $doc->setGender($this->gender);

        if ($this->fullName)
            $doc->setFullName($this->fullName);

        if ($this->licenseNumber)
            $doc->setLicenseNumber($this->licenseNumber);
            
        if ($this->countryCode)
        $doc->setCountryCode($this->countryCode);

        if ($this->issuanceDate)
            $doc->setIssueDate($this->issuanceDate);

        if ($this->expiryDate)
            $doc->setExpiresAt($this->expiryDate);

        if ($this->issuanceState)
            $doc->setIssuanceState($this->issuanceState);
    }
}
