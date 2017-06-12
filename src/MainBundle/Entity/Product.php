<?php

// src/AppBundle/Entity/User.php

namespace MainBundle\Entity;

use MainBundle\Entity\User;
use MainBundle\Entity\Category;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product {

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
     * @ORM\Column(type="string", length=100)
     */
    protected $descripcion;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $img;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $precio;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $category;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $created_by;

    /**
     * @ORM\Column(type="date")
     */
    
    protected $created_at;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $updated_at;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $buy_at;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="buy_by", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $buy_by;

    
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
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrecio() {
        return $this->precio;
    }

    /**
     * {@inheritdoc}
     */
    public function setPrecio($precio) {
        $this->precio = $precio;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getImg() {
        return $this->img;
    }

    /**
     * {@inheritdoc}
     */
    public function setImg($img) {
        $this->img = $img;

        return $this;
    }
    
        /**
     * {@inheritdoc}
     */
    public function getCategory() {
        return $this->category;
    }
    /**
     * {@inheritdoc}
     */
    public function setCategory($category) {
        return $this->category = $category;
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
     * {@inheritdoc}
     */
    public function getBuyBy() {
        return $this->buy_by;
    }
    /**
     * {@inheritdoc}
     */
    public function setBuyBy($user) {
        return $this->buy_by = $user;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCreatedAt() {
        return $this->created_at;
    }
    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($fecha) {
        return $this->created_at = $fecha;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt() {
        return $this->updated_at;
    }
    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt($fecha) {
        return $this->updated_at = $fecha;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBuyAt() {
        return $this->buy_at;
    }
    /**
     * {@inheritdoc}
     */
    public function setBuyAt($fecha) {
        return $this->buy_at = $fecha;
    }

    /**
     * @return string
     */
    public function __toString() {
        return (string) $this->getNombre();
    }

}
