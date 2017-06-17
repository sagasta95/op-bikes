<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductoController extends Controller {

    public function showAction($id) {

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $categorias = $em->getRepository('MainBundle:Category')->findAll();
        $producto = $em->getRepository('MainBundle:Product')->find($id);

        $inCart = $em->getRepository('MainBundle:Cart')->findOneBy(array('user' => $user, 'product' => $producto));

        $ultimos_productos = $em->createQueryBuilder()
                ->select('p')
                ->from('MainBundle:Product', 'p')
                ->where('p.buy_at is null and p.category = :category and p.id != :id')
                ->orderBy('p.created_at', 'ASC')
                ->setParameter('category', $producto->getCategory())
                ->setParameter('id', $id)
                ->setMaxResults(4)
                ->getQuery()
                ->getResult();

        return $this->render('MainBundle:Product:show.html.twig', array(
                    'producto' => $producto,
                    'ultimos_productos' => $ultimos_productos,
                    'user' => $user,
                    'categorias' => $categorias,
                    'incart' => $inCart
        ));
    }

    public function removeVentaAction($id) {

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $producto = $em->getRepository('MainBundle:Product')->find($id);

        $producto->setBuyBy(null);
        $producto->setBuyAt(null);
        $user->setN_products($user->getN_products() + 1);
        $em->persist($producto);
        $em->persist($user);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Venta cancelada correctamente');

        return $this->redirect($this->generateUrl('producto_show', array('id' => $producto->getId())));
    }

    public function renderModalBuyRevertAction($id) {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('MainBundle:Product')->find($id);

        return $this->render('MainBundle:Product:modal/remove-buy.html.twig', array(
                    'p' => $product,
        ));
    }

}
