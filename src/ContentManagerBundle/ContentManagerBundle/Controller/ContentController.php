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
 * @Route("/contentmanager")
 */
class ContentController extends Controller
{
     /**
     * @Route("/contents/list", name="contents")
     * @Template("ContentManagerBundle:ContentManager:contents-list.html.twig")
     */
    public function listAction()
    {
    	$defaultLanguage = $this->getLanguageDefault();

        if(empty($defaultLanguage)){
            $this->get('session')->getFlashBag()->add('error', 'No default language exist. Please create one.');
            
            return array('display'=>false);
        }
        
    	$languages = $this->getLanguages();
        $contentType = $this->generateListTypeField();
        $contents = $this->getDoctrine()->getRepository('ContentManagerBundle:CMContent')->getContentByLangId($defaultLanguage->getId());

        $request = $this->getRequest();
        $locale = $request->getLocale();

        return array('contents'=>$contents, 'defaultLanguage'=>$defaultLanguage, 'languages'=>$languages, 'display'=>true, 'contentType'=>$contentType);
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

    private function generateListTypeField(){
        $contentTypes = $this->getDoctrine()->getRepository('ContentManagerBundle:CMContentType')->findAll();

        $html = '<select name="contentType" id="contentType">';
        foreach ($contentTypes as $key => $type) {
            $html .= '<option value="'.$type->getId().'">'.$type->getTitle().'</option>';
        }
        $html .= '</select>';

        return $html;
    }

	/**
     * @Route("/contents/new/{lang}", name="contents_new")
     * @Template("ContentManagerBundle:ContentManager:contents-item.html.twig")
     */
    public function newItemAction(Request $request, $lang)
    {
        $content = new CMContent;
        $language = $this->getDoctrine()->getRepository('ContentManagerBundle:CMLanguage')->find($lang);
        $content->setLanguage($language);
        $form = $this->createForm(new ContentType(), $content);
        $contenttype = $request->query->get('contentType');
        $html = ExtraFields::loadFields($this, $contenttype);
        if ($request->isMethod('POST')) {
        	$form->bind($request);
	        if ($form->isValid()) {
	        	$em = $this->getDoctrine()->getManager();

	        	$created = $this->getDateTimeObject($content->getCreated());
	        	$content->setCreated($created);

	        	$content = $this->getMetas($content);

                $contentTaxonomy = new CMContentTaxonomy;
                $contentTaxonomy->addContent($content);
	        	$em->persist($contentTaxonomy);
	        	$em->flush();

                $content->setTaxonomy($contentTaxonomy);
                $em->persist($content);
                $em->flush();

                $contenttype = $request->get('contenttype');
                ExtraFields::saveFields($this, $em, $request, $content, $contenttype);
                $em->persist($content);
                $em->flush();

	            return $this->redirect($this->generateUrl('contents'));
	        }
	    }

        return array('form' => $form->createView(),'content' => $content, 'lang' => $lang, 'html' => $html, 'contenttype' => $contenttype); 
    }

    /**
     * @Route("/contents/translation/{reference}/{lang}/{contenttype}", name="contents_translation")
     * @Template("ContentManagerBundle:ContentManager:contents-item.html.twig")
     */
    public function newItemTranslationAction(Request $request, $reference, $lang, $contenttype)
    {
        $content = new CMContent;
        $language = $this->getDoctrine()->getRepository('ContentManagerBundle:CMLanguage')->find($lang);
        $content->setLanguage($language);
        $form = $this->createForm(new ContentType(), $content);
        $html = ExtraFields::loadFields($this, $contenttype);

        if ($request->isMethod('POST')) {
        	$form->bind($request);
	        if ($form->isValid()) {
	        	$em = $this->getDoctrine()->getManager();
	        	
	        	/** DEBUT MODIFICATION DCA POUR ENREGISTRER A JOUR L'ARTICLE DE REFERENCE **/
			$referenceArticle = $this->getDoctrine()->getRepository('CMSContentBundle:CMContent')->find($reference);
		        $content->setReferenceContent($referenceArticle);
		        /** FIN MODIFICATION DCA **/
		        
		        $taxonomy = $this->getDoctrine()->getRepository('ContentManagerBundle:CMContentTaxonomy')->find($reference);
		        $taxonomy->addContent($content);

		        $content->setTaxonomy($taxonomy);

	        	$created = $this->getDateTimeObject($content->getCreated());
	        	$content->setCreated($created);

	        	$content = $this->getMetas($content);
                $em->persist($content);
                $em->flush();

	        	$em->persist($taxonomy);
                $contenttype = $request->request->get('contenttype');
                ExtraFields::saveFields($this, $em, $request, $content, $contenttype);
	        	$em->persist($content);
	        	$em->flush();

	            return $this->redirect($this->generateUrl('contents'));
	        }
	    }

        return array('form' => $form->createView(),'content' => $content, 'lang' => $lang, 'referenceContent'=>$reference, 'html' => $html, 'contenttype' => $contenttype); 
    }

    /**
     * @Route("/contents/edit/{id}", name="contents_edit")
     * @Template("ContentManagerBundle:ContentManager:contents-item.html.twig")
     */
    public function editItemAction(Request $request, $id)
    {
        $content = $this->getDoctrine()->getRepository('ContentManagerBundle:CMContent')->find($id);
        $content = $this->getStringDate($content);
        $html = '';

        foreach ($content->getContentType()->getFields() as $key1 => $field) {
            $display_elem=null;
            foreach ($content->getFieldValues() as $key2 => $value) {
                if($field->getPublished()){
                    if($field->getId() == $value->getField()->getId() && $content->getId() == $value->getContent()->getId()){
                        $html .= $field->getField()->displayfield($field,$value->getValue());
                        $display_elem=1;
                    }
                }
            }
            if(!$display_elem){
                if($field->getPublished()){
                    $html .= $field->getField()->displayfield($field);
                }
            }
            
        }

        $form = $this->createForm(new ContentType(), $content);

        if ($request->isMethod('POST')) {
        	$form->bind($request);
	        if ($form->isValid()) {
	        	$em = $this->getDoctrine()->getManager();

	        	$created = $this->getDateTimeObject($content->getCreated());
	        	$content->setCreated($created);
	        	$content = $this->getMetas($content);

                $type = $content->getContentType();
                foreach ($type->getFields() as $key => $field) {
                    $additem=null;
                    foreach ($content->getFieldValues() as $key => $fieldvalue) {
                        if($fieldvalue->getField()->getId() == $field->getId())
                        {
                            $value = $request->request->get($field->getName());
                            $fieldvalue->setValue($value);
                            $em->persist($fieldvalue);
                            $additem=1;
                        }
                    }
                    if(!$additem){
                        $value = $request->request->get($field->getName());
                        $fieldvalue = new CMFieldValue;
                        $fieldvalue->setValue($value);
                        $fieldvalue->setContent($content);
                        $fieldvalue->setField($field);
                        $content->addFieldValue($fieldvalue);
                        $field->addFieldValue($fieldvalue);
                        $em->persist($fieldvalue);
                    }
                }                

	        	$em->persist($content);
	        	$em->flush();

	            return $this->redirect($this->generateUrl('contents'));
	        }
	    }

        return array('form' => $form->createView(),'content' => $content, 'html'=>$html); 
    }

    private function getDateTimeObject($date){
    	//input format : M/d/Y
    	$date = explode('-', $date);
    	if(is_array($date) && count($date)==3){
    		$year = $date[0];
	    	$month = $date[1];
	    	$day = $date[2];

	    	$date = new \DateTime();
		    $date->setDate($year,$month,$day);
    	}else{
    		$date = new \DateTime();
    	}

    	return $date;
    }

    private function getStringDate($content){
    	$datetime = $content->getCreated();
    	$datetime = $datetime->format('m/d/Y');

    	$content->setCreated($datetime);

    	return $content;
    }

    private function getMetas($content){
    	if(!$content->getMetatitle()){
    		$title = $content->getTitle();
    		$content->setMetatitle($title);
    	}
    	if(!$content->getMetadescription()){
    		$content->getMetadescription(" ");
    	}
    	if(!$content->getCanonical()){
    		$content->getCanonical(" ");
    	}

    	return $content;
    }

    private function getCopyItem($content){
    	$copy = new CMContent;
        $copy->setTitle($content->getTitle());
        $copy->setDescription($content->getDescription());
        return $copy;
    }

    /**
     * @Route("/contents/copy/{id}", name="contents_copy")
     * @Template("ContentManagerBundle:Langues:list.html.twig")
     */
    public function copyItemAction(Request $request, $id)
    {
        $content = $this->getDoctrine()->getRepository('ContentManagerBundle:CMContent')->find($id);
        $copy = $this->getCopyItem($content);

        $em = $this->getDoctrine()->getManager();

        $created = $this->getDateTimeObject($copy->getCreated());
    	$copy->setCreated($created);

    	$copy = $this->getMetas($copy);

       	$em->persist($copy);
       	$em->flush();

        return $this->redirect($this->generateUrl('contents'));
    }

    /**
     * @Route("/contents/published/{id}", name="contents_published")
     * @Template()
     */
    public function publishedItemAction(Request $request, $id)
    {
        $content = $this->getDoctrine()->getRepository('ContentManagerBundle:CMContent')->find($id);

    	if($content->getPublished())
    		$content->setPublished(0);
    	else
    		$content->setPublished(1);

        $em = $this->getDoctrine()->getManager();

       	$em->persist($content);
       	$em->flush();

        return $this->redirect($this->generateUrl('contents'));
    }

    /**
     * @Route("/contents/delete/{id}", name="contents_delete")
     * @Template)
     */
    public function deleteContentAction(Request $request, $id)
    {
        $content= $this->getDoctrine()->getRepository('ContentManagerBundle:CMContent')->find($id);
       	$em = $this->getDoctrine()->getManager();

       	$em->remove($content);
       	$em->flush();

       	return $this->redirect($this->generateUrl('contents'));
    }

}
