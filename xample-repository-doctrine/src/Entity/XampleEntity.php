<?php

namespace Reliv\PipeRat2\XampleRepositoryDoctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author James Jervis - https://github.com/jerv13
 *
 * @ORM\Entity (repositoryClass="Rcm\Repository\Site")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="rcm_sites")
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
}
