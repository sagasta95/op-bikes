<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MainBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 */
class Category{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $created_by;


    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * {@inheritdoc}
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function getCreatedBy() {
        return $this->created_by;
    }
    /**
     * {@inheritdoc}
     */
    public function setCreatedBy($user) {
        return $this->created_by = $user;
    }

    /**
     * @return string
     */
    public function __toString() {
        return (string) $this->getNombre();
    }

}
