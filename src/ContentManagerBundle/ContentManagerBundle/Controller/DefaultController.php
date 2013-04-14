<?php

namespace ContentManagerBundle\ContentManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Default Controller
 */
class DefaultController extends Controller
{
    /**
     * Get Entity Manager
     *
     * @return Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Get a Repository
     *
     * @param string $class
     *
     * @return Doctrine\ORM\EntityRepository
     */
    protected function getRepository($class)
    {
        return $this->getDoctrine()->getRepository($class);
    }

    /**
     * Get local
     *
     * @return local
     */
    protected function getLocale()
    {
        return $this->getRequest()->getLocale();
    }

    /**
     * Add Flash message
     *
     * @param string $type
     * @param string $text
     */
    protected function addFlashMsg($type, $text)
    {
        $this->get('session')->getFlashBag()->add($type, $text);
    }

    /**
     * Persist and flush
     *
     * @param Object $entity
     */
    protected function persistAndFlush($entity)
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
    }

    /**
     * Persist
     *
     * @param Object $entity
     */
    protected function persist($entity)
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
    }

    /**
     * Flush
     */
    protected function flush()
    {
        $em = $this->getEntityManager();
        $em->flush();
    }

    /**
     * Remove and flush
     *
     * @param Object $entity
     */
    protected function removeAndFlush($entity)
    {
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
    }

    /**
     * Get default language
     *
     * @return CMLanguage $language
     */
    protected function getLanguageDefault()
    {
        $language = $this->getRepository('ContentManagerBundle:CMLanguage')->findBy( array( 'default_lan' => '1' ) );
        $language = current( $language );

        return $language;
    }

    /**
     * Get all languages except default language
     *
     * @return CMLanguage $language
     */
    protected function getLanguages()
    {
        $languages = $this->getRepository('ContentManagerBundle:CMLanguage')->findBy( array( 'default_lan' => '0', 'published' => '1' ) );

        return $languages;
    }
}
