<?php

namespace ContentManagerBundle\ContentManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContentManagerBundle\ContentManagerBundle\Entity\CMMetas
 *
 * @ORM\Table(name="cm_metas")
 * @ORM\Entity(repositoryClass="ContentManagerBundle\ContentManagerBundle\Entity\Repository\MetasRepository")
 */
class CMMetas
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set metatitle
     *
     * @param string $metatitle
     * @return CMMetas
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
     * @return CMMetas
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
     * @return CMMetas
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
}