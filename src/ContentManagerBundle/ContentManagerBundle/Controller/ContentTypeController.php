<?php

namespace ContentManagerBundle\ContentManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use ContentManagerBundle\ContentManagerBundle\Entity\CMContentType;
use ContentManagerBundle\ContentManagerBundle\Type\ContentTypeType;

/**
 * @Route("/contentmanager")
 */
class ContentTypeController extends Controller
{
     /**
     * @Route("/contenttypes/list", name="types")
     * @Template("ContentManagerBundle:ContentManager:types-list.html.twig")
     */
    public function listAction()
    {
        $types = $this->getDoctrine()->getRepository('ContentManagerBundle:CMContentType')->findAll();

        return array('types'=>$types);
    }
    
     /**
     * @Route("/contenttypes/new", name="types_new")
     * @Template("ContentManagerBundle:ContentManager:types-item.html.twig")
     */
    public function newItemAction(Request $request)
    {
        $type = new CMContentType; 
        $form = $this->createForm(new ContentTypeType(), $type);  

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->persist($type);
                $em->flush();

                return $this->redirect($this->generateUrl('types'));
            }
        }

        return array('form' => $form->createView(),'type' => $type); 
    }

    /**
     * @Route("/contenttypes/edit/{id}", name="types_edit")
     * @Template("ContentManagerBundle:ContentManager:types-item.html.twig")
     */
    public function editItemAction(Request $request, $id)
    {
        $type = $this->getDoctrine()->getRepository('ContentManagerBundle:CMContentType')->find($id);
        $form = $this->createForm(new ContentTypeType(), $type);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->persist($type);
                $em->flush();

                return $this->redirect($this->generateUrl('types'));
            }
        }

        return array('form' => $form->createView(),'type' => $type); 
    }

    private function getCopyItem($type){
        $copy = new CMContentType;
        $copy->setTitle($type->getTitle());
        return $copy;
    }

    /**
     * @Route("/contenttypes/copy/{id}", name="types_copy")
     * @Template("ContentManagerBundle:ContentManager:list.html.twig")
     */
    public function copyItemAction(Request $request, $id)
    {
        $type = $this->getDoctrine()->getRepository('ContentManagerBundle:CMContentType')->find($id);
        $copy = $this->getCopyItem($type);

        $em = $this->getDoctrine()->getManager();

        $em->persist($copy);
        $em->flush();

        return $this->redirect($this->generateUrl('types'));
    }

    /**
     * @Route("/contenttypes/delete/{id}", name="types_delete")
     * @Template("ContentManagerBundle:ContentManager:list.html.twig")
     */
    public function deleteAction(Request $request, $id)
    {
        $type = $this->getDoctrine()->getRepository('ContentManagerBundle:CMContentType')->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($type);
        $em->flush();

        return $this->redirect($this->generateUrl('types'));
    }
}

?>