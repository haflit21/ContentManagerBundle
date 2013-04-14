<?php

namespace ContentManagerBundle\ContentManagerBundle\Classes;

use ContentManagerBundle\ContentManagerBundle\Entity\CMFieldValue;

/**
 * class ExtraFields (use to generate extra fields html)
 */
class ExtraFields {

    /**
     *
     * @param Controller    $controller
     * @param int           $contenttype
     *
     * @return string       $html
     */
    static function loadFields($controller, $contenttype){
        $html = null;
        if ($contenttype) {
            $type = $controller->getDoctrine()->getRepository('ContentManagerBundle:CMContentType')->find($contenttype);
            foreach ($type->getFields() as $key => $field) {
                if ($field->getPublished()) {
                    $html .= $field->getField()->displayfield($field,null);
                }
            }
        }

        return $html;
    }

    /**
     *
     * @param Controller    $controller
     * @param EntityManager $em
     * @param Request       $request
     * @param Content       $content
     * @param int           $contenttype
     *
     * @return boolean      true
     */
    static function saveFields($controller, $em, $request, $content, $contenttype){
        if ($contenttype) {
            $type = $controller->getDoctrine()->getRepository('ContentManagerBundle:CMContentType')->find($contenttype);
            $content->setContentType($type);
            foreach ($type->getFields() as $key => $field) {
                $value = $request->request->get($field->getName());
                $fieldvalue = new CMFieldValue;
                $fieldvalue->setValue($value);
                $fieldvalue->setContent($content);
                $fieldvalue->setField($field);
                $content->addFieldValue($fieldvalue);
                $field->addFieldValue($fieldvalue);
                $em->persist($fieldvalue);
                $em->persist($field);
            }
        }

        return true;
    }
}

?>
