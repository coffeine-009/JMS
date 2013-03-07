/*	***	Create struct of DataBase for site: JMS	***	***	***	***	***	***	***	*/

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 *																	*
	 *		@copyright 2012
	 *			by														*
	 *		@author Vitaliy Myroslavovych Tsutsman
	 *																	*
	 *		@date 2013/03/01 - 2013/../..
	 *																	*
	 *		@description Journal Mannager System(JMS)
	 *			System for mannager of journals and users.
	 *																	*
	 *		@adress Paland/Krakow/Budryka/11
	 *																	*
	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*/

/*	***	Create DataBase	***	***	***	***	***	***	***	***	***	***	***	***	***	*/
CREATE DATABASE `youtube_video` 
	DEFAULT 
		CHARACTER SET utf8 
		COLLATE utf8_general_ci;

USE `youtube_video`;

/*	***	Create Tables and relations	***	***	***	***	***	***	***	***	***	***	*/
/* -# Role of users #- */
CREATE TABLE `role`(
	`id`			INTEGER NOT NULL AUTO_INCREMENT, /* Identificator 		*/
	`title`			VARCHAR( 16 ) NOT NULL, 		/* Title(const) 		*/
	`description`	TEXT, 							/* Description of role 	*/
	`creation`		TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 	*/

	/* Keys */
	PRIMARY KEY( `id` )
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Status of users #- */
CREATE TABLE `user_status`(
	`id`			INTEGER NOT NULL AUTO_INCREMENT, /* Identificator 		*/
	`title`			VARCHAR( 16 ) NOT NULL, 		/* Title(const) 		*/
	`description`	TEXT, 							/* Description of role 	*/
	`creation`		TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 	*/

	/* Keys */
	PRIMARY KEY( `id` )
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Data about users #- */
CREATE TABLE `user`(
	`id`			INTEGER NOT NULL AUTO_INCREMENT, /* Identificator 		*/
	`id_role`		INTEGER NOT NULL, 				/* Identificator of role */
	`id_status`		INTEGER NOT NULL, /* Identificator of user's status 	*/
	/* Data */
	`first_name`	VARCHAR( 16 ) NOT NULL, /* First name of user 			*/
	`second_name`	VARCHAR( 32 ) NOT NULL, /* Second name(family name) 	*/
	`father_name`	VARCHAR( 32 ) NOT NULL, /* Name of your father 			*/
	/* Other data */
	`gender`		BOOLEAN, 		/* Gender of user 	*/
	`country`		VARCHAR( 2 ), 	/* Native country 	*/
	`language`		VARCHAR( 2 ), 	/* Native language 	*/

	`creation`		TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 	*/

	/* Indexes and keys */
	PRIMARY KEY( `id` ), 

	INDEX( `id_role` ), 

	FOREIGN KEY( `id_role` ) REFERENCES `role`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Contact's user #- */
/* - Email - */
CREATE TABLE `email`(
	`id`		INTEGER NOT NULL AUTO_INCREMENT, 	/* Identificator 		*/
	`id_user`	INTEGER NOT NULL, 					/* Identificator of user */
	`address`	VARCHAR( 80 ) NOT NULL UNIQUE, 		/* Email address 		*/
	`creation`	TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 		*/

	/* Keys */
	PRIMARY KEY( `id` ), 

	INDEX( `id_user` ), 

	FOREIGN KEY( `id_user` ) REFERENCES `user`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT
)
ENGINE = InnoDB CHARACTER SET = utf8;


/* -# Content of service #-	---	---	---	---	---	---	---	---	---	---	---	--- */
/* -# Journals #- */
CREATE TABLE `journal`(
	`id`			INTEGER NOT NULL AUTO_INCREMENT, 
	`issn`			VARCHAR( 100 ) NOT NULL, 
	`title`			VARCHAR( 100 ) NOT NULL, 
	`description`	TEXT, 
	`creation`		TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 		*/

	/* Keys */
	PRIMARY KEY( `id` )
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Numbers of journal #- */
CREATE TABLE `journal_number`(
	`id`			INTEGER NOT NULL AUTO_INCREMENT, /* Identificator */
	`id_journal`	INTEGER NOT NULL, /* Identificator of journal */
	`tom`			INTEGER NOT NULL, /* Tom of edition */
	`number`		INTEGER NOT NULL, /* Number of edition */
	`creation`		TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 	*/

	/* Keys */
	PRIMARY KEY( `id` ), 

	FOREIGN KEY( `id_journal` ) REFERENCES `journal`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Articles of journals #- */
CREATE TABLE `article`(
	`id`				INTEGER NOT NULL AUTO_INCREMENT, /* Identificator */
	`id_user`			INTEGER, /* Identificator of user */
	`id_number_journal`	INTEGER, /* Identificator of journal number */
	`code_language`		VARCHAR( 2 ) NOT NULL, /* Code of language all article */
	`pageno`			INTEGER, /* Number of page in journal */

	/* Keys */
	PRIMARY KEY( `id` )
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Journals #- */
CREATE TABLE `article_language`(
	`id`			INTEGER NOT NULL AUTO_INCREMENT, /* Identificator 	*/
	`code_language`	VARCHAR( 2 ) NOT NULL, 		/* Code of language 	*/
	`author`		VARCHAR( 256 ) NOT NULL, 	/* Author(s) of article */
	`title`			VARCHAR( 256 ) NOT NULL, 	/* Title of article 	*/
	`abstract`		VARCHAR( 1024 ) NOT NULL, 	/* Abstract for article */

	/* Keys */
	PRIMARY KEY( `id` )
)
ENGINE = InnoDB CHARACTER SET = utf8;
