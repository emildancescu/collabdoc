-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Dec 09, 2012 at 10:34 PM
-- Server version: 5.0.45
-- PHP Version: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `collabdoc`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `spreadsheets`
-- 

CREATE TABLE `spreadsheets` (
  `id` int(11) NOT NULL auto_increment,
  `fk_userID` int(11) NOT NULL,
  `docname` varchar(100) NOT NULL,
  `data` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- 
-- Dumping data for table `spreadsheets`
-- 

INSERT INTO `spreadsheets` VALUES (2, 5, 'sdad', '2012-12-09 16:28:45');
INSERT INTO `spreadsheets` VALUES (7, 1, '''test''', '2012-12-09 16:16:30');
INSERT INTO `spreadsheets` VALUES (6, 0, '''asdad''', '2012-12-09 16:15:45');
INSERT INTO `spreadsheets` VALUES (8, 6, 'First spreadsheet', '2012-12-09 17:56:55');
INSERT INTO `spreadsheets` VALUES (9, 6, 'SAC team members', '2012-12-09 18:00:12');

-- --------------------------------------------------------

-- 
-- Table structure for table `sp_data`
-- 

CREATE TABLE `sp_data` (
  `id` int(11) NOT NULL auto_increment,
  `fk_sheetID` int(11) NOT NULL,
  `fk_userID` int(11) NOT NULL,
  `content` text NOT NULL,
  `cell_id` varchar(100) NOT NULL,
  `data` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `sp_data`
-- 

INSERT INTO `sp_data` VALUES (1, 1, 2, 'asdasd', '5', '2012-12-09 16:58:13');
INSERT INTO `sp_data` VALUES (2, 1, 2, 'sdfasdadasd', '5', '2012-12-09 17:15:27');
INSERT INTO `sp_data` VALUES (3, 1, 2, 'sadasdas', '6', '2012-12-09 17:15:27');

-- --------------------------------------------------------

-- 
-- Table structure for table `sp_rights`
-- 

CREATE TABLE `sp_rights` (
  `id` int(11) NOT NULL auto_increment,
  `fk_sheetID` int(11) NOT NULL,
  `fk_userID` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `sp_rights`
-- 

INSERT INTO `sp_rights` VALUES (1, 2, 1);
INSERT INTO `sp_rights` VALUES (2, 7, 4);

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` VALUES (7, 'alexandru138@yahoo.com', '098f6bcd4621d373cade4e832627b4f6', 'Alexandru Irimia');
INSERT INTO `users` VALUES (6, 'dancescu_emilian@yahoo.com', '8287458823facb8ff918dbfabcd22ccb', 'Emil');
