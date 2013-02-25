<?php

namespace ContentManagerBundle\ContentManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContentManagerBundle\ContentManagerBundle\Entity\CMCategory
 *
 * @ORM\Table(name="cm_categories")
 * @ORM\Entity
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
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="CMContent", mappedBy="categories")
     */
    private $contents;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tutorials = new \Doctrine\Common\Collections\ArrayCollection();
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
}