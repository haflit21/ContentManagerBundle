<?php

namespace NGclick\ContentManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NGclick\ContentManagerBundle\Entity\Content
 *
 * @ORM\Table(name="cm_contents")
 * @ORM\Entity(repositoryClass="NGclick\ContentManagerBundle\Entity\Repository\ContentRepository")
 */
class CMContent
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
     * @ORM\ManyToOne(targetEntity="CMLanguage", inversedBy="contents")
     * @ORM\JoinColumn(name="language_id")
     */
    private $language;

    /**
     * @ORM\ManyToOne(targetEntity="CMContentTaxonomy", inversedBy="contents" ,cascade={"persist"})
     * @ORM\JoinColumn(name="taxonomy")
     */
    private $taxonomy;

    /**
     * @ORM\ManyToMany(targetEntity="CMCategory", inversedBy="contents")
     * @ORM\JoinTable(name="CMcontent_category_relation")
     */
    private $categories;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var boolean $published
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @var string $metatitle
     *
     * @ORM\Column(name="metatitle", type="string", length=255)
     */
    private $metatitle;

    /**
     * @var text $metadescription
     *
     * @ORM\Column(name="metadescription", type="text", nullable=true)
     */
    private $metadescription;

    /**
     * @var string $canonical
     *
     * @ORM\Column(name="canonical", type="string", length=255, nullable=true)
     */
    private $canonical;

    /**
     * @ORM\OneToMany(targetEntity="CMContent", mappedBy="referenceContent")
     */
    private $translations;

    /**
     * @ORM\ManyToOne(targetEntity="CMContent", inversedBy="translations")
     */
    private $referenceContent;

    /**
     * @ORM\OneToMany(targetEntity="CMFieldValue", mappedBy="content", cascade={"remove", "persist"})
     */
    private $fieldvalues;

    /**
     * @ORM\ManyToOne(targetEntity="CMContentType", inversedBy="contents")
     */
    private $contenttype;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fieldvalues = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CMContent
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
     * @return CMContent
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
     * Set created
     *
     * @param \DateTime $created
     * @return CMContent
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return CMContent
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
     * Set metatitle
     *
     * @param string $metatitle
     * @return CMContent
     */
    public function setMetatitle($metatitle)
    {
        $this->metatitle = $metatitle;
    
        return $this;
    }

    /**
     * Get metatitle
     *
     * @return string 
     */
    public function getMetatitle()
    {
        return $this->metatitle;
    }

    /**
     * Set metadescription
     *
     * @param string $metadescription
     * @return CMContent
     */
    public function setMetadescription($metadescription)
    {
        $this->metadescription = $metadescription;
    
        return $this;
    }

    /**
     * Get metadescription
     *
     * @return string 
     */
    public function getMetadescription()
    {
        return $this->metadescription;
    }

    /**
     * Set canonical
     *
     * @param string $canonical
     * @return CMContent
     */
    public function setCanonical($canonical)
    {
        $this->canonical = $canonical;
    
        return $this;
    }

    /**
     * Get canonical
     *
     * @return string 
     */
    public function getCanonical()
    {
        return $this->canonical;
    }

    /**
     * Set language
     *
     * @param \NGclick\ContentManagerBundle\Entity\CMLanguage $language
     * @return CMContent
     */
    public function setLanguage(\NGclick\ContentManagerBundle\Entity\CMLanguage $language = null)
    {
        $this->language = $language;
    
        return $this;
    }

    /**
     * Get language
     *
     * @return \NGclick\ContentManagerBundle\Entity\CMLanguage 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set taxonomy
     *
     * @param \NGclick\ContentManagerBundle\Entity\CMContentTaxonomy $taxonomy
     * @return CMContent
     */
    public function setTaxonomy(\NGclick\ContentManagerBundle\Entity\CMContentTaxonomy $taxonomy = null)
    {
        $this->taxonomy = $taxonomy;
    
        return $this;
    }

    /**
     * Get taxonomy
     *
     * @return \NGclick\ContentManagerBundle\Entity\CMContentTaxonomy 
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * Add categories
     *
     * @param \NGclick\ContentManagerBundle\Entity\CMCategory $categories
     * @return CMContent
     */
    public function addCategorie(\NGclick\ContentManagerBundle\Entity\CMCategory $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \NGclick\ContentManagerBundle\Entity\CMCategory $categories
     */
    public function removeCategorie(\NGclick\ContentManagerBundle\Entity\CMCategory $categories)
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
     * Add translations
     *
     * @param \NGclick\ContentManagerBundle\Entity\CMContent $translations
     * @return CMContent
     */
    public function addTranslation(\NGclick\ContentManagerBundle\Entity\CMContent $translations)
    {
        $this->translations[] = $translations;
    
        return $this;
    }

    /**
     * Remove translations
     *
     * @param \NGclick\ContentManagerBundle\Entity\CMContent $translations
     */
    public function removeTranslation(\NGclick\ContentManagerBundle\Entity\CMContent $translations)
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
     * Set referenceContent
     *
     * @param \NGclick\ContentManagerBundle\Entity\CMContent $referenceContent
     * @return CMContent
     */
    public function setReferenceContent(\NGclick\ContentManagerBundle\Entity\CMContent $referenceContent = null)
    {
        $this->referenceContent = $referenceContent;
    
        return $this;
    }

    /**
     * Get referenceContent
     *
     * @return \NGclick\ContentManagerBundle\Entity\CMContent 
     */
    public function getReferenceContent()
    {
        return $this->referenceContent;
    }

    /**
     * Add fieldvalues
     *
     * @param \NGclick\ContentManagerBundle\Entity\CMFieldValue $fieldvalues
     * @return CMContent
     */
    public function addFieldvalue(\NGclick\ContentManagerBundle\Entity\CMFieldValue $fieldvalues)
    {
        $this->fieldvalues[] = $fieldvalues;
    
        return $this;
    }

    /**
     * Remove fieldvalues
     *
     * @param \NGclick\ContentManagerBundle\Entity\CMFieldValue $fieldvalues
     */
    public function removeFieldvalue(\NGclick\ContentManagerBundle\Entity\CMFieldValue $fieldvalues)
    {
        $this->fieldvalues->removeElement($fieldvalues);
    }

    /**
     * Get fieldvalues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFieldvalues()
    {
        return $this->fieldvalues;
    }

    /**
     * Set contenttype
     *
     * @param \NGclick\ContentManagerBundle\Entity\CMContentType $contenttype
     * @return CMContent
     */
    public function setContenttype(\NGclick\ContentManagerBundle\Entity\CMContentType $contenttype = null)
    {
        $this->contenttype = $contenttype;
    
        return $this;
    }

    /**
     * Get contenttype
     *
     * @return \NGclick\ContentManagerBundle\Entity\CMContentType 
     */
    public function getContenttype()
    {
        return $this->contenttype;
    }
}