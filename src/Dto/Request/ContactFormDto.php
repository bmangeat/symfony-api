<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ContactFormDto 
{
    #[Assert\NotBlank()]
    public string $name;
    #[Assert\NotBlank()]
    public string $email;
    #[Assert\NotBlank()]
    public string $message;
    public string $service;

    public function getName() : string
    {
        return $this->name;
    }
    
    public function getEmail() : string
    {
        return $this->email;
    }
    
    public function getMessage() : string
    {
        return $this->message;
    }
}