<?php

namespace ContentManagerBundle\ContentManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use ContentManagerBundle\ContentManagerBundle\Entity\CMContent;
use ContentManagerBundle\ContentManagerBundle\Entity\CMContentTaxonomy;
use ContentManagerBundle\ContentManagerBundle\Entity\CMFieldValue;
use ContentManagerBundle\ContentManagerBundle\Type\ContentType;

use ContentManagerBundle\ContentManagerBundle\Classes\ExtraFields;

/**
 * class ContentController
 *
 * @Route("/contentmanager")
 */
class ContentController extends DefaultController
{
     /**
     * List Contents
     *
     * @Route("/contents", name="contents")
     * @Template("ContentManagerBundle:Content:list.html.twig")
     *
     * @return array
     */
    public function listAction()
    {
        $defaultLanguage = $this->getLanguageDefault();

        if (empty($defaultLanguage)) {
            $this->addFlashMsg('error', 'No default language exist. Please create one.');

            return array('display'=>false);
        }

        $languages   = $this->getLanguages();
        $contentType = $this->generateListTypeField();
        $contents    = $this->getRepository('ContentManagerBundle:CMContent')->getContentByLangId($defaultLanguage->getId());

        $locale      = $this->getLocale();

        return array(
            'contents'        =>$contents,
            'defaultLanguage' =>$defaultLanguage,
            'languages'       =>$languages,
            'display'         =>true,
            'contentType'     =>$contentType
        );
    }

    /**
     * Generate the list of content type
     *
     * @return string $html
     */
    private function generateListTypeField()
    {
        $contentTypes = $this->getRepository('ContentManagerBundle:CMContentType')->findAll();

        $html = '<select name="contentType" id="contentType">';
        foreach ($contentTypes as $key => $type) {
            $html .= '<option value="'.$type->getId().'">'.$type->getTitle().'</option>';
        }
        $html .= '</select>';

        return $html;
    }

