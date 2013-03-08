<?php

namespace ContentManagerBundle\ContentManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContentManagerBundle\ContentManagerBundle\Entity\CMCategory
 *
 * @ORM\Table(name="cm_categories")
 * @ORM\Entity(repositoryClass="ContentManagerBundle\ContentManagerBundle\Entity\Repository\CategoryRepository")
 */
class CMCategory
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
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean $published
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity="CMLanguage", inversedBy="categories")
     * @ORM\JoinColumn(name="language_id")
     */
    private $language;

    /**
     * @ORM\OneToMany(targetEntity="CMCategory", mappedBy="referenceCategory")
     */
    private $translations;

    /**
     * @ORM\ManyToOne(targetEntity="CMCategory", inversedBy="translations")
     */
    private $referenceCategory;

    /**
     * @ORM\ManyToOne(targetEntity="CMCategoryTaxonomy", inversedBy="categories" ,cascade={"persist"})
     * @ORM\JoinColumn(name="taxonomy")
     */
    private $taxonomy;

    /**
     * @ORM\ManyToMany(targetEntity="CMContent", mappedBy="categories")
     */
    private $contents;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contents = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CMCategory
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
     * Set description
     *
     * @param string $description
     * @return CMCategory
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set language
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMLanguage $language
     * @return CMCategory
     */
    public function setLanguage(\ContentManagerBundle\ContentManagerBundle\Entity\CMLanguage $language = null)
    {
        $this->language = $language;
    
        return $this;
    }

    /**
     * Get language
     *
     * @return \ContentManagerBundle\ContentManagerBundle\Entity\CMLanguage 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Add translations
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMCategory $translations
     * @return CMCategory
     */
    public function addTranslation(\ContentManagerBundle\ContentManagerBundle\Entity\CMCategory $translations)
    {
        $this->translations[] = $translations;
    
        return $this;
    }

    /**
     * Remove translations
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMCategory $translations
     */
    public function removeTranslation(\ContentManagerBundle\ContentManagerBundle\Entity\CMCategory $translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Set referenceCategory
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMCategory $referenceCategory
     * @return CMCategory
     */
    public function setReferenceCategory(\ContentManagerBundle\ContentManagerBundle\Entity\CMCategory $referenceCategory = null)
    {
        $this->referenceCategory = $referenceCategory;
    
        return $this;
    }

    /**
     * Get referenceCategory
     *
     * @return \ContentManagerBundle\ContentManagerBundle\Entity\CMCategory 
     */
    public function getReferenceCategory()
    {
        return $this->referenceCategory;
    }

    /**
     * Set taxonomy
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMCategoryTaxonomy $taxonomy
     * @return CMCategory
     */
    public function setTaxonomy(\ContentManagerBundle\ContentManagerBundle\Entity\CMCategoryTaxonomy $taxonomy = null)
    {
        $this->taxonomy = $taxonomy;
    
        return $this;
    }

    /**
     * Get taxonomy
     *
     * @return \ContentManagerBundle\ContentManagerBundle\Entity\CMCategoryTaxonomy 
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * Add contents
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMContent $contents
     * @return CMCategory
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

    /**
     * Set published
     *
     * @param boolean $published
     * @return CMCategory
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
}