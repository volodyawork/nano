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
        $admin = new User(array('ROLE_ADMIN'));
        $admin->setName('admin');
        $admin->setEmail('admin@admin.ua');
        $admin->setSalt(md5(time()));
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword('123456', $admin->getSalt());
        $admin->setPassword($password);

        $manager->persist($admin);


        $category1 = new Category();
        $category1->setName('новости');
        $category1->setAlias('novosti');
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('статьи');
        $category2->setAlias('pages');
        $manager->persist($category2);

        $article1 = new Article();
        $article1->setName('О компании');
        $article1->setSlug('about');
        $article1->setContent('Здесь текст о компании.');
        $article1->setStatus(1); // опубликовано, доступно для просмотра
        $article1->setShowInList(0); // показывать в списке
        $article1->setCategory($category2);
        $article1->setUser($admin);
        $manager->persist($article1);

        $article1 = new Article();
        $article1->setName('Контакты');
        $article1->setSlug('contacts');
        $article1->setContent('Здесь контакты компании.');
        $article1->setStatus(1);
        $article1->setShowInList(0);
        $article1->setCategory($category2);
        $article1->setUser($admin);
        $manager->persist($article1);

        $article1 = new Article();
        $article1->setName('Мы рады приветствовать вас на страницах сайта компании "Наносвет"');
        $article1->setSlug('homepage');
        $article1->setContent(
            '<p>С каждым днем становится все больше людей, которых волнует вопрос энергосбережения,
                                снижение эксплуатационных затрат, повышение надежности системы освещения и мы очень
                                признательны,
                                что Вы один из них. Эффективное и экономичное освещение, это залог успешного развития
                                Вашей организации. </p>

                            <p>Нашими заказчиками являются промышленные предприятия, электромонтажные организации,
                                связанные с ремонтом, реконструкцией и
                                строительством промышленных, жилых и прочих объектов, организации сферы ЖКХ,
                                товарищества собственников жилья, индивидуальные предприниматели и частные лица.</p>

                            <p>Накопленный опыт позволяет нам подобрать энергосберегающее оборудование для любых зон
                                освещения и снизить расходы там, где казалось бы, сэкономить уже невозможно.</p>

                            <p>Качество, простота монтажа и эксплуатации делает нашу продукцию очень
                                привлекательной. </p>

                            <p>Мы являемся сертифицированными представителями заводов - производителей энергосберегающей
                                светотехники и предлагаем Вам приобрести продукцию по ценам производителя.</p>'
        );
        $article1->setStatus(1);
        $article1->setShowInList(0);
        $article1->setCategory($category2);
        $article1->setUser($admin);
        $manager->persist($article1);

        $article1 = new Article();
        $article1->setName('На склад поступило 4 вида алюминиевого профиля для производства бегущих строк');
        //$article1->setSlug('na_sklad_postupilo');
        $article1->setContent(
            'Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. '
        );
        $article1->setStatus(0); // опубликовано, доступно для просмотра
        $article1->setShowInList(1); // показывать в списке
        $article1->setCategory($category1);
        $article1->setUser($admin);
        $manager->persist($article1);

        $article2 = new Article();
        $article2->setName('Весь модельный ряд светодиодных экранов Absen теперь доступен по прямым ценам завода');
        $article2->setSlug('ves_modelniy_ryad');
        $article2->setContent(
            'Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. Здесь полное описание первой новости. '
        );
        $article2->setStatus(1); // опубликовано, доступно для просмотра
        $article2->setShowInList(0); // показывать в списке
        $article2->setCategory($category2);
        $article2->setUser($admin);
        $manager->persist($article2);


        $manager->flush();

    }

}