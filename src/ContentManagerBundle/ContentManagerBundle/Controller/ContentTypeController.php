<?php

namespace ContentManagerBundle\ContentManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use ContentManagerBundle\ContentManagerBundle\Entity\CMContentType;
use ContentManagerBundle\ContentManagerBundle\Type\ContentTypeType;

/**
 * class ContentController
 *
 * @Route("/contentmanager")
 */
class ContentTypeController extends DefaultController
{
    /**
     * List Content Types
     *
     * @Route("/contentTypes", name="types")
     * @Template("ContentManagerBundle:Type:list.html.twig")
     *
     * @return array
     */
    public function listAction()
    {
        $types = $this->getRepository('ContentManagerBundle:CMContentType')->findAll();

        return array(
            'types'=>$types
        );
    }

    /**
     * Create Content Type
     *
     * @param Request $request
     *
     * @Route("/contentType/new", name="type_new")
     * @Template("ContentManagerBundle:Type:item.html.twig")
     *
     * @return array
     */
    public function newItemAction(Request $request)
    {
        $type = new CMContentType;
        $form = $this->createForm(new ContentTypeType(), $type);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $this->persistAndFlush($type);

                return $this->redirect($this->generateUrl('types'));
            }
        }

        return array(
            'form' => $form->createView(),
            'type' => $type
        );
    }

    /**
     * Edit Content Type
     *
     * @param Request $request
     *
     * @Route("/contentType/edit/{id}", name="type_edit")
     * @Template("ContentManagerBundle:Type:item.html.twig")
     *
     * @return array
     */
    public function editItemAction(Request $request, $id)
    {
        $type = $this->getRepository('ContentManagerBundle:CMContentType')->find($id);
        $form = $this->createForm(new ContentTypeType(), $type);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $this->persistAndFlush($type);

                return $this->redirect($this->generateUrl('types'));
            }
        }

        return array(
            'form' => $form->createView(),
            'type' => $type
        );
    }

    /**
     * Get Copy of ContentType
     *
     * @param CMContent     $content
     *
     * @return CMContent    $copy
     */
    private function getCopyItem($type){
        $copy = new CMContentType;
        $copy->setTitle($type->getTitle());
        $copy->setTemplate($type->getTemplate());
        return $copy;
    }

    /**
     * Copy ContentType
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/contentType/copy/{id}", name="type_copy")
     * @Template()
     *
     * @return redirect url
     */
    public function copyItemAction(Request $request, $id)
    {
        $type = $this->getRepository('ContentManagerBundle:CMContentType')->find($id);
        $copy = $this->getCopyItem($type);

        $this->persistAndFlush($copy);

        return $this->redirect($this->generateUrl('types'));
    }

    /**
     * Delete ContentType
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/contentType/delete/{id}", name="type_delete")
     * @Template("ContentManagerBundle:Type:list.html.twig")
     *
     * @return redirect url
     */
    public function deleteAction(Request $request, $id)
    {
        $type = $this->getRepository('ContentManagerBundle:CMContentType')->find($id);

        $this->removeAndFlush($type);

        return $this->redirect($this->generateUrl('types'));
    }
}

?>
