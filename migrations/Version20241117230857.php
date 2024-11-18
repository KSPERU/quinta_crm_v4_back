<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241117230857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE colaborador (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, c_nombres VARCHAR(64) NOT NULL, c_apellido_paterno VARCHAR(64) NOT NULL, c_apellido_materno VARCHAR(64) DEFAULT NULL, c_fecha_nacimiento DATETIME DEFAULT NULL, c_telefono VARCHAR(9) DEFAULT NULL, c_correo VARCHAR(64) DEFAULT NULL, c_foto LONGTEXT DEFAULT NULL, c_genero VARCHAR(32) DEFAULT NULL, c_fecha_creacion DATETIME NOT NULL, c_estado TINYINT(1) NOT NULL, INDEX IDX_D2F80BB3DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE colaborador_taller (id INT AUTO_INCREMENT NOT NULL, colaboradores_id INT NOT NULL, talleres_id INT NOT NULL, ct_tipo_colaborador INT DEFAULT NULL, ct_asistencia TINYINT(1) DEFAULT NULL, ct_justificacion LONGTEXT DEFAULT NULL, ct_estado TINYINT(1) NOT NULL, INDEX IDX_65EF6D77FFC56C40 (colaboradores_id), INDEX IDX_65EF6D778761C72 (talleres_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE colectiva (id INT AUTO_INCREMENT NOT NULL, temporada_id INT NOT NULL, c_nombre VARCHAR(64) NOT NULL, c_miembros VARCHAR(64) DEFAULT NULL, c_fecha_creacion DATETIME NOT NULL, c_estado TINYINT(1) NOT NULL, c_estado_sys INT DEFAULT NULL, INDEX IDX_A01AE6E76E1CF8A8 (temporada_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programa (id INT AUTO_INCREMENT NOT NULL, pg_nombre VARCHAR(64) NOT NULL, pg_imagen LONGTEXT DEFAULT NULL, pg_organizacion VARCHAR(64) NOT NULL, pg_fecha_creacion DATETIME NOT NULL, pg_fecha_inicio DATETIME NOT NULL, pg_fecha_fin DATETIME NOT NULL, pg_estado TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taller (id INT AUTO_INCREMENT NOT NULL, colectiva_id INT NOT NULL, t_nombre VARCHAR(64) NOT NULL, t_fecha_hora DATETIME NOT NULL, t_modalidad INT NOT NULL, t_tipo_taller VARCHAR(32) DEFAULT NULL, t_estado TINYINT(1) NOT NULL, t_estado_sys INT DEFAULT NULL, INDEX IDX_139F45843FAAAEA5 (colectiva_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE temporada (id INT AUTO_INCREMENT NOT NULL, programa_id INT NOT NULL, tem_nombre VARCHAR(64) NOT NULL, tem_fecha_creacion DATETIME NOT NULL, tem_fecha_inicio DATETIME NOT NULL, tem_fecha_fin DATETIME NOT NULL, tem_estado TINYINT(1) NOT NULL, tem_estado_sys INT DEFAULT NULL, INDEX IDX_9A6BDEBDFD8A7328 (programa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, estado TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE colaborador ADD CONSTRAINT FK_D2F80BB3DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE colaborador_taller ADD CONSTRAINT FK_65EF6D77FFC56C40 FOREIGN KEY (colaboradores_id) REFERENCES colaborador (id)');
        $this->addSql('ALTER TABLE colaborador_taller ADD CONSTRAINT FK_65EF6D778761C72 FOREIGN KEY (talleres_id) REFERENCES taller (id)');
        $this->addSql('ALTER TABLE colectiva ADD CONSTRAINT FK_A01AE6E76E1CF8A8 FOREIGN KEY (temporada_id) REFERENCES temporada (id)');
        $this->addSql('ALTER TABLE taller ADD CONSTRAINT FK_139F45843FAAAEA5 FOREIGN KEY (colectiva_id) REFERENCES colectiva (id)');
        $this->addSql('ALTER TABLE temporada ADD CONSTRAINT FK_9A6BDEBDFD8A7328 FOREIGN KEY (programa_id) REFERENCES programa (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colaborador DROP FOREIGN KEY FK_D2F80BB3DB38439E');
        $this->addSql('ALTER TABLE colaborador_taller DROP FOREIGN KEY FK_65EF6D77FFC56C40');
        $this->addSql('ALTER TABLE colaborador_taller DROP FOREIGN KEY FK_65EF6D778761C72');
        $this->addSql('ALTER TABLE colectiva DROP FOREIGN KEY FK_A01AE6E76E1CF8A8');
        $this->addSql('ALTER TABLE taller DROP FOREIGN KEY FK_139F45843FAAAEA5');
        $this->addSql('ALTER TABLE temporada DROP FOREIGN KEY FK_9A6BDEBDFD8A7328');
        $this->addSql('DROP TABLE colaborador');
        $this->addSql('DROP TABLE colaborador_taller');
        $this->addSql('DROP TABLE colectiva');
        $this->addSql('DROP TABLE programa');
        $this->addSql('DROP TABLE taller');
        $this->addSql('DROP TABLE temporada');
        $this->addSql('DROP TABLE usuario');
    }
}
