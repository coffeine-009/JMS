/*	***	Create struct of DataBase for site: JMS	***	***	***	***	***	***	***	*/

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 *																	*
	 *		@copyright 2013
	 *			by														*
	 *		@author Vitaliy Myroslavovych Tsutsman
	 *																	*
	 *		@date 2013/03/01 - 2013/05/01
	 *																	*
	 *		@description Journal Mannager System(JMS)
	 *			System for mannager of journals and users.
	 *																	*
	 *		@adress Paland/Krakow/Budryka/11/414
	 *																	*
	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*/

/*	***	Create DataBase	***	***	***	***	***	***	***	***	***	***	***	***	***	*/
CREATE DATABASE `jms` 
	DEFAULT 
		CHARACTER SET utf8 
		COLLATE utf8_general_ci;

USE `jms`;

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
	`id_role`		INTEGER NOT NULL, 			/* Identificator of role	*/
	`id_status`		INTEGER NOT NULL, /* Identificator of user's status 	*/
	/* Access data */
	`username`		VARCHAR( 40 ) NOT NULL UNIQUE, /* Username for access 	*/
	`password`		VARCHAR( 128 ) NOT NULL, /* Hash of password for access */
	/* Data */
	`first_name`	VARCHAR( 16 ) NOT NULL, /* First name of user 			*/
	`second_name`	VARCHAR( 32 ) NOT NULL, /* Second name(family name) 	*/
	`father_name`	VARCHAR( 32 ) NOT NULL, /* Name of your father 			*/
	/* Other data */
	`gender`		BOOLEAN DEFAULT NULL, 	/* Gender of user 	*/
	`country`		VARCHAR( 2 ), 	/* Native country 	*/
	`language`		VARCHAR( 2 ), 	/* Native language 	*/

	`creation`		TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 	*/

	/* Indexes and keys */
	PRIMARY KEY( `id` ), 

	INDEX( `id_role` ), 

	FOREIGN KEY( `id_role` ) REFERENCES `role`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT, 

	FOREIGN KEY( `id_status` ) REFERENCES `user_status`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Contact's user #- */
/* -# Email #- */
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

