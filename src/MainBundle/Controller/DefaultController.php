<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {

        $em = $this->getDoctrine()->getManager();

        $ultimos_productos = $em->createQueryBuilder()
                ->select('p')
                ->from('MainBundle:Product', 'p')
                ->where('p.buy_at is null')
                ->orderBy('p.created_at', 'DESC')
                ->setMaxResults(4)
                ->getQuery()
                ->getResult();

        return $this->render('MainBundle:Default:index.html.twig', array(
                    'ultimos_productos' => $ultimos_productos
        ));
    }

}