    /**
     * Create Content
     *
     * @param Request   $request
     * @param int       $lang
     *
     * @Route("/content/new/{lang}", name="content_new")
     * @Template("ContentManagerBundle:Content:item.html.twig")
     *
     * @return array
     */
    public function newItemAction(Request $request, $lang)
    {
        $content  = new CMContent;
        $language = $this->getRepository('ContentManagerBundle:CMLanguage')->find($lang);

        $content->setLanguage($language);

        $form        = $this->createForm(new ContentType(), $content, array('lang'=>$language->getIso()));
        $contenttype = $request->query->get('contentType');
        $html        = ExtraFields::loadFields($this, $contenttype);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $created = $this->getDateTimeObject($content->getCreated());
                $content->setCreated($created);

                $content = $this->getMetas($content);

                $contentTaxonomy = new CMContentTaxonomy;
                $contentTaxonomy->addContent($content);
                $this->persistAndFlush($contentTaxonomy);

                $content->setTaxonomy($contentTaxonomy);
                $this->persistAndFlush($content);

                $contenttype = $request->get('contenttype');
                /*
                 * ContentManagerBundle\ContentManagerBundle\Classes\ExtraFields
                 * saveFields : used to save all fields add to a content type
                 */
                $em = $this->getEntityManager();
                ExtraFields::saveFields($this, $em, $request, $content, $contenttype);
                $this->persistAndFlush($content);

                return $this->redirect($this->generateUrl('contents'));
            }
        }

        return array(
            'form'        => $form->createView(),
            'content'     => $content,
            'lang'        => $lang,
            'html'        => $html,
            'contenttype' => $contenttype
        );
    }

     /**
     * Create Content Translation
     *
     * @param Request   $request
     * @param int       $reference
     * @param int       $lang
     * @param int       $contenttype
     *
     * @Route("/content/translation/{reference}/{lang}/{contenttype}", name="content_translation")
     * @Template("ContentManagerBundle:Content:item.html.twig")
     *
     * @return array
     */
    public function newItemTranslationAction(Request $request, $reference, $lang, $contenttype)
    {
        $content  = new CMContent;
        $language = $this->getRepository('ContentManagerBundle:CMLanguage')->find($lang);

        $content->setLanguage($language);
        $form = $this->createForm(new ContentType(), $content, array('lang'=>$language->getIso()));
        $html = ExtraFields::loadFields($this, $contenttype);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $referenceArticle = $this->getRepository('ContentManagerBundle:CMContent')->find($reference);
                $content->setReferenceContent($referenceArticle);

                $taxonomy = $this->getRepository('ContentManagerBundle:CMContentTaxonomy')->find($reference);
                $taxonomy->addContent($content);

                $content->setTaxonomy($taxonomy);

                $created = $this->getDateTimeObject($content->getCreated());
                $content->setCreated($created);

                $content = $this->getMetas($content);
                $this->persistAndFlush($content);

                $this->persistAndFlush($taxonomy);
                $contenttype = $request->request->get('contenttype');
                /*
                 * ContentManagerBundle\ContentManagerBundle\Classes\ExtraFields
                 * saveFields : used to save all fields add to a content type
                 */
                $em = $this->getEntityManager();
                ExtraFields::saveFields($this, $em, $request, $content, $contenttype);
                $this->persistAndFlush($content);

                return $this->redirect($this->generateUrl('contents'));
            }
        }

        return array(
            'form'             => $form->createView(),
            'content'          => $content,
            'lang'             => $lang,
            'referenceContent' =>$reference,
            'html'             => $html,
            'contenttype'      => $contenttype
        );
    }

    /**
     * Edit Content
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/content/edit/{id}", name="content_edit")
     * @Template("ContentManagerBundle:Content:item.html.twig")
     *
     * @return array
     */
    public function editItemAction(Request $request, $id)
    {
        $content = $this->getRepository('ContentManagerBundle:CMContent')->find($id);
        $content = $this->getStringDate($content);
        $html    = '';

        foreach ($content->getContentType()->getFields() as $key1 => $field) {
            $displayElem = null;
            foreach ($content->getFieldValues() as $key2 => $value) {
                if ($field->getPublished()) {
                    if ($field->getId() == $value->getField()->getId() && $content->getId() == $value->getContent()->getId()) {
                        $html .= $field->getField()->displayfield($field,$value->getValue());
                        $displayElem = 1;
                    }
                }
            }

            if (!$displayElem) {
                if ($field->getPublished()) {
                    $html .= $field->getField()->displayfield($field);
                }
            }

        }

        $form = $this->createForm(new ContentType(), $content, array('lang'=>$content->getLanguage()->getIso()));

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $created = $this->getDateTimeObject($content->getCreated());
                $content->setCreated($created);
                $content = $this->getMetas($content);

                $type = $content->getContentType();
                foreach ($type->getFields() as $key => $field) {
                    $addItem = null;
                    foreach ($content->getFieldValues() as $key => $fieldvalue) {
                        if ($fieldvalue->getField()->getId() == $field->getId()) {
                            $value = $request->request->get($field->getName());
                            $fieldvalue->setValue($value);
                            $this->persist($fieldvalue);
                            $addItem = 1;
                        }
                    }
                    if(!$addItem){
                        $value = $request->request->get($field->getName());
                        $fieldvalue = new CMFieldValue;
                        $fieldvalue->setValue($value);
                        $fieldvalue->setContent($content);
                        $fieldvalue->setField($field);
                        $content->addFieldValue($fieldvalue);
                        $field->addFieldValue($fieldvalue);
                        $this->persist($fieldvalue);
                    }
                }

                $this->persistAndFlush($content);

                return $this->redirect($this->generateUrl('contents'));
            }
        }

        return array(
            'form' => $form->createView(),
            'content' => $content,
            'html'=>$html
        );
    }

    /**
     * Get DateTime Object
     *
     * @param string        $date
     *
     * @return \DateTime    $date
     */
    private function getDateTimeObject($date){
        //input format : M/d/Y

        $date = explode('-', $date);
        if (is_array($date) && count($date)==3) {
            $year  = $date[0];
            $month = $date[1];
            $day   = $date[2];

            $date  = new \DateTime();
            $date->setDate($year,$month,$day);
        } else {
            $date = new \DateTime();
        }

        return $date;
    }

    /**
     * Get String for Date
     *
     * @param CMContent     $content
     *
     * @return CMContent    $content
     */
    private function getStringDate($content){
        $datetime = $content->getCreated();
        $datetime = $datetime->format('m/d/Y');

        $content->setCreated($datetime);

        return $content;
    }

    /**
     * Get Metas
     *
     * @param CMContent     $content
     *
     * @return CMContent    $content
     */
    private function getMetas($content){
        $metas = $content->getMetas();
        if (!$metas->getMetatitle()) {
            $title = $content->getTitle();
            $metas->setMetatitle($title);
        }
        if (!$metas->getMetadescription()) {
            $metas->setMetadescription(" ");
        }
        if (!$metas->getCanonical()) {
            $metas->setCanonical(" ");
        }
        $content->setMetas($metas);

        return $content;
    }

    /**
     * Get Copy of Content
     *
     * @param CMContent     $content
     *
     * @return CMContent    $copy
     */
    private function getCopyItem($content){
        $copy = new CMContent;

        $copy->setTitle($content->getTitle());
        $copy->setDescription($content->getDescription());

        return $copy;
    }

    /**
     * Copy Content
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/content/copy/{id}", name="content_copy")
     * @Template()
     *
     * @return redirect url
     */
    public function copyItemAction(Request $request, $id)
    {
        $content = $this->getRepository('ContentManagerBundle:CMContent')->find($id);
        $copy = $this->getCopyItem($content);

        $created = $this->getDateTimeObject($copy->getCreated());
        $copy->setCreated($created);

        $copy = $this->getMetas($copy);

        $this->persistAndFlush($copy);

        return $this->redirect($this->generateUrl('contents'));
    }

    /**
     * Publish Content
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/content/publish/{id}", name="content_publish")
     * @Template()
     *
     * @return redirect url
     */
    public function publishItemAction(Request $request, $id)
    {
        $content = $this->getRepository('ContentManagerBundle:CMContent')->find($id);

        if ($content->getPublished())
            $content->setPublished(0);
        else
            $content->setPublished(1);

        $this->persistAndFlush($content);

        return $this->redirect($this->generateUrl('contents'));
    }

    /**
     * Delete Content
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/content/delete/{id}", name="content_delete")
     * @Template)
     *
     * @return redirect url
     */
    public function deleteContentAction(Request $request, $id)
    {
        $content= $this->getRepository('ContentManagerBundle:CMContent')->find($id);

        $this->removeAndFlush($content);

        return $this->redirect($this->generateUrl('contents'));
    }

    /**
     * Delete Translations
     *
     * @param CMContent   $content
     *
     * @return CMContent  $content
     */
    private function deleteTranslation($content){
        $translations = $content->getTranslations();

        foreach ($translations as $key => $translation) {
            $this->removeAndFlush($translation);
        }

        $content->setTranslations(new \Doctrine\Common\Collections\ArrayCollection());

        return $content;
    }

}
