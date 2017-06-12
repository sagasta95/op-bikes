<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use MainBundle\Form\UserFormType;

class AdministracionController extends Controller {

    public function indexAction() {

        $em = $this->getDoctrine()->getManager();

        $usuarios = $em->getRepository('MainBundle:User')->findAll();

        return $this->render('MainBundle:Administracion:index.html.twig', array(
                    'usuarios' => $usuarios,
        ));
    }

    public function removeUserAction($id) {
        $em = $this->getDoctrine()->getManager();

        $usuario = $em->getRepository('MainBundle:User')->find($id);

        $em->remove($usuario);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Usuario eliminado con exito');

        return $this->redirect($this->generateUrl('administracion_show'));
    }

    public function editUserAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('MainBundle:User')->find($id);

        $form = $this->createForm('MainBundle\Form\UserFormType', $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $role = $form->get('roles')->getData();
            foreach ($user->getRoles() as $r) {
                $user->removeRole($r);
            }
            if ($role != '') {
                $user->setRoles(array($role));
            }
            $pw = $form->get('password')->getData();
            if ($pw != null) {
                $user->setPlainPassword($pw);
            }
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Usuario editado con exito');

            return $this->redirect($this->generateUrl('administracion_show'));
        }

        return $this->render('MainBundle:Administracion:administracion_user_edit.html.twig', array(
                    'form' => $form->createView(),
                    'usuario' => $user,
        ));
    }

    public function editPasswordUserAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('MainBundle:User')->find($id);

        $form = $this->createForm('MainBundle\Form\UserPasswordFormType', $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pw = $form->get('password')->getData();
            $user->setPlainPassword($pw);
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Contraseña de ' . $user->getUserName() . ' cambiada con exito');

            return $this->redirect($this->generateUrl('administracion_show'));
        }

        return $this->render('MainBundle:Administracion:administracion_user_password_edit.html.twig', array(
                    'form' => $form->createView(),
                    'usuario' => $user,
        ));
    }

}
