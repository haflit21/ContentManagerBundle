<?php

namespace ContentManagerBundle\ContentManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use ContentManagerBundle\ContentManagerBundle\Entity\CMLanguage;
use ContentManagerBundle\ContentManagerBundle\Type\LanguageType;

/**
 * class LanguageController
 *
 * @Route("/contentmanager")
 */
class LanguageController extends DefaultController
{
    /**
     * List Languages
     *
     * @Route("/languages", name="languages")
     * @Template("ContentManagerBundle:Language:list.html.twig")
     *
     * @return array
     */
    public function listAction()
    {
    	$languages = $this->getRepository('ContentManagerBundle:CMLanguage')->findall();

        return array(
            'languages' => $languages
        );
    }

    /**
     * Is default language
     *
     * @param CMLanguage $language
     *
     * @return boolean
     */
    private function isDefault($language){
    	$default = $this->loadDefault();
    	if (!empty($default)) {
    		if ($language->getId() == $default->getId()) {
	    		return true;
	    	} else {
	    		return false;
	    	}
    	}

    	return false;
    }

    /**
     * Have default language
     *
     * @return boolean
     */
    private function haveDefault(){
    	$language = $this->getRepository('ContentManagerBundle:CMLanguage')->findBy(array('default_lan'=>1));
    	if (!empty($language)) {
    		return true;
    	} else {
    		return false;
    	}
    }

    /**
     * Load default language
     *
     * @return CMLanguage $language
     */
    private function loadDefault(){
    	$language = $this->getRepository('ContentManagerBundle:CMLanguage')->findBy(array('default_lan'=>1));

    	return current($language);
    }

    /**
     * Manage default language
     *
     * @param CMLanguage $language
     *
     * @return CMLanguage $language
     */
   	private function manageDefault($language){
   		if(!$this->isDefault($language)){
   			if($default = $this->loadDefault()){
   				$default->setDefaultLan(0);

		   		$em = $this->getDoctrine()->getManager();
		   		$em->persist($default);
		   		$em->flush();

   				$language->setDefaultLan(1);
   				$language->setPublished(1);
   			}else{
   				$language->setDefaultLan(1);
   				$language->setPublished(1);
   			}
   		}

   		return $language;
   	}

    /**
     * Create Language
     *
     * @param Request   $request
     *
     * @Route("/language/new", name="language_new")
     * @Template("ContentManagerBundle:Language:item.html.twig")
     *
     * @return array
     */
    public function newItemAction(Request $request)
    {
        $language = new CMLanguage;
        $form = $this->createForm(new LanguageType(), $language);

        if ($request->isMethod('POST')) {
        	$form->bind($request);
	        if ($form->isValid()) {
	        	if ($language->getDefaultLan()) {
	        		$language = $this->manageDefault($language);
	        	} else {
	        		if (!$this->haveDefault()) {
	        			$this->addFlashMsg('info', 'No default language. Please select this one as default');

	        			return array(
                            'form'     => $form->createView(),
                            'language' => $language
                        );
	        		}
	        	}

	        	$this->persistAndFlush($language);

	            return $this->redirect($this->generateUrl('languages'));
	        }
	    }

        return array(
            'form'     => $form->createView(),
            'language' => $language
        );
    }

    /**
     * Edit Language
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/language/edit/{id}", name="language_edit")
     * @Template("ContentManagerBundle:Language:item.html.twig")
     *
     * @return array
     */
    public function editItemAction(Request $request, $id)
    {
        $language = $this->getRepository('ContentManagerBundle:CMLanguage')->find($id);
        $form = $this->createForm(new LanguageType(), $language);

        if ($request->isMethod('POST')) {
        	$form->bind($request);

	        if ($form->isValid()) {
	        	if ($language->getDefaultLan()) {
	        		$language = $this->manageDefault($language);
	        	} else {
	        		if (!$this->haveDefault()) {
	        			$this->addFlashMsg('info', 'No default language. Please select this one as default');

	        			return array(
                            'form'     => $form->createView(),
                            'language' => $language
                        );
	        		}
	        	}

	        	$this->persistAndFlush($language);

	            return $this->redirect($this->generateUrl('languages'));
	        }
	    }

        return array(
            'form'     => $form->createView(),
            'language' => $language
        );
    }

    /**
     * Get copy Language
     *
     * @param CMLanguage $language
     *
     * @return CMLanguage $copy
     */
    private function getCopyItem($language){
    	$copy = new CMLanguage;
        $copy->setTitle($language->getTitle());
        $copy->setIso($language->getIso());

        return $copy;
    }

    /**
     * Copy Language
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/language/copy/{id}", name="language_copy")
     * @Template()
     *
     * @return redirect url
     */
    public function copyItemAction(Request $request, $id)
    {
        $language = $this->getRepository('ContentManagerBundle:CMLanguage')->find($id);
        $copy = $this->getCopyItem($language);

       	$this->persistAndFlush($copy);

        return $this->redirect($this->generateUrl('languages'));
    }

    /**
     * Default Language
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/language/default/{id}", name="language_default")
     * @Template()
     *
     * @return redirect url
     */
    public function defaultItemAction(Request $request, $id)
    {
        $language = $this->getRepository('ContentManagerBundle:CMLanguage')->find($id);
        $default = $this->loadDefault();

        if (!$language->getDefaultLan()) {
        	if (!empty($default)) {
        		$default->setDefaultLan(0);
        	}
        	$language->setDefaultLan(1);
        	$language->setPublished(1);
        } else {
        	$this->addFlashMsg('error', 'You can\'t change state of this element' );

			return $this->redirect($this->generateUrl('languages'));
        }

       	$this->persist($language);
        if(!empty($default)){
       		$this->persist($default);
        }
       	$this->flush();

        return $this->redirect($this->generateUrl('languages'));
    }

    /**
     * Publish Language
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/language/publish/{id}", name="language_publish")
     * @Template()
     *
     * @return redirect url
     */
    public function publishItemAction(Request $request, $id)
    {
        $language = $this->getRepository('ContentManagerBundle:CMLanguage')->find($id);

        if (!$language->getDefaultLan()) {
        	if ($language->getPublished())
        		$language->setPublished(0);
        	else
        		$language->setPublished(1);
        } else {
        	$this->addFlashMsg('error', 'You can\'t change unpublished this element' );

			return $this->redirect($this->generateUrl('languages'));
        }

       	$this->persistAndFlush($language);

        return $this->redirect($this->generateUrl('languages'));
    }

    /**
     * Delete Language
     *
     * @param Request   $request
     * @param int       $id
     *
     * @Route("/language/delete/{id}", name="language_delete")
     * @Template()
     *
     * @return redirect url
     */
    public function deleteAction(Request $request, $id)
    {
        $language = $this->getRepository('ContentManagerBundle:CMLanguage')->find($id);

       	if ($this->isDefault($language)) {
			$this->addFlashMsg('error', 'You can delete the default language');

			return $this->redirect($this->generateUrl('languages'));
		}

       	$this->removeAndFlush($language);

       	return $this->redirect($this->generateUrl('languages'));
    }

}
