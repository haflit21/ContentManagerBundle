<?php

namespace ContentManagerBundle\ContentManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContentManagerBundle\ContentManagerBundle\Entity\Field
 *
* @ORM\Table(name="cm_fields")
 * @ORM\Entity
 */
class CMField
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
     * @var string type
     *
     * @ORM\Column(name="type",type="string")
     */
    private $type;

    /**
     * @var text value
     *
     * @ORM\Column(name="value",type="text", nullable=true)
     */
    private $value;

    /**
     * @var object field
     *
     * @ORM\Column(name="field",type="object")
     */
    private $field;

    /**
     * @var string title
     *
     * @ORM\Column(name="title",type="string")
     */
    private $title;

    /**
     * @var string name
     *
     * @ORM\Column(name="name",type="string")
     */
    private $name;

    /**
     * @var boolean published
     *
     * @ORM\Column(name="published",type="boolean")
     */
    private $published;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToMany(targetEntity="CMContentType", inversedBy="fields")
     * @ORM\JoinTable(name="CMfields_type_relation")
     */
    private $contentType;

    /**
     * @ORM\OneToMany(targetEntity="CMFieldValue", mappedBy="content", cascade={"remove"})
     */
    private $fieldvalues;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contentType = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fieldvalues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->published = 0;
        $this->created = new \DateTime();
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
     * Set type
     *
     * @param string $type
     * @return CMField
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return CMField
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
     * Set field
     *
     * @param \stdClass $field
     * @return CMField
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \stdClass
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return CMField
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
     * Set name
     *
     * @param string $name
     * @return CMField
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return CMField
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
     * Set created
     *
     * @param \DateTime $created
     * @return CMField
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
     * Add contentType
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMContentType $contentType
     * @return CMField
     */
    public function addContentType(\ContentManagerBundle\ContentManagerBundle\Entity\CMContentType $contentType)
    {
        $this->contentType[] = $contentType;

        return $this;
    }

    /**
     * Remove contentType
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMContentType $contentType
     */
    public function removeContentType(\ContentManagerBundle\ContentManagerBundle\Entity\CMContentType $contentType)
    {
        $this->contentType->removeElement($contentType);
    }

    /**
     * Get contentType
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Add fieldvalues
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMFieldValue $fieldvalues
     * @return CMField
     */
    public function addFieldvalue(\ContentManagerBundle\ContentManagerBundle\Entity\CMFieldValue $fieldvalues)
    {
        $this->fieldvalues[] = $fieldvalues;

        return $this;
    }

    /**
     * Remove fieldvalues
     *
     * @param \ContentManagerBundle\ContentManagerBundle\Entity\CMFieldValue $fieldvalues
     */
    public function removeFieldvalue(\ContentManagerBundle\ContentManagerBundle\Entity\CMFieldValue $fieldvalues)
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
}