/* -# Phone #- */
CREATE TABLE `phone`(
	`id`		INTEGER NOT NULL AUTO_INCREMENT, 	/* Identificator 		*/
	`id_user`	INTEGER NOT NULL, 					/* Identificator of user */

	`address`	VARCHAR( 15 ) NOT NULL UNIQUE, 		/* Phone number 		*/

	`creation`	TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 		*/

	/* Keys */
	PRIMARY KEY( `id` ), 

	INDEX( `id_user` ), 

	FOREIGN KEY( `id_user` ) REFERENCES `user`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Skype #- */
CREATE TABLE `skype`(
	`id`		INTEGER NOT NULL AUTO_INCREMENT, 	/* Identificator 		*/
	`id_user`	INTEGER NOT NULL, 					/* Identificator of user*/

	`address`	VARCHAR( 80 ) NOT NULL UNIQUE, 		/* Address for skype	*/

	`creation`	TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 		*/

	/* Keys */
	PRIMARY KEY( `id` ), 

	INDEX( `id_user` ), 

	FOREIGN KEY( `id_user` ) REFERENCES `user`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Address #- */
CREATE TABLE `address`(
	`id`		INTEGER NOT NULL AUTO_INCREMENT, 	/* Identificator 		*/
	`id_user`	INTEGER NOT NULL, 					/* Identificator of user*/

	`address`	TEXT NOT NULL, 						/* Address 				*/

	`creation`	TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 		*/

	/* Keys */
	PRIMARY KEY( `id` ), 

	INDEX( `id_user` ), 

	FOREIGN KEY( `id_user` ) REFERENCES `user`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT
)
ENGINE = InnoDB CHARACTER SET = utf8;


/* #- Tools for user -#	---	---	---	---	---	---	---	---	---	---	---	---	--- */
/* -# Tools #- */
CREATE TABLE `tool`(
	`id`		INTEGER NOT NULL AUTO_INCREMENT, 	/* Identificator 		*/
	`id_user`	INTEGER NOT NULL, 					/* Identificator of user*/

	`title`		VARCHAR( 32 ) NOT NULL UNIQUE, 		/* Title of tools 		*/
	/* Params */
	

	`creation`	TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 		*/

	/* Keys */
	PRIMARY KEY( `id` ), 

	INDEX( `id_user` ), 

	FOREIGN KEY( `id_user` ) REFERENCES `user`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT
)
ENGINE = InnoDB CHARACTER SET = utf8;


/* #- Content of service -#	---	---	---	---	---	---	---	---	---	---	---	--- */
/* -# Journals #- */
CREATE TABLE `journal`(
	`id`			INTEGER NOT NULL AUTO_INCREMENT, /* Identificator 		*/
	`isbn`			VARCHAR( 100 ) UNIQUE NOT NULL, /* ISBN of registration */

	`creation`		TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 	*/

	/* Keys */
	PRIMARY KEY( `id` )
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Journals language #- */
CREATE TABLE `journal_language`(
	`id`			INTEGER NOT NULL, 		/* Identificator 	*/
 
	`code_language`	VARCHAR( 2 ) NOT NULL, /* Code of language 	*/
	`title`			VARCHAR( 100 ) NOT NULL, 	/* Title of journal 		*/
	`description`	TEXT, 				/* Short description about journal 	*/

	`creation`		TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 	*/

	/* Keys */
	FOREIGN KEY( `id` ) REFERENCES `journal`( `id` )
		ON UPDATE CASCADE
		ON DELETE CASCADE
)
ENGINE = InnoDB CHARACTER SET = utf8;


/* -# Numbers of journal #- */
CREATE TABLE `journal_number`(
	`id`			INTEGER NOT NULL AUTO_INCREMENT, /* Identificator 		*/
	`id_journal`	INTEGER NOT NULL, 			/* Identificator of journal */
	`volume`		INTEGER NOT NULL, 			/* Tom of edition 			*/
	`issue`			INTEGER NOT NULL, 			/* Number of edition 		*/

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
	`id`				INTEGER NOT NULL AUTO_INCREMENT, /* Identificator 	*/
	`id_user`			INTEGER, /* Identificator of user-copyrighter(corespondent)*/
	`id_journal_number`	INTEGER, 		/* Identificator of journal number 	*/

	`code_language`		VARCHAR( 2 ) NOT NULL, /* Code of language of article */
	`pageno`			INTEGER, 			/* Number of page in journal 	*/

	`creation`			TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create*/

	/* Keys */
	PRIMARY KEY( `id` ), 

/*	FOREIGN KEY( `id_journal_number` ) REFERENCES `journal_number`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT, 
*/
	FOREIGN KEY( `id_user` ) REFERENCES `user`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Article lang content of journals #- */
CREATE TABLE `article_language`(
	`id`			INTEGER NOT NULL, 			/* Identificator 		*/

	`code_language`	VARCHAR( 2 ) NOT NULL, 		/* Code of language 	*/

	`author`		VARCHAR( 256 ) NOT NULL, 	/* Author(s) of article */
	`title`			VARCHAR( 256 ) NOT NULL, 	/* Title of article 	*/
	`abstract`		VARCHAR( 1024 ) NOT NULL, 	/* Abstract for article */

	/* Keys */
	FOREIGN KEY( `id` ) REFERENCES `article`( `id` )
		ON UPDATE CASCADE
		ON DELETE CASCADE
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Sub authors for article #- */
CREATE TABLE `article_authors`(
	`id_user`		INTEGER NOT NULL, /* Id of user( Other author of article )*/
	`id_article`	INTEGER NOT NULL, /* Id of article */

	/* Keys */
	FOREIGN KEY( `id_user` ) REFERENCES `user`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT, 

	FOREIGN KEY( `id_article` ) REFERENCES `article`( `id` )
		ON UPDATE CASCADE
		ON DELETE CASCADE
)
ENGINE = InnoDB CHARACTER SET = utf8;


/* -# Recension for article #- */
CREATE TABLE `recension`(
	`id`				INTEGER NOT NULL AUTO_INCREMENT, /* Identificator 	*/
	`id_user`			INTEGER, 				/* Identificator of user 	*/
	`id_article`		INTEGER, 		/* Identificator of journal number 	*/

	`code_language`		VARCHAR( 2 ) NOT NULL, /* Code of language of article */

	`creation`			TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create*/

	/* Keys */
	PRIMARY KEY( `id` ), 

	FOREIGN KEY( `id_user` ) REFERENCES `user`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT, 

	FOREIGN KEY( `id_article` ) REFERENCES `article`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT	
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Recension lang content for article #- */
CREATE TABLE `recension_language`(
	`id`			INTEGER NOT NULL, 			/* Identificator 		*/
	`code_language`	VARCHAR( 2 ) NOT NULL, 		/* Code of language 	*/

	`text`		VARCHAR( 1024 ) NOT NULL, 		/* Abstract for article */

	/* Keys */
	FOREIGN KEY( `id` ) REFERENCES `recension`( `id` )
		ON UPDATE CASCADE
		ON DELETE CASCADE
)
ENGINE = InnoDB CHARACTER SET = utf8;


/* #- Logs -# */
/* -# Log type #- */
CREATE TABLE `log_type`(
	`id`		INTEGER NOT NULL AUTO_INCREMENT, 	/* Identificator 		*/

	`title`		VARCHAR( 80 ) NOT NULL UNIQUE, 		/* Title of logs		*/
	`description` TEXT, 

	`creation`	TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 		*/

	/* Keys */
	PRIMARY KEY( `id` )
)
ENGINE = InnoDB CHARACTER SET = utf8;

/* -# Logs #- */
CREATE TABLE `log`(
	`id`			INTEGER NOT NULL AUTO_INCREMENT, 	/* Identificator 		*/
	`id_user`		INTEGER, 					/* Identificator of user*/
	`id_log_type`	INTEGER NOT NULL, 

	`ip_address`	VARCHAR( 128 ), 
	`os`			VARCHAR( 32 ), 
	`browser`		VARCHAR( 32 ), 

	`creation`	TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /* Time of create 		*/

	/* Keys */
	PRIMARY KEY( `id` ), 

	INDEX( `id_log_type` ), 

	FOREIGN KEY( `id_log_type` ) REFERENCES `log_type`( `id` )
		ON UPDATE CASCADE
		ON DELETE RESTRICT
)
ENGINE = InnoDB CHARACTER SET = utf8;

