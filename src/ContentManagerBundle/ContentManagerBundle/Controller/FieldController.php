<?php

namespace ContentManagerBundle\ContentManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;

use ContentManagerBundle\ContentManagerBundle\Entity\CMField;
use ContentManagerBundle\ContentManagerBundle\Type\FieldType;


/**
 * class ContentController
 *
 * @Route("/contentmanager")
 */
class FieldController extends DefaultController
{
    /**
     * List Fields
     *
     * @Route("/fields", name="fields")
     * @Template("ContentManagerBundle:Field:list.html.twig")
     *
     * @return array
     */
    public function listAction()
    {
        $fieldslist = $this->generateListFieldType();
        $fields     = $this->getRepository('ContentManagerBundle:CMField')->findAll();

        return array(
            'fields'     => $fields,
            'fieldstype' => $fieldslist
        );
    }

    /**
     * Get Field Types
     *
     * @return array $fields
     */
    private function getFieldsType()
    {

        $path = $this->getEntityPath();
        $fieldsDirectory = $path.DIRECTORY_SEPARATOR.'Entity'.DIRECTORY_SEPARATOR.'Fields';

        //open my directory
        $myDirectory = opendir($fieldsDirectory);

        // get each entry
        while ($field = readdir($myDirectory)) {
            if ($field != '.' && $field != '..') {
                $field = substr($field, 0, strrpos($field,'.'));
                $fields[] = $field;
            }
        }

        // close directory
        closedir($myDirectory);
        sort($fields);

        return $fields;
    }

    /**
     * Generate list of Field Types
     *
     * @return string $html
     */
    private function generateListFieldType(){
        $fieldsListType  = $this->getFieldsType();
        $fieldsDirectory = $this->getFieldPath();
        $path            = $this->getEntityPath();

        $html = '<select name="fieldtype" id="fieldtype">';
        foreach ($fieldsListType as $key => $fieldtype) {
            $fieldclass = $fieldsDirectory.$fieldtype;
            $field = new $fieldclass;
            $html .= '<option value="'.$fieldtype.'">'.$field->getName().'</option>';
        }
        $html .= '</select>';

        return $html;
    }

    /**
     * Get Entity Path
     *
     * @return string $dirbase
     */
    private function getEntityPath(){
        $dirbase = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        return $dirbase;
    }

    /**
     * Get Field Path
     *
     * @return string $dirbase
     */
    private function getFieldPath(){
        $dirbase = '\ContentManagerBundle\ContentManagerBundle\Entity\Fields\\';
        return $dirbase;
    }

