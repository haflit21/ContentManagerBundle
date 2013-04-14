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
 * class CategoryController
 *
 * @Route("/contentmanager")
 */
class CategoryController extends DefaultController
{
    /**
     * List Categories
     *
     * @Route("/categories", name="categories")
     * @Template("ContentManagerBundle:Category:list.html.twig")
     *
     * @return array
     */
    public function listAction()
    {
        $defaultLanguage = $this->getLanguageDefault();

        if (empty($defaultLanguage)) {
            $this->addFlashMsg('error', 'No default language exist. Please create one.');

            return array(
                'display' => false
            );
        }

        $languages  = $this->getLanguages();
        $categories = $this->getRepository('ContentManagerBundle:CMCategory')->getCategoriesByLangId($defaultLanguage->getId());
        $locale     = $this->getLocale();

        return array(
            'categories'      => $categories,
            'defaultLanguage' => $defaultLanguage,
            'languages'       => $languages,
            'display'         => true
        );
    }

    /**
     * Create Category
     *
     * @param Request   $request
     * @param int        $lang
     *
     * @Route("/category/new/{lang}", name="category_new")
     * @Template("ContentManagerBundle:Category:item.html.twig")
     *
     * @return array
     */
    public function newItemAction(Request $request, $lang)
    {

        $category = new CMCategory;
        $language = $this->getRepository('ContentManagerBundle:CMLanguage')->find($lang);
        $category->setLanguage($language);
        $form     = $this->createForm(new CategoryType(), $category);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {

                $categoryTaxonomy = new CMCategoryTaxonomy;
                $categoryTaxonomy->addCategorie($category);
                $this->persistAndFlush($categoryTaxonomy);

                $category->setTaxonomy($categoryTaxonomy);
                $this->persistAndFlush($category);

                return $this->redirect($this->generateUrl('categories'));
            }
        }

        return array(
            'form'     => $form->createView(),
            'category' => $category,
            'lang'     => $lang
        );
    }

    /**
     * Create Category Translation
     *
     * @param Request   $request
     * @param int       $reference
     * @param int       $lang
     *
     * @Route("/category/translation/{reference}/{lang}", name="category_translation")
     * @Template("ContentManagerBundle:Category:item.html.twig")
     *
     * @return array
     */
    public function newItemTranslationAction(Request $request, $reference, $lang)
    {
        $category = new CMCategory;
        $language = $this->getRepository('ContentManagerBundle:CMLanguage')->find($lang);
        $category->setLanguage($language);
        $form     = $this->createForm(new CategoryType(), $category);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {

                $categoryTaxonomy = $this->getRepository('ContentManagerBundle:CMCategoryTaxonomy')->find($reference);
                $categoryTaxonomy->addCategorie($category);
                $this->persistAndFlush($categoryTaxonomy);

                $category->setTaxonomy($categoryTaxonomy);
                $this->persistAndFlush($category);

                return $this->redirect($this->generateUrl('categories'));
            }
        }

        return array(
            'form'              => $form->createView(),
            'category'          => $category,
            'lang'              => $lang,
            'referenceCategory' =>$reference
        );
    }

    /**
     * Edit Category
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/category/edit/{id}", name="category_edit")
     * @Template("ContentManagerBundle:Category:item.html.twig")
     *
     * @return array
     */
    public function editItemAction(Request $request, $id)
    {
        $category = $this->getRepository('ContentManagerBundle:CMCategory')->find($id);
        $form     = $this->createForm(new CategoryType(), $category);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {

                $this->persistAndFlush($category);

                return $this->redirect($this->generateUrl('categories'));
            }
        }

        return array(
            'form'     => $form->createView(),
            'category' => $category
        );
    }

    /**
     * Publish Category
     *
     * @param int $id
     *
     * @Route("/category/publish/{id}", name="category_publish")
     * @Template()
     *
     * @return redirect url
     */
    public function publishItemAction($id)
    {
        $category = $this->getRepository('ContentManagerBundle:CMCategory')->find($id);

        if ($category->getPublished())
            $category->setPublished(0);
        else
            $category->setPublished(1);

        $this->persistAndFlush($category);

        return $this->redirect($this->generateUrl('categories'));
    }

    /**
     * Delete Category
     *
     * @param int $id
     *
     * @Route("/category/delete/{id}", name="category_delete")
     * @Template()
     *
     * @return redirect url
     */
    public function deleteItemAction($id)
    {
        $category = $this->getRepository('ContentManagerBundle:CMCategory')->find($id);

        $this->removeAndFlush($category);

        return $this->redirect($this->generateUrl('categories'));
    }
}
