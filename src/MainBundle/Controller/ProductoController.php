<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductoController extends Controller {

    public function indexAction($id = null) {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $c = null;
        
        
        $categorias = $em->getRepository('MainBundle:Category')->findAll();
        
        if (!$id) {
            $productos = $em->createQueryBuilder()
                    ->select('p')
                    ->from('MainBundle:Product', 'p')
                    ->where('p.buy_at is null')
                    ->orderBy('p.created_at', 'ASC')
                    ->setMaxResults(30)
                    ->getQuery()
                    ->getResult();
        } else {
            $productos = $em->createQueryBuilder()
                    ->select('p')
                    ->from('MainBundle:Product', 'p')
                    ->where('p.buy_at is null and p.category = :category')
                    ->orderBy('p.created_at', 'ASC')
                    ->setParameter('category', $id)
                    ->setMaxResults(30)
                    ->getQuery()
                    ->getResult();
            $c = $em->getRepository('MainBundle:Category')->find($id);
        }


        return $this->render('MainBundle:Product:index.html.twig', array(
                    'productos' => $productos,
                    'user' => $user,
                    'categorias' => $categorias,
                    'cat' => $c
        ));
    }

    public function showAction($id) {

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

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
                    'user' => $user
        ));
    }

}
