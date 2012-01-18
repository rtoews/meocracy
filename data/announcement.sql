-- MySQL dump 10.11
--
-- Host: meocracy.db.3410806.hostedresource.com    Database: meocracy
-- ------------------------------------------------------
-- Server version	5.0.91-log

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
-- Table structure for table `old_announcement`
--

DROP TABLE IF EXISTS `old_announcement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `old_announcement` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `entity_id` varchar(10) default '',
  `anouncement_id` varchar(10) default '',
  `date_beginning` date default NULL,
  `date_ending` date default NULL,
  `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `title` text,
  `description` text NOT NULL,
  `text` text,
  `image` varchar(50) default 'default.jpg',
  `question` text,
  `fiscal_impact` int(10) default NULL,
  `staffing_impact` int(10) default NULL,
  `sponsor_dept_1` int(3) default NULL,
  `sponsor_dept_2` int(3) default NULL,
  `views` int(8) default NULL,
  `feedback_support` int(8) default NULL,
  `feedback_oppose` int(8) default NULL,
  `feedback_total` int(8) NOT NULL,
  `feedback_average` int(8) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `old_announcement`
--

LOCK TABLES `old_announcement` WRITE;
/*!40000 ALTER TABLE `old_announcement` DISABLE KEYS */;
INSERT INTO `old_announcement` VALUES (1,'1','','0000-00-00','0000-00-00','0000-00-00 00:00:00','Green City Initiative Workshop 9: Implementation Strategies for Waste Management & Recycling','Want to recycle better? Come to our Green City Initiative Workshop 9.','City Hall Council Chambers, 12 Journey Aliso Viejo, CA. Refreshments will be provided.','photo_av_3.jpg','Should Aliso Viejo do more to recycle?',0,0,10,0,245,45,245,290,687),(2,'1','','0000-00-00','0000-00-00','0000-00-00 00:00:00','Aliso Niguel High School Batting Cages','This improvement allows the high school baseball team to use the school\'s batting cages at night and frees up time for Little League teams to use Woodfield Park.','The City worked collaboratively with the school and school district to install the lights to benefit the Aliso Viejo Little League and high school players in the community. The baseball teams at the high school had been using the lighted cages at nearby Woodfield Park for night practices, and sharing those cages with local Little League teams, which caused scheduling issues for the league. This improvement allows the high school baseball team to use the school\'s batting cages at night and frees up time for Little League teams to use Woodfield Park.','photo_av_1.jpg','Do you like the new batting cages?',240000,0,10,0,76,674,76,750,687),(3,'1','','0000-00-00','0000-00-00','0000-00-00 00:00:00','Founders Day Fair','Celebrate the City of Aliso Viejo','Celebrate the City of Aliso Viejo\'s 10th anniversary at its annual Founders Day - A Day at the Ranch from noon to 6 p.m. on Saturday, October 15 at 100 Park Ave.<p>Strolling the grounds of the old Aliso Viejo Ranch is a once-a-year opportunity to experience a glimpse of Aliso Viejo\'s history and heritage. This unique fun-filled event will feature live entertainment, a carnival zone with an array of rides, games, and amusements along with pony rides, a petting zoo, delectable food and much more! Enjoy storytelling, interactive exhibits and other demonstrations in the historical area to learn a little about Aliso Viejo\'s past to boot. Mark your calendars for this signature event and join the celebration! For more information, call 949-425-2537.','photo_av_4.jpg','Was last year\'s Founder\'s day fair a success?',0,0,6,0,986,765,986,1751,687),(4,'1','','0000-00-00','0000-00-00','0000-00-00 00:00:00','Woodfield Park and Aliso Viejo Community Park Shade Structures','The City, Aliso Viejo Community Association (AVCA), Aliso Viejo Little League and Aliso Viejo Girls Softball have been working together to install shade structures above the bleachers at the ball fields. The structures will shade spectators and protect them from foul balls and reduce glare. ','Aliso Viejo Little League and Aliso Viejo Girls Softball play at Woodfield Park and Aliso Viejo Community Park, respectively. The City, Aliso Viejo Community Association (AVCA), Aliso Viejo Little League and Aliso Viejo Girls Softball have been working together to install shade structures above the bleachers at the ball fields. The structures will shade spectators and protect them from foul balls and reduce glare. The structures are expected to be installed by the end of October/early November.','photo_av_2.jpg','Should Aliso Viejo install more shade structures?',24000,0,6,0,453,67,453,520,687),(5,'1','','0000-00-00','0000-00-00','0000-00-00 00:00:00','New School Resource Officer Joins Community This Fall','With more than 17 years of law-enforcement experience, and the last five on patrol, Deputy Darren Braham will take the helm as the City\'s new SRO.','Aliso Niguel High School students have a new School Resource Officer (SRO) joining them this fall.<p>With more than 17 years of law-enforcement experience, and the last five on patrol, Deputy Darren Braham will take the helm as the City\'s new SRO.<p>SROs play an important role in the safety of our schools. Collaboration between schools and law enforcement is a major step to increasing school security and preventing acts of terrorism or violence within schools.<p>Deputy Braham comes to the job with much experience. Before transferring to patrol, he worked as a jail training officer and classification deputy at the James A. Musick Facility. He\'s been a member of the department\'s Special Enforcement Team (S.E.T.), and is an essential part of the Critical Incident Response Team and DUI Task Force. He is a Drug & Alcohol Recognition (DAR) Expert.<p>Along with serving his community, Deputy Braham faithfully served his country as a Marine. From 1988 to 1993, he served in the Middle East (Operation Desert Shield/Storm) and in Somalia, Africa (Operation Restore Hope).<p>For more information about the SRO program, call 949-425-2560.','photo_av_5.jpg','Are you happy with our Community SRO program?',0,0,10,0,24,25,24,49,687),(6,'1','','0000-00-00','0000-00-00','0000-00-00 00:00:00','Pet Owners Should be Cautious About Coyotes','Coyotes are found throughout Orange County and ','With coyote sightings and attacks prevalent in the region, the City is reminding pet owners to beware of coyotes. Coyotes are found throughout Orange County and ','photo_av_6.jpg','Should Aliso Viejo do more about coyotes?',0,0,11,0,311,245,311,556,687),(7,'1','','0000-00-00','0000-00-00','0000-00-00 00:00:00','Save the Date for Snow Fest January 28','The City will once again transform the pristine park into a winter wonderland complete with snow.','Aliso Viejo\'s annual Snow Fest is set for January 28 at Grand Park, 26703 Aliso Creek Road. The City will once again transform the pristine park into a winter wonderland complete with snow.<p>Visitors will enjoy the Grand Mountain (long run), Bunny Hill (short run) and Snow Man Park for youngsters 5 and under. Along with tons of snow, Snow Fest will feature refreshments and music. The holiday event takes place in three sessions from 10 a.m. to 2 p.m.<p>Registration begins January 7. A rain date is set for February 11 (and February 25), if needed. Stay tuned to the City\'s eNews and website for more information.','photo_av_7.jpg','Was last year\'s Snow Fest a success?',6000,0,10,0,435,76,435,511,687),(8,'1','','0000-00-00','0000-00-00','0000-00-00 00:00:00','Make Plans for Special Holiday Concert Saturday, Dec. 10','The festive event will feature community choirs, music ensembles and dance companies representing diverse cultures and time-honored holiday traditions.','Enjoy a special Holiday Concert on Saturday, Dec. 10 in celebration of the 10th anniversary of the City of Aliso Viejo and Soka University.<p>The Holiday Concert is from 7 p.m. to 9 p.m. at Soka\'s new 1,000-seat Performing Arts Center, which was designed by Walt Disney Concert Hall designer Yasuhisa Toyota. The festive event will feature community choirs, music ensembles and dance companies representing diverse cultures and time-honored holiday traditions.<p>The event is $10 for adults; $5 for children and free for those who bring at least two canned food or nonperishable food items to benefit South County Outreach. Concert proceeds will benefit local scholarship programs. Tickets will be available at the door.<p>For more information, contact the Aliso Viejo Community Services Department at 949-425-2550.','photo_av_8.jpg','Was last year\'s Holiday Concert a success?',14000,0,6,0,345,986,345,1331,687),(9,'1','','0000-00-00','0000-00-00','0000-00-00 00:00:00','The Renaissance of Aliso Viejo October 26','This year\'s presentation - The Renaissance of Aliso Viejo - will focus on the City\'s rich cultural arts and highlight local talent within the community.','Mayor Carmen Cave will give her State of the City address on October 26 in Soka University\'s new 1,000-seat Performing Arts Center. This year\'s presentation - The Renaissance of Aliso Viejo - will focus on the City\'s rich cultural arts and highlight local talent within the community.<p>The event will feature a reception at 6 p.m. in the Soka art gallery followed by the presentation at 7 p.m. Guests will learn about the state of Aliso Viejo and will have the chance to savor talented local singers, dancers and musicians in a dazzling display that is sure to entertain people of all ages. The City\'s Citizen, Senior and Youth of the Year will be awarded, and a special documentary film in honor of the City of Aliso Viejo\'s 10th anniversary will be shown. The anniversary film will reflect the past decade in Aliso Viejo, the nation and world.<p>Tickets are $10 and are available by calling 949-425-2550. Soka University is located at 1 University Drive.','photo_av_9.jpg','Will you be attending this event?',0,0,6,0,76,453,76,529,687),(10,'1','','0000-00-00','0000-00-00','0000-00-00 00:00:00','Basic Yoga Class','Yoga Basics is perfect for beginners and people with some yoga experience.  ','Wednesday\'s thru October 26<ul><li>11:00 am - 11:30 am<li>Iglesia Park Community Center<li>$14 per class</ul>Yoga Basics is perfect for beginners and people with some yoga experience. Students practice correct alignment and breath in yoga poses. Classes end with relaxation and help students to achieve more flexibility, strength, balance and peace of mind. Bring your own yoga mat. Registration is available by contacting Angie Knight at 949-362-7742 or yogaknights711@aol.com.s','photo_av_10.jpg','Would you like to see more yoga in Aliso Viejo?',0,0,6,0,765,24,765,789,687),(11,'1','','0000-00-00','0000-00-00','0000-00-00 00:00:00','Tax Day Workshop','This workshop will help people of varying income levels to prepare and file their tax returns. ','This workshop will help people of varying income levels to prepare and file their tax returns. <ul><li>February 4 and 18<li>9:00 am - 4:00 pm<li>Iglesia Park Community Center<li>24671 Via Iglesia<li>FREE</ul>This workshop will help people of varying income levels to prepare and file their tax returns. For more information, call 949-425-2519 or e-mail gduran@cityofalisoviejo.com.','photo_av_11.jpg','Would you like more tax workshops like these?',0,0,6,0,133,453,133,586,687),(12,'1','','0000-00-00','0000-00-00','0000-00-00 00:00:00','Pet and Vet Clinic','The Family Resource Center and Mission Viejo Animal Services Center will be offering vaccinations and micro-chipping at a discounted price. ','The Family Resource Center and Mission Viejo Animal Services Center will be offering vaccinations and micro-chipping at a discounted price. <ul><li>Iglesia Park Community Center<li>January 14<li>9:30 - 11:00 a.m.<li>cash only</ul>The Family Resource Center and Mission Viejo Animal Services Center will be offering vaccinations and micro-chipping at a discounted price. Don\'t miss out on this opportunity to keep your pet healthy.<ul><li>$7 Rabies Vaccination<li>$17 FELV<li>$16 4 in 1 (Dog) DHPP<li>$13 Bordetella<li>$22 Lyme<li>$20 Purevax</ul>Family Resource Center<br>24671 Via Iglesia<br>Phone: (949) 425-2519<br>E-mail: gduran@cityofalisoviejo.com','photo_av_12.jpg','Is animal care a helpful service for the city to provide?',0,0,13,0,453,134,453,587,687);
/*!40000 ALTER TABLE `old_announcement` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-01-06 20:38:24
