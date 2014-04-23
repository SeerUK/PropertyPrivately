
/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Security Database & Tables
 */
CREATE DATABASE IF NOT EXISTS PPSecurity
    CHARACTER SET = utf8;

USE PPSecurity;

CREATE TABLE IF NOT EXISTS User (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    username varchar(25) NOT NULL,
    password varchar(128) NOT NULL,
    email varchar(255) NOT NULL,
    created datetime NOT NULL,
    enabled boolean NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY (username),
    UNIQUE KEY (email)
) COMMENT="Users";

CREATE TABLE IF NOT EXISTS Person (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    userId int UNSIGNED NOT NULL,
    name varchar(50),
    location varchar(50),
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY (userId),
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT="Person linked to user";

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
    description varchar(500),
    token varchar(64) NOT NULL,
    created datetime NOT NULL,
    enabled boolean NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY (token),
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT="Registered applications";

CREATE TABLE IF NOT EXISTS Token (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    applicationId int UNSIGNED NOT NULL,
    userId int UNSIGNED NOT NULL,
    token varchar(64) NOT NULL,
    created datetime NOT NULL,
    enabled boolean NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY (token),
    FOREIGN KEY (applicationId) REFERENCES Application(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT="UserToken";


/**
 * Property Database & Tables
 */
CREATE DATABASE IF NOT EXISTS PPProperty
    CHARACTER SET = utf8;

USE PPProperty;

CREATE TABLE IF NOT EXISTS Property (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    userId int UNSIGNED NOT NULL,
    title varchar(50) NOT NULL,
    description varchar(1000) NOT NULL,
    created datetime NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (userId) REFERENCES PPSecurity.User(id) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT="Properties (houses etc)";

CREATE TABLE IF NOT EXISTS Address (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    propertyId int UNSIGNED NOT NULL,
    buildingName varchar(50),
    address1 varchar(100) NOT NULL,
    address2 varchar(50),
    town varchar(50),
    postcode varchar(7),
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (propertyId) REFERENCES Property(id) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT="Property address";

CREATE TABLE IF NOT EXISTS Image (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    propertyId int UNSIGNED NOT NULL,
    description varchar(250),
    path varchar(250) NOT NULL,
    displayOrder int UNSIGNED NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (propertyId) REFERENCES Property(id) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT="Property images";

CREATE TABLE IF NOT EXISTS RoomType (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    name varchar(30) NOT NULL,
    ref varchar(30) NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY (name),
    UNIQUE KEY (ref)
) COMMENT="Various room types";

INSERT IGNORE INTO
    RoomType (name, ref)
VALUES
    ('Kitchen', 'ROOM_KITCHEN'),
    ('Bedroom', 'ROOM_BEDROOM'),
    ('Bathroom', 'ROOM_BATHROOM');

CREATE TABLE IF NOT EXISTS Room (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    propertyId int UNSIGNED NOT NULL,
    roomTypeId int UNSIGNED NOT NULL,
    width int UNSIGNED NOT NULL,
    height int UNSIGNED NOT NULL,
    length int UNSIGNED NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (propertyId) REFERENCES Property(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (roomTypeId) REFERENCES RoomType(id) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT="A room in a property";

CREATE TABLE IF NOT EXISTS Sale (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    propertyId int UNSIGNED NOT NULL,
    price int UNSIGNED NOT NULL,
    start datetime NOT NULL,
    end datetime NOT NULL,
    created datetime NOT NULL,
    enabled boolean NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (propertyId) REFERENCES Property(id) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT="Prepaid sale of property, sales are listed, not properties";

CREATE TABLE IF NOT EXISTS Offer (
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    saleId int UNSIGNED NOT NULL,
    userId int UNSIGNED NOT NULL,
    offer int UNSIGNED NOT NULL,
    created datetime NOT NULL,
    lastModified timestamp NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (saleId) REFERENCES Sale(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (userId) REFERENCES PPSecurity.User(id) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT="Offer made by a user on a sale";