    /**
     * New Field
     *
     * @param Request $request
     *
     * @Route("/field/new", name="field_new")
     * @Template("ContentManagerBundle:Field:item.html.twig")
     *
     * @return array
     */
    public function newItemAction(Request $request)
    {
        $field         = new CMField;
        $fieldsOptions = null;
        $fieldtype     = $request->query->get('fieldtype');

        if ($fieldtype) {
            $fieldPath     = $this->getFieldPath();
            $fieldclass    = $fieldPath.$fieldtype;
            $fieldsOptions = new $fieldclass;
            $fieldsOptions = $fieldsOptions->getOptions();
        }

        $form = $this->createForm(new FieldType(), $field);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $fieldtype     = $request->request->get('fieldtype');

                $fieldPath     = $this->getFieldPath();
                $fieldclass    = $fieldPath.$fieldtype;
                $fieldValue    = new $fieldclass;

                $fieldsOptions = $request->request->get('options');

                $fieldValue->setParams($fieldsOptions);

                $created = $this->getDateTimeObject($field->getCreated());

                $field->setCreated($created);
                $field->setField($fieldValue);
                $field->setType($fieldtype);

                foreach ($field->getContentType() as $key => $type) {
                    $type->addField($field);
                    $this->persist($type);
                }

                $this->persistAndFlush($field);

                return $this->redirect($this->generateUrl('fields'));
            }
        }

        return array(
            'form'          => $form->createView(),
            'fieldsOptions' => $fieldsOptions,
            'field'         => $field,
            'fieldtype'     => $fieldtype
        );
    }

    /**
     * Edit Field
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/field/edit/{id}", name="field_edit")
     * @Template("ContentManagerBundle:Field:item.html.twig")
     *
     * @return array
     */
    public function editItemAction(Request $request, $id)
    {
        $field         = $this->getRepository('ContentManagerBundle:CMField')->find($id);
        $field         = $this->getStringDate($field);
        $fieldsOptions = null;

        if (is_object($field->getField())) {
            $fieldsOptions = $field->getField()->getOptions();
        }

        $form = $this->createForm(new FieldType(), $field);
        $fieldtype = $field->getType();

        if ($request->isMethod('POST')) {
            $old_field = $field;
            $form->bind($request);
            if ($form->isValid()) {
                $options = $request->request->get('options');
                $created = $this->getDateTimeObject($field->getCreated());

                $field->setCreated($created);
                $fieldClass = $field->getField();

                if (is_object($fieldClass)) {
                    $fieldClass->setParams($options);
                }

                $field->setField(null);
                foreach ($field->getContentType() as $key => $type) {
                    $this->persist($type);
                }
                $this->persistAndFlush($field);

                $field->setField($fieldClass);
                $this->persistAndFlush($field);

                return $this->redirect($this->generateUrl('fields'));
            }
        }

        return array(
            'form'          => $form->createView(),
            'fieldsOptions' => $fieldsOptions,
            'field'         => $field,
            'fieldtype'     => $fieldtype
        );
    }

    /**
     * Get DateTime
     *
     * @param string $date
     *
     * @return \DateTime $date
     */
    private function getDateTimeObject($date){
        //input format : M/d/Y
        if (!is_object($date)){
            $date = explode('-', $date);
            if (is_array($date) && count($date)==3) {
                $year = $date[0];
                $month = $date[1];
                $day = $date[2];

                $date = new \DateTime();
                $date->setDate($year,$month,$day);
            } else {
                $date = new \DateTime();
            }
        }

        return $date;
    }

    /**
     * Get Date String
     *
     * @param CMField $field
     *
     * @return CMField $field
     */
    private function getStringDate($field){
        $datetime = $field->getCreated();
        $datetime = $datetime->format('m/d/Y');

        $field->setCreated($datetime);

        return $field;
    }

    /**
     * Get Copy Field
     *
     * @param CMField $field
     *
     * @return CMField $copy
     */
    private function getCopyItem($field){
        $copy = new CMField;
        $copy->setTitle($field->getTitle());
        $copy->setName($field->getName());
        $copy->setField($field->getField());
        $copy->setType($field->getType());

        return $copy;
    }

    /**
     * Copy Field
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/field/copy/{id}", name="field_copy")
     * @Template()
     *
     * @return redirect url
     */
    public function copyItemAction(Request $request, $id)
    {
        $field = $this->getRepository('ContentManagerBundle:CMField')->find($id);
        $copy = $this->getCopyItem($field);

        $this->persistAndFlush($copy);

        return $this->redirect($this->generateUrl('fields'));
    }

    /**
     * Publish Field
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/field/publish/{id}", name="field_publish")
     * @Template()
     *
     * @return redirect url
     */
    public function publishItemAction(Request $request, $id)
    {
        $field = $this->getRepository('ContentManagerBundle:CMField')->find($id);

        if ($field->getPublished())
            $field->setPublished(0);
        else
            $field->setPublished(1);

        $this->persistAndFlush($field);

        return $this->redirect($this->generateUrl('fields'));
    }

    /**
     * Delete Field
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/field/delete/{id}", name="field_delete")
     * @Template()
     *
     * @return redirect url
     */
    public function deleteAction(Request $request, $id)
    {
        $field = $this->getRepository('ContentManagerBundle:CMField')->find($id);

        $this->removeAndFlush($field);

        return $this->redirect($this->generateUrl('fields'));
    }
}
