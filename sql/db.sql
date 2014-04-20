
/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

CREATE DATABASE IF NOT EXISTS PP
    CHARACTER SET = utf8;

USE PP;

CREATE TABLE IF NOT EXISTS User (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    username varchar(25) NOT NULL,
    name varchar(50) NOT NULL,
    location varchar(50) NOT NULL,
    password varchar(128) NOT NULL,
    email varchar(255) NOT NULL,
    created datetime NOT NULL,
    enabled boolean NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY (username),
    UNIQUE KEY (email)
) COMMENT="Users";

CREATE TABLE IF NOT EXISTS Role (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    name varchar(30) NOT NULL,
    role varchar(20) NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY (role)
) COMMENT="User roles";

INSERT IGNORE INTO
    Role (name, role)
VALUES
    ('User', 'ROLE_USER'),
    ('Administrator', 'ROLE_ADMIN'),
    ('Super Administrator', 'ROLE_SUPER_ADMIN');

CREATE TABLE IF NOT EXISTS UserRoleMap (
    userId int UNSIGNED NOT NULL,
    roleId int UNSIGNED NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (userId, roleId),
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (roleId) REFERENCES Role(id) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT="User roles";

CREATE TABLE IF NOT EXISTS Application (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    userId int UNSIGNED NOT NULL,
    name varchar(50) NOT NULL,
    description varchar(500) NOT NULL,
    token varchar(64) NOT NULL,
    created datetime NOT NULL,
    enabled boolean NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY (name),
    UNIQUE KEY (token),
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT="Registered applications";

CREATE TABLE IF NOT EXISTS Token (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    applicationId int UNSIGNED NOT NULL,
    userId int UNSIGNED NOT NULL,
    description varchar(50) NOT NULL,
    token varchar(64) NOT NULL,
    created datetime NOT NULL,
    enabled boolean NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY (token),
    FOREIGN KEY (applicationId) REFERENCES Application(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT="UserToken";
