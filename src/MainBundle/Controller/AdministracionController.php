<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use MainBundle\Form\UserFormType;
use MainBundle\Form\CategoryFormType;
use MainBundle\Form\NewProductFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MainBundle\Entity\Category;
use MainBundle\Entity\User;
use MainBundle\Entity\Product;

class AdministracionController extends Controller {

    public function indexAction() {

        $em = $this->getDoctrine()->getManager();

        $productos = array();
        $usuarios = $em->getRepository('MainBundle:User')->findAll();
        $categorias = $em->getRepository('MainBundle:Category')->findAll();
        $productos['en_venta'] = $em->createQueryBuilder()
                ->select('p')
                ->from('MainBundle:Product', 'p')
                ->where('p.buy_at is null')
                ->getQuery()
                ->getResult();

        $category = new Category();
        $user = new User();
        $product = new Product();

        $form_cat = $this->createForm('MainBundle\Form\CategoryFormType', $category);
        $form_user = $this->createForm('MainBundle\Form\NewUserFormType', $user);
        $form_product = $this->createForm('MainBundle\Form\NewProductFormType', $product);

        return $this->render('MainBundle:Administracion:index.html.twig', array(
                    'usuarios' => $usuarios,
                    'categorias' => $categorias,
                    'productos' => $productos,
                    'form_cat' => $form_cat->createView(),
                    'form_user' => $form_user->createView(),
                    'form_product' => $form_product->createView()
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

    public function renderModalEditProductAction($id) {
        $em = $this->getDoctrine()->getManager();

        $producto = $em->getRepository('MainBundle:Product')->find($id);

        $form_edit_prod = $this->createForm('MainBundle\Form\EditProductFormType', $producto);

        return $this->render('MainBundle:Administracion:modal/edit-product.html.twig', array(
                    'producto' => $producto,
                    'form_edit_prod' => $form_edit_prod->createView()
        ));
    }

    public function renderModalNewProductAction() {

        $producto = new Product();

        $form_product = $this->createForm('MainBundle\Form\NewProductFormType', $producto);

        return $this->render('MainBundle:Administracion:modal/add-product.html.twig', array(
                    'form_product' => $form_product->createView()
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

    public function renderModalRemoveProductAction($id) {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('MainBundle:Product')->find($id);

        return $this->render('MainBundle:Administracion:modal/remove-product.html.twig', array(
                    'producto2' => $product,
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

    public function removeProductAction($id) {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('MainBundle:Product')->find($id);

        $em->remove($product);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Producto eliminado con exito');

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('administracion_show'));
        } else {
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }
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
            $user->setN_products(0);
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Usuario creado con exito');

            return $this->redirect($this->generateUrl('administracion_show'));
        }
        return $this->redirect($this->generateUrl('administracion_show'));
    }

    public function newProductAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $product = new Product();

        $form = $this->createForm('MainBundle\Form\NewProductFormType', $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$this->isGranted('ROLE_ADMIN')) {
                $product->setCreatedBy($this->getUser());
                $user->setN_products($user->getN_products() + 1);
                $em->persist($user);
                $em->flush();
            }

            $product->setCreatedAt(new \DateTime());

            $file = $product->getImg();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()) . '.jpg';

            // Move the file to the directory where brochures are stored
            $file->move(
                    $this->getParameter('img_directory'), $fileName
            );
            $product->setImg($fileName);

            $em->persist($product);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Producto creado con exito');
        }
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('administracion_show'));
        } else {
            return $this->redirect($this->generateUrl('producto_show', array('id' => $product->getId())));
        }
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

    public function editProductAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $categorias = $em->getRepository('MainBundle:Category')->findAll();
        $product = $em->getRepository('MainBundle:Product')->find($id);

        $form = $this->createForm('MainBundle\Form\EditProductFormType', $product);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $product->setUpdatedAt(new \DateTime());
            $em->persist($product);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Producto modificado con exito');
            
                   return $this->redirect($this->generateUrl('producto_show', array('id' => $product->getId())));
        }
        return $this->render('MainBundle:Administracion:administracion_product_edit.html.twig', array(
                    'form' => $form->createView(),
                    'producto' => $product,
                    'categorias' => $categorias
        ));
    }

    public function editPasswordUserAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $categorias = $em->getRepository('MainBundle:Category')->findAll();
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
                    'categorias' => $categorias
        ));
    }

}
