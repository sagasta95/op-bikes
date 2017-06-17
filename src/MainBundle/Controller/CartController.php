<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MainBundle\Entity\Cart;

class CartController extends Controller {

    public function showAction() {

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $categorias = $em->getRepository('MainBundle:Category')->findAll();

        $cart = $em->getRepository('MainBundle:Cart')->findBy(array('user' => $user));

        return $this->render('MainBundle:Cart:show.html.twig', array(
                    'user' => $user,
                    'categorias' => $categorias,
                    'cart' => $cart
        ));
    }

    public function addToCartAction($id) {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $producto = $em->getRepository('MainBundle:Product')->find($id);

        $item_cart = new Cart();
        $item_cart->setUser($user);
        $item_cart->setProduct($producto);
        $em->persist($item_cart);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Producto añadido a tu carro satisfactoriamente');

        return $this->redirect($this->generateUrl('producto_show', array('id' => $producto->getId())));
    }

    public function removeToCartAction($id) {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $producto = $em->getRepository('MainBundle:Product')->find($id);

        $item_cart = $em->getRepository('MainBundle:Cart')->findOneBy(array('user' => $user, 'product' => $producto));
        $em->remove($item_cart);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Producto eliminado de tu carro satisfactoriamente');

        return $this->redirect($this->generateUrl('producto_show', array('id' => $producto->getId())));
    }

    public function buyAction() {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $cart = $em->getRepository('MainBundle:Cart')->findBy(array('user' => $user));

        foreach ($cart as $c) {
            $c->getProduct()->setBuyBy($user);
            $c->getProduct()->setBuyAt(new \DateTime);
            $c->getProduct()->getCreatedBy()->setN_products($c->getProduct()->getCreatedBy()->getN_products() - 1);
            $em->persist($c->getProduct());
            $em->remove($c);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Compra finalizada');

        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }

}
