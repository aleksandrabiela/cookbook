CREATE DATABASE  IF NOT EXISTS `cookbook` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `cookbook`;
-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: cookbook
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.16-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Ciasta','wypieki różne'),(2,'Desery','Desery'),(3,'Dania obiadowe','obiady');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipe_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `recipe_id` (`recipe_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`id`),
  CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `programuser` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (2,'pyszne ciasto',5,7),(3,'bardzo dobre',10,4),(4,'smaczne',9,4),(5,'najlepszy sernik',8,4),(6,'również uważam, że bardzo dobre',10,8),(7,'najlepszy kurczak',7,8),(8,'ok',4,9),(9,'super',10,9),(10,'komentarz',4,4);
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programuser`
--

DROP TABLE IF EXISTS `programuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programuser` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin` int(11) NOT NULL,
  `awatar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programuser`
--

LOCK TABLES `programuser` WRITE;
/*!40000 ALTER TABLE `programuser` DISABLE KEYS */;
INSERT INTO `programuser` VALUES (1,'root','root',1,NULL),(3,'ola','ola',0,NULL),(4,'aleksandra','ola',0,'aleksandra_awatar.jpg'),(6,'pawel','test',0,NULL),(7,'admin','admin',1,NULL),(8,'magdalena','magda',0,NULL),(9,'katarzyna','kasia',0,NULL),(10,'olcia','olcia',0,'olcia_awatar.jpg'),(11,'b','b',0,'b_awatar.jpg');
/*!40000 ALTER TABLE `programuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe`
--

DROP TABLE IF EXISTS `recipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipe` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `recipe_category_id` int(10) unsigned NOT NULL,
  `picture` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `recipe_category_id` (`recipe_category_id`),
  CONSTRAINT `recipe_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `programuser` (`id`),
  CONSTRAINT `recipe_ibfk_2` FOREIGN KEY (`recipe_category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe`
--

LOCK TABLES `recipe` WRITE;
/*!40000 ALTER TABLE `recipe` DISABLE KEYS */;
INSERT INTO `recipe` VALUES (4,'Cannelloni','Składniki: \r\n\r\n    ok 50 dag mięsa mielonego (np. szynka wieprzowa)\r\n    25 dag makaron cannelloni (u mnie: opakowanie 24 rurek o średnicy ok 2,5)\r\n    50 dag sera żółtego\r\n    jajo\r\n    2 duże cebule\r\n    2 puszki pomidorów\r\n    kilka ząbków czosnku (u mnie 5)\r\n    zioła prowansalskie\r\n    sól, pieprz\r\n\r\nMięso podsmażyć. Rzucić na olej pokrojoną cebulę, przepuszczony przez praskę czosnek (4 ząbki) i zeszklić. Mięso połączyć z cebulą i czosnkiem, dodać połowę drobno startego sera, zioła, posolić, popieprzyć, a kiedy całość ostygnie, wbić jajo i zamieszać. Formę do pieczenia wysmarować masłem i układać rury nadziane farszem jedna obok drugiej. Rurek nie należy wcześniej gotować. Farsz nakładać łyżeczką upychając nadzienie, w myśl zasady : \"ile rura przyjmie\".\r\n\r\nPrzygotować sos do zalania nafaszerowanych rurek. Pomidory przełożyć do miski, posolić, wcisnąć ząbek czosnku, dodać zioła i rozdrobnić blenderem, aż powstanie jednolity sos. Zalać nim makaron tak, aby rurki dokładnie przykryć. W tych miejscach, gdzie makaron będzie odsłonięty, wyschnie i stwardnieje. Piekarnik nagrzać do 180-200 stopni i piec ok 40 minut. 10 minut przed wyjęciem, posypać danie resztą startego sera i zapiec.\r\n\r\nDanie można odgrzewać w piekarniku, ale przewidując taką sytuację, nie należy posypywać serem tej części, która będzie odgrzewana. Przed odgrzaniem można dodatkowo polać danie rozrobionym z wodą koncentratem pomidorowym, z ziołami, solą, czosnkiem, a kiedy się zagrzeje, posypać serem i zapiec.',3,3,NULL),(5,'Sos śmietankowy','1. Pierś kurczaka pokroić w paski. Cebulę i czosnek posiekać.\r\n\r\n    2. Mięso oprószyć przyprawą do drobiu i odstawić na 30 minut do lodówki.\r\n\r\n    3. Na patelni rozgrzać olej z masłem, podsmażyć cebulę i czosnek. Dodać kurczaka i smażyć, od czasu do czasu mieszając, aż mięso będzie miękkie.\r\n\r\n    4. W międzyczasie w dużej ilości osolonej wody ugotować makaron al dente. Odcedzić.\r\n\r\n    5. Mąkę rozprowadzić w zimnej wodzie, dodać do kurczaka, wymieszać i chwilę gotować. Całość zalać śmietaną i chwilę gotować. Doprawić solą i pieprzem. Dodać makaron, wymieszać i podawać.\r\n\r\n   6.  Przed podaniem można posypać natką pietruszki.',3,3,NULL),(6,'Ciasto kokosowe','Wysmarować masłem i oprószyć mąką blaszkę 16x25 cm. Rozgrzać piekarnik do 180 stopni C.\r\n    Zmiksować wszystkie składniki w robocie kuchennym lub w blenderze.\r\n    Przełożyć ciasto do blaszki i piec w rozgrzanym piekarniku przez 50 minut.\r\n    Gdy całkowicie ostygnie, pokroić w kwadraty.',3,1,NULL),(7,'Kurczak w ziołach','Składniki:\r\n\r\n\r\n4 łyżki świeżych listków rozmarynu lub 2 łyżki suszonych\r\n6 do 8 świeżych listków szałwii\r\nzależnie od wielkości\r\nlub 3-4 suszone\r\n2 łyżki świeżego majeranku lub 1 łyżka suszonego\r\n1 łyżka świeżych listków tymianku lub 1½ łyżeczki suszonych\r\n1 łyżka siekanego szczypiorku\r\n2 liście laurowe\r\n1 łyżka kminku\r\nsok z 2 cytryn (8 łyżek); skórki zachować\r\n2 łyżki oliwy\r\n1½ łyżeczki grubej soli\r\n½ łyżeczki świeżo mielonego czarnego pieprzu\r\n2 kurczaki (po 1,5 kg) w całości\r\n\r\nPrzygotowanie:\r\n\r\nRozmaryn, szałwię, majeranek, tymianek, szczypiorek i listki laurowe posiekać na desce tak, żeby uzyskać jednorodną masę. W misce wymieszać z kminkiem, 2 łyżkami soku z cytryny, oliwą, ½ łyżeczki soli i ¼ łyżeczki pieprzu. Odstawić na bok. Starannie sprawić kurczaki. Wysuszyć z zewnątrz i od środka papierowym ręcznikiem. Polać 3 łyżkami soku z cytryny z zewnątrz i od środka. Rozłożyć i natrzeć pastą ziołową z zewnątrz i od środka. Skórki z cytryny włożyć do jamy brzusznej. Wstawić do lodówki na co najmniej godzinę, maksymalnie do 12 godzin. Piekarnik rozgrzać do 230°C. Oba kurczaki posypać resztą soli i ¼ łyżeczki pieprzu. Ułożyć piersią do dołu w brytfance na posmarowanej olejem kratce. Piec 30 minut. Obrócić piersią do góry i piec jeszcze 30 minut w temperaturze obniżonej do 190°C, aż skórka będzie chrupiąca i przybierze złocistą barwę. Temperatura mięsa powinna wynosić 74°C. Przed pokrojeniem i podaniem odstawić na 15-20 minut.',3,3,NULL),(8,'Sernik','Żółtka utrzeć z cukrem i cukrem waniliowym na puszystą masę, dodać tłuszcz, ucierać, następnie porcjami ser nie przerywając miksowania i budyń /proszek/.\r\nZ białek ubić sztywną pianę, dodać do masy serowej i delikatnie drewnianą łyżką wymieszać.\r\nOkrągłą dużą tortownicę wyłożyć papierem do pieczenia, ułożyć na dnia biszkopty, a na nie wylać masę serową.\r\nPiec w nagrzanym do max. 170st. piekarniku na 3 poziomie od dołu 40 min. Po tym czasie piekarnik wyłączyć, a sernik pozostawić do przestudzenia.',3,1,NULL),(9,'Naleśniki','Mąkę wsypać do miski, dodać jajka, mleko, wodę i sól. Zmiksować na gładkie ciasto. Dodać roztopione masło lub olej roślinny i razem zmiksować (lub wykorzystać tłuszcz do smarowania patelni przed smażeniem każdego naleśnika).\r\n    Naleśniki smażyć na dobrze rozgrzanej patelni z cienkim dnem np. naleśnikowej. Przewrócić na drugą stronę gdy spód naleśnika będzie już ładnie zrumieniony i ścięty.',1,2,NULL),(10,'Gofry','W średniej wielkości garnku (nie mniej niż 3 l poj.) na małym ogniu przez chwilę podgrzać mleko aby było tylko lekko ciepłe. Zdjąć z ognia, dodać pokrojone na kawałki masło i poczekać chwilę aż zacznie się rozpuszczać.\r\n    Do mleka z masłem wbić jajka, wkruszyć (lub wsypać) drożdże, dodać cukier i wymieszać ręcznym mikserem aż składniki się połączą.\r\n    Do garnka dodać mąkę, proszek do pieczenia i szczyptę soli, następnie wszystko dokładnie zmiksować aż do uzyskania jednorodnej masy. Garnek przykryć i odstawić aż masa kilkukrotnie powiększy swoją objętość (min. 1 i 1/2 godziny, można na dłużej).\r\n    Wyrośnięte ciasto krótko wymieszać (łyżką) po czym wykładać łyżką wazową na dobrze rozgrzaną gofrownicę. Gofry smażyć na złoty kolor. Podawać z marmoladą, bitą śmietaną i owocami.',3,2,NULL),(11,'Zupa z dyni','zrobić zupę',4,3,'C:\\xampp\\tmp\\phpC18E.tmp'),(12,'Makowiec','Mak',4,1,'vimeo.jpg'),(13,'przepis','cdscs',10,3,'vimeo.jpg'),(14,'nkk','nn',4,1,'C:\\xampp\\tmp\\php33E7.tmp'),(15,'a','a',4,2,'C:\\xampp\\tmp\\php8169.tmp');
/*!40000 ALTER TABLE `recipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'cookbook'
--

--
-- Dumping routines for database 'cookbook'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-12 19:11:22
