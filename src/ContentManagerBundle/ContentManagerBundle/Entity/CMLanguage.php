<?php

namespace ContentManagerBundle\ContentManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContentManagerBundle\ContentManagerBundle\Entity\Language
 *
 * @ORM\Table(name="cm_languages")
 * @ORM\Entity(repositoryClass="ContentManagerBundle\ContentManagerBundle\Entity\Repository\LanguageRepository")
 */
class CMLanguage
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string $iso
     *
     * @ORM\Column(name="iso", type="string", length=255)
     */
    private $iso;

    /**
     * @var boolean $published
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @var boolean $default_lan
     *
     * @ORM\Column(name="default_lan", type="boolean")
     */
    private $default_lan;

    /**
     * @ORM\OneToMany(targetEntity="CMCategory", mappedBy="language")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="CMContent", mappedBy="language")
     */
    private $contents;

    public function __construct(){
        $this->published = 0;
        $this->default_lan = 0;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return CMLanguage
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set iso
     *
     * @param string $iso
     * @return CMLanguage
     */
    public function setIso($iso)
    {
        $this->iso = $iso;

        return $this;
    }

    /**
     * Get iso
     *
     * @return string
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return CMLanguage
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set default_lan
     *
     * @param boolean $defaultLan
     * @return CMLanguage
     */
    public function setDefaultLan($defaultLan)
    {
        $this->default_lan = $defaultLan;

        return $this;
    }

    /**
     * Get default_lan
     *
     * @return boolean
     */
    public function getDefaultLan()
    {
        return $this->default_lan;
    }

    /**
     * Add categories
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMCategory $categories
     * @return CMLanguage
     */
    public function addCategorie(\ContentManagerBundle\ContentManagerBundle\Entity\CMCategory $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMCategory $categories
     */
    public function removeCategorie(\ContentManagerBundle\ContentManagerBundle\Entity\CMCategory $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add contents
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMContent $contents
     * @return CMLanguage
     */
    public function addContent(\ContentManagerBundle\ContentManagerBundle\Entity\CMContent $contents)
    {
        $this->contents[] = $contents;

        return $this;
    }

    /**
     * Remove contents
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMContent $contents
     */
    public function removeContent(\ContentManagerBundle\ContentManagerBundle\Entity\CMContent $contents)
    {
        $this->contents->removeElement($contents);
    }

    /**
     * Get contents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContents()
    {
        return $this->contents;
    }
}
