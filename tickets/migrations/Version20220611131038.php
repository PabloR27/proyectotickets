<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220611131038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activo CHANGE nombre nombre VARCHAR(255) DEFAULT NULL, CHANGE descripcion descripcion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE incidencia CHANGE activo_id activo_id INT DEFAULT NULL, CHANGE usuario_cliente_id usuario_cliente_id INT DEFAULT NULL, CHANGE usuario_tecnico_id usuario_tecnico_id INT DEFAULT NULL, CHANGE gravedad gravedad VARCHAR(20) DEFAULT NULL, CHANGE descripcion descripcion VARCHAR(255) DEFAULT NULL, CHANGE titulo titulo VARCHAR(100) DEFAULT NULL, CHANGE tipo tipo VARCHAR(100) DEFAULT NULL, CHANGE localizacion localizacion VARCHAR(255) DEFAULT NULL, CHANGE fecha fecha DATETIME DEFAULT NULL, CHANGE estado estado VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE registro ADD cambioestado VARCHAR(10) DEFAULT NULL, CHANGE incidencia_id incidencia_id INT DEFAULT NULL, CHANGE usuario_id usuario_id INT DEFAULT NULL, CHANGE texto texto VARCHAR(255) DEFAULT NULL, CHANGE fecha fecha DATETIME DEFAULT NULL, CHANGE gravedad gravedad VARCHAR(8) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuarios CHANGE roles roles JSON NOT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE image_name image_name VARCHAR(255) DEFAULT NULL, CHANGE image_size image_size INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activo CHANGE nombre nombre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE descripcion descripcion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE incidencia CHANGE activo_id activo_id INT DEFAULT NULL, CHANGE usuario_cliente_id usuario_cliente_id INT DEFAULT NULL, CHANGE usuario_tecnico_id usuario_tecnico_id INT DEFAULT NULL, CHANGE gravedad gravedad VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE descripcion descripcion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE titulo titulo VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE tipo tipo VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE localizacion localizacion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE fecha fecha DATETIME DEFAULT \'NULL\', CHANGE estado estado VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE registro DROP cambioestado, CHANGE incidencia_id incidencia_id INT DEFAULT NULL, CHANGE usuario_id usuario_id INT DEFAULT NULL, CHANGE texto texto VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE fecha fecha DATETIME DEFAULT \'NULL\', CHANGE gravedad gravedad VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE usuarios CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE image_name image_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE image_size image_size INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
    }
}
