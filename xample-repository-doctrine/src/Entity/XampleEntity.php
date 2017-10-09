<?php

namespace Reliv\PipeRat2\XampleRepositoryDoctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author James Jervis - https://github.com/jerv13
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="pipe_rat_2_xample")
 */
class XampleEntity
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $example;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getExample(): string
    {
        return (string)$this->example;
    }

    /**
     * @param string $example
     */
    public function setExample(string $example)
    {
        $this->example = $example;
    }
}
