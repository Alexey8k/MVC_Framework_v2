USE `mysql`;

DROP DATABASE IF EXISTS `BookCatalog`;

CREATE DATABASE `BookCatalog`;

USE `BookCatalog`;

CREATE TABLE `Book`
(
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` NVARCHAR(128) NOT NULL,
  `description` NVARCHAR(512),
  `price` DECIMAL(6,2) NOT NULL,
  CONSTRAINT PK_Book PRIMARY KEY (`id`)
);

CREATE TABLE `Author`(
  `id` INT AUTO_INCREMENT NOT NULL,
  `name` NVARCHAR(128) NOT NULL,
  CONSTRAINT PK_Author PRIMARY KEY(`id`)
);

CREATE TABLE `Genre`(
  `id` INT AUTO_INCREMENT NOT NULL,
  `name` NVARCHAR(64) NOT NULL,
  CONSTRAINT PK_Genre PRIMARY KEY(`id`)
);

CREATE TABLE `BookAuthor`(
  `bookId` INT NOT NULL,
  `authorId` INT NOT NULL,
  CONSTRAINT FK_BookAuthor_Book FOREIGN KEY(`bookId`)
    REFERENCES `Book`(`id`),
  CONSTRAINT FK_BookAuthor_Author FOREIGN KEY(`authorId`)
    REFERENCES `Author`(`id`),
  CONSTRAINT UQ_BookAuthor_Book_Author UNIQUE (`bookId`,`authorId`)
);

CREATE TABLE `BookGenre`(
  `bookId` INT NOT NULL,
  `genreId` INT NOT NULL,
  CONSTRAINT FK_BookGenre_Book FOREIGN KEY(`bookId`)
   REFERENCES `Book`(`id`),
  CONSTRAINT FK_BookGenre_Genre FOREIGN KEY(`genreId`)
   REFERENCES `Genre`(`id`),
  CONSTRAINT UQ_BookGenre_Book_Genre UNIQUE (`bookId`,`genreId`)
);

DROP PROCEDURE IF EXISTS `GetAuthorsByBook`;
CREATE PROCEDURE `GetAuthorsByBook`(IN _bookId INT)
  READS SQL DATA
BEGIN
  SELECT a.*
  FROM `BookAuthor` ba
         JOIN `Author` a ON ba.authorId = a.id
  WHERE ba.bookId=_bookId;
END;

DROP PROCEDURE IF EXISTS `GetGenresByBook`;
CREATE PROCEDURE `GetGenresByBook`(IN _bookId INT)
  READS SQL DATA
BEGIN
  SELECT g.*
  FROM `BookGenre` bg
         JOIN `Genre` g ON bg.genreId = g.id
  WHERE bg.bookId=_bookId;
END;

DROP PROCEDURE IF EXISTS `GetBook`;
CREATE PROCEDURE `GetBook`(IN _id INT)
BEGIN
  SELECT * FROM `Book` WHERE `id`=_id LIMIT 1;
  CALL GetAuthorsByBook(_id);
  CALL GetGenresByBook(_id);
END;

DROP FUNCTION IF EXISTS `SaveBook`;
CREATE FUNCTION `SaveBook`(
  _id INT,
  _name NVARCHAR(128),
  _description NVARCHAR(512),
  _price DECIMAL(6,2))
  RETURNS INT
BEGIN
  IF IsExistsBook(_id)
  THEN
    UPDATE `Book`
    SET `name`=_name,
        `description`=_description,
        `price`=_price
    WHERE `id`=_id;
    DELETE FROM `BookAuthor` WHERE `bookId`=_id;
    DELETE FROM `BookGenre` WHERE `bookId`=_id;
  ELSE
    INSERT INTO `Book`(`name`,`description`,`price`)
    VALUES (_name,_description,_price);
  END IF;
  RETURN IF(LAST_INSERT_ID(),LAST_INSERT_ID(),_id);
END;

DROP FUNCTION IF EXISTS `IsExistsBook`;
CREATE FUNCTION `IsExistsBook`(_id INT)
  RETURNS BOOLEAN
BEGIN
  RETURN EXISTS(SELECT '' FROM `Book` WHERE `id`=_id);
END;

DROP PROCEDURE IF EXISTS `AddAuthorsToBook`;
CREATE PROCEDURE `AddAuthorsToBook`(IN _bookId INT, IN _authorId NVARCHAR(128))
BEGIN
  IF NOT EXISTS(SELECT '' FROM `Author` WHERE `id`=CAST(_authorId AS UNSIGNED))
  THEN
    INSERT INTO `Author`(`name`) VALUES (_authorId);
    SET _authorId = LAST_INSERT_ID();
  END IF;
  INSERT INTO `BookAuthor`(`bookId`,`authorId`) VALUES (_bookId,_authorId);
END;

DROP PROCEDURE IF EXISTS `AddGenresToBook`;
CREATE PROCEDURE `AddGenresToBook`(IN _bookId INT, IN _genreId NVARCHAR(128))
BEGIN
  IF NOT EXISTS(SELECT '' FROM `Genre` WHERE `id`=CAST(_genreId AS UNSIGNED))
  THEN
    INSERT INTO `Genre`(`name`) VALUES (_genreId);
    SET _genreId = LAST_INSERT_ID();
  END IF;
  INSERT INTO `BookGenre`(`bookId`,`genreId`) VALUES (_bookId,_genreId);
END;

DROP PROCEDURE IF EXISTS GetBookByFilterPager;
CREATE PROCEDURE GetBooksByFilterPager(IN _genreId INT, IN _authorId INT, IN _page INT, IN _count INT)
  READS SQL DATA
BEGIN
  DECLARE _offset INT DEFAULT (_page-1)*_count;
  SELECT b.* FROM `Book` b
    JOIN `BookGenre` bg ON _genreId IS NULL OR b.id=bg.bookId AND bg.genreId=_genreId
    JOIN `BookAuthor` ba ON _authorId IS NULL OR b.id=ba.bookId AND ba.authorId=_authorId
    GROUP BY b.id
    LIMIT _offset, _count;
END;

DROP FUNCTION IF EXISTS `GetQuantityByFilter`;
CREATE FUNCTION `GetQuantityByFilter`(_genreId INT, _authorId INT)
  RETURNS INT
BEGIN
  DECLARE `_quantityByFilter` INT;
  SELECT COUNT(DISTINCT b.id) INTO `_quantityByFilter` FROM `Book` b
    JOIN `BookGenre` bg ON _genreId IS NULL OR b.id=bg.bookId AND bg.genreId=_genreId
    JOIN `BookAuthor` ba ON _authorId IS NULL OR b.id=ba.bookId AND ba.authorId=_authorId;
  RETURN `_quantityByFilter`;
END;