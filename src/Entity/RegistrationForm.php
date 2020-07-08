<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegistrationForm
 * @package App\Entity
 */
class RegistrationForm
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min="1", max="100")
     */
    private $username;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min="1", max="255", allowEmptyString = false)
     * @Assert\Email(mode="html5")
     */
    private $email;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min="8", allowEmptyString = false)
     */
    private $password;

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     *
     * @return Registration
     */
    public function setUsername(?string $username): RegistrationForm
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     *
     * @return Registration
     */
    public function setEmail(?string $email): RegistrationForm
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     *
     * @return Registration
     */
    public function setPassword(?string $password): RegistrationForm
    {
        $this->password = $password;

        return $this;
    }
}