<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160306161250 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO param (name, slug, value) VALUES ("Почта для обратной связи", "feedback_mail", "");');
        $this->addSql('INSERT INTO param (name, slug, value) VALUES ("Почта админа", "admin_email", "");');
        $this->addSql('INSERT INTO param (name, slug, value) VALUES ("Адрес в подвале сайта", "address", "");');
        $this->addSql('INSERT INTO param (name, slug, value) VALUES ("Телефон", "phone", "");');
        $this->addSql('INSERT INTO param (name, slug, value) VALUES ("Слоган под лого", "slogan", "");');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
