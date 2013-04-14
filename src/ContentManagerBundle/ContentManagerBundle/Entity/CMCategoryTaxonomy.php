<?php

namespace ContentManagerBundle\ContentManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryTaxonomy
 *
 * @ORM\Table(name="cm_categorytaxonomy")
 * @ORM\Entity
 */
class CMCategoryTaxonomy
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
     * @ORM\OneToMany(targetEntity="CMCategory", mappedBy="taxonomy",cascade={"persist"})
     */
    private $categories;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add categories
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMCategory $categories
     * @return CMCategoryTaxonomy
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
}
