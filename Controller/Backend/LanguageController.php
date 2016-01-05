<?php

namespace Bacon\Bundle\LanguageBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Bacon\Bundle\CoreBundle\Controller\AdminController;
use Bacon\Bundle\CoreBundle\Office\PHPExcel as PHPOfficeExcel;
use Bacon\Bundle\LanguageBundle\Entity\Language;
use Bacon\Bundle\LanguageBundle\Form\Type\LanguageFormType;
use Bacon\Bundle\LanguageBundle\Form\Handler\LanguageFormHandler;

/**
 * Language controller.
 *
 * @Route("/language")
 */
class LanguageController extends AdminController
{

    /**
     * Lists all Language entities.
     *
     * @Route("/",defaults={"page"=1, "sort"="id", "direction"="asc"}, name="admin_language")
     * @Route("/page/{page}/sort/{sort}/direction/{direction}/", defaults={"page"=1, "sort"="id", "direction"="asc"}, name="admin_language_pagination")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function indexAction($page,$sort,$direction)
    {
        $breadcumbs = $this->container->get('bacon_breadcrumbs');

        $breadcumbs->addItem(array(
            'title' => 'Language',
            'route' => '',
        ));

        $breadcumbs->addItem(array(
            'title' => 'List',
            'route' => '',
        ));

        $entity = new Language();
        $query = $this->getDoctrine()->getRepository('BaconLanguageBundle:Language')->getQueryPagination($entity,$sort,$direction);

        if ($this->get('session')->has('language_search_session')) {
            $objSerialize = $this->get('session')->get('language_search_session');
            $entity = unserialize($objSerialize);
            $query = $this->getDoctrine()->getRepository('BaconLanguageBundle:Language')->getQueryPagination($entity,$sort,$direction);
        }

        $paginator = $this->getPagination($query,$page,Language::PER_PAGE);
        $paginator->setUsedRoute('admin_language_pagination');

        $form = $this->createForm(new LanguageFormType(),$entity,array(
            'search' => true,
        ));

        return array(
            'pagination' => $paginator,
            'form_search' => $form->createView(),
            'form_delete' => $this->createDeleteForm()->createView(),
        );
    }

   /**
    * Search filter Language entities.
    *
    * @Route("/search", name="admin_language_search")
    * @Method({"POST","GET"})
    * @Security("has_role('ROLE_ADMIN')")
    * @Template()
    */
    public function searchAction(Request $request)
    {
        $this->get('session')->remove('language_search_session');

        if ($request->getMethod() === Request::METHOD_POST) {

            $form = $this->createForm(new LanguageFormType(),new Language(),array(
                'search' => true,
            ));

            $form->handleRequest($request);

            $this->get('session')->set('language_search_session',serialize($form->getData()));
        }

        return $this->redirect($this->generateUrl('admin_language'));
    }

    /**
     * Displays a form to create a new Language entity.
     *
     * @Route("/new", name="admin_language_new")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function newAction()
    {
        $breadcumbs = $this->container->get('bacon_breadcrumbs');

        $breadcumbs->addItem(array(
            'title' => 'Language',
            'route' => 'admin_language',
        ));

        $breadcumbs->addItem(array(
            'title' => 'New',
            'route' => '',
        ));

        $form = $this->createForm(new LanguageFormType(), new Language());

        return array(
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Language entity.
     *
     * @Route("/", name="admin_language_create")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("BaconLanguageBundle:Backend/Language:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $breadcumbs = $this->container->get('a2c_breadcrumbs');

        $breadcumbs->addItem(array(
            'title' => 'Language',
            'route' => 'admin_language',
        ));

        $breadcumbs->addItem(array(
            'title' => 'New',
            'route' => '',
        ));

        $form = $this->createForm(new LanguageFormType(),new Language());

        $handler = new LanguageFormHandler(
            $form,
            $request,
            $this->getDoctrine()->getManager(),
            $this->get('session')->getFlashBag()
        );

        if ($handler->save()) {
            return $this->redirect($this->generateUrl('admin_language'));
        }

        return array(
            'form'   => $form->createView(),
        );
    }


    /**
     * Displays a form to edit an existing Language entity.
     *
     * @Route("/{id}/edit", name="admin_language_edit")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function editAction($id)
    {
        $breadcumbs = $this->container->get('bacon_breadcrumbs');

        $breadcumbs->addItem(array(
            'title' => 'Language',
            'route' => 'admin_language',
        ));

        $breadcumbs->addItem(array(
            'title' => 'Edit',
            'route' => '',
        ));

        $entity = $this->getDoctrine()->getRepository('BaconLanguageBundle:Language')->find($id);

        if (!$entity) {

            $this->get('session')->getFlashBag()->add('message', array(
                'type' => 'error',
                'message' => 'The registry not Found',
            ));

            return $this->redirect($this->generateUrl('admin_language'));
        }

        $form = $this->createForm(new LanguageFormType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'form'        => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Language entity.
     *
     * @Route("/{id}", name="admin_language_update")
     * @Method("PUT")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("BaconLanguageBundle:Backend/Language:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $breadcumbs = $this->container->get('bacon_breadcrumbs');

        $breadcumbs->addItem(array(
            'title' => 'Language',
            'route' => 'admin_language',
        ));

        $breadcumbs->addItem(array(
            'title' => 'Edit',
            'route' => '',
        ));

        $entity = $this->getDoctrine()->getRepository('BaconLanguageBundle:Language')->find($id);

        if (!$entity) {

            $this->get('session')->getFlashBag()->add('message', array(
                'type' => 'error',
                'message' => 'Registry not Found',
            ));

            return $this->redirect($this->generateUrl('admin_language'));
        }

        $form = $this->createForm(new LanguageFormType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $handler = new LanguageFormHandler(
            $form,
            $request,
            $this->getDoctrine()->getManager(),
            $this->get('session')->getFlashBag()
        );

        if ($handler->save()) {
            return $this->redirect($this->generateUrl('admin_language'));
        }

        return array(
            'entity'      => $entity,
            'form'        => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Finds and displays a Language entity.
     *
     * @Route("/{id}", name="admin_language_show")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function showAction($id)
    {
        $breadcumbs = $this->container->get('bacon_breadcrumbs');

        $breadcumbs->addItem(array(
            'title' => 'Language',
            'route' => 'admin_language',
        ));

        $breadcumbs->addItem(array(
            'title' => 'Details',
            'route' => '',
        ));

        $entity = $this->getDoctrine()->getRepository('BaconLanguageBundle:Language')->find($id);

        if (!$entity) {

            $this->get('session')->getFlashBag()->add('message', array(
                'type' => 'error',
                'message' => 'The registry not Found',
            ));

            return $this->redirect($this->generateUrl('admin_language'));
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Language entity.
     *
     * @Route("/{id}", name="admin_language_delete")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {

        $handler = new LanguageFormHandler(
            $this->createDeleteForm(),
            $request,
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag()
        );


        $entity = $this->getDoctrine()->getRepository('BaconLanguageBundle:Language')->find($id);

        if ($handler->delete($entity)) {
            return $this->redirect($this->generateUrl('admin_language'));
        } else {
            return $this->redirect($this->generateUrl('admin_language_show', array('id' => $id)));
        }
    }

}