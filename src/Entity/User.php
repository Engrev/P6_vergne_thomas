<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

/**
 * Class User
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="st_users", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_username", columns={"username"})})
 * @UniqueEntity(fields={"username"}, message="Un utilisateur existe déjà avec ce pseudo.")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ORM\OneToMany(targetEntity=Figure::class, mappedBy="user", orphanRemoval=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Type("string")
     * @Assert\Length(min = 2, max = 100, allowEmptyString = false)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type("string")
     * @Assert\Length(min = 2, max = 255, allowEmptyString = false)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type("string")
     * @Assert\Length(min = 8, allowEmptyString = false)
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Figure::class, mappedBy="user", orphanRemoval=true)
     */
    private $figures;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $isVerifiedAt;

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

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="user", orphanRemoval=true)
     */
    private $messages;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->created_at = $this->updated_at = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
        $this->figures = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
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
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
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
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    /**
     * @param array|string[] $roles
     *
     * @return $this
     */
    public function setRoles(array $roles = ['ROLE_USER']): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar
     *
     * @return $this
     */
    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Figure[]
     */
    public function getFigures(): Collection
    {
        return $this->figures;
    }

    /**
     * @param Figure $figure
     *
     * @return $this
     */
    public function addFigure(Figure $figure): self
    {
        if (!$this->figures->contains($figure)) {
            $this->figures[] = $figure;
            $figure->setUser($this);
        }

        return $this;
    }

    /**
     * @param Figure $figure
     *
     * @return $this
     */
    public function removeFigure(Figure $figure): self
    {
        if ($this->figures->contains($figure)) {
            $this->figures->removeElement($figure);
            // set the owning side to null (unless already changed)
            if ($figure->getUser() === $this) {
                $figure->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return $this
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * @param bool $isVerified
     *
     * @return $this
     */
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getIsVerifiedAt(): ?\DateTimeInterface
    {
        return $this->isVerifiedAt;
    }

    /**
     * @param \DateTimeInterface|null $isVerifiedAt
     *
     * @return $this
     */
    public function setIsVerifiedAt(/*?\DateTimeInterface $isVerifiedAt*/): self
    {
        $this->isVerifiedAt = new \DateTime("now", new \DateTimeZone("Europe/Paris"));

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getLastConnectionAt(): ?\DateTimeInterface
    {
        return $this->last_connection_at;
    }

    /**
     * @param \DateTimeInterface|null $last_connection_at
     *
     * @return $this
     */
    public function setLastConnectionAt(/*?\DateTimeInterface $last_connection_at*/): self
    {
        $this->last_connection_at = new \DateTime("now", new \DateTimeZone("Europe/Paris"));

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param \DateTimeInterface $created_at
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    /**
     * @return $this
     */
    public function setUpdatedAt(): self
    {
        $this->updated_at = new \DateTime("now", new \DateTimeZone("Europe/Paris"));

        return $this;
    }

    /**
     * @return string|void|null
     */
    public function getSalt()
    {}

    /**
     *
     */
    public function eraseCredentials()
    {}

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->roles,
            $this->avatar,
            $this->last_connection_at,
            $this->created_at,
            $this->updated_at
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->roles,
            $this->avatar,
            $this->last_connection_at,
            $this->created_at,
            $this->updated_at
            ) = unserialize($serialized, ['allowed_classes' => false]); // Ne pas instancier la classe
    }

    /**
     * @param int $length
     *
     * @return false|string
     */
    private function getToken(int $length)
    {
        $alphabet = '0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN';
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    /**
     * @param Message $message
     *
     * @return $this
     */
    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    /**
     * @param Message $message
     *
     * @return $this
     */
    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }
}
