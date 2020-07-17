<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class File
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=FileRepository::class)
 * @ORM\Table(name="st_files")
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Figure::class, inversedBy="files")
     * @ORM\JoinColumn(nullable=false)
     */
    private $figure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $uploaded_name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $uploaded_at;

    /**
     * File constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->uploaded_at = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Figure|null
     */
    public function getFigure(): ?Figure
    {
        return $this->figure;
    }

    /**
     * @param Figure|null $figure
     *
     * @return $this
     */
    public function setFigure(?Figure $figure): self
    {
        $this->figure = $figure;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUploadedName(): ?string
    {
        return $this->uploaded_name;
    }

    /**
     * @param string $uploaded_name
     *
     * @return $this
     */
    public function setUploadedName(string $uploaded_name): self
    {
        $this->uploaded_name = $uploaded_name;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUploadedAt(): ?\DateTimeInterface
    {
        return $this->uploaded_at;
    }

    /**
     * @param \DateTimeInterface $uploaded_at
     *
     * @return $this
     */
    public function setUploadedAt(\DateTimeInterface $uploaded_at): self
    {
        $this->uploaded_at = $uploaded_at;

        return $this;
    }
}
