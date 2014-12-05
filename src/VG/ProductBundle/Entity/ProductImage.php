<?php


namespace VG\ProductBundle\Entity;

use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\ORM\Mapping\ManyToOne;
use VG\ProductBundle\Entity\Product;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductImage
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ProductImage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;
    /**
     * @var Product
     * @ManyToOne(targetEntity="VG\ProductBundle\Entity\Product", inversedBy="images")
     */
    protected $product;

    /**
     * @var Media
     *
     * @ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     */
    protected $image;
    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \VG\ProductBundle\Entity\Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return \VG\ProductBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param \Application\Sonata\MediaBundle\Entity\Media $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
}