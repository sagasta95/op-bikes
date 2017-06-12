<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use MainBundle\Form\UserFormType;
use MainBundle\Form\CategoryFormType;
use MainBundle\Entity\Category;
use MainBundle\Entity\User;

class AdministracionController extends Controller {

    public function indexAction() {

        $em = $this->getDoctrine()->getManager();

        $usuarios = $em->getRepository('MainBundle:User')->findAll();
        $categorias = $em->getRepository('MainBundle:Category')->findAll();

        $category = new Category();
        $user = new User();

        $form_cat = $this->createForm('MainBundle\Form\CategoryFormType', $category);
        $form_user = $this->createForm('MainBundle\Form\NewUserFormType', $user);

        return $this->render('MainBundle:Administracion:index.html.twig', array(
                    'usuarios' => $usuarios,
                    'categorias' => $categorias,
                    'form_cat' => $form_cat->createView(),
                    'form_user' => $form_user->createView()
        ));
    }

    public function renderModalEditCategoryAction($id) {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('MainBundle:Category')->find($id);

        $form_edit_cat = $this->createForm('MainBundle\Form\CategoryFormType', $category);

        return $this->render('MainBundle:Administracion:modal/edit-category.html.twig', array(
                    'cat' => $category,
                    'form_edit_cat' => $form_edit_cat->createView()
        ));
    }

    public function renderModalEditUserAction($id) {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('MainBundle:User')->find($id);

        $form_edit_user = $this->createForm('MainBundle\Form\UserFormType', $user);

        return $this->render('MainBundle:Administracion:modal/edit-user.html.twig', array(
                    'user' => $user,
                    'form_edit_user' => $form_edit_user->createView()
        ));
    }

    public function renderModalRemoveCategoryAction($id) {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('MainBundle:Category')->find($id);

        return $this->render('MainBundle:Administracion:modal/remove-category.html.twig', array(
                    'cat' => $category,
        ));
    }

    public function renderModalRemoveUserAction($id) {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('MainBundle:User')->find($id);

        return $this->render('MainBundle:Administracion:modal/remove-user.html.twig', array(
                    'user' => $user,
        ));
    }

    public function removeCategoryAction($id) {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('MainBundle:Category')->find($id);

        $em->remove($category);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Categoría eliminada con exito');

        return $this->redirect($this->generateUrl('administracion_show') . '#adm-cat');
    }

    public function removeUserAction($id) {
        $em = $this->getDoctrine()->getManager();

        $usuario = $em->getRepository('MainBundle:User')->find($id);

        $em->remove($usuario);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Usuario eliminado con exito');

        return $this->redirect($this->generateUrl('administracion_show'));
    }

    public function newCategoryAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $category = new Category();

        $form = $this->createForm('MainBundle\Form\CategoryFormType', $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category->setCreatedBy($this->getUser());
            $em->persist($category);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Categoria creada con exito');

            return $this->redirect($this->generateUrl('administracion_show'));
        }
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
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Usuario editado con exito');

            return $this->redirect($this->generateUrl('administracion_show'));
        }
        return $this->redirect($this->generateUrl('administracion_show'));
    }

    public function newUserAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $user = new User();

        $form = $this->createForm('MainBundle\Form\NewUserFormType', $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $role = $form->get('roles')->getData();

            if ($role != '') {
                $user->setRoles(array($role));
            }

            $pw = $form->get('password')->getData();
            $user->setPlainPassword($pw);

            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Usuario creado con exito');

            return $this->redirect($this->generateUrl('administracion_show'));
        }
        return $this->redirect($this->generateUrl('administracion_show'));
    }

    public function editCategoryAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('MainBundle:Category')->find($id);

        $form = $this->createForm('MainBundle\Form\CategoryFormType', $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($category);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Categoría modificada con exito');

            return $this->redirect($this->generateUrl('administracion_show'));
        }
        return $this->redirect($this->generateUrl('administracion_show'));
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
                    'user' => $user,
        ));
    }

}
