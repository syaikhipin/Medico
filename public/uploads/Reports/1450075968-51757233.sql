-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 18, 2015 at 08:13 AM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `myceo`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE IF NOT EXISTS `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` mediumtext,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `google_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=800 ;

--
-- Dumping data for table `calendar`
--

INSERT INTO `calendar` (`id`, `title`, `description`, `start`, `end`, `entry_by`, `google_id`) VALUES
(14, 'Student', 'this is a test', '2015-10-08', '2015-10-09', 1, ''),
(15, 'trial', 'sdd', '2015-10-15', '2015-10-15', 7, ''),
(787, ' ISTE STATE LEVEL CONVENTION ', 'Dear Students,\n\nThis year our institute is bestowed with the honor of conducting “ISTE Students State Level Convention 2013” on 21st September, 2013 by the Executive Council, ISTE, New Delhi. Various ISTE awards will be presented in the Convention to the students for their performance during the year. Paper / Poster / Project Presentation /Robotics and Lan Gaming activities will be conducted during the convention.\n\nI m sending you details of the same just for your knowledge and information.\n\nDETAILS OF EVENTS ISTE STATE LEVEL CONVENTION 2013\nPATRONS\n\nDr. R. Murugesan\nPresident, ISTE\nDr. Vasani Rupesh P.\nChairman, ISTE Gujarat Section\nShree Pravinbhai Maniar\nChairman, V. V. P. E. C.\nDr. Sachin Parikh\nPrincipal, V. V. P. E. C.\n\nADVISORY BOARD\nProf. P. K .Desai, Vice President, ISTE\nDr. S. Basil Gnanappa, Executive Secretary, ISTE\nProf. Indrajit Patel, Executive Council Member, ISTE\nShree Chandrakantbhai Pavagadhi, Managing Trustee, V. V. P. E. C.\nShree Lalitbhai Mehta, Trustee, V. V. P. E. C.\nShree Kaushikbhai Shukla, Trustee, V. V. P. E. C.\n\nORGANIZING COMMITTEE\nORGANIZING CONVENERS\nMs. Raja Pooja A.\nFaculty Advisor, ISTE Student Chapter\nDr. Chirag Vibhakar\nH. O. D, Electrical Engg Department\nMs. Shilpa Kathad,\nFaculty Advisor, ISTE Staff Chapter\n\nPROGRAMME MEMBERS\n\nMs. Devangi Kotak\nAssistant Professor, C. E. Dept.\nMs. Darshana H. Patel\nAssistant Professor, I. T. Dept.\nMr. Jignesh Ajmera\nAssistant Professor, E. C. Dept.\nDr. Krishna Joshi\nAssistant Professor, B. T. Dept.\n \nMr. K. V. Nagecha\nAssistant Prof. & HOD, Civil Dept.\nDr. O. K. Mahadwad\nAssistant Prof. & HOD, Chemical Dept.\nMs. Megha Kariya\nAssistant Professor, Mechanical Dept.\n\nDATE OF EVENT: 21st September, 2013.\nEVENTS:      1) Paper Presentation\n                        2) Poster Presentation\n                        3) Project Presentation\n                        4) Robotics\n                        5) Lan Gaming\n \n \n \nFEES:\n·         Rs. 250 /- per person for maximum 3 Events (Paper, Poster, Project) – For ISTE Members.\n·         Rs. 350/- per person for maximum 3 Events (Paper, Poster, Project) – For Non - ISTE Members.\n·         Rs. 100/- per person for Robotics – For ISTE / Non-ISTE Members both.\n·         On the spot Registration for Lan Gaming.\n \nSUBMISSION:\nPaper: Full Length\nPoster / Project: Abstract\n \nDEADLINES:\n17th August - 2013 Submission of paper, poster and project details only for VVPians\n31st August – 2013: Submission of paper, poster and project details as mentioned above.\n5th September – 2013: Declaration of Final Acceptance on Website / Through Email.\n10th September – 2013: Registration / Payment of Fees\n \nPROCEDURE FOR REGISTRATION:\nStep – 1: Submit your paper / poster / project details on  iste2013@vvpedulink.ac.in(Please mention your branch with your name) before 31st August 2013.\nStep – 2: After confirmation of acceptance, on 5th September, Download Registration Form \nStep – 3: Draw a D. D. of the name “V. V. P. Project Co-ordinator”, payable at “Rajkot”. (Please write down your name at the back side of D. D. with title of your paper/poster/project)\nStep – 4: Post both Registration form as well as D. D. to our address, VVP Engineering College, Virda Vajadi, Kalawad Road, Rajkot, Gujarat-360005\n\nFOR FURTHER QUERIES:\nContact:\nStudent Co-ordinators:\n1)      Mangukiya Ajay                               2) Goswami Darshit\nM: 89800 89853                                 M: 90169 90875\n\nThanking you,\n\nPooja Raja\nFaculty', '2013-09-21', '2013-09-21', 31, '2phv51meb9g8rg4art4bmoppcg'),
(799, 'Testing Cal', 'This is a test to see how the Calendar works in the current system.', '2015-11-04', '2015-11-04', 41, ''),
(798, 'Hina birthday', NULL, '2015-11-10', '2015-11-10', 38, 'hjjs58lgbibea34k69sgr0t9gs'),
(788, 'GDG Ahmedabad - DevFest 2013', 'DevFest is a series of community-led events that have technical sessions centered around Google developer technologies and platforms (like Android, Chrome, Google+, and App Engine) and also Open source technologies.\n\nMore at: http://gdgahmedabad.com/devfest13/\n\nOfficial hash tags are: #DevFestAhm #GDGahmedabad #gDayX', '2013-09-15', '2013-09-15', 31, 'lhondp6270jft3r2gjvtbf5o1o'),
(789, 'Google I/O  Extended Gandhinagar 2013', NULL, '2013-05-15', '2013-05-15', 31, 'mfa04rpptp40v0o338npou5bco'),
(790, NULL, NULL, '2014-02-18', '2014-02-19', 31, '6on9tnqf3uhkfg9vfj2a702blc'),
(791, 'GDG Rajkot Devfest 2014', 'After the Grand success of ?#?AndroidWear? Event, we are really excited to see you all for our Devfest !\n\nYes, we are announcing the Devfest 2014 on 9th Nov 2014 in Association with Atmiya Institute Of Technology and Sciencet & Elluminati as our Partners !\n\nThis time we have come up with the new concept of ?#?CodeLabs? Only. As from the past few feedbacks we analysed that you guys are more interested into practical sessions rather than speaker talks. So this time we have kept only CodeLabs and no speaker session.\n\nCodeLab sessions will be conducted on below technologies\n1) ?#?Polymer?\n2) ?#?AngularJS?\n3) ?#?NodeJS? (A javascript based server)\n4) Android Wear\n5) ?#?IonicFramework? (Cross Platform Mobile Apps)\n6) ?#?MongoDB?\n\nFor this amazing Developer festival we would like to invite the GBG Rajkot and all the other Entrepreneurs and Developers.\n\nPlease stay tuned for the speaker details and registration link.\nThis time we have only 200 seats for the Devfest so book yours as soon as we publish the registration link.\n\nAITS - VENUE & INFRASTRUCTURE PARTNER\nELLUMINATI - TECHNOLOGY PARTNER\n\nThank you @Rajdeep Vaghela for the awesome design !', '2014-11-09', '2014-11-09', 31, 'il6ht3jdaqac9mr745v6a9a5d4'),
(792, 'Your Facebook account was accessed from Firefox on Linux at 11:26. Log in for more info.cfccccgccccc', NULL, '2014-12-15', '2014-12-15', 31, 'q6bvbcbk9u8k2s8kn2ug2sh418'),
(793, 'Baahubali - The Beginning (Hindi) (U/A)', 'To see detailed information for automatically created events like this one, use the official Google Calendar app. http://g.co/calendar\n\nThis event was created from an email that you received in Gmail. https://mail.google.com/mail?extsrc=cal&plid=ACUX6DMY_LHceeDABU0y099Lhik2FXG8C12k2EI\n', '2015-07-21', '2015-07-21', 31, '_6tlnaqrle5p6cpb4dhmj4phpehp7apb369hn0c3hdsq32s1mc5lnar3ae1j6ispgd1qjaq3f6plmicrf68rmoc1p69o6qobl64rn4qr76kqme'),
(794, 'August Office Hour: Campaign Challenge to rally around the latest Firefox!', 'The latest and greatest Firefox is optimized for Windows 10. Find out what kind of online sharing and offline events you can do to help Windows users use and love our browser.\n\n**Participating in this campaign challenge will allow you to move up in the recognition system, don''t miss out!\n\nAsk any program related questions in this etherpad: mzl.la/1MgPBZk. We''ll be answering them during Office Hour!', '2015-08-13', '2015-08-13', 31, 'picv6ii9upad8pqsr2bfkiuvo0'),
(795, 'Trial Event', NULL, '2015-10-28', '2015-10-28', 31, 'p96amsen6na6g0kspetqn2vdas');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
