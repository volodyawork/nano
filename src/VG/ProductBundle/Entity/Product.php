<?php

namespace VG\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sonata\MediaBundle\Model\Media;
use VG\CatalogBundle\Entity\Section;
use VG\ProductBundle\Entity\ProductImage;

/**
 * Product
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="marking", type="string", length=255)
     */
    private $marking;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var boolean
     * @ORM\Column(name="best", type="boolean", nullable=true)
     */
    private $best;

    /**
     * @var Section
     * @ORM\ManyToOne(targetEntity="VG\CatalogBundle\Entity\Section", inversedBy="products")
     */
    private $section;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="VG\ProductBundle\Entity\ProductImage", mappedBy="product", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"position"="ASC"})
     */
    private $images;


    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $marking
     */
    public function setMarking($marking)
    {
        $this->marking = $marking;

        return $this;
    }

    /**
     * @return string
     */
    public function getMarking()
    {
        return $this->marking;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \VG\CatalogBundle\Entity\Section $section
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * @return \VG\CatalogBundle\Entity\Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param boolean $best
     */
    public function setBest($best)
    {
        $this->best = $best;
    }

    /**
     * @return boolean
     */
    public function getBest()
    {
        return $this->best;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $images
     */
    public function setImages($images)
    {

        if (!$images) {
            $this->images = new \Doctrine\Common\Collections\ArrayCollection();
            return;
        }

        foreach ($images as $image) {
            $image->setProduct($this);
        }

        foreach ($this->getImages() as $image) {
            if (!$images->contains($image)) {
                $this->getImages()->removeElement($image);
                $image->setProduct(null);
            }
        }

        $this->images = $images;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add image
     *
     * @param ProductImage $ProductImage
     */
    public function addImage(\VG\ProductBundle\Entity\ProductImage $ProductImage)
    {
        $ProductImage->setProduct($this);
        $this->images[] = $ProductImage;

        return $this;
    }

    /**
     * @param ProductImage $ProductImage
     */
    public function removeImage(\VG\ProductBundle\Entity\ProductImage $ProductImage)
    {
        $this->getImages()->removeElement($ProductImage);
        $ProductImage->setProduct(null);
    }

}
