<?php

// src/AppBundle/Entity/User.php

namespace MainBundle\Entity;
use MainBundle\Entity\User;
use MainBundle\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cart")
 */
class Cart {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $product;

    /**
     * {@inheritdoc}
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * {@inheritdoc}
     */
    public function setProduct($product) {
        $this->product = $product;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser($user) {
        $this->user = $user;

        return $this;
    }

}
