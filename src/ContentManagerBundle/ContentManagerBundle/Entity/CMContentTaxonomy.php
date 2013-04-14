<?php

namespace ContentManagerBundle\ContentManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContentTaxonomy
 *
 * @ORM\Table(name="cm_contenttaxonomy")
 * @ORM\Entity(repositoryClass="ContentManagerBundle\ContentManagerBundle\Entity\Repository\ContentTaxonomyRepository")
 */
class CMContentTaxonomy
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="CMContent", mappedBy="taxonomy",cascade={"persist"})
     */
    private $contents;

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Add contents
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMContent $contents
     * @return CMContentTaxonomy
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
