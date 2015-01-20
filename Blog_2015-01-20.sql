# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.34)
# Database: Blog
# Generation Time: 2015-01-20 23:58:12 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table access_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `access_tokens`;

CREATE TABLE `access_tokens` (
  `oauth_token` varchar(40) NOT NULL,
  `client_id` char(36) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `expires` int(12) NOT NULL,
  `scope` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`oauth_token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `access_tokens` WRITE;
/*!40000 ALTER TABLE `access_tokens` DISABLE KEYS */;

INSERT INTO `access_tokens` (`oauth_token`, `client_id`, `user_id`, `expires`, `scope`)
VALUES
	('debc6438907fc6791d270569af083ff26e067ff2','NTRiZDBkZTU0ZjE4Njli',7,2147483647,'');

/*!40000 ALTER TABLE `access_tokens` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table auth_codes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auth_codes`;

CREATE TABLE `auth_codes` (
  `code` varchar(40) NOT NULL,
  `client_id` char(36) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `redirect_uri` varchar(200) NOT NULL,
  `expires` int(11) NOT NULL,
  `scope` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `auth_codes` WRITE;
/*!40000 ALTER TABLE `auth_codes` DISABLE KEYS */;

INSERT INTO `auth_codes` (`code`, `client_id`, `user_id`, `redirect_uri`, `expires`, `scope`)
VALUES
	('362e1144171cb6619f478292f89a80da509d109c','NTRiZDBkZTU0ZjE4Njli',7,'/cakephp/users/demoCode',1421676234,'');

/*!40000 ALTER TABLE `auth_codes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table clients
# ------------------------------------------------------------

DROP TABLE IF EXISTS `clients`;

CREATE TABLE `clients` (
  `client_id` char(20) NOT NULL,
  `client_secret` char(40) NOT NULL,
  `redirect_uri` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;

INSERT INTO `clients` (`client_id`, `client_secret`, `redirect_uri`, `user_id`)
VALUES
	('NTRiZDBkZTU0ZjE4Njli','5a06f016a354bb327c16519d03d6eed7628dae3e','/cakephp/users/demoCode',NULL);

/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table evaluaciones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `evaluaciones`;

CREATE TABLE `evaluaciones` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `puntaje` int(11) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluacion_belongsTo_user` (`user_id`),
  CONSTRAINT `evaluacion_belongsTo_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

LOCK TABLES `evaluaciones` WRITE;
/*!40000 ALTER TABLE `evaluaciones` DISABLE KEYS */;

INSERT INTO `evaluaciones` (`id`, `user_id`, `puntaje`, `tipo`, `created`, `modified`)
VALUES
	(4,6,0,1,'2014-12-01 13:20:58','2014-12-01 13:20:58'),
	(5,6,1,1,'2014-12-01 13:22:22','2014-12-01 13:22:22'),
	(6,6,12,1,'2014-12-01 13:28:28','2014-12-01 13:28:28'),
	(7,6,20,1,'2014-12-01 13:28:38','2014-12-01 13:28:38'),
	(8,6,34,1,'2014-12-01 18:30:32','2014-12-01 18:30:32'),
	(9,6,42,1,'2014-12-01 18:30:55','2014-12-01 18:30:55'),
	(10,6,62,1,'2014-12-01 18:34:46','2014-12-01 18:34:46'),
	(11,6,58,1,'2014-12-01 18:34:46','2014-12-01 18:34:46'),
	(12,6,72,1,'2014-12-01 19:07:05','2014-12-01 19:07:05'),
	(13,6,86,1,'2014-12-01 19:56:19','2014-12-01 19:56:19'),
	(14,6,0,1,'2014-12-02 11:10:30','2014-12-02 11:10:30'),
	(15,11,5,1,'2015-01-20 17:28:52','2015-01-20 17:28:52'),
	(16,11,1,1,'2015-01-20 17:51:08','2015-01-20 17:51:08');

/*!40000 ALTER TABLE `evaluaciones` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table incorrectas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `incorrectas`;

CREATE TABLE `incorrectas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `opcSel` int(11) DEFAULT NULL,
  `pregunta_id` int(11) unsigned DEFAULT NULL,
  `resultado_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `incorrectas_belongsTo_resultado` (`resultado_id`),
  KEY `incorrectas_has_pregunta` (`pregunta_id`),
  CONSTRAINT `incorrectas_belongsTo_resultado` FOREIGN KEY (`resultado_id`) REFERENCES `resultados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `incorrectas_has_pregunta` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=latin1;

LOCK TABLES `incorrectas` WRITE;
/*!40000 ALTER TABLE `incorrectas` DISABLE KEYS */;

INSERT INTO `incorrectas` (`id`, `opcSel`, `pregunta_id`, `resultado_id`)
VALUES
	(8,3,25,6),
	(9,1,1,8),
	(10,4,6,8),
	(11,2,7,8),
	(12,4,11,8),
	(13,2,12,8),
	(14,2,15,8),
	(15,3,17,8),
	(16,3,20,8),
	(19,2,23,9),
	(20,1,27,9),
	(21,3,28,9),
	(22,4,30,9),
	(23,3,33,10),
	(24,4,36,10),
	(25,1,39,10),
	(26,1,43,10),
	(27,1,53,11),
	(28,4,55,11),
	(29,3,70,11),
	(30,3,79,11),
	(31,2,81,12),
	(32,1,82,12),
	(33,1,83,12),
	(34,4,84,12),
	(35,3,85,12),
	(36,2,88,12),
	(37,3,96,13),
	(38,4,98,13),
	(39,4,99,13),
	(40,4,100,13),
	(41,3,102,13),
	(42,4,117,14),
	(43,4,119,14),
	(44,1,1,15),
	(45,4,6,15),
	(46,2,7,15),
	(47,4,11,15),
	(48,2,12,15),
	(49,2,15,15),
	(50,3,17,15),
	(51,3,20,15),
	(54,2,23,16),
	(55,1,27,16),
	(56,3,28,16),
	(57,4,30,16),
	(58,3,33,17),
	(59,4,36,17),
	(60,1,39,17),
	(61,1,43,17),
	(62,1,53,18),
	(63,4,55,18),
	(64,3,70,18),
	(65,3,79,18),
	(66,2,81,19),
	(67,1,82,19),
	(68,1,83,19),
	(69,4,84,19),
	(70,3,85,19),
	(71,2,88,19),
	(72,3,96,20),
	(73,4,98,20),
	(74,4,99,20),
	(75,4,100,20),
	(76,3,102,20),
	(77,4,117,21),
	(78,4,119,21),
	(79,1,21,22),
	(80,1,24,22),
	(81,1,25,22),
	(82,1,19,23),
	(84,1,27,24),
	(85,3,72,25),
	(86,3,74,25),
	(87,2,82,26),
	(88,4,90,26),
	(89,3,99,27),
	(90,3,111,28),
	(91,1,19,29),
	(93,1,27,30),
	(94,3,72,31),
	(95,3,74,31),
	(96,2,82,32),
	(97,4,90,32),
	(98,3,99,33),
	(99,3,111,34),
	(100,4,38,35),
	(101,3,42,35),
	(102,3,43,35),
	(103,2,1,36),
	(104,1,12,36),
	(105,2,21,38),
	(106,4,54,39),
	(107,3,71,39),
	(108,2,80,39),
	(109,3,81,40),
	(110,3,85,40),
	(111,2,86,40),
	(112,1,89,40),
	(113,4,8,41),
	(114,1,28,42),
	(115,3,32,43);

/*!40000 ALTER TABLE `incorrectas` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table materias
# ------------------------------------------------------------

DROP TABLE IF EXISTS `materias`;

CREATE TABLE `materias` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `numpreguntas` int(11) NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

LOCK TABLES `materias` WRITE;
/*!40000 ALTER TABLE `materias` DISABLE KEYS */;

INSERT INTO `materias` (`id`, `nombre`, `numpreguntas`, `label`)
VALUES
	(1,'Comunicación Español',20,'ESP'),
	(2,'Comunicación Inglés',10,'ENG'),
	(3,'Habilidad Matemática',15,'MAT'),
	(4,'Biología',10,'BIO'),
	(5,'Química',15,'QUI'),
	(6,'Física',15,'FIS'),
	(7,'Matemáticas',35,'HAB');

/*!40000 ALTER TABLE `materias` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table preguntas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `preguntas`;

CREATE TABLE `preguntas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `oracion` varchar(450) NOT NULL DEFAULT '',
  `opc1` varchar(450) NOT NULL DEFAULT '',
  `opc2` varchar(450) NOT NULL DEFAULT '',
  `opc3` varchar(450) NOT NULL DEFAULT '',
  `opc4` varchar(450) NOT NULL DEFAULT '',
  `just` varchar(400) NOT NULL DEFAULT '',
  `id_tema` int(11) unsigned NOT NULL,
  `opcc` int(11) NOT NULL,
  `recurso` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pregunta_has_tema` (`id_tema`),
  CONSTRAINT `pregunta_has_tema` FOREIGN KEY (`id_tema`) REFERENCES `temas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=latin1;

LOCK TABLES `preguntas` WRITE;
/*!40000 ALTER TABLE `preguntas` DISABLE KEYS */;

INSERT INTO `preguntas` (`id`, `oracion`, `opc1`, `opc2`, `opc3`, `opc4`, `just`, `id_tema`, `opcc`, `recurso`)
VALUES
	(1,'Los autores del texto le llaman ?Nuestro Pecado Mortal?, ¿Por qué razones usan éste título? ','a) Hacen referencia religiosa del origen del hombre y de la mujer para su existencia. ','b) Se refieren a nuestros pecados cometidos al vivir el día a día. ','c) Se trata de una analogía respecto a nuestros hábitos diarios de vivir. ','d) Realizan una comparación entre el nivel de lectura que hemos desarrollado.','Justificación',1,1,'1/NUESTRO_PECADO_MORTAL.pdf'),
	(2,'Hacen referencia en el texto de los autores que escribieron las siguientes obras: ','a) Umberto Eco y la Historia del Tiempo. Milan Kundera y La Inmortalidad. Stephen Hawking y El Péndulo de Foucault. ','b) Umberto Eco y el Péndulo de Foucault. Milan Kundera y La Inmortalidad. Stephen Hawking y La Historia del Tiempo. ','c) Umberto Eco y La inmortalidad. Milan Kundera y El Péndulo de Foucault. Stephen Hawking y Historia del Tiempo. ','d) Umberto Eco y La Inmortalidad. Milan Kundera y Historia del Tiempo. Stephen Hawking y El Péndulo de Foucault.','Pendiente',1,2,'1/NUESTRO_PECADO_MORTAL.pdf'),
	(3,'Dentro del texto mencionan tres librerías que son: ','a) Casa del libro, librería de Cristal y el Sótano. ','b) El Libro Viejo, libros Antiguos y el Libro del Saber. ','c) Porrúa, Zaplana y Gandhi. ','d) El Aleph, Rayuela y las Trampas de la Fe.','Pendiente',1,3,'1/NUESTRO_PECADO_MORTAL.pdf'),
	(4,'El tema principal del texto trata de: ','a) La importancia de la literatura latinoamericana y sus principales exponentes. ','b) Domina el concepto de la lectura enfocada a la calidad de los autores. ','c) Hace un recuento de los autores que sobresalen en las letras latinoamericanas. ','d) Interpreta la realidad de los escritores con relación al lector y sus habilidades.','Pendiente',1,2,'1/NUESTRO_PECADO_MORTAL.pdf'),
	(5,'Con referencia artística mencionan el nombre de tres pintores que son: ','a) Goya, Van Gogh y El Bosco. ','b) Eco, Hawking y Kundera. ','c) Borges, Córtazar y Paz. ','d) Marx, Gorbachov y Galbarith','Pendiente',1,1,'1/NUESTRO_PECADO_MORTAL.pdf'),
	(6,'En la oración ?este genio que bordea los límites de la locura con la cordura?. ¿Qué palabra está presentada como sujeto? ','a) Este genio? ','b) Este genio que bordea? ','c) Este? ','d) Ninguna de las tres.','El sujeto es aquel que lleva acabo la accion del Verbo.',1,1,NULL),
	(7,'En la oración ?los best-seller de Europa y América en México?, la palabra best-seller significa: ','a) Son los principales autores en ambos continentes. ','b) Son los autores más conocidos en el mundo por escribir sus obras. ','c) Son los libros más populares dentro del público consumidor. ','d) Son los libros que registran mayor número de ventas.','Un best seller es aquel libro mas vendido o de los más vendidos',1,4,NULL),
	(8,'En la oración ?Auspiciando la publicación de una serie de libros pequeños? la palabra acentuada con tilde se clasifica como: ','a) Aguda. ','b) Grave.','c) Esdrújula. ','d) Sobreesdrújula.','Pendiente',1,1,NULL),
	(9,'En la oración ?Para muchos de nosotros lo importante es HACER COMO SI, aunque en el fondo sabemos que nuestro quehacer sea COMO NO?, se refiere a: ','a) Nuestros hábitos no son adecuados y solo hacemos la apariencia de que existen. ','b) Nuestros conocimientos están presentes pero no logran exponerse dentro de nuestra mente. ','c) Nuestro método de lectura no es el adecuado pero aparentamos que si entendimos lo leído. ','d) Como no es una negación que no existe, ya que se trata de una oración anulada.','Pendiente',1,1,NULL),
	(10,'Al utilizar la palabra ? intelectual? le conceden el siguiente significado: ','a) Se trata de una persona con conocimiento llegando a la sabiduría. ','b) Es una persona que cree tener conocimiento de algo pero solo es una apariencia. ','c) Persona que tiene conocimientos comunes y corrientes en el uso diario. ','d) Se dice de una persona que nació sobre dotada en el uso de su cerebro.','Pendiente',1,2,'1/NUESTRO_PECADO_MORTAL.pdf'),
	(11,'Al utilizar en el texto el verbo leer con distintas acepciones principalmente se refiere a: ','a) La capacidad de solo unir letras. ','b) La utilidad para leer lo cotidiano. ','c) La capacidad de leer y escribir como lo hacemos en el nivel primaria. ','d) La capacidad de llegar al entendimiento de las ideas expuestas.','Pendiente',1,1,'1/NUESTRO_PECADO_MORTAL.pdf'),
	(12,'En el párrafo que inicia con ?Por ahí anda el talachero?? las palabras ?magister dixit? significan: ','a) Magisterio designado. ','b) Maravilloso en las decisiones. ','c) Magnífico en el diccionario. ','d) Maestro en el decir. ','Pendiente',1,4,'1/NUESTRO_PECADO_MORTAL.pdf'),
	(13,'En el párrafo ?A propósito del verbo hacer? el verbo se encuentra en que tiempo: ','a)	Gerundio',' b) Infinitivo','c) Imperativo','d) Presente perfecto ','Pendiente',1,2,'1/NUESTRO_PECADO_MORTAL.pdf'),
	(14,'Al final de un texto se utiliza ?Es una cédula embrionaria?, se trata de un: ','a) Sinónimo ','b) Antónimo ','c) Analogía ','d) Anocronismo ','Pendente',1,3,'1/NUESTRO_PECADO_MORTAL.pdf'),
	(15,'?Si es cierto que hoy se venden muchos más libros?, se está cometiendo un:','a) Pleonasmo ','b) Aforismo ','c) Silogismo ','d) Error gramatical ','pendiente',1,1,NULL),
	(16,'Al conjugar el verbo hacer en? ?ya la hizo. No la hace?, en que tiempo de conjugación se presentan: ','a)	Presente','b)Pretérito ','c) Futuro ','d) Pospretérito','Pendiente',1,1,NULL),
	(17,'Dentro del texto se mencionan a distintos autores, cuál de las opciones trata sobre filósofos: ','a) Bosco, Van Gogh y Goya',' b) Borges, Cortazar y Paz ','c) Eco, Hawking y Kundera ','d) Heidegger, Sartre y Freud','Pendiente',1,4,'1/NUESTRO_PECADO_MORTAL.pdf'),
	(18,'Al terminar de leer el texto la idea general para su discusión es: ','a) Hacer una campaña para que se editen y vendan más libros en México. ','b) La necesidad de crear el hábito de lectura y una profunda comprensión. ','c) Publicitar a los escritores mexicanos para que sean famosos. ','d) ¿Qué son mejores? Los poetas, escritores, científicos ó literatos.','Pendiente',1,2,'1/NUESTRO_PECADO_MORTAL.pdf'),
	(19,'En la oración ¿puede haber creación científica sin raíz esotérica? La última palabra significa: ','a) Relación con las brujas.','b) Interacción con el más allá. ','c) Práctica y objetos metafísicos.','d) Práctica de la adivinación.','Pendiente',1,3,NULL),
	(20,'?Leer de verdad? es una oración que se refiere a: ','a) Lograr una comprensión total y profunda del texto. ','b) No caer en la mentira. ','c) Tener un conocimiento de la teoría sin llegar a la práctica. ','d) Estar en una realidad totalizadora.','Pendiente',1,1,NULL),
	(21,'He´s my best friend and _______________ since I was a child. ','a) I´ve known him ','b) I knew him ','c) I´m knowing him ','d) I know him ','Pendiente',2,4,NULL),
	(22,'I knew his name because ____________________ before. ','a) We have met ','b) We´d meted ','c) We had met ','d) We have been meeting ','Pendiente',2,3,NULL),
	(23,'When the rain ___________________, we all went into the garden. ','a) Was stopping ','b) Stoped ','c) Was stopped ','d) Stopped ','Pendiente',2,4,NULL),
	(24,'I´m sorry I´m late. How long ________________ here? ','a) Have you sat ','b) Did you sit ','c) Are you sitting ','d) Have you been sitting ','Pendiente',2,4,NULL),
	(25,'They say that ______________ it is, the more elegant it looks. ','a) The simple ','b) Simpler ','c) The simpler ','d) More simple ','Pendiente',2,2,NULL),
	(26,'Where´s dad? In ______________, practicing his golf as usual. ','a) The garden ','b) Gardens ','c) A garden \n','d) Garden ','Pendiente',2,1,NULL),
	(27,'We got it to work better by __________ it off, then on again. ','a) Switch ','b) Switches ','c) Switching ','d) Switched ','Pendiente',2,3,NULL),
	(28,'What is the gist (the main idea) that this piece of writing conveys? ','a. Wolves like to take care of human children. ','b. The city of Rome had many wolves in the old days. ','c. The city of Rome was founded by a wolf','c. The city of Rome was founded by a wolf','Pendiente',2,4,'2/lec1Ingles.png'),
	(29,'What is a herdsman? ','a. someone who builds cities ','b. someone who cares for children ','c. someone who cares for domestic animals ','d. someone who can hear very well','Pendiente',2,3,'2/lec1Ingles.png'),
	(30,'\"...they sought revenge on the king who had killed their mother...\" means... ','a. They attacked the king who had harmed their mother and made them orphans. ','b. They went to court to sue the king for his crime against their mother. ','c. They hired some gangsters to take care of their problem with the king. ','d. They went to talk to the king about his crime against their mother.','Pendiente',2,1,'2/lec1Ingles.png'),
	(31,'Los siguientes números de la sucesión aritmética son: ','a) 19, 27           ','b) 11, 25              ','c) 22, 27      ','d) 22, 34','Pendiente',3,3,NULL),
	(32,'Andrea al saltar de un trampolín, se eleva 1 m., para posteriormente bajar 5 m. (sumergiéndose en el agua) y después sube 2 m. para llegar a la superficie del agua. ¿A qué altura sobre el nivel del agua se encuentra el trampolín? ','a) 2m           ','b) 3m          ','c) 5.2m','d) 5.1m','Pendiente',3,1,NULL),
	(33,'Los términos que faltan en la siguiente sucesión  son:                          ','a) 40, 24           ','b) 100, 80             ','c) 20, 60                 ','d) 100, 14','Pendiente',3,4,'3/33.png'),
	(34,'¿Qué figura es la que sigue a la secuencia? ','a)','b)','c)','d)','pendiente',3,4,'3/34.png'),
	(35,'Al simplificar la operación de adición (2+4+6+8+10+12+......+98+100) - (1+3+5+7+9+11+13+.......+97+99) la diferencia es:','a)0','b)50','c)-50','d)22','pendiente',3,2,NULL),
	(36,'¿Qué figura es la que sigue a la secuencia? ','a)','b)','c)','d)','pendiente',3,2,'3/36.png'),
	(37,'Unos parásitos al reproducirse duplican su número cada minuto. Si hay un frasco a la mitad a las 7 horas 15 minutos, ¿qué hora será cuando se llene el frasco? ','a) 14 horas 30 minutos               ','b) 8 horas 30 minutos ','c) 14 horas 15 minutos              ','d) 7 horas 16 minutos','Pendiente',3,4,NULL),
	(38,'Un cartero debe entregar en diferentes direcciones 12 paquetes. Si cada hora debe entregar 4 paquetes pero olvida entregar 2 , el tiempo que invierte en entregar todos es: ','a) 5 horas ','b) 4 horas ','c) 6 horas ','d) 3 horas ','Pendiente',3,3,NULL),
	(39,'El décimo término de la sucesión aritmética 3, 10, 17, 24, ? es: ','a) 30 ','b) 70 ','c) 66 ','d) 73 ','Pendiente',3,3,NULL),
	(40,'Sea la siguente expresión','a)2 dígitos','b) 3 dígitos            ','c) 4 dígitos     ','d) 5 dígitos ','Pendiente',3,3,'3/40.png'),
	(41,'Sea la siguiente expresión','a)','b)','c)','d)','Pendiente',3,4,'3/41.png'),
	(42,'En la fiesta de fin de año, asistieron varios profesores, todos se saludaron de mano, alguien se ocupó de contar el número de saludos y en total fueron 105 apretones de mano. ¿Cuántos profesores asistieron a la fiesta? ','a) 15 ','b) 105 ','c) 66 ','d) 53 ','Pendiente',3,1,NULL),
	(43,'Un recipiente contiene 10 litros de jugo de zanahoria. Se toma un litro y se sustituye por jugo de naranja. Posteriormente se toman 2 litros de esta mezcla y se reponen con jugo de naranja. ¿Qué porcentaje de jugo de zanahoria hay finalmente en los 10 litros? ','a) 82 %          ','b) 62 %        ','c) 90 %              ','d) 72 % ','Pendiente',3,4,NULL),
	(44,'Sea la siguiente Expresión:','a)','b)','c)','d)','Pendiente',3,2,'3/44.png'),
	(45,'En una escuela naval, cada estudiante ha de dibujar una bandera blanca y negra, de tal manera que la parte negra cubra exactamente los tres quintos de la bandera. ¿Cuántas de estas banderas cumplen esa condición? ','a) Ninguna      ','b) Una               ','c) Dos                ','d) Tres ','Pendiente',3,3,'3/45.png'),
	(46,'La expresión algebraica que representa el enunciado: ? Un número más su recíproco es igual a 2? es: ','a)','b)','c)','d)','Pendiente',3,1,'3/46.png'),
	(47,'El binomio que falta en la siguiente expresión matemática ','a)','b)','c)','d)','Pendiente',7,2,'7/47.png'),
	(48,'Sea la siguiente expresión','a)','b)','c)','d)','Pendiente',7,2,'7/48.png'),
	(49,'Sea la siguiente expresión','a)','b)','c)','d)','Pendiente',7,3,'7/49.png'),
	(50,'La simplificación a su mínima expresión de la fracción algebraica es:','a)','b)','c)','d)','Pendiente',7,1,'7/50.png'),
	(51,'Al efectuar las operaciones indicadas y simplificar se obtiene','a)','b)','c)','d)','Pendiente',7,3,'7/51.png'),
	(52,'Si factorizamos el polinomio, los factores son:','a)','b)','c)','d)','Pendiente',7,3,'7/52.png'),
	(53,'La solución de la ecuación   5 (2x-1) -5=3(x+2)-x  es:','a) x = -5           ','b) x = -11             ','c) x =5                 ','d) x = -2','Pnediente',7,4,NULL),
	(54,'Un tinaco de almacenamiento de agua se puede llenar con 2 bombas trabajando juntas en 12 minutos. Se sabe que la bomba mayor puede hacer el llenado en 10 minutos menos que la otra. El tiempo en que puede llenar cada una trabajando sola el tinaco es: ','a) 10, 20 min.              ','b) 20, 30 min.             ','c) 30, 40 min.          ','d) 40, 50 min. ','Pendiente',7,2,NULL),
	(55,'Una compañía que vende calculadoras científicas vende en promedio 600 calculadoras mensualmente a $800.00 por cada una, la empresa observa que si reducen el precio en $20.00 por calculadora se venderán 25 calculadoras más mensualmente, ¿A qué precio hay que vender las calculadoras para obtener el máximo posible? ','a) $ 800.00            ','b) $720.00              ','c) $640.00              ','d) $ 520.00 ','Pendiente',7,2,NULL),
	(56,'Al simplificar al máximo aplicando leyes de los exponentes se obtiene','a)','b)','c)','d)','Pendiente',7,2,'7/56.png'),
	(57,'El valor de X es:','a)','b)','c)','d)','Pendiente',7,2,'7/57.png'),
	(58,'Al simplificar se obtiene','a)1','b)0','c)-1','d)2','Pendiente',7,2,'7/58.png'),
	(59,'Sea la siguinete expresión','a)','b)','c)','d)','Pendiente',7,4,'7/59.png'),
	(60,'Sea la siguinete expresión','a)','b)','c)','d)','Pendiente',7,4,'7/60.png'),
	(61,'El menor de dos números impares consecutivos es el doble del mayor disminuido en 15. Halla los números. ','a) 17 y 11     ','b) 9 y 11         ','c) 11 y 13       ','d)  11 y 15 ','Pendiente',7,3,NULL),
	(62,'Sea la siguiente expresión','a)','b)','c)','d)','pendiente',7,3,'7/62.png'),
	(63,'Sea la siguiente expresión','a)','b)','c)','d)','pendiente',7,2,'7/63.png'),
	(64,'Sea la siguiente expresión','a)','b)','c)','d)','pendiente',7,2,'7/64.png'),
	(65,'Sea la siguiente expresión','a)-2','b)-1','c)1','d)5','pendiente',7,4,'7/65.png'),
	(66,'Sea la siguiente Expresión','a)','b)','c)','d)','pendiente',7,2,'7/66.png'),
	(67,'Calcula el valor del angulo B','a)15°','b)7°|','c)4°','d)66°','pendiente',7,4,'7/67.png'),
	(68,'Determina el valor de 2 ángulos que son suplementarios y el mayor es  50° más grande que la quinta parte del menor. ','a) 108.33° y 71.67°      ',' b) 33.33° y 56.67°     ','c) 258.33° y 101.67°','d) 156° y 24°','Pendiente',7,1,NULL),
	(69,'En el siguiente polígono regular, indica cuál es el valor del ángulo ?:','a) 18°             ','b) 144° ','c)  72°','d) 66° ','Pendiente',7,2,'7/69.png'),
	(70,'Calcula el número de lados que tiene un polígono regular en el que se pueden trazar ','a) 10          ','b) 9             ','c) 12             ','d) 15 ','Pendiente',7,4,NULL),
	(71,'El perímetro del siguiente triángulo es:','a) 103.2592 cm.            ','b) 48.5 cm.            ',' c) 208 cm','d) 30 cm. ','Pendiente',7,2,'7/71.png'),
	(72,'Al simplificar la expresión  ( sec ? + tan ?) (1 ? sen ?) se obtiene: ','a)   1           ','b)  cos ?              ','c)    tan ?                  ','d) sen ?','Pendiente',7,2,NULL),
	(73,'Si los triángulos de la siguiente figura son semejantes, Encuentra el valor del lado BC.','a) 52              ','b) 62                   ','c)42','d)32','Pendiente',7,3,'7/73.png'),
	(74,'La expresión      11/6  (pi)  radianes   es equivalente a, ¿cuántos grados sexagesimales? ','a) 360°              ','b) 330°               ','c) 180°          ','d) 45° ','Pendiente',7,2,NULL),
	(75,'En un triángulo rectángulo isósceles uno de los ángulos agudos es igual a 45°. ¿Cuál es el valor numérico de la Hipotenusa? ','a)','b)','c)','d)','Pendiente',7,1,'7/75.png'),
	(76,'La medida de un ángulo es igual a (4x + 20)°  y su ángulo opuesto por el vértice mide (6x ? 34)° . El valor de es igual a: ','a)  22°                       ','b)   27°                    ','c)  37°                 ','d) 52°','Pendiente',7,2,NULL),
	(77,'Sea la siguiente expresión','a)','b)','c)','d)','pendiente',7,2,'7/77.png'),
	(78,'Sea la siguiente expresión','a)','b)','c)','d)','pendiente',7,1,'7/78.png'),
	(79,'El Círculo Trigonométrico queda dividido en cuatro partes por los Ejes Coordenados, denominadas Cuadrantes. Por tanto, el ángulo de 415° está ubicado ¿en qué Cuadrante? ','a) Primer Cuadrante             ','b) Segundo Cuadrante ','c) Tercer Cuadrante             ','d) Cuarto Cuadrante','pendiente',7,1,''),
	(80,'Sea la siguiente expresión','a)','b)','c)','d)','pendiente',7,3,'7/80.png'),
	(81,'Postularon que todas las especies de seres vivos han evolucionado con el tiempo a partir de un antepasado común mediante un proceso denominado selección natural. ','a) Charles Darwin y Alfred Russel Wallace ','b) Alfred Russel Wallace y Jean B. Lamarck ','c) Carlos Linneo y Charles Darwin ','d) Gregor Mendel y Charles Darwin','Pendiente',4,1,NULL),
	(82,'Son ejemplo de factores bióticos: ','a) Luz y agua         ','b) Sal y plantas     ','c) Agua y polen      ','d) Plantas y animales','pendiente',4,4,NULL),
	(83,'Ordena los siguientes niveles taxonómicos en forma descendente: ','a) 7, 3, 2, 5, 6, 4, 1 ','b) 7, 3, 2, 5, 6, 1, 4 ','c) 7, 6, 5, 4, 3, 2, 1 ','d) 7, 3, 2, 6, 4, 5, 1','pendiente',4,4,NULL),
	(84,'Conjunto de organismos o individuos de la misma especie que coexisten en un mismo espacio y tiempo, y que comparten ciertas propiedades biológicas. ','a) Población','b) Ecosistema           ','c) Comunidad             ','d) Biodiversidad','pendiente',4,1,NULL),
	(85,'Estudia las interacciones de los organismos y su ambiente. ','a) Taxonomía           ','b) Ecología             ','c) Sistemática            ','d) Botánica','pendiente',4,2,NULL),
	(86,'Es el centro de la hemoglobina, y necesario para el transporte de oxígeno a las células. ','a) El fósforo            ','b) El potasio              ','c) El yodo                 ','d) El hierro','pendiente',4,4,NULL),
	(87,'La alteración de la composición sanguínea entendida como la condición clínica determinada por una disminución de la masa eritrocitaria que condiciona una concentración baja de hemoglobina es conocida cómo. ','a) Anemia         ','b) Diabetes II         ','c) Bulimia nerviosa       ','d) Anorexia nerviosa','pendiente',4,1,NULL),
	(88,'Tipo de metabolismo energético en el que los seres vivos extraen energía de moléculas orgánicas, como la glucosa, por un proceso complejo en el que el carbono es oxidado: ','a) Respiración aerobia \n','b) Respiración anaerobia ','c) Respiración con Dióxido de carbono y agua ','d) Respiración con Dióxido de carbono y oxígeno','pendiente',4,1,NULL),
	(89,'¿Cuáles son métodos anticonceptivos quirúrgicos? ','a) Implante anticonceptivo y anillo vaginal ','b) DIU y Condón masculino ','c) Salpingoclasia y Vasectomía ','d) Condón femenino y Salpingoclasia','pendiente',4,3,NULL),
	(90,'Consiste en que de un organismo ya desarrollado se desprende una sola célula o trozos del cuerpo, los que por procesos mitóticos son capaces de formar un individuo completo, genéticamente idéntico a él. Se lleva a cabo con un solo progenitor y sin la intervención de los núcleos de las células sexuales o gametos. ','a) Reproducción sexual ','b) Reproducción asexual ','c) Reproducción con un solo progenitor y no hay células especializadas ','d) Reproducción con dos progenitores y no hay células especializadas','pendiente',4,2,NULL),
	(91,'Son aquellas sustancias resultantes de la combinación de dos o más sustancias, de tal forma que es posible identificarlos por sus propiedades individuales y originales: ','a) Mezclas             ',' b) Fórmulas             ','c) Compuestos             ','d) Soluciones','pendiente',5,1,NULL),
	(92,'Cuando se unen dos no metales, en donde los átomos son iguales, generalmente son moléculas diatónicas da origen al enlace: ','a) Electrovalente ','b) Covalente puro ','c) Covalente polar          ','d) Covalente coordinado ','pendiente',5,2,NULL),
	(93,'¿De qué tipo son los hidrocarburos que presentan doble enlace en su estructura? ','a) Alcanos           ','b) Alquinos           ','  c) Alquenos               ','d) Ciclo Alcanos','pendiente',5,3,NULL),
	(94,'La fórmula del Ácido Sulfúrico es: ','a) HCl           ',' b) N2H3 ','c) H2SO4           ','d) NaOH','pendiente',5,3,NULL),
	(95,'Este tipo de enlaces se presentan en moléculas como el Cloruro de Sodio: ','a) Iónicos           ','b) Metálicos ','c) Coordinados','d) Covalentes no polares','pendiente',5,1,NULL),
	(96,'En la siguiente ecuación química: KClO4                      KCl + O2 el coeficiente del KCl que nos indica la ecuación ajustada es: ','a) 1               ','b) 2                  ','c) 3                  ','d) 5','pendiente',5,1,'5/96.png'),
	(97,'La ecuación Al(OH)3 + H2SO4       ------------>   Al2(SO4)3 + H2O está correctamente ajustada si el coeficiente del H2SO4 es: ','a) 1             ','b) 2                 ','c) 3            ','d) 4','pendiente',5,3,'5/97.png'),
	(98,'Es el número de orbitales que se tienen en el subnivel ?p?: ','a) 1            ','b) 3               ','c) 5            ','d) 10','pendiente',5,2,NULL),
	(99,'Los anhídridos están formados por: ','a) Un metal y oxígeno                   ','b) Un no metal y oxígeno ','c) Un metal e ión hidroxilo            ','d) Un no metal e ión hidroxilo','pendiente',5,2,NULL),
	(100,'La reacción entre un hidrácido y un hidróxido, se clasifica como de:','a) Síntesis o adición','b) Descomposición o análisis ','c) Doble sustitución o catatesis','d) Simple sustitución o simple desplazamiento','pendiente',5,3,NULL),
	(101,'¿Cuál es la fórmula que representa la cadena lineal del Pentano? ','a) CH3 -CH2-CH2-CH2-CH3                                   ','b) CH3-CH2-CH2 -CH3 ','b) CH3-CH2-CH2 -CH3 ','d) CH3 ? CH2-CH2-CH3','pendiente',5,1,NULL),
	(102,'Para obtener 37.74g de Fe a partir de la reacción: \nFe2O3 + CH4 ? 2 Fe+ CO + 2 H2O \n( 159.6 ) ( 16 ) ( 111.6 ) ( 28 ) ( 36 ) Masas de combinación se cuenta con mineral de Fe2O3, el cual contiene 20% de impurezas. En estas condiciones, la cantidad de mineral necesaria para el proceso es aproximadamente:\n','a) 27 g                        ','b) 43 g                    ','c) 54 g                          ','d) 214 g','pendiente',5,2,NULL),
	(103,'Los compuestos _____________, se caracterizan por sus altos puntos de ebullición, formación de cristales, solubles en agua y al estar fundidos conducen la corriente eléctrica. ','a) Iónicos        ','B) Metálicos    ','C) Covalentes polares   ','D) Covalentes no polares','pendiente',5,1,NULL),
	(104,'En la clasificación de cadenas de compuestos orgánicos el benceno tiene las siguientes características. ','a) Alicíclico, saturado, arborescente, con aroma ','b) Cíclico, saturado, arborescente, con aroma ','c) Alicíclico, saturado, simple, con aroma ','d) Cíclico, no saturado, simple, con aroma','pendiente',5,4,NULL),
	(105,'En 1865 se propuso una estructura para el benceno. La Fórmula fue propuesta por: ','a) Dalton              ','b) Kekulé                  ','c) Millikan              ','d) Neuton','pendiente',5,2,NULL),
	(106,'Un automóvil viaja a razón de 90 km./h. ¿Qué distancia recorre en un minuto? ','a) 90 m.             ','b)150 m.              ','c) 900 m.                   ','d) 1500 m.','pendiente',6,4,NULL),
	(107,'Se deja caer un cuerpo desde la parte más alta de un edificio y tarda 6 segundos en llegar al suelo, la altura del edificio es igual a: ','a) 58.86 m.             ','b) 353.16 m.           ','c) 176.58 m.               ','d) 88.29 m.','pendiente',6,1,NULL),
	(108,'¿Cuál es el módulo del vector A= 3i-4j? ','a) -1                 ','b)1                   ','c)5                    ','d) 7','pendiente',6,3,NULL),
	(109,'Encuentra el tiempo que requiere el motor de un elevador cuya potencia es de 42 kw. Para elevar una carga de 4500 N. hasta una altura de 30 m. ','a) 10.34 s.                ','b) 9.17 s.                   ','c) 5.2 s.                  ','d) 3.2 s.','pendiente',6,4,NULL),
	(110,'Al convertir 60 °F a Celsius se obtiene: ','a) 155 °C                ','b) 15.5 °C                   ','c) 140 °C                ','d) 14 °C','pendiente',6,2,NULL),
	(111,'¿Cuál es el incremento en la temperatura de 400 g. de agua cuando se aplican 34 Kcal? ','a) 80 °C                   ','b) 85 °C                       ','c) 8.5 °C            ','d) 0.85 °C','pendiente',6,2,NULL),
	(112,'Dos cargas de 3 mC. se encuentran separadas 16 mm., ¿Qué sucede con la fuerza si la distancia se reduce a la mitad? ','a) 4 Farads                         ','b) 2 Farads\n                       ','c) Farad /2                       ','d) Farad /4','pendiente',6,2,NULL),
	(113,'¿Cuál es la intensidad de corriente que circula por un conductor de 25 ?, cuando se aplica en sus extremos una diferencia de potencial de 100 V.? \n','a) 4 Amperes.                    ','b) 0.25 Amperes.                 ','c) 2.5 Amperes. ','d) 40 Amperes','pendiente',6,1,NULL),
	(114,'Dos resistencias de 3 y de 6 ohm. se conectan en paralelo, ¿Cuál es su resistencia equivalente?','a)	9 ohms','b) 4.5 ohms','c) 2 ohms','d) 1/4 ohms','pendiente',6,3,NULL),
	(115,'Es el cambio de estado que ocurre de gas a líquido: ','a) Sublimación     ','b) Condensación      ','c) Evaporación      ','d) Cristalización','pendiente',6,2,NULL),
	(116,'¿Cuál de los siguientes cuerpos no es inercial: ','a) Una silla           ','b) Un Edificio        ','c) Un poste de luz       ','d) Una Fábrica','pendiente',6,1,NULL),
	(117,'¿Cuál será la aceleración que produce una fuerza de 100 N a un cuerpo que pesa 5000 g.?','a) 30 metros /segundo al cuadrado                      ','b)15 metros /segundo al cuadrado         ','c) 20 metros /segundo al cuadrado                                  ','d) 12 metros /segundo al cuadrado','pendiente',6,3,NULL),
	(118,'Indica cuál de los siguientes cuerpos es un ejemplo de Energía Mecánica','a) Una maquina       ','b) Un lápiz       ','c) Un desarmador       ','d) Un vaso','pendiente',6,1,NULL),
	(119,'Cuando dos bolas de billar colisionan a este efecto se le denomina','a) Choque Elástico                   ','b) Choque Inelástico ','c) Conducción                          ','d) Deformación Plástica','pendiente',6,1,NULL),
	(120,'Indica cuál de estos siguientes efectos físicos no es un método de electrización: ','a) Frotamiento             ','b) Contacto            ','c) Rigidez               ','d) Inducción','pendiente',6,3,NULL);

/*!40000 ALTER TABLE `preguntas` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profiles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `profiles`;

CREATE TABLE `profiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `apaterno` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `amaterno` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_profile` (`user_id`),
  CONSTRAINT `user_profile` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;

INSERT INTO `profiles` (`id`, `nombre`, `apaterno`, `amaterno`, `user_id`)
VALUES
	(1,'Admin','Admin','Admin',1),
	(6,'Daniel','Franco','Dilandy',6),
	(7,'Lockon','Stratos','Dilandy',8),
	(8,'Francisco','Cerda','Martinez',7),
	(9,'Test','User','Lalala',9),
	(10,'Francisco','Cerda','Martinez',10),
	(11,'Francisco','Cerda','Martínez',11),
	(12,'Administrador','Administrador','Administrador',12),
	(13,'Profesor','Profesor','Profesor',13),
	(14,'Alumno','Prueba','Dos',14);

/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table refresh_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `refresh_tokens`;

CREATE TABLE `refresh_tokens` (
  `refresh_token` varchar(40) NOT NULL,
  `client_id` char(36) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `expires` int(11) NOT NULL,
  `scope` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`refresh_token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `refresh_tokens` WRITE;
/*!40000 ALTER TABLE `refresh_tokens` DISABLE KEYS */;

INSERT INTO `refresh_tokens` (`refresh_token`, `client_id`, `user_id`, `expires`, `scope`)
VALUES
	('960cd80bd61be31f6175a2ec6b10f1cb5c336423','NTRiZDBkZTU0ZjE4Njli',7,1422885830,'');

/*!40000 ALTER TABLE `refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rest_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rest_logs`;

CREATE TABLE `rest_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `controller` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `model_id` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `requested` datetime NOT NULL,
  `apikey` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `httpcode` smallint(3) unsigned NOT NULL,
  `error` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ratelimited` tinyint(1) unsigned NOT NULL,
  `data_in` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `meta` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data_out` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `responded` datetime NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table resultados
# ------------------------------------------------------------

DROP TABLE IF EXISTS `resultados`;

CREATE TABLE `resultados` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `examen_id` int(11) unsigned NOT NULL,
  `tema_id` int(11) unsigned NOT NULL,
  `puntaje` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `resultado_has_evaluacion` (`examen_id`),
  KEY `resultado_has_tema` (`tema_id`),
  CONSTRAINT `resultado_has_evaluacion` FOREIGN KEY (`examen_id`) REFERENCES `evaluaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resultado_has_tema` FOREIGN KEY (`tema_id`) REFERENCES `temas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

LOCK TABLES `resultados` WRITE;
/*!40000 ALTER TABLE `resultados` DISABLE KEYS */;

INSERT INTO `resultados` (`id`, `examen_id`, `tema_id`, `puntaje`)
VALUES
	(6,4,2,0),
	(7,5,2,1),
	(8,6,1,3),
	(9,6,2,1),
	(10,6,3,2),
	(11,6,7,0),
	(12,6,4,3),
	(13,6,5,2),
	(14,6,6,1),
	(15,7,1,3),
	(16,7,2,1),
	(17,7,3,2),
	(18,7,7,0),
	(19,7,4,3),
	(20,7,5,2),
	(21,7,6,1),
	(22,9,2,2),
	(23,10,1,3),
	(24,10,2,3),
	(25,10,7,0),
	(26,10,4,0),
	(27,10,5,1),
	(28,10,6,0),
	(29,11,1,3),
	(30,11,2,3),
	(31,11,7,0),
	(32,11,4,0),
	(33,11,5,1),
	(34,11,6,0),
	(35,13,3,1),
	(36,14,1,0),
	(37,15,1,1),
	(38,15,2,2),
	(39,15,7,1),
	(40,15,4,1),
	(41,16,1,0),
	(42,16,2,0),
	(43,16,3,0),
	(44,16,4,1);

/*!40000 ALTER TABLE `resultados` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;

INSERT INTO `roles` (`id`, `name`, `created`, `modified`)
VALUES
	(1,'Administrador','2014-08-31 16:25:13','2014-08-31 16:25:13'),
	(2,'Profesor','2014-08-31 16:25:46','2014-08-31 16:25:46'),
	(3,'Alumno','2014-08-31 16:25:36','2014-08-31 16:25:36');

/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table temas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `temas`;

CREATE TABLE `temas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT '',
  `id_materia` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`id_materia`),
  KEY `fk_Tema_Materia1_idx` (`id_materia`),
  CONSTRAINT `materia_has_tema` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

LOCK TABLES `temas` WRITE;
/*!40000 ALTER TABLE `temas` DISABLE KEYS */;

INSERT INTO `temas` (`id`, `nombre`, `id_materia`)
VALUES
	(1,'Comunicación Español',1),
	(2,'Comunicación Inglés',2),
	(3,'Habilidad Matemática',3),
	(4,'Biología',4),
	(5,'Química',5),
	(6,'Física',6),
	(7,'Matemáticas',7);

/*!40000 ALTER TABLE `temas` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `password` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `email` varchar(40) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `role_id` int(10) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_role` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role_id`, `created`, `modified`)
VALUES
	(1,'Admin','$2a$10$ZhBSD5A2J9bCTKVLjQW15uCqFYXKTNDx1aXIGlFzISnhsCnmYQA2y','lalala@gmail.com',1,'2014-08-31 18:25:30','2014-08-31 18:25:30'),
	(6,'Daniel','$2a$10$/JOmIFikvm5vltHADrm.u.HbfAuKULaNMBJpDw0sMU0n80OkZ6DSi','lalala@gmail.com',1,'2014-08-31 18:25:30','2014-08-31 23:06:01'),
	(7,'Lockon','$2a$10$ZhBSD5A2J9bCTKVLjQW15uCqFYXKTNDx1aXIGlFzISnhsCnmYQA2y','lalala@gmail.com',2,'2014-08-31 19:32:37','2014-08-31 19:32:37'),
	(8,'Zuriel','$2a$10$6cIoyr0CWz3Rmg/PdWsMuepeS6Bv7mpiFid3msevS81t7vFV8OTvC','lalala@gmail.com',3,'2014-08-31 23:04:24','2014-08-31 23:04:24'),
	(9,'Test2','$2a$10$VzziFl8HuxBdK7MeS3lfjehVnpsZZTYOe/N6v3OW1LFSjH4/xgM7K','lalala@gmail.com',1,'2014-09-06 23:38:53','2014-09-07 02:20:02'),
	(10,'Paquito','$2a$10$2YuL4zzx7tAy78xYJXccdOzWQi8bQDABDq8vc9JK1TSKmARf4hQWG','francisco_cerda@gmail.com',1,'2014-12-01 12:48:35','2014-12-01 12:48:35'),
	(11,'Francisco','$2a$10$Gysi19P7ri0GzF1gEYUvSuOvZSFD0uh.bwS.QtnV52myg0iZZBqnO','paco@example.com',3,'2014-12-01 12:54:57','2014-12-01 12:54:57'),
	(12,'Administrador','$2a$10$htpNuUgmYCJQ31gOBCSE0e.kMM7qTNN7Z1ej.OAtCF1oLgEHlDZqK','admin@admin.com',1,'2014-12-01 20:06:55','2014-12-01 20:06:55'),
	(13,'Profesor','$2a$10$yDxr/2rjRuy.2R8HXygez.vJWaMvmkCPexfo9cXBigFWgwunTIbR6','profesor@profesor.com',2,'2014-12-01 20:07:39','2014-12-01 20:07:39'),
	(14,'Alumno1','$2a$10$Yh9FlfzshMbbv5RiLzw1N.SBGvW194I1gnX8wOXkoa0ABJUIi9C.O','alumno@alumno.com',3,'2014-12-01 20:08:31','2014-12-01 20:08:31');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
