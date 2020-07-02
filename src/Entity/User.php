<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="st_users", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_username", columns={"username"})})
 * @UniqueEntity(fields={"username"}, message="Un utilisateur existe déjà avec ce pseudo.")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ORM\OneToMany(targetEntity="Figure", mappedBy="author")
     * @ORM\OneToMany(targetEntity="Message", mappedBy="id_user")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $reset_token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $reset_token_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $remember_token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_connection_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public function __construct()
    {
        $this->created_at = $this->updated_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getAvatar(): ?int
    {
        return $this->avatar;
    }

    public function setAvatar(?int $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    public function getResetTokenAt(): ?\DateTimeInterface
    {
        return $this->reset_token_at;
    }

    public function setResetTokenAt(?\DateTimeInterface $reset_token_at): self
    {
        $this->reset_token_at = $reset_token_at;

        return $this;
    }

    public function getRememberToken(): ?string
    {
        return $this->remember_token;
    }

    public function setRememberToken(?string $remember_token): self
    {
        $this->remember_token = $remember_token;

        return $this;
    }

    public function getLastConnectionAt(): ?\DateTimeInterface
    {
        return $this->last_connection_at;
    }

    public function setLastConnectionAt(?\DateTimeInterface $last_connection_at): self
    {
        $this->last_connection_at = $last_connection_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
