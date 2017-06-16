<?php

// src/AppBundle/Entity/User.php

namespace MainBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct() {
        parent::__construct();
        // your own logic
    }

    /**
     * @ORM\Column(type="integer")
     */
    protected $n_products;

        /**
     * {@inheritdoc}
     */
    public function getN_products() {
        return $this->n_products;
    }

    /**
     * {@inheritdoc}
     */
    public function setN_products($n_products) {
        $this->n_products = $n_products;

        return $this;
    }
}
