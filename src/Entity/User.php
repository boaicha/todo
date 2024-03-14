<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;


    #[ORM\Column(type: Types::STRING, length: 25, unique: true)]
    #[Assert\NotBlank(message:"Vous devez saisir un nom d'utilisateur.")]
    private String $username;
    #[ORM\Column(type: Types::STRING, length: 64)]
    private String $password;


    #[ORM\Column(type: Types::STRING, length: 60, unique: true)]
    #[Assert\NotBlank(message:"Vous devez saisir une adresse email.")]
    #[Assert\Email(
        message: "Le format de l'adresse n'est pas correcte.",
    )]
    private String $email;

    #[ORM\Column]
    private array $role = [];

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'user')]
    private Collection $tasks;

    public function __construct()
    {
        $this->role = ["ROLE_USER"];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }


    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(String $username): void
    {
        $this->username = $username;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }


    public function setPassword(String $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }


    public function setEmail(String $email): void
    {
        $this->email = $email;
    }



    public function getRoles(): array
{
        return $this->role;
    }

    public function eraseCredentials() : void
    {
    }

    public function getUserIdentifier(): string
    {
       return $this->username;
    }

    public function getRole(): array
    {
        return $this->role;
    }

    public function setRole(array $role): void
    {
        $this->role = $role;
    }




}