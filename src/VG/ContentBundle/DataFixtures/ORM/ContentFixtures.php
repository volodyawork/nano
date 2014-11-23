<?php
namespace VG\ContentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use VG\ContentBundle\Entity\Article;
use VG\ContentBundle\Entity\Category;
use VG\UserBundle\Entity\User;

class ContentFixtures implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $admin= new User(array('ROLE_ADMIN'));
        $admin->setName('admin');
        $admin->setEmail('admin@admin.ua');
        $admin->setSalt(md5(time()));
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword('123456', $admin->getSalt());
        $admin->setPassword($password);

        $manager->persist($admin);



        $category1 = new Category();
        $category1->setName('новости');
        $category1->setAlias('news');
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('статьи');
        $category2->setAlias('articles');
        $manager->persist($category2);


        $article1 = new Article();
        $article1->setName('На склад поступило 4 вида алюминиевого профиля для производства бегущих строк');
        $article1->setAlias('na_sklad_postupilo');
        $article1->setContent('Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. ');
        $article1->setStatus(1);// опубликовано, доступно для просмотра
        $article1->setShowInList(1);// показывать в списке
        $article1->setCategory($category1);
        $article1->setUser($admin);
        $manager->persist($article1);

        $article2 = new Article();
        $article2->setName('Весь модельный ряд светодиодных экранов Absen теперь доступен по прямым ценам завода');
        $article2->setAlias('ves_modelniy_ryad');
        $article2->setContent('Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. ');
        $article2->setStatus(1);// опубликовано, доступно для просмотра
        $article2->setShowInList(1);// показывать в списке
        $article2->setCategory($category1);
        $article2->setUser($admin);
        $manager->persist($article2);

        $manager->flush();


        /*
                $blog2 = new Blog();
                $blog2->setTitle('The pool on the roof must have a leak');
                $blog2->setBlog('Vestibulum vulputate mauris eget erat congue dapibus imperdiet justo scelerisque. Na. Cras elementum molestie vestibulum. Morbi id quam nisl. Praesent hendrerit, orci sed elementum lobortis.');
                $blog2->setImage('pool_leak.jpg');
                $blog2->setAuthor('Zero Cool');
                $blog2->setTags('pool, leaky, hacked, movie, hacking, symblog');
                $blog2->setCreated(new \DateTime("2011-07-23 06:12:33"));
                $blog2->setUpdated($blog2->getCreated());
                $manager->persist($blog2);
        */
    }

}