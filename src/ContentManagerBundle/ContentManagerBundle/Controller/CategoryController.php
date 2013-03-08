<?php

namespace ContentManagerBundle\ContentManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use ContentManagerBundle\ContentManagerBundle\Entity\CMCategory;
use ContentManagerBundle\ContentManagerBundle\Entity\CMCategoryTaxonomy;
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

        $defaultLanguage = $this->getLanguageDefault();

        if(empty($defaultLanguage)){
            $this->get('session')->getFlashBag()->add('error', 'No default language exist. Please create one.');
            
            return array('display'=>false); 
        }
        
        $languages = $this->getLanguages();
        $categories = $this->getDoctrine()->getRepository('ContentManagerBundle:CMCategory')->getCategoriesByLangId($defaultLanguage->getId());

        $request = $this->getRequest();
        $locale = $request->getLocale();

        return array('categories'=>$categories, 'defaultLanguage'=>$defaultLanguage, 'languages'=>$languages, 'display'=>true);
    }

    private function getLanguageDefault(){
        $language = $this->getDoctrine()->getRepository('ContentManagerBundle:CMLanguage')->findBy(array('default_lan'=>'1'));
        $language = current($language);

        return $language;
    }

    private function getLanguages(){
        $languages = $this->getDoctrine()->getRepository('ContentManagerBundle:CMLanguage')->findBy(array('default_lan'=>'0', 'published'=>'1'));

        return $languages;
    }

	/**
     * @Route("/categories/new/{lang}", name="categories_new")
     * @Template("ContentManagerBundle:ContentManager:category-item.html.twig")
     */
    public function newItemAction(Request $request, $lang)
    {

        $category = new CMCategory;
        $language = $this->getDoctrine()->getRepository('ContentManagerBundle:CMLanguage')->find($lang);
        $category->setLanguage($language);
        $form = $this->createForm(new CategoryType(), $category);

        if ($request->isMethod('POST')) {
        	$form->bind($request);            
	        if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $categoryTaxonomy = new CMCategoryTaxonomy;
                $categoryTaxonomy->addCategorie($category);
                $em->persist($categoryTaxonomy);
                $em->flush();

                $category->setTaxonomy($categoryTaxonomy);
                $em->persist($category);
                $em->flush();

                return $this->redirect($this->generateUrl('categories'));
	        }
	    }

        return array('form' => $form->createView(),'category' => $category, 'lang' => $lang);
    }

    /**
     * @Route("/categories/translation/{reference}/{lang}", name="categories_translation")
     * @Template("ContentManagerBundle:ContentManager:category-item.html.twig")
     */
    public function newItemTranslationAction(Request $request, $reference, $lang)
    {
        $category = new CMCategory;
        $language = $this->getDoctrine()->getRepository('ContentManagerBundle:CMLanguage')->find($lang);
        $category->setLanguage($language);
        $form = $this->createForm(new CategoryType(), $category);

        if ($request->isMethod('POST')) {
            $form->bind($request);            
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $categoryTaxonomy = $this->getDoctrine()->getRepository('ContentManagerBundle:CMCategoryTaxonomy')->find($reference);
                $categoryTaxonomy->addCategorie($category);
                $em->persist($categoryTaxonomy);
                $em->flush();

                $category->setTaxonomy($categoryTaxonomy);
                $em->persist($category);
                $em->flush();

                return $this->redirect($this->generateUrl('categories'));
            }
        }

        return array('form' => $form->createView(),'category' => $category, 'lang' => $lang, 'referenceCategory'=>$reference); 
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
     * @Route("/categories/published/{id}", name="categories_published")
     * @Template()
     */
    public function publishedItemAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository('ContentManagerBundle:CMCategory')->find($id);

        if($category->getPublished())
            $category->setPublished(0);
        else
            $category->setPublished(1);

        $em = $this->getDoctrine()->getManager();

        $em->persist($category);
        $em->flush();

        return $this->redirect($this->generateUrl('categories'));
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
