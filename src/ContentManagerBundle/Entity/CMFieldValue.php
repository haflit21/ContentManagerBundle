<?php

namespace NGclick\ContentManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FieldValue
 *
 * @ORM\Table(name="cm_fieldsvalues")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
*/
class CMFieldValue
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CMContent", inversedBy="fieldsvalues", cascade={"remove", "persist"})
     */
    private $content;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CMField", inversedBy="fieldsvalues", cascade={"remove"})
     */
    private $field;

    /**
     * @var string value
     *
     * @ORM\Column(name="value",type="string")
     */
    private $value;

    /**
     * @ORM\postLoad
     */
    public function unserializeFieldValue()
    {
        $this->value = unserialize($this->value);
    }

    /**
     * @ORM\prePersist
     * @ORM\preUpdate
     */
    public function serializeFieldValue()
    {        
        $this->value = serialize($this->value);
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fieldsvalue = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set value
     *
     * @param string $value
     * @return CMFieldValue
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set content
     *
     * @param \NGclick\ContentManagerBundle\Entity\CMContent $content
     * @return CMFieldValue
     */
    public function setContent(\NGclick\ContentManagerBundle\Entity\CMContent $content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return \NGclick\ContentManagerBundle\Entity\CMContent 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set field
     *
     * @param \NGclick\ContentManagerBundle\Entity\CMField $field
     * @return CMFieldValue
     */
    public function setField(\NGclick\ContentManagerBundle\Entity\CMField $field)
    {
        $this->field = $field;
    
        return $this;
    }

    /**
     * Get field
     *
     * @return \NGclick\ContentManagerBundle\Entity\CMField 
     */
    public function getField()
    {
        return $this->field;
    }
}