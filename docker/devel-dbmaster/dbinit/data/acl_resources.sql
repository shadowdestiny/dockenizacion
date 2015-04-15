-- phpMyAdmin SQL Dump
-- version 2.6.2-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Apr 15, 2015 at 11:40 AM
-- Server version: 1.0.16
-- PHP Version: 5.6.7-1
-- 
-- Database: `euromillions`
-- 

-- 
-- Dumping data for table `acl_resources`
-- 

INSERT INTO `acl_resources` VALUES (1, 'acp', 'backend', 'system', 0);
INSERT INTO `acl_resources` VALUES (2, 'article', 'frontend', 'content', 1);
INSERT INTO `acl_resources` VALUES (3, 'acp_article', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (4, 'news', 'frontend', 'content', 1);
INSERT INTO `acl_resources` VALUES (5, 'acp_news', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (6, 'faq', 'frontend', 'content', 1);
INSERT INTO `acl_resources` VALUES (7, 'acp_faq', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (8, 'acp_translation', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (9, 'acp_stats', 'backend', 'system', 0);
INSERT INTO `acl_resources` VALUES (10, 'acp_lottery', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (11, 'acp_user', 'backend', 'user', 0);
INSERT INTO `acl_resources` VALUES (12, 'acp_billing_payout', 'backend', 'billing', 0);
INSERT INTO `acl_resources` VALUES (28, 'billing', 'frontend', 'billing', 1);
INSERT INTO `acl_resources` VALUES (14, 'acp_news_publish', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (15, 'acp_article_publish', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (16, 'acp_article_delete', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (17, 'acp_news_delete', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (27, 'contact', 'frontend', 'content', 1);
INSERT INTO `acl_resources` VALUES (19, 'acp_default_acl', 'backend', 'system', 0);
INSERT INTO `acl_resources` VALUES (20, 'acp_default_country', 'backend', 'system', 0);
INSERT INTO `acl_resources` VALUES (21, 'acp_faq_edit', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (22, 'acp_faq_delete', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (23, 'acp_faq_publish', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (24, 'acp_lottery_edit', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (25, 'acp_news_edit', 'backend', 'content', 0);
INSERT INTO `acl_resources` VALUES (26, 'acp_translation_delete', 'backend', 'system', 0);
INSERT INTO `acl_resources` VALUES (29, 'cart', 'frontend', 'play', 1);
INSERT INTO `acl_resources` VALUES (30, 'pay', 'frontend', 'billing', 1);
INSERT INTO `acl_resources` VALUES (31, 'payout', 'frontend', 'billing', 1);
INSERT INTO `acl_resources` VALUES (32, 'login', 'frontend', 'user', 1);
INSERT INTO `acl_resources` VALUES (33, 'acp_log', 'backend', '', 0);
INSERT INTO `acl_resources` VALUES (34, 'lottery', 'frontend', 'play', 1);
INSERT INTO `acl_resources` VALUES (35, 'play', 'frontend', 'play', 1);
INSERT INTO `acl_resources` VALUES (36, 'user_account', 'frontend', 'user', 0);
INSERT INTO `acl_resources` VALUES (37, 'registration', 'frontend', 'user', 1);
INSERT INTO `acl_resources` VALUES (38, 'acp_default_currency', 'backend', 'system', 0);
INSERT INTO `acl_resources` VALUES (39, 'cron', 'backend', 'system', 0);
