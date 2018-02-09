<?php

namespace Reliv\PipeRat2\XampleRepositoryDoctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    protected $int = 42;
    protected $null = null;
    protected $bool = true;
    protected $object;
    protected $objectNull;
    protected $array = ['this', 'is', 'an', 'array'];
    protected $associativeArray;
    protected $basicCollection;
    protected $objectCollection;

    public function __construct()
    {
        $this->object = new XampleObject();
        $this->basicCollection = new ArrayCollection(
            ['this', 'is', 'a', 'collection']
        );

        $this->objectCollection = new ArrayCollection(
            [$this->object]
        );

        $this->associativeArray = [
            'prop-string' => 'string',
            'prop-int' => 13,
            'prop-null' => null,
            'prop-bool' => false,
            'prop-object' => $this->object,
            'prop-object-null' => null,
            'prop-array' => $this->array,
            'prop-basic-collection' => $this->basicCollection,
            'prop-object-collection' => $this->objectCollection,
        ];
    }

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

    /**
     * @return bool
     */
    public function isExample(): bool
    {
        return true;
    }

    /**
     * @return int
     */
    public function getInt(): int
    {
        return $this->int;
    }

    /**
     * @return null
     */
    public function getNull()
    {
        return $this->null;
    }

    /**
     * @return bool
     */
    public function isBool(): bool
    {
        return $this->bool;
    }


    /**
     * @return XampleObject
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return mixed
     */
    public function getObjectNull()
    {
        return $this->objectNull;
    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        return $this->array;
    }

    /**
     * @return array
     */
    public function getAssociativeArray(): array
    {
        return $this->associativeArray;
    }

    /**
     * @return Collection
     */
    public function getBasicCollection(): Collection
    {
        return $this->basicCollection;
    }

    /**
     * @return Collection
     */
    public function getObjectCollection(): Collection
    {
        return $this->objectCollection;
    }
}
