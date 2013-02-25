<?php

namespace NGclick\ContentManagerBundle\Classes;

use NGclick\ContentManagerBundle\Entity\FieldValue;

class ExtraFields {
	
    static function loadFields($controller, $contenttype){
        $html = null;

        if($contenttype){
            $type = $controller->getDoctrine()->getRepository('ContentManagerBundle:CM_ContentType')->find($contenttype);
            foreach ($type->getFields() as $key => $field) {
                if($field->getPublished()){
                    $html .= $field->getField()->displayfield($field,null);
                }
            }
        }

        return $html;
    }

    static function saveFields($controller, $em, $request, $content, $contenttype){
        if($contenttype){
            $type = $controller->getDoctrine()->getRepository('ContentManagerBundle:CM_ContentType')->find($contenttype);
            $content->setContentType($type);
            foreach ($type->getFields() as $key => $field) {
                $value = $request->request->get($field->getName());
                $fieldvalue = new FieldValue;
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