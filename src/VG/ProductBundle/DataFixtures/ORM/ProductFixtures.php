<?php
namespace VG\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use VG\ProductBundle\Entity\Product;
use VG\ProductBundle\Entity\Section;

class ProductFixtures implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }

    public function load(ObjectManager $manager)
    {

        $data = array(
            'ECOBEAM E27 A6-5X1W (Par20) Warm 45deg',
            'Geniled Е27 5W500',
        );
        $sectionRoot = $manager->getRepository('VGCatalogBundle:Section')->getRootNodes();
        $sections = $manager->getRepository('VGCatalogBundle:Section')->children($sectionRoot[0], false);

        $i = 0;
        foreach ($data as $name) {
            $i++;
            $product1 = new Product();
            $product1->setName($i . $name);
            $product1->setMarking('001232' . $i);
            $product1->setPrice(10.2 + $i);
            $product1->setDescription('Здесь произвольное описание товара' . $i . '!!!');
            $section_id = 1;
            $product1->setSection($sections[$section_id]);

            $product1->setBest(true);
            $product1->setStatus(1);

            $manager->persist($product1);
        }
        $manager->flush();
    }

}