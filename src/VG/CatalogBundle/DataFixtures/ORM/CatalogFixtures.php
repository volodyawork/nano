<?php
namespace VG\CatalogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use VG\CatalogBundle\Entity\Section;

class CatalogFixtures implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }

    public function load(ObjectManager $manager)
    {
        $food = new Section();
        $food->setName('Каталог');
        $manager->persist($food);

        $data = array(
            'Светодиодные лампы',
            'Светодиодные лампы промышленные',
            'Светильники общего назначения',
            'Светильники со встроеным датчиком',
            'Прожекторы',
            'Светильники общие и административные',
            'Светодиодные лампы',
            'Светодиодные лампы промышленные',
            'Светильники общего назначения',
            'Светильники со встроеным датчиком',
        );
        foreach ($data as $name) {
            $section = new Section();
            $section->setName($name);
            $section->setParent($food);
            $manager->persist($section);

            if ($name == 'Светодиодные лампы') {
                $subSection = new Section();
                $subSection->setName('Для точечных светильников');
                $subSection->setParent($section);
                $manager->persist($subSection);

                $subSection = new Section();
                $subSection->setName('Общего назначения');
                $subSection->setParent($section);
                $manager->persist($subSection);

                $subSection = new Section();
                $subSection->setName('Линейные Т8');
                $subSection->setParent($section);
                $manager->persist($subSection);

                $subSection = new Section();
                $subSection->setName('Дежурного освещения');
                $subSection->setParent($section);
                $manager->persist($subSection);

                $subSection = new Section();
                $subSection->setName('Для промышленных и личных светильников');
                $subSection->setParent($section);
                $manager->persist($subSection);

                $subSection = new Section();
                $subSection->setName('Автомобильные лампы');
                $subSection->setParent($section);
                $manager->persist($subSection);
            }
        }


        $manager->flush();
    }

}