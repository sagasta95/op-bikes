<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductoController extends Controller {

    public function showAction($id) {

        $em = $this->getDoctrine()->getManager();
        
        $user = $this->getUser();

        $categorias = $em->getRepository('MainBundle:Category')->findAll();
        $producto = $em->getRepository('MainBundle:Product')->find($id);

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
        ));
    }

}
