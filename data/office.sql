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
-- Table structure for table `office`
--

DROP TABLE IF EXISTS `office`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `entity_id` int(8) NOT NULL,
  `office_id` int(8) default NULL,
  `office_name` varchar(255) default NULL,
  `office_type` varchar(50) default NULL,
  `order_rank` int(4) default NULL,
  `phone_area` int(3) default NULL,
  `phone_prefix` int(3) default NULL,
  `phone_suffix` int(3) default NULL,
  `phone_extension` varchar(10) default '',
  `email` varchar(100) default '',
  `description` text,
  `image` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office`
--

LOCK TABLES `office` WRITE;
/*!40000 ALTER TABLE `office` DISABLE KEYS */;
INSERT INTO `office` VALUES (1,1,1,'City Council','department',1,949,425,2510,'','city-council@cityofalisoviejo.com','The City Council is the legislative and policy-making body for the City of Aliso Viejo. Five Council Members are elected at-large for four-year, staggered terms of office. The Council annually elects one of its members to serve as Mayor. The Mayor presides over all Council meetings and is the ceremonial head of the City for official functions. <p>As Aliso Viejo\'s elected representatives, the City Council expresses the values of the electorate in keeping pace with viable community needs and for establishing the quality of municipal services. The Council determines service levels and expenditure obligations through the adoption of an annual budget; authorizes City contracts and expenditures; establishes City service and operating policies; and adopts such regulatory measures as may be necessary for the benefit and protection of the community. <p>Council members also represent the City on various intergovernmental organizations to achieve governmental cooperation, support legislation, and create programs that are consistent with the needs of the community. ','city_aliso_viejo.png'),(2,1,2,'City Manager','office',2,949,425,2510,'','city-manager@cityofalisoviejo.com','The City Manager\'s Office provides for continued administrative direction to all departments. This office is responsible for the execution of Council policy and the enforcement of all laws and ordinances. <p>Under Council\'s direction, the City Manager implements Council policy. The City Manager is the director of all City personnel; and as such, the City Manager establishes and maintains appropriate controls to ensure that all operating departments adhere to Council and other legally mandated policies and regulations. The City Manager oversees the preparation of the Annual Budget and its administration. <p>Additionally, the City Manager\'s Office is responsible for the administration of Human Resources. This includes salary and benefit administration, as well as review of personnel policies. Administrative and clerical support to the City Council Members are also provided through this office. ','city_aliso_viejo.png'),(3,1,3,'City Clerk','office',3,949,425,2505,'','city-clerk@cityofalisoviejo.com','The City Clerk\'s Office is the depository for all-official documents and records. The City Clerk has the statutory duty to record the official minutes of all City Council meetings; maintains original resolutions and ordinances and acts as the custodian of the City seal. The Clerk\'s Office prepares all necessary documents for public hearings, posts notices and copies of ordinances as required by law. <p>This Office assists and supports the public and City departments by making available the records necessary for the City to advance its administrative, legal and legislative functions. Additionally, the Clerk is responsible for municipal elections, maintenance of the Municipal Code, and the records management system for the City. <p>In Fiscal Year 2003-04, the positions of the City Clerk and Assistant to the City Manager were combined. As such, the office assists the City Manager with policy and operational activities. ','city_aliso_viejo.png'),(4,1,4,'City Attorney','department',4,949,425,2510,'','city-attorney@cityofalisoviejo.com','The City Attorney acts as the City\'s legal counsel and prepares resolutions, ordinances, and agreements. The City Attorney advises the City Council and staff on all legal matters relating to the operation of the City. This service is provided through a contract with the law firm of Best Best & Krieger LLP','city_aliso_viejo.png'),(5,1,5,'Financial Services','department',5,949,425,2521,'','finance@cityofalisoviejo.com','The Financial Services Department administers the financial operations of the City through Accounting, Treasury, Purchasing, and Payroll functions. This department is responsible for safeguarding the City\'s financial assets through prudent internal control policies; providing responsive accounting services within Generally Accepted Accounting Principles; and providing strategic and financial planning support and maintaining budgetary control over all City funds. The Financial Services Department is responsible for the safety, liquidity and maximization of the yields of the City\'s financial resources in accordance with the City\'s Investment Policy and for the preparation and administration of the City\'s annual budget. <p>Specific functions include: annual financial report preparation; financial audits; treasury portfolio administration; ledger reconciliation; receipt, custody and recordation of all revenues; banking services; accounts payable; accounts receivable; payroll; fixed assets; budget preparation; maintenance of the financial system. <p>The Financial Services Department is the proud recipient of the GFOA \"Certificate of Achievement for excellence in Financial Reporting\" for its Comprehensive Annual Financial Report for fiscal years 2002, 2003, 2004, 2005 and 2006. <p>The Director of Financial Services also serves as the City Treasurer. ','city_aliso_viejo.png'),(6,1,6,'Community Services & Special Projects','department',6,949,425,2550,'','communityservices@cityofalisoviejo.com','The Community Services & Special Projects Department serves our diverse community by enhancing the quality of life through a variety of recreational, cultural and educational opportunities and maximizes City resources through collaborative partnerships within the community, Additionally, the department provides oversight of the use and maintenance of Iglesia Park, the former Aliso Viejo Ranch site, as well as the the day-to-day operation of the City\'s Family Resource Center. <p>Of the 23 community parks located in the City, only Iglesia Park is owned and managed by the City. All others are maintained by Aliso Viejo Community Association (AVCA). Plans for the former AV Ranch site are under consideration by the City Council and not complete at this time. <p>Special projects include management of the City\'s website and Enews; providing support to community groups, organizations, AVCA and Chamber of Commerce with special events; production of public information material; and administration of the City Council\'s Community Grant Program. ','city_aliso_viejo.png'),(7,1,7,'Planning','department',7,949,425,2525,'','planning@cityofalisoviejo.com','The Planning Department provides the community with long and short term planning in order to coordinate and monitor growth and development. It is charged with the development and implementation responsibilities of the General Plan. It prepares and administers the zoning and subdivision ordinances and reviews development projects for compliance with various development ordinances. The Planning Department insures that all projects receive the required environmental review in compliance with CEQA. Further, it provides technical support to the City Council and the various planning related committees/commissions. ','city_aliso_viejo.png'),(8,1,8,'Building and Safety','department',8,949,425,2540,'','building@cityofalisoviejo.com','The Building and Safety Department\'s mission is to preserve life and property and protect residents\' safety. <p>In order to ensure the health and the safety of City residents, the Building and Safety Department earnestly coordinates and enforces all building and housing regulations established by State and Local Governments. Residents may obtain building permits, seek answers for questions related to building and safety issues, and attain copies of building plans through this Department as well. Building inspections are performed Monday - Friday by appointment only. Please call (949) 425-2545 to schedule your inspection. ','city_aliso_viejo.png'),(9,1,9,'Code Enforcement ','department',9,949,425,2539,'','codeenforce@cityofalisoviejo.com','The Code Enforcement Department is responsible for investigating complaints against and enforcing regulations regarding, zoning, signage, public nuisance, noise, and various other City codes. <p>The goal of the Code Enforcement Officer is to seek City residents\' voluntary compliance of City Ordinances and procedures in the areas where other residents\' health and welfare are concerned. ','city_aliso_viejo.png'),(10,1,10,'Public Works','department',10,949,425,2530,'','public-works@cityofalisoviejo.com','The Public Works Department oversees matters relating to City streets, public right-of-ways, City\'s capital projects, utilities, traffic related issues, solid waste, water quality, storm water conveyance and private developments. The Department coordinates with and provides information to general public, contractors, developers, utility companies, and other public agencies. It coordinates City projects and plans with other governmental agencies to ensure that the City\'s concerns are addressed (i.e. Orange County Transportation Authority, CalTrans, County of Orange, Moulton Niguel and El Toro Water Districts). Furthermore, it is also responsible for the day-to-day maintenance of all publicly owned properties, including streets, curbs, gutters, sidewalks, traffic signals, signs, street lights, and storm drains. The Department administers various maintenance contracts to accomplish all field maintenance works. <p>The Traffic Engineering Division investigates traffic issues by conducting traffic surveys and studies, and then recommending solutions to the City Council and the City Manager. <p>Responsibilities include review and development of City traffic engineering guidelines and standards, processing citizen concerns related to the existing circulation system and implementing corrective measures when appropriate, analyzing traffic collision records to identify accident patterns and recommending any corrective measures. <p>The goal of the Traffic Engineering Division is to improve the quality of life for Aliso Viejo residents by maximizing traffic safety and minimizing traffic congestion. <p>The Street Maintenance Division\'s goals are maintaining a pleasant living environment for the residents of Aliso Viejo, and providing hazard-free, safe roadways for motorists, and obstruction-free access for pedestrians. <p>In order to obtain the above goals, the Street Maintenance Division conducts inspections of street lights, intersection lights, and traffic controllers, monitors the City\'s street sweeping, plus removes debris from the public right of way, repairs streets and sidewalks. The Public Works Department includes the following Divisions: <ul><li>Engineering (General) <li>Traffic Engineering <li>Street Maintenance <li>Environmental Services </ul><p>Environmental Services includes programs for storm water run-off, solid waste and recycling.','city_aliso_viejo.png'),(11,1,11,'Public Safety','department',11,949,425,2562,'','policeservices@cityofalisoviejo.com','The City of Aliso Viejo contracts with the Orange County Sheriff for law enforcement services. These services include general law enforcement and traffic safety. <p>Law Enforcement<br>General law enforcement provides an assortment of officers to provide crime prevention via around-the-clock street patrols, narcotics prevention, special investigations, and the general enforcement of laws. Traffic safety provides routine traffic patrol as a means of encouraging motorists and pedestrians to comply with traffic laws and ordinances and, when necessary, issues citations and/or warnings for violations. Traffic accidents are investigated and their circumstances recorded and analyzed. This division also includes contract services for crossing guards and the administration of the City\'s parking citations. www.ocsd.org. <p>Crime Prevention<br>Crime Prevention: As a component of Police Services, the Crime Prevention Specialist\'s function involves the implementation of proactive Community Oriented Police programs such as Neighborhood Watch and Business Watch. Child safety programs, including bicycle rodeos, Walk to School Day events, and fingerprinting are additional services that are offered to the Aliso Viejo community. <p>Animal Care Services <br>Animal Care Services provides for the care, protection, and control of animals that stray from their homes or are abused by their owners. This service, currently under contract with the City of Mission Viejo, includes pick up of injured wildlife, impounding of stray dogs/cats, issuance of citations, and the provision of a shelter for homeless animals. http://cityofmissionviejo.org/Department.aspx?id=74 <p>Emergency Operations Plan (EOP) <br>The preservation of life and property is an inherent responsibility of Local, State, and Federal Governments. The City of Aliso Viejo, therefore, has prepared a comprehensive planning document, known as the Emergency Operation Plan, which serves as the basis for the City\'s emergency organization and emergency operations. The primary objective of this plan is to enhance the overall capabilities of government to respond to emergencies. ','city_aliso_viejo.png'),(12,1,12,'Fire Protection and Emergency Services ','agency',12,714,573,6000,'','info@OCFA.org','Fire Protection and Emergency Services are provided by the Orange County Fire Authority (OCFA). The OCFA was formed in 1995 and is governed by a Board of Directors comprised of representatives from the cities and unincorporated communities it protects www.ocfa.org. For inspection services, call (949) 362-4617. ','city_aliso_viejo.png'),(13,1,13,'Family Resource Center','department',13,949,425,2519,'','','The Aliso Viejo Family Resource Center is one of several throughout Orange County and is open to the residents of Aliso Viejo. Additionally, the South Orange County Family Resource Center is located in Mission Viejo and their number is (949) 364-0500 for residents of surrounding cities. <p>The Aliso Viejo FRC is located in the Iglesia Park Community Center, the building was constructed in the 1970\'s and the building was recently refurbished to accommodate the community\'s growing needs. <p>To help with training and personal development, the FRC provides positive discipline and parenting classes twice a month in the evenings. School readiness programs for children under age 5 were offered every Monday afternoon during the summer, sponsored by the Saddleback Unified School District. Additional weekly classes are offered for basic first aid, CPR, health and nutrition. Future programs such as computer learning and cooking classes are being developed. ESL and after-school Homework Club is offered three times a week through a partnership with SVUSD. Trips to the local library, immunization and health screenings are also on our wish list. <p>Our Summer drop-in recreation program, \"Kid\'s Factory\" was available during July and August, Monday through Friday, from 2:00 pm to 4:00 pm. There was no fee and it was a lot of fun! <p>We also offer a very popular Teen Program and Children\'s Choir. ','city_aliso_viejo.png'),(14,1,14,'Administrative Services','department',14,949,425,2510,'','','This department still needs a descroption.','city_aliso_viejo.png');
/*!40000 ALTER TABLE `office` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-01-08 20:05:38
