<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class ContactMail
{
    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Ce champ doit être renseigné"
     * )
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Ce champ doit être renseigné"
     * )
     */
    private $secondName;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message="'{{ value }} n'est pas un email valide."
     * )
     */
    private $mail;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Ce champ doit être renseigné"
     * )
     */
    private $subject;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Ce champ doit être renseigné"
     * )
     */
    private $message;

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getSecondName(): ?string
    {
        return $this->secondName;
    }

    /**
     * @param string $secondName
     */
    public function setSecondName(string $secondName): void
    {
        $this->secondName = $secondName;
    }

    /**
     * @return string
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     */
    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return string
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
