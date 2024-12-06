<?php
class User
{
    private $id;             // Identifiant unique (auto-incrémenté dans la base de données)
    private $firstName;      // Prénom de l'utilisateur
    private $lastName;       // Nom de l'utilisateur
    private $dateOfBirth;    // Date de naissance
    private $email;          // Adresse e-mail
    private $password;       // Mot de passe haché
    private $location;       // Localisation
    private $mobile;         // Numéro de téléphone
    private $gender;         // Genre (male/female)
    private $role;           // Rôle (client, farmer, beekeeper, etc.)

    // Constructeur pour initialiser les propriétés de l'utilisateur
    public function __construct($firstName, $lastName, $dateOfBirth, $email, $password, $location, $mobile, $gender, $role)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->dateOfBirth = $dateOfBirth;
        $this->email = $email;
        $this->password = $password;
        $this->location = $location;
        $this->mobile = $mobile;
        $this->gender = $gender;
        $this->role = $role;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getRole()
    {
        return $this->role;
    }

    // setters : besh tbadel 
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    // Méthode pour retourner les informations sous forme de tableau (utile pour JSON ou base de données)
    public function toArray()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'date_of_birth' => $this->dateOfBirth,
            'email' => $this->email,
            'password' => $this->password,
            'location' => $this->location,
            'mobile' => $this->mobile,
            'gender' => $this->gender,
            'role' => $this->role
        ];
    }
}
