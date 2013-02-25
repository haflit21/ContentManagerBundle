<?php

namespace ContentManagerBundle\ContentManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use ContentManagerBundle\ContentManagerBundle\Entity\CMCategory;
use ContentManagerBundle\ContentManagerBundle\Type\CategoryType;

/**
 * @Route("/contentmanager")
 */
class CategoryController extends Controller
{
	/**
     * @Route("/categories/list", name="categories")
     * @Template("ContentManagerBundle:ContentManager:category-list.html.twig")
     */
    public function listCategoriesAction()
    {
    	$em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('ContentManagerBundle:CMCategory')->findAll();

        return array(
            'categories' => $categories,
        );
    }

	/**
     * @Route("/categories/new", name="categories_new")
     * @Template("ContentManagerBundle:ContentManager:category-item.html.twig")
     */
    public function newItemAction(Request $request)
    {
        $category = new CMCategory;
        $form = $this->createForm(new CategoryType(), $category);

        if ($request->isMethod('POST')) {

        	$form->bind($request);
            
	        if ($form->isValid()) {

	        	$em = $this->getDoctrine()->getManager();

	        	$em->persist($category);
	        	$em->flush();

	            return $this->redirect($this->generateUrl('categories'));
	        }
	    }

        return array('form' => $form->createView(),'category' => $category); 
    }

    /**
     * @Route("/categories/edit/{id}", name="categories_edit")
     * @Template("ContentManagerBundle:ContentManager:category-item.html.twig")
     */
    public function editItemAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository('ContentManagerBundle:CMCategory')->find($id);

        $form = $this->createForm(new CategoryType(), $category);

        if ($request->isMethod('POST')) {
        	$form->bind($request);
	        if ($form->isValid()) {

	        	$em = $this->getDoctrine()->getManager();

	        	$em->persist($category);
	        	$em->flush();

	            return $this->redirect($this->generateUrl('categories'));
	        }
	    }

        return array('form' => $form->createView(),'category' => $category); 
    }

    /**
     * @Route("/categories/delete/{id}", name="categories_delete")
     * @Template()
     */
    public function deleteItemAction($id)
    {
        $category = $this->getDoctrine()->getRepository('ContentManagerBundle:CMCategory')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirect($this->generateUrl('categories'));
    }
}
