<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $createdAt;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING)]
    private string $title;

    #[ORM\Column(type: Types::TEXT)]
    private string $content;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isDone;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tasks')]
    private User|null $user = null;

    public function __construct()
    {
        $this->isDone = false;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }


    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    public function getTitle(): string
    {
        return $this->title;
    }


    public function setTitle(String $title): void
    {
        $this->title = $title;
    }


    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(String $content): void
    {
        $this->content = $content;
    }


    public function getIsDone(): bool
    {
        return $this->isDone;
    }

    public function toggle(bool $isDoneNew): void
    {
        $this->isDone = $isDoneNew;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }





}