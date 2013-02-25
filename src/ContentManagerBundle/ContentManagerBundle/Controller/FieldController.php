<?php

namespace ContentManagerBundle\ContentManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;

use ContentManagerBundle\ContentManagerBundle\Entity\CMField;
use ContentManagerBundle\ContentManagerBundle\Type\FieldType;


/**
 * @Route("/contentmanager")
 */
class FieldController extends Controller
{
    /**
     * @Route("/fields/list", name="fields")
     * @Template("ContentManagerBundle:ContentManager:fields-list.html.twig")
     */
    public function listAction()
    {
    	$fieldslist=$this->generateListTypeField();
    	$fields = $this->getDoctrine()->getRepository('ContentManagerBundle:CMField')->findAll();

        return array('fields' => $fields, 'fieldstype' => $fieldslist);
    }

    private function getFieldsType(){
    	
		$path = $this->getEntityPath();	
		$fieldsDirectory = $path.DIRECTORY_SEPARATOR.'Entity'.DIRECTORY_SEPARATOR.'Fields';
        //var_dump($fieldsDirectory);die();
        //var_dump(file_exists($fieldsDirectory));die();

    	//open my directory
    	$myDirectory = opendir($fieldsDirectory);

		// get each entry
		while($field = readdir($myDirectory)) {
			if($field != '.' && $field != '..'){
				$field = substr($field, 0, strrpos($field,'.'));
				$fields[] = $field;
			}
		}

		// close directory
		closedir($myDirectory);
		sort($fields);
		
		return $fields;
	}
    
	private function generateListTypeField(){
		$fieldsListType=$this->getFieldsType();	
    	$fieldsDirectory = $this->getFieldPath();
        $path = $this->getEntityPath(); 

		$html = '<select name="fieldtype" id="fieldtype">';
		foreach ($fieldsListType as $key => $fieldtype) {
			$fieldclass = $fieldsDirectory.$fieldtype;
			$field = new $fieldclass;
			$html .= '<option value="'.$fieldtype.'">'.$field->getName().'</option>';
		}
		$html .= '</select>';

		return $html;
	}

	private function getEntityPath(){
		$dirbase = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
		return $dirbase;
	}

	private function getFieldPath(){
		$dirbase = '\ContentManagerBundle\ContentManagerBundle\Entity\Fields\\';
		return $dirbase;
	}

	/**
     * @Route("/fields/new", name="fields_new")
     * @Template("ContentManagerBundle:ContentManager:fields-item.html.twig")
     */
    public function newItemAction(Request $request)
    {
        $field = new CMField;
        $fieldsOptions = null;
		$fieldtype = $request->query->get('fieldtype');
    	if($fieldtype){
    		$fieldPath = $this->getFieldPath();
			$fieldclass = $fieldPath.$fieldtype;
        	$fieldsOptions = new $fieldclass;
        	$fieldsOptions = $fieldsOptions->getOptions();
    	}
       	$form = $this->createForm(new FieldType(), $field);

        if ($request->isMethod('POST')) {
        	$form->bind($request);
	        if ($form->isValid()) {
	        	$em = $this->getDoctrine()->getManager();
				$fieldtype = $request->request->get('fieldtype');

	    		$fieldPath = $this->getFieldPath();
				$fieldclass = $fieldPath.$fieldtype;
	        	$fieldValue = new $fieldclass;

				$fieldsOptions = $request->request->get('options');

				$fieldValue->setParams($fieldsOptions);

	        	$created = $this->getDateTimeObject($field->getCreated());
	        	$field->setCreated($created);
	        	$field->setField($fieldValue);
	        	$field->setType($fieldtype);
				
				foreach ($field->getContentType() as $key => $type) {
					$type->addField($field);
					$em->persist($type);
				}

                $em->persist($field);
                $em->flush();

	            return $this->redirect($this->generateUrl('fields'));
	        }
	    }

        return array('form' => $form->createView(), 'fieldsOptions' => $fieldsOptions, 'field' => $field, 'fieldtype' => $fieldtype); 
    }

    /**
     * @Route("/fields/edit/{id}", name="fields_edit")
     * @Template("ContentManagerBundle:ContentManager:fields-item.html.twig")
     */
    public function editItemAction(Request $request, $id)
    {
        $field = $this->getDoctrine()->getRepository('ContentManagerBundle:CMField')->find($id);
        $field = $this->getStringDate($field);
        $fieldsOptions = null;
        if(is_object($field->getField())){
        	$fieldsOptions = $field->getField()->getOptions();        	
        }
       	$form = $this->createForm(new FieldType(), $field);
       	$fieldtype = $field->getType();

        if ($request->isMethod('POST')) {
            $old_field = $field;
        	$form->bind($request);
	        if ($form->isValid()) {
	        	$em = $this->getDoctrine()->getManager();

				$options = $request->request->get('options');
	        	$created = $this->getDateTimeObject($field->getCreated());
	        	$field->setCreated($created);
	        	$fieldClass = $field->getField();
	        	if(is_object($fieldClass)){
	        		$fieldClass->setParams($options);
	        	}

                $field->setField(null); 
                foreach ($field->getContentType() as $key => $type) {
                    $em->persist($type);
                }               
                $em->persist($field);
                $em->flush();

				$field->setField($fieldClass);
                $em->persist($field);
                $em->flush();

	            return $this->redirect($this->generateUrl('fields'));
	        }
	    }

        return array('form' => $form->createView(), 'fieldsOptions' => $fieldsOptions, 'field' => $field, 'fieldtype' => $fieldtype); 
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

    private function getCopyItem($field){
    	$copy = new CMField;
        $copy->setTitle($field->getTitle());
        $copy->setName($field->getName());
        $copy->setField($field->getField());
        $copy->setType($field->getType());
        return $copy;
    }

    /**
     * @Route("/fields/copy/{id}", name="fields_copy")
     * @Template("ContentManagerBundle:ContentManager:list.html.twig")
     */
    public function copyItemAction(Request $request, $id)
    {
        $field = $this->getDoctrine()->getRepository('ContentManagerBundle:CMField')->find($id);
        $copy = $this->getCopyItem($field);

        $em = $this->getDoctrine()->getManager();

       	$em->persist($copy);
       	$em->flush();

        return $this->redirect($this->generateUrl('fields'));
    }

    /**
     * @Route("/fields/published/{id}", name="fields_published")
     * @Template("ContentManagerBundle:ContentManager:list.html.twig")
     */
    public function publishedItemAction(Request $request, $id)
    {
        $field = $this->getDoctrine()->getRepository('ContentManagerBundle:CMField')->find($id);

    	if($field->getPublished())
    		$field->setPublished(0);
    	else
    		$field->setPublished(1);

        $em = $this->getDoctrine()->getManager();

       	$em->persist($field);
       	$em->flush();

        return $this->redirect($this->generateUrl('fields'));
    }

    /**
     * @Route("/fields/delete/{id}", name="fields_delete")
     * @Template("ContentManagerBundle:ContentManager:list.html.twig")
     */
    public function deleteAction(Request $request, $id)
    {
        $field = $this->getDoctrine()->getRepository('ContentManagerBundle:CMField')->find($id);
       	$em = $this->getDoctrine()->getManager();

       	$em->remove($field);
       	$em->flush();

       	return $this->redirect($this->generateUrl('fields'));
    }
}
