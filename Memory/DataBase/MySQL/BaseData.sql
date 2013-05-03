/*	***	Insert base data to DataBase for site: JMS	***	***	***	***	***	***	*/

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 *																	*
	 *		@copyright 2013
	 *			by														*
	 *		@author Vitaliy Myroslavovych Tsutsman
	 *																	*
	 *		@date 2013/05/03 - 2013/05/01
	 *																	*
	 *		@description Journal Mannager System(JMS)
	 *			System for mannager of journals and users.
	 *																	*
	 *		@adress Ukraine/Petranka/Grushevskii/234
	 *																	*
	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*/

/*	***	Set default database	***	***	***	***	***	***	***	***	***	***	***	*/
USE `jms`;

/*	***	Insert data	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*/
/* -# Role of users #- */
INSERT INTO `role`(
	`id`, 
	`title`, 
	`description`
)
VALUES
(	1,	'administrator',	'Administrator of service. Supper user.'	), 
(	2,	'author',			'Author of article(s).'						);

/* -# User's status #- */
INSERT INTO `user_status`(
	`id`, 
	`title`, 
	`description`
)
VALUES
(	1,	'Active',		'Normal active account.'	), 
(	2,	'NotActive',	'Not comfirmed account'		), 
(	3,	'Deleted',		'Deleted of administrator'	);
