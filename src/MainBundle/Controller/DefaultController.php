<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    public function indexAction(Request $request) {


        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $c = null;

        $id = $request->get('id');

        $categorias = $em->getRepository('MainBundle:Category')->findAll();

        if (!$id) {
            $productos = $em->createQueryBuilder()
                    ->select('p')
                    ->from('MainBundle:Product', 'p')
                    ->where('p.buy_at is null')
                    ->setMaxResults(50)
                    ->getQuery()
                    ->getResult();
        } else {
            $productos = $em->createQueryBuilder()
                    ->select('p')
                    ->from('MainBundle:Product', 'p')
                    ->where('p.buy_at is null and p.category = :category')
                    ->setParameter('category', $id)
                    ->setMaxResults(50)
                    ->getQuery()
                    ->getResult();
            $c = $em->getRepository('MainBundle:Category')->find($id);
        }

        $productos = array_reverse($productos);
        return $this->render('MainBundle:Default:index.html.twig', array(
                    'productos' => $productos,
                    'categorias' => $categorias,
                    'user' => $user,
                    'c' => $c
        ));
    }

    public function searchAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $c = null;

        
        $txt = $request->get('search');

        $categorias = $em->getRepository('MainBundle:Category')->findAll();


        $productos = $em->createQueryBuilder()
                ->select('p')
                ->from('MainBundle:Product', 'p')
                ->where('p.buy_at is null and (p.nombre like :search or p.descripcion like :search)')
                ->orderBy('p.created_at', 'ASC')
                ->setParameter('search','%'.$txt.'%')
                ->getQuery()
                ->getResult();


        return $this->render('MainBundle:Default:index.html.twig', array(
                    'productos' => $productos,
                    'categorias' => $categorias,
                    'user' => $user,
                    'c' => $c
        ));
    }

}
