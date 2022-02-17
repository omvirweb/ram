-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2022 at 04:34 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.0.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sbio`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(11) NOT NULL,
  `account_name` varchar(222) DEFAULT NULL,
  `account_phone` varchar(222) DEFAULT NULL,
  `account_email_ids` varchar(222) DEFAULT NULL,
  `account_mobile_numbers` varchar(255) DEFAULT NULL,
  `account_address` text,
  `account_state` int(11) DEFAULT NULL,
  `account_city` int(11) DEFAULT NULL,
  `account_postal_code` int(22) DEFAULT NULL,
  `account_gst_no` varchar(222) DEFAULT NULL,
  `account_pan` varchar(22) DEFAULT NULL,
  `account_aadhaar` varchar(22) DEFAULT NULL,
  `account_contect_person_name` varchar(222) DEFAULT NULL,
  `account_group_id` int(11) DEFAULT NULL,
  `opening_balance` double DEFAULT NULL,
  `account_type` int(11) DEFAULT NULL COMMENT '1 = cgst, 2 = cgst_interest, 3 = cgst_penalty, 4 = cgst_fees, 5 = cgst_other_charges, 6 = sgst, 7 = sgst_interest, 8 = sgst_penalty, 9 = sgst_fees, 10 = sgst_other_charges, 11 = igst, 12 = igst_interest, 13 = igst_penalty, 14 = igst_fees, 15 = igst_other_charges, 16 = utgst, 17 = utgst_interest, 18 = utgst_penalty, 19 = utgst_fees, 20 = utgst_other_charges',
  `credit_debit` tinyint(1) DEFAULT NULL COMMENT '1-Credit, 2-Debit',
  `consider_in_pl` tinyint(1) DEFAULT '0' COMMENT '1-Yes, 0-No',
  `is_tally` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=not from tally; 1=from tally',
  `added_from` text COMMENT 'Account added from',
  `is_kasar_account` tinyint(1) DEFAULT '0' COMMENT '1=Yes, 0=No',
  `is_bill_wise` int(11) DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `account_group`
--

CREATE TABLE `account_group` (
  `account_group_id` int(11) NOT NULL,
  `parent_group_id` int(11) NOT NULL COMMENT '0 = Is Parent',
  `account_group_name` varchar(255) NOT NULL,
  `sequence` int(11) NOT NULL,
  `display_in_balance_sheet` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-Yes, 0-No',
  `is_deletable` int(11) NOT NULL DEFAULT '1' COMMENT '1 = Deletable, 0 = Not Deletable',
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '1=deleted,0=not deleted',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_group`
--

INSERT INTO `account_group` (`account_group_id`, `parent_group_id`, `account_group_name`, `sequence`, `display_in_balance_sheet`, `is_deletable`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`, `user_created_by`, `user_updated_by`) VALUES
(1, 10, 'Expenses (Direct)', 2, 1, 0, 0, 1, '2017-08-04 07:40:24', 1, '2017-08-04 07:44:08', NULL, NULL),
(2, 10, 'Trading Account', 0, 1, 0, 0, 1, '2017-08-04 07:40:48', 1, '2017-08-04 07:46:54', NULL, NULL),
(3, 2, 'General Trading Account', 99, 1, 0, 0, 1, '2017-08-04 07:41:06', 1, '2017-08-04 07:41:06', NULL, NULL),
(4, 10, 'Income (Trading)', 2, 0, 0, 0, 1, '2017-08-04 07:41:33', 74, '2020-07-17 09:20:26', NULL, 74),
(5, 10, 'Jobwork Expense', 3, 1, 0, 0, 1, '2017-08-04 07:41:50', 1, '2017-08-04 07:45:38', NULL, NULL),
(6, 10, 'Jobwork Income (Trading)', 3, 0, 0, 0, 1, '2017-08-04 07:42:06', 74, '2020-07-17 09:20:37', NULL, 74),
(7, 10, 'Purchase Account', 1, 1, 0, 0, 1, '2017-08-04 07:42:18', 1, '2017-08-04 07:45:52', NULL, NULL),
(8, 10, 'Sales Account', 1, 1, 0, 0, 1, '2017-08-04 07:42:27', 1, '2017-08-04 07:45:58', NULL, NULL),
(9, 11, 'Expense Account', 1, 0, 0, 0, 1, '2017-08-04 07:42:43', 2, '2019-12-23 18:14:21', NULL, NULL),
(10, 0, 'Trading', 0, 1, 0, 0, 1, '2017-08-04 07:43:54', 1, '2017-08-04 07:43:54', NULL, NULL),
(11, 0, 'Profit & Loss', 0, 1, 0, 0, 1, '2017-08-04 07:45:17', 1, '2017-08-04 07:45:17', NULL, NULL),
(12, 0, 'Balance Sheet', 0, 1, 0, 0, 1, '2017-08-04 07:47:13', 1, '2017-08-04 07:47:13', NULL, NULL),
(13, 9, 'Financial Expense', 3, 1, 0, 0, 1, '2017-08-04 07:47:42', 1, '2017-08-04 09:16:27', NULL, NULL),
(14, 11, 'Income', 22, 0, 0, 0, 1, '2017-08-04 07:48:00', 74, '2020-07-17 09:19:39', NULL, 74),
(15, 11, 'Income (Other Then Sales)', 3, 0, 0, 0, 1, '2017-08-04 07:48:51', 74, '2020-07-17 09:20:10', NULL, 74),
(16, 11, 'Partner Interest', 4, 1, 0, 0, 1, '2017-08-04 09:17:14', 1, '2017-08-04 09:17:14', NULL, NULL),
(17, 11, 'Partner Remuneration', 4, 1, 0, 0, 1, '2017-08-04 09:17:31', 1, '2017-08-04 09:17:31', NULL, NULL),
(18, 11, 'Revenue Accounts', 1, 1, 0, 0, 1, '2017-08-04 09:17:59', 1, '2017-08-04 09:17:59', NULL, NULL),
(19, 9, 'Salary Expense', 2, 0, 0, 0, 1, '2017-08-04 09:18:27', 2, '2019-12-23 18:13:53', NULL, NULL),
(20, 12, 'Current Assets', 1, 1, 0, 0, 1, '2017-08-04 09:18:47', 43, '2019-11-05 17:51:56', NULL, NULL),
(21, 20, 'Bank Accounts (Banks)', 8, 1, 0, 0, 1, '2017-08-04 09:19:11', 1, '2017-08-04 09:19:11', NULL, NULL),
(22, 12, 'Loans (Liability)', 9, 1, 0, 0, 1, '2017-08-04 09:19:45', 1, '2017-08-04 09:19:45', NULL, NULL),
(23, 22, 'Bank OCC a/c', 4, 1, 0, 0, 1, '2017-08-04 09:20:08', 1, '2017-08-04 09:20:08', NULL, NULL),
(24, 12, 'Capital Account', 1, 1, 0, 0, 1, '2017-08-04 09:20:23', 1, '2017-08-04 09:20:23', NULL, NULL),
(25, 12, 'Cash Ledger A/C.', 98, 1, 0, 0, 1, '2017-08-04 09:20:40', 1, '2017-08-04 09:20:40', NULL, NULL),
(26, 20, 'Cash-in-hand', 9, 1, 0, 0, 1, '2017-08-04 09:21:01', 1, '2017-08-04 09:21:01', NULL, NULL),
(27, 12, 'Current Liabilities', 5, 1, 0, 0, 1, '2017-08-04 09:21:18', 1, '2017-08-04 09:21:18', NULL, NULL),
(28, 20, 'Deposits (Asset)', 4, 1, 0, 0, 1, '2017-08-04 09:21:41', 1, '2017-08-04 09:21:41', NULL, NULL),
(29, 27, 'Duties & Taxes', 6, 1, 0, 0, 1, '2017-08-04 09:22:07', 1, '2017-08-04 09:22:07', NULL, NULL),
(30, 12, 'Fixed Assets', 2, 1, 0, 0, 1, '2017-08-04 09:22:21', 1, '2017-08-04 09:22:21', NULL, NULL),
(31, 12, 'Investments', 3, 1, 0, 0, 1, '2017-08-04 09:22:42', 1, '2017-08-04 09:22:42', NULL, NULL),
(32, 20, 'Loans & Advances (Asset)', 5, 1, 0, 0, 1, '2017-08-04 09:23:01', 1, '2017-08-04 09:23:01', NULL, NULL),
(33, 12, 'Misc. Expenses (Asset)', 6, 0, 0, 0, 1, '2017-08-04 09:23:52', 2, '2019-12-23 18:13:39', NULL, NULL),
(34, 12, 'Profit & Loss A/c', 99, 1, 0, 0, 1, '2017-08-04 09:24:33', 1, '2017-08-04 09:24:33', NULL, NULL),
(35, 27, 'Provisions', 7, 1, 0, 0, 1, '2017-08-04 09:24:47', 1, '2017-08-04 09:24:47', NULL, NULL),
(36, 24, 'Reserves & Surplus', 2, 1, 0, 0, 1, '2017-08-04 09:25:05', 1, '2017-08-04 09:25:05', NULL, NULL),
(37, 22, 'Secured Loans', 10, 1, 0, 0, 1, '2017-08-04 09:25:59', 1, '2017-08-04 09:25:59', NULL, NULL),
(38, 12, 'Stock-in-hand', 10, 1, 0, 0, 1, '2017-08-04 09:26:11', 1, '2017-08-04 09:26:11', NULL, NULL),
(39, 27, 'Sundry Creditors', 11, 1, 0, 0, 1, '2017-08-04 09:26:32', 1, '2017-08-04 09:26:32', NULL, NULL),
(40, 27, 'Sundry Creditors (Others)', 12, 1, 0, 0, 1, '2017-08-04 09:26:52', 1, '2017-08-04 09:26:52', NULL, NULL),
(41, 27, 'Sundry Creditors (Salary)', 13, 1, 0, 0, 1, '2017-08-04 09:27:26', 1, '2017-08-04 09:27:26', NULL, NULL),
(42, 20, 'Sundry Debtors', 7, 1, 0, 0, 1, '2017-08-04 09:27:44', 1, '2017-08-04 09:27:44', NULL, NULL),
(45, 12, 'Suspense Account', 8, 1, 0, 0, 1, '2017-08-04 09:28:34', 1, '2017-08-04 09:28:34', NULL, NULL),
(46, 22, 'Unsecured Loans', 3, 1, 0, 0, 1, '2017-08-04 09:28:47', 1, '2017-08-04 09:28:47', NULL, NULL),
(47, 27, 'Staff', 99, 1, 0, 0, 1, '2017-08-04 09:45:18', 1, '2017-08-04 09:45:18', NULL, NULL),
(48, 27, 'Supplier', 99, 1, 0, 0, 1, '2017-08-04 09:45:26', 1, '2017-08-04 09:45:26', NULL, NULL),
(49, 20, 'Customer', 99, 1, 0, 0, 1, '2017-08-04 09:45:47', 1, '2017-08-04 09:45:47', NULL, NULL),
(50, 0, 'Sales Bill', 1, 0, 0, 0, 43, '2019-10-26 15:16:34', 43, '2019-11-05 19:44:59', NULL, NULL),
(51, 0, 'Purchase Bill', 1, 0, 0, 0, 43, '2019-11-01 14:57:32', 43, '2019-11-05 19:45:19', NULL, NULL),
(52, 0, 'Credit Note Bill (Sales Return)', 1, 0, 0, 0, 43, '2019-11-01 14:57:41', 43, '2019-11-05 19:45:42', NULL, NULL),
(53, 0, 'Debit Note Bill (Purchase Return)', 1, 0, 0, 0, 43, '2019-11-01 14:57:52', 43, '2019-11-05 19:45:31', NULL, NULL),
(54, 0, 'abc', 0, 1, 1, 0, 2, '2019-11-13 19:13:28', 2, '2019-11-13 19:13:28', NULL, NULL),
(55, 42, 'Sundry Debtors (Misc)', 0, 1, 1, 0, 48, '2019-12-16 18:58:46', 48, '2019-12-16 18:58:46', NULL, NULL),
(56, 9, 'Professional Charges', 0, 0, 1, 0, 80, '2020-10-05 16:36:13', 80, '2020-10-05 16:38:15', 80, 80);

-- --------------------------------------------------------

--
-- Table structure for table `bank_master`
--

CREATE TABLE `bank_master` (
  `bank_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `ifsc_code` varchar(255) DEFAULT NULL,
  `delete_allow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = Not_allow, 1 = Allow',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bank_master`
--

INSERT INTO `bank_master` (`bank_id`, `company_id`, `bank_name`, `account_name`, `account_number`, `ifsc_code`, `delete_allow`, `created_by`, `created_at`, `updated_by`, `updated_at`, `user_created_by`, `user_updated_by`) VALUES
(1, 27, 'Cash', NULL, NULL, NULL, 0, 27, '2019-03-11 00:00:00', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`, `created_by`, `created_at`, `updated_by`, `updated_at`, `user_created_by`, `user_updated_by`) VALUES
(1, 'Cat 1', 39, '2019-08-29 14:41:47', 39, '2019-08-29 14:41:47', NULL, NULL),
(2, 'Cat 2', 65, '2020-05-28 18:57:56', 65, '2020-05-28 18:57:56', 46, 46);

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `city_id` int(11) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `city_name` varchar(222) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '1=deleted,0=not deleted',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`city_id`, `state_id`, `city_name`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`, `user_created_by`, `user_updated_by`) VALUES
(1, 7, 'Ahmedabad', 0, 2, '2019-06-12 14:38:40', 2, '2019-06-12 14:38:40', NULL, NULL),
(2, 7, 'Rajkot', 0, 2, '2019-06-12 14:38:50', 2, '2019-06-12 14:38:50', NULL, NULL),
(3, 7, 'Surat', 0, 2, '2019-12-16 17:46:37', 2, '2019-12-16 17:46:37', NULL, NULL),
(4, 7, '', 0, 2, '2019-12-16 17:48:42', 2, '2019-12-16 17:48:42', NULL, NULL),
(5, 15, 'Pune', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(6, 15, 'Mumbai', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(7, 7, 'Palanpur', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(8, 7, 'Ranakpur', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(9, 7, 'Baroda', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(10, 7, 'Bhavnagar', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(11, 31, 'Delhi', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(12, 12, 'Belgaum', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(13, 7, 'Kalawad', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(14, 29, 'Baghdogra', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(15, 15, 'Vasai', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(16, 7, 'Netrang', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(17, 15, 'Raver', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(18, 7, 'Rajula', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(19, 7, 'Dodiyala', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(20, 5, 'Raipur', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(21, 7, 'Jamnagar', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(22, 12, 'Banglore', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(23, 22, 'Udaipur', 0, 48, '2019-12-16 18:57:51', 48, '2019-12-16 18:57:51', NULL, NULL),
(24, 7, 'Dwarka', 0, 48, '2019-12-16 18:59:40', 48, '2019-12-16 18:59:40', NULL, NULL),
(25, 7, 'Gadu', 0, 48, '2019-12-16 18:59:40', 48, '2019-12-16 18:59:40', NULL, NULL),
(26, 7, 'Wakaner', 0, 48, '2019-12-16 18:59:40', 48, '2019-12-16 18:59:40', NULL, NULL),
(27, 7, 'Una', 0, 48, '2019-12-16 18:59:40', 48, '2019-12-16 18:59:40', NULL, NULL),
(28, 7, 'Jafrabad', 0, 48, '2019-12-16 18:59:40', 48, '2019-12-16 18:59:40', NULL, NULL),
(29, 7, 'Keshod', 0, 48, '2019-12-16 18:59:40', 48, '2019-12-16 18:59:40', NULL, NULL),
(30, 7, 'Mahuva', 0, 48, '2019-12-16 18:59:40', 48, '2019-12-16 18:59:40', NULL, NULL),
(31, 7, 'Prachi', 0, 48, '2019-12-16 18:59:40', 48, '2019-12-16 18:59:40', NULL, NULL),
(32, 7, 'Okha', 0, 48, '2019-12-16 18:59:40', 48, '2019-12-16 18:59:40', NULL, NULL),
(33, 7, 'Vithon', 0, 48, '2019-12-16 18:59:40', 48, '2019-12-16 18:59:40', NULL, NULL),
(34, 7, 'Aadipur', 0, 48, '2019-12-16 18:59:40', 48, '2019-12-16 18:59:40', NULL, NULL),
(35, 7, 'Jetpur', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(36, 7, 'Malgadh', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(37, 7, 'Metoda', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(38, 7, 'Gondal', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(39, 7, 'Jasdan', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(40, 7, 'Bhuj', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(41, 7, 'Mandvi-Kutch', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(42, 7, 'Paddhari', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(43, 7, 'Himmatnagar', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(44, 7, 'Jamkhambhaliya', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(45, 7, 'Porbandar', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(46, 7, 'Bharuch', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(47, 7, 'Samkhiyali', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(48, 7, 'Bhatiya', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(49, 7, 'Veraval', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(50, 7, 'Amreli', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(51, 7, 'Mundra', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(52, 7, 'Junagadh', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(53, 7, 'Amaran', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(54, 22, 'Pali', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(55, 7, 'Upleta', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(56, 7, 'Dhari', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(57, 7, 'Vadodara', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(58, 7, 'Lajai', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(59, 7, 'Viavader', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(60, 7, 'Jamjodhpur', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(61, 7, 'Nadiad', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(62, 7, 'Bhader', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(63, 7, 'Godhra', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(64, 7, 'Botad', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(65, 7, 'Anand', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(66, 7, 'Chotila', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(67, 7, 'Maliya', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(68, 7, 'Khambhaliya', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(69, 22, 'Ajmer', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(70, 7, 'Talaja', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(71, 7, 'Navagam', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(72, 15, 'Barshi', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(73, 26, 'Tripura', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(74, 7, 'Jamkandorana', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(75, 7, 'Sidhpur', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(76, 7, 'Kutiyana', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(77, 7, 'Anjar', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(78, 7, 'Gandhidham', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(79, 7, 'Deesa', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(80, 7, 'Nakhatrana', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(81, 7, 'Bagsara', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(82, 7, 'Dhrangdhra', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(83, 7, 'Radhanpur', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(84, 7, 'Bhunava', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(85, 7, 'Tankara', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(86, 22, 'Jodhpur', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(87, 7, 'Sardhar', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(88, 7, 'Kodinar', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(89, 7, 'Rapar', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(90, 22, 'Jaipur', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(91, 6, 'Ponda', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(92, 7, 'Babra', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(93, 6, 'Goa', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(94, 7, 'Naliya', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(95, 7, 'Morbi', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(96, 7, 'Raipur', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(97, 7, 'Surendranager', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(98, 15, 'Nasik', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(99, 7, 'Mangrod', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(100, 9, 'Sirmour', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(101, 7, 'Dhoraji', 0, 52, '2019-12-26 11:27:10', 52, '2019-12-26 11:27:10', NULL, NULL),
(102, 14, 'Ratlam', 0, 52, '2019-12-28 18:01:26', 52, '2019-12-28 18:01:26', NULL, NULL),
(103, 7, 'Derdi', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(104, 7, 'Devaliya', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(105, 7, 'Damnagar', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(106, 7, 'Surendranagar', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(107, 7, 'Bagasara', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(108, 7, 'Sikka', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(109, 7, 'Samakhiyari', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(110, 7, 'Padadhari', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(111, 7, 'Bhanvad', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(112, 7, 'Halvad', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(113, 7, 'Talala', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(114, 7, 'Kharoi', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(115, 7, 'Jam Raval', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(116, 7, 'Limbdi', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(117, 7, 'Chikhli', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(118, 7, 'Shapar', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(119, 7, 'Dhandhuka', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(120, 7, 'Sihor', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(121, 7, 'Sayla', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(122, 7, 'Kukavav', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(123, 7, 'Vyara', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(124, 7, 'Mangrol', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(125, 7, 'Atkot', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(126, 7, 'Lalpur', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(127, 7, 'Bhuhari', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(128, 7, 'Savarkundla', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(129, 7, 'Trajpar(Morbi)', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(130, 7, 'Dhrol', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(131, 7, 'Palitana', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(132, 7, 'Dhrangadhra', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(133, 7, 'Pardi', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(134, 7, 'Vishavadar', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(135, 7, 'Kamrej', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(136, 7, 'Adeshar', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(137, 7, 'Chobari', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(138, 7, 'Karjan', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(139, 7, 'Jam Dewaliya', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(140, 7, 'Vinchiya', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(141, 7, 'Mendarda', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(142, 7, 'Gariyadhar', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(143, 7, 'Bhachau', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(144, 7, 'Lathidad', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(145, 7, 'Zakharpatia', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(146, 7, 'Ravapar', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(147, 7, 'Ranavav', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(148, 7, 'Khambha', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(149, 7, 'Tarapur', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(150, 7, 'Dhokadava', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(151, 7, 'Manavadar', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(152, 7, 'Liliya', 0, 54, '2019-12-31 18:08:21', 54, '2019-12-31 18:08:21', NULL, NULL),
(153, 7, 'Rana Kandorana', 0, 54, '2020-01-06 17:28:29', 54, '2020-01-06 17:28:29', NULL, NULL),
(154, 14, 'Indore', 0, 48, '2020-01-25 13:22:55', 48, '2020-01-25 13:22:55', NULL, NULL),
(155, 7, 'Kutch', 0, 54, '2020-02-05 13:25:51', 54, '2020-02-05 13:25:51', NULL, NULL),
(156, 7, 'Amerli', 0, 65, '2020-05-28 18:44:07', 65, '2020-05-28 18:44:07', 46, 46),
(157, 7, 'Delhi', 0, 65, '2020-05-28 18:44:07', 65, '2020-05-28 18:44:07', 46, 46),
(158, 7, 'Bhatia', 0, 65, '2020-05-28 18:44:07', 65, '2020-05-28 18:44:07', 46, 46),
(159, 7, 'Bhavnager', 0, 65, '2020-05-28 18:44:07', 65, '2020-05-28 18:44:07', 46, 46),
(160, 7, 'Hariyana', 0, 65, '2020-05-28 18:44:07', 65, '2020-05-28 18:44:07', 46, 46),
(161, 7, 'Jamkandora', 0, 65, '2020-05-28 18:44:07', 65, '2020-05-28 18:44:07', 46, 46),
(162, 7, 'Jamkhambalia', 0, 65, '2020-05-28 18:44:07', 65, '2020-05-28 18:44:07', 46, 46),
(163, 7, 'Savarkundala', 0, 65, '2020-05-28 18:44:07', 65, '2020-05-28 18:44:07', 46, 46),
(164, 7, 'Madhapar', 0, 65, '2020-05-28 18:44:07', 65, '2020-05-28 18:44:07', 46, 46),
(165, 7, 'SUKHPAR', 0, 65, '2020-05-28 18:44:07', 65, '2020-05-28 18:44:07', 46, 46),
(166, 7, 'Amrali', 0, 66, '2020-05-28 19:15:12', 66, '2020-05-28 19:15:12', 46, 46),
(167, 7, 'Bagsra', 0, 66, '2020-05-28 19:15:12', 66, '2020-05-28 19:15:12', 46, 46),
(168, 7, 'Dhasa', 0, 66, '2020-05-28 19:15:12', 66, '2020-05-28 19:15:12', 46, 46),
(169, 7, 'Gandedam', 0, 66, '2020-05-28 19:15:12', 66, '2020-05-28 19:15:12', 46, 46),
(170, 7, 'Jamkhmbhaliaya', 0, 66, '2020-05-28 19:15:12', 66, '2020-05-28 19:15:12', 46, 46),
(171, 7, 'Jamnager', 0, 66, '2020-05-28 19:15:12', 66, '2020-05-28 19:15:12', 46, 46),
(172, 7, 'Jungad', 0, 66, '2020-05-28 19:15:12', 66, '2020-05-28 19:15:12', 46, 46),
(173, 7, 'Kodenar', 0, 66, '2020-05-28 19:15:12', 66, '2020-05-28 19:15:12', 46, 46),
(174, 7, 'Porbanmder', 0, 66, '2020-05-28 19:15:12', 66, '2020-05-28 19:15:12', 46, 46),
(175, 7, 'Surndnger', 0, 66, '2020-05-28 19:15:12', 66, '2020-05-28 19:15:12', 46, 46),
(176, 7, 'Ta Lala', 0, 66, '2020-05-28 19:15:12', 66, '2020-05-28 19:15:12', 46, 46),
(177, 7, 'Uplata', 0, 66, '2020-05-28 19:15:12', 66, '2020-05-28 19:15:12', 46, 46),
(178, 7, 'Wakner', 0, 66, '2020-05-28 19:15:12', 66, '2020-05-28 19:15:12', 46, 46),
(179, 7, 'Pranchi', 0, 61, '2020-05-30 21:15:45', 61, '2020-05-30 21:15:45', 61, 61),
(180, 7, 'Amran', 0, 61, '2020-05-30 21:15:45', 61, '2020-05-30 21:15:45', 61, 61),
(181, 7, 'Pali', 0, 67, '2020-06-01 17:57:03', 67, '2020-06-01 17:57:03', 67, 67),
(182, 7, 'Ratlam', 0, 67, '2020-06-01 17:57:03', 67, '2020-06-01 17:57:03', 67, 67),
(183, 7, 'Ajmer', 0, 67, '2020-06-01 17:57:03', 67, '2020-06-01 17:57:03', 67, 67),
(184, 7, 'Barshi', 0, 67, '2020-06-01 17:57:03', 67, '2020-06-01 17:57:03', 67, 67),
(185, 7, 'Mahesana', 0, 67, '2020-06-01 17:57:03', 67, '2020-06-01 17:57:03', 67, 67),
(186, 7, 'Tripura', 0, 67, '2020-06-01 17:57:03', 67, '2020-06-01 17:57:03', 67, 67),
(187, 7, 'Jaipur', 0, 67, '2020-06-01 17:57:03', 67, '2020-06-01 17:57:03', 67, 67),
(188, 7, 'Jodhpur', 0, 67, '2020-06-01 17:57:03', 67, '2020-06-01 17:57:03', 67, 67),
(189, 7, 'Ponda', 0, 67, '2020-06-01 17:57:03', 67, '2020-06-01 17:57:03', 67, 67),
(190, 7, 'Goa', 0, 67, '2020-06-01 17:57:03', 67, '2020-06-01 17:57:03', 67, 67),
(191, 7, 'Mumbai', 0, 67, '2020-06-01 17:57:03', 67, '2020-06-01 17:57:03', 67, 67),
(192, 7, 'Nasik', 0, 67, '2020-06-01 17:57:03', 67, '2020-06-01 17:57:03', 67, 67),
(193, 7, 'Sirmour', 0, 67, '2020-06-01 17:57:03', 67, '2020-06-01 17:57:03', 67, 67),
(194, 22, '', 0, 68, '2020-06-01 18:01:35', 68, '2020-06-01 18:01:35', 68, 68),
(195, 22, 'Nathdwara', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(196, 7, 'Mansa', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(197, 7, 'Gandhinagar', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(198, 24, 'Chennai', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(199, 7, 'Ahmadabad', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(200, 7, 'Nadiyad', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(201, 7, 'Patan', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(202, 22, 'Kota', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(203, 22, 'Bhilwara', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(204, 22, 'Ganganagar', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(205, 22, 'Sirohi', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(206, 7, 'Adipur', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(207, 22, 'Kankroli', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(208, 22, 'Sikar', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(209, 22, 'Abu Road', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(210, 7, 'Mehsana', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(211, 15, 'Nanded', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(212, 7, 'Visavadar', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(213, 22, 'Badmer', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(214, 22, 'Sumerpur', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(215, 22, 'Bikaner', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(216, 7, 'Mandavi', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69),
(217, 7, 'Bayad', 0, 69, '2020-06-01 20:22:10', 69, '2020-06-01 20:22:10', 69, 69);

-- --------------------------------------------------------

--
-- Table structure for table `company_invoice_prefix`
--

CREATE TABLE `company_invoice_prefix` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `prefix_name` varchar(15) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL COMMENT '0 = Not , 1 = Default',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `company_settings_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `module_name` tinyint(1) DEFAULT NULL COMMENT '1=Sales Invoice Main Fields; 2=Sales Invoice Line Item Fields; 3=Sales Invoice Prefix Setting',
  `setting_key` varchar(255) DEFAULT NULL,
  `setting_value` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(255) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`country_id`, `country_name`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`, `user_created_by`, `user_updated_by`) VALUES
(1, 'india', 0, 1, '2017-09-27 00:00:00', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `credit_note`
--

CREATE TABLE `credit_note` (
  `credit_note_id` int(11) NOT NULL,
  `credit_note_no` int(11) DEFAULT NULL,
  `bill_no` varchar(255) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL COMMENT 'From Account Table',
  `against_account_id` int(11) DEFAULT NULL,
  `credit_note_date` date DEFAULT NULL,
  `credit_note_desc` text NOT NULL,
  `qty_total` double DEFAULT NULL,
  `pure_amount_total` double DEFAULT NULL,
  `discount_total` double DEFAULT NULL,
  `cgst_amount_total` double DEFAULT NULL,
  `sgst_amount_total` double DEFAULT NULL,
  `igst_amount_total` double DEFAULT NULL,
  `other_charges_total` double DEFAULT NULL,
  `round_off_amount` double DEFAULT NULL,
  `amount_total` double DEFAULT NULL,
  `data_lock_unlock` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 0 = Unlock, 1 = Lock',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `debit_note`
--

CREATE TABLE `debit_note` (
  `debit_note_id` int(11) NOT NULL,
  `debit_note_no` int(11) DEFAULT NULL,
  `bill_no` varchar(255) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL COMMENT 'From Account Table',
  `against_account_id` int(11) DEFAULT NULL,
  `debit_note_date` date DEFAULT NULL,
  `debit_note_desc` text NOT NULL,
  `qty_total` double DEFAULT NULL,
  `pure_amount_total` double DEFAULT NULL,
  `discount_total` double DEFAULT NULL,
  `cgst_amount_total` double DEFAULT NULL,
  `sgst_amount_total` double DEFAULT NULL,
  `igst_amount_total` double DEFAULT NULL,
  `other_charges_total` double DEFAULT NULL,
  `round_off_amount` double DEFAULT NULL,
  `amount_total` double DEFAULT NULL,
  `data_lock_unlock` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 0 = Unlock, 1 = Lock',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `discount_id` int(11) NOT NULL,
  `account_id` varchar(255) DEFAULT NULL,
  `item_group_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `discount_detail`
--

CREATE TABLE `discount_detail` (
  `id` int(11) NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `discount_for_id` int(11) DEFAULT NULL COMMENT '1 = Sales, 2 = Purchase',
  `account_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_group_id` int(11) DEFAULT NULL,
  `discount_per_1` double DEFAULT NULL,
  `discount_per_2` double DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `document_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `document_link` text,
  `remark` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hsn`
--

CREATE TABLE `hsn` (
  `hsn_id` int(11) NOT NULL,
  `hsn` varchar(255) DEFAULT NULL,
  `hsn_discription` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_paid_details`
--

CREATE TABLE `invoice_paid_details` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `paid_amount` double DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_type`
--

CREATE TABLE `invoice_type` (
  `invoice_type_id` int(11) NOT NULL,
  `invoice_type` varchar(255) DEFAULT NULL,
  `delete_allow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = Yes, 1 = No',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice_type`
--

INSERT INTO `invoice_type` (`invoice_type_id`, `invoice_type`, `delete_allow`, `created_by`, `created_at`, `updated_by`, `updated_at`, `user_created_by`, `user_updated_by`) VALUES
(6, 'Tax Invoice', 1, 45, '2019-11-20 12:22:40', 45, '2019-11-20 12:22:40', NULL, NULL),
(7, 'Bill Of Supply', 1, 45, '2019-11-20 12:22:50', 45, '2019-11-20 12:22:50', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `sub_category_id` int(11) DEFAULT NULL,
  `hsn_code` double DEFAULT NULL,
  `list_price` double DEFAULT NULL,
  `mrp` double DEFAULT NULL,
  `current_stock_qty` double DEFAULT NULL,
  `opening_qty` double DEFAULT NULL,
  `opening_amount` double DEFAULT NULL,
  `cgst_per` double DEFAULT NULL,
  `sgst_per` double DEFAULT NULL,
  `igst_per` double DEFAULT NULL,
  `cess` varchar(222) DEFAULT NULL,
  `alternate_unit_id` int(11) DEFAULT NULL,
  `pack_unit_id` int(11) DEFAULT NULL,
  `item_type_id` int(11) DEFAULT NULL,
  `item_group_id` int(11) DEFAULT NULL,
  `item_desc` text,
  `purchase_rate` int(11) DEFAULT NULL COMMENT '1 = Including Tax, 2 = Excluding Tax.',
  `sales_rate` int(11) DEFAULT NULL COMMENT '1 = Including Tax, 2 = Excluding Tax.',
  `purchase_rate_val` double DEFAULT NULL,
  `sales_rate_val` double DEFAULT NULL,
  `minimum` int(11) DEFAULT NULL,
  `maximum` int(11) DEFAULT NULL,
  `reorder_stock` int(11) DEFAULT NULL,
  `shortname` varchar(255) DEFAULT NULL,
  `discount_on` tinyint(1) DEFAULT NULL COMMENT '1 = List Price, 2 = MRP',
  `discount_per` double DEFAULT NULL,
  `exempted_from_gst` tinyint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_current_stock_detail`
--

CREATE TABLE `item_current_stock_detail` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `st_change_id` int(11) DEFAULT NULL,
  `is_sales_or_purchase` varchar(255) DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_group`
--

CREATE TABLE `item_group` (
  `item_group_id` int(11) NOT NULL,
  `item_group_name` varchar(255) DEFAULT NULL,
  `discount_on` tinyint(1) DEFAULT NULL COMMENT '1 = List Price, 2 = MRP',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_group`
--

INSERT INTO `item_group` (`item_group_id`, `item_group_name`, `discount_on`, `created_at`, `created_by`, `updated_at`, `user_created_by`, `user_updated_by`, `updated_by`) VALUES
(2, 'test', NULL, '2019-04-25 14:52:47', 35, '2019-04-25 14:52:47', NULL, NULL, 35),
(3, 'hi', NULL, '2019-04-26 14:27:53', 36, '2019-04-26 14:27:53', NULL, NULL, 36);

-- --------------------------------------------------------

--
-- Table structure for table `item_type`
--

CREATE TABLE `item_type` (
  `item_type_id` int(11) NOT NULL,
  `item_type_name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_type`
--

INSERT INTO `item_type` (`item_type_id`, `item_type_name`, `created_by`, `created_at`, `updated_by`, `updated_at`, `user_created_by`, `user_updated_by`) VALUES
(1, 'Goods', 1, '2017-08-22 13:10:24', 1, '2017-08-22 13:10:24', NULL, NULL),
(2, 'Services', 1, '2017-08-22 13:10:49', 2, '2017-08-23 13:12:33', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `journal`
--

CREATE TABLE `journal` (
  `journal_id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `module` int(11) DEFAULT NULL COMMENT 'purchase_invoice = 1, sales_invoice = 2, credit_note = 3, debit_note = 4',
  `journal_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lineitems`
--

CREATE TABLE `lineitems` (
  `id` int(11) NOT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `sub_cat_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_group_id` int(11) DEFAULT NULL,
  `item_qty` double DEFAULT NULL,
  `pure_amount` double DEFAULT NULL,
  `item_qty2` double DEFAULT NULL COMMENT 'item_qty * item standerd_pack',
  `unit_id` int(11) DEFAULT NULL,
  `price_type` int(11) DEFAULT NULL COMMENT 'Purchase Rate = 1, Sell Rate = 2, List Price = 3, DLP = 4, MRP = 5',
  `price` double DEFAULT NULL,
  `item_mrp` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `discount_type` int(11) NOT NULL DEFAULT '1' COMMENT 'Percentage = 1, Amount = 2',
  `discounted_price` double DEFAULT NULL,
  `cgst` double DEFAULT NULL,
  `cgst_amount` double DEFAULT NULL,
  `sgst` double DEFAULT NULL,
  `sgst_amount` double DEFAULT NULL,
  `igst` double DEFAULT NULL,
  `igst_amount` double DEFAULT NULL,
  `other_charges` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `module` int(11) DEFAULT NULL COMMENT 'purchase_invoice = 1, sales_invoice = 2, credit_note = 3, debit_note = 4',
  `parent_id` int(11) DEFAULT NULL COMMENT 'Modules Parent Id ( e.g., for Purchase Invoice, purchase_invoice_id)',
  `picker_lineitem_id` int(11) DEFAULT NULL,
  `item_type_id` int(11) DEFAULT NULL,
  `rate_for_itax` double NOT NULL,
  `price_for_itax` double NOT NULL,
  `igst_for_itax` double NOT NULL,
  `note` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `locked_daterange`
--

CREATE TABLE `locked_daterange` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `daterange` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `module_roles`
--

CREATE TABLE `module_roles` (
  `module_role_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `role_name` varchar(255) DEFAULT NULL,
  `website_module_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `module_roles`
--

INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES
(1, 'view', 'view', 1),
(2, 'add', 'add', 1),
(3, 'edit', 'edit', 1),
(4, 'delete', 'delete', 1),
(5, 'view', 'view', 2),
(6, 'add', 'add', 2),
(7, 'edit', 'edit', 2),
(8, 'delete', 'delete', 2),
(9, 'view', 'view', 3),
(10, 'view', 'view', 4),
(11, 'add', 'add', 4),
(12, 'edit', 'edit', 4),
(13, 'delete', 'delete', 4),
(14, 'view', 'view', 5),
(15, 'add', 'add', 5),
(16, 'edit', 'edit', 5),
(17, 'delete', 'delete', 5),
(18, 'view', 'view', 6),
(19, 'add', 'add', 6),
(20, 'edit', 'edit', 6),
(21, 'delete', 'delete', 6),
(22, 'view', 'view', 7),
(23, 'add', 'add', 7),
(24, 'edit', 'edit', 7),
(25, 'delete', 'delete', 7),
(26, 'view', 'view', 8),
(27, 'add', 'add', 8),
(28, 'edit', 'edit', 8),
(29, 'delete', 'delete', 8),
(30, 'view', 'view', 9),
(31, 'add', 'add', 9),
(32, 'edit', 'edit', 9),
(33, 'delete', 'delete', 9),
(34, 'view', 'view', 10),
(35, 'add', 'add', 10),
(36, 'edit', 'edit', 10),
(37, 'delete', 'delete', 10),
(38, 'view', 'view', 11),
(39, 'add', 'add', 11),
(40, 'edit', 'edit', 11),
(41, 'delete', 'delete', 11),
(42, 'view', 'view', 12),
(43, 'add', 'add', 12),
(44, 'edit', 'edit', 12),
(45, 'delete', 'delete', 12),
(46, 'view', 'view', 13),
(47, 'add', 'add', 13),
(48, 'edit', 'edit', 13),
(49, 'delete', 'delete', 13),
(50, 'view', 'view', 14),
(51, 'add', 'add', 14),
(52, 'edit', 'edit', 14),
(53, 'delete', 'delete', 14),
(54, 'view', 'view', 15),
(55, 'add', 'add', 15),
(56, 'edit', 'edit', 15),
(57, 'delete', 'delete', 15),
(58, 'view', 'view', 16),
(59, 'view', 'view', 17),
(60, 'add', 'add', 17),
(61, 'edit', 'edit', 17),
(62, 'delete', 'delete', 17),
(63, 'view', 'view', 18),
(64, 'add', 'add', 18),
(65, 'edit', 'edit', 18),
(66, 'delete', 'delete', 18),
(67, 'view', 'view', 19),
(68, 'view', 'view', 20),
(69, 'add', 'add', 20),
(70, 'edit', 'edit', 20),
(71, 'delete', 'delete', 20),
(72, 'view', 'view', 21),
(73, 'add', 'add', 21),
(74, 'edit', 'edit', 21),
(75, 'delete', 'delete', 21),
(76, 'view', 'view', 22),
(77, 'add', 'add', 22),
(78, 'edit', 'edit', 22),
(79, 'delete', 'delete', 22),
(80, 'view', 'view', 23),
(81, 'add', 'add', 23),
(82, 'edit', 'edit', 23),
(83, 'delete', 'delete', 23),
(84, 'view', 'view', 24),
(85, 'add', 'add', 24),
(86, 'edit', 'edit', 24),
(87, 'delete', 'delete', 24),
(88, 'view', 'view', 25),
(89, 'add', 'add', 25),
(90, 'edit', 'edit', 25),
(91, 'delete', 'delete', 25),
(92, 'view', 'view', 26),
(93, 'add', 'add', 26),
(94, 'edit', 'edit', 26),
(95, 'delete', 'delete', 26),
(96, 'view', 'view', 27),
(97, 'add', 'add', 27),
(98, 'edit', 'edit', 27),
(99, 'delete', 'delete', 27),
(100, 'journal type 2', 'journal type 2', 27),
(101, 'view', 'view', 28),
(102, 'view', 'view', 29),
(103, 'view', 'view', 30),
(104, 'view', 'view', 31),
(105, 'view', 'view', 32),
(106, 'view', 'view', 33),
(107, 'view', 'view', 34),
(108, 'view', 'view', 35),
(109, 'view', 'view', 36),
(110, 'view', 'view', 37),
(111, 'view', 'view', 38),
(112, 'view', 'view', 39),
(113, 'view', 'view', 40),
(114, 'view', 'view', 41),
(115, 'view', 'view', 42),
(116, 'view', 'view', 43),
(117, 'view', 'view', 44),
(118, 'view', 'view', 44),
(119, 'Transport Name', 'Transport Name', 20),
(120, 'LR No', 'LR No', 20),
(123, 'View', 'view', 45),
(124, 'Add', 'add', 45),
(125, 'Edit', 'edit', 45),
(126, 'Delete', 'delete', 45),
(127, 'View', 'view', 46),
(128, 'View', 'view', 47),
(129, 'Add', 'add', 47),
(130, 'Edit', 'edit', 47),
(131, 'Delete', 'delete', 47),
(132, 'View', 'view', 48),
(133, 'Add', 'add', 48),
(134, 'Edit', 'edit', 48),
(135, 'Delete', 'delete', 48),
(136, 'View', 'view', 49),
(137, 'Add', 'add', 49),
(138, 'Edit', 'edit', 49),
(139, 'Delete', 'delete', 49);

-- --------------------------------------------------------

--
-- Table structure for table `pack_unit`
--

CREATE TABLE `pack_unit` (
  `pack_unit_id` int(11) NOT NULL,
  `pack_unit_name` varchar(222) NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '1=deleted,0=not deleted',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pack_unit`
--

INSERT INTO `pack_unit` (`pack_unit_id`, `pack_unit_name`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`, `user_created_by`, `user_updated_by`) VALUES
(1, 'KG', 0, 5, '2017-08-23 15:33:37', 2, '2017-08-31 15:14:42', NULL, NULL),
(2, 'GM', 0, 2, '2017-08-31 15:14:45', 2, '2017-08-31 15:14:57', NULL, NULL),
(3, 'Liter', 0, 2, '2017-08-31 15:14:51', 2, '2017-08-31 15:14:51', NULL, NULL),
(4, 'Nos', 0, 2, '2017-08-31 22:26:26', 2, '2017-11-16 17:02:26', NULL, NULL),
(5, 'MT ', 0, 2, '2017-08-31 22:28:59', 2, '2017-08-31 22:28:59', NULL, NULL),
(6, 'GMR', 0, 2, '2017-09-06 16:43:20', 2, '2017-11-16 17:02:14', NULL, NULL),
(8, 'Gram', 0, 2, '2019-04-22 11:15:56', 2, '2019-04-22 11:16:01', NULL, NULL),
(9, 'Pcs', 0, 48, '2019-12-13 17:51:28', 0, '0000-00-00 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_invoice`
--

CREATE TABLE `purchase_invoice` (
  `purchase_invoice_id` int(11) NOT NULL,
  `purchase_invoice_no` int(11) DEFAULT NULL,
  `bill_no` varchar(255) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL COMMENT 'From Account Table',
  `against_account_id` int(11) DEFAULT NULL,
  `purchase_invoice_date` date DEFAULT NULL,
  `purchase_invoice_desc` text NOT NULL,
  `qty_total` double DEFAULT NULL,
  `pure_amount_total` double DEFAULT NULL,
  `discount_total` double DEFAULT NULL,
  `cgst_amount_total` double DEFAULT NULL,
  `sgst_amount_total` double DEFAULT NULL,
  `igst_amount_total` double DEFAULT NULL,
  `other_charges_total` double DEFAULT NULL,
  `round_off_amount` double DEFAULT NULL,
  `amount_total` double DEFAULT NULL,
  `data_lock_unlock` tinyint(1) NOT NULL DEFAULT '0',
  `invoice_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Order;2=Purchase,3=Order Type 2',
  `transport_name` varchar(255) DEFAULT NULL,
  `lr_no` varchar(255) DEFAULT NULL,
  `shipment_invoice_no` varchar(255) DEFAULT NULL,
  `shipment_invoice_date` date DEFAULT NULL,
  `sbill_no` varchar(255) DEFAULT NULL,
  `sbill_date` date DEFAULT NULL,
  `origin_port` varchar(255) DEFAULT NULL,
  `port_of_discharge` varchar(255) DEFAULT NULL,
  `container_size` varchar(255) DEFAULT NULL,
  `container_bill_no` varchar(255) DEFAULT NULL,
  `container_date` date DEFAULT NULL,
  `container_no` varchar(255) DEFAULT NULL,
  `vessel_name_voy` varchar(255) DEFAULT NULL,
  `your_invoice_no` varchar(255) DEFAULT NULL,
  `print_date` date DEFAULT NULL,
  `print_type` tinyint(4) DEFAULT '0',
  `is_shipping_same_as_billing_address` tinyint(1) DEFAULT NULL,
  `shipping_address` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales_invoice`
--

CREATE TABLE `sales_invoice` (
  `sales_invoice_id` int(11) NOT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `sales_invoice_no` int(11) DEFAULT NULL,
  `bill_no` varchar(255) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL COMMENT 'From Account Table',
  `cash_customer` varchar(255) DEFAULT NULL,
  `against_account_id` int(11) DEFAULT NULL,
  `sales_invoice_date` date DEFAULT NULL,
  `sales_invoice_desc` text NOT NULL,
  `transport_name` varchar(255) DEFAULT NULL,
  `lr_no` varchar(255) DEFAULT NULL,
  `qty_total` double DEFAULT NULL,
  `pure_amount_total` double DEFAULT NULL,
  `discount_total` double DEFAULT NULL,
  `cgst_amount_total` double DEFAULT NULL,
  `sgst_amount_total` double DEFAULT NULL,
  `igst_amount_total` double DEFAULT NULL,
  `other_charges_total` double DEFAULT NULL,
  `round_off_amount` double DEFAULT NULL,
  `amount_total` double DEFAULT NULL,
  `data_lock_unlock` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 0 = Unlock, 1 = Lock',
  `invoice_type` int(11) DEFAULT NULL,
  `tax_type` tinyint(1) DEFAULT NULL COMMENT '1=GST, 2=IGST',
  `shipment_invoice_no` varchar(255) DEFAULT NULL,
  `shipment_invoice_date` date DEFAULT NULL,
  `sbill_no` varchar(255) DEFAULT NULL,
  `sbill_date` date DEFAULT NULL,
  `origin_port` varchar(255) DEFAULT NULL,
  `port_of_discharge` varchar(255) DEFAULT NULL,
  `container_size` varchar(255) DEFAULT NULL,
  `container_bill_no` varchar(255) DEFAULT NULL,
  `container_date` date DEFAULT NULL,
  `container_no` varchar(255) DEFAULT NULL,
  `vessel_name_voy` varchar(255) DEFAULT NULL,
  `your_invoice_no` varchar(255) DEFAULT NULL,
  `print_date` date DEFAULT NULL,
  `print_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=Original;1=Duplicate;2=Triplicate',
  `is_shipping_same_as_billing_address` tinyint(1) DEFAULT NULL COMMENT '0=same;1=different',
  `shipping_address` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `setting_key` varchar(255) DEFAULT NULL,
  `setting_value` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `setting_key`, `setting_value`, `created_at`, `updated_at`, `user_created_by`, `user_updated_by`) VALUES
(1, 'login_logo', 'khatri_logo.jpg', NULL, NULL, NULL, NULL),
(2, 'package_name', 'Saas', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `state_id` int(11) NOT NULL,
  `state_name` varchar(222) DEFAULT NULL,
  `state_code` varchar(15) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '1=deleted,0=not deleted',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`state_id`, `state_name`, `state_code`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`, `user_created_by`, `user_updated_by`) VALUES
(1, 'Andra Pradesh', '28', 0, 1, '2019-04-19 17:48:30', 2, '2019-12-02 10:57:48', NULL, NULL),
(2, 'Arunachal Pradesh', '12', 0, 1, '2019-04-19 17:48:44', 1, '2019-04-19 17:48:44', NULL, NULL),
(3, 'Assam', '18', 0, 1, '2019-04-19 17:49:01', 1, '2019-04-19 17:49:01', NULL, NULL),
(4, 'Bihar', '10', 0, 1, '2019-04-19 17:49:14', 1, '2019-04-19 17:49:14', NULL, NULL),
(5, 'Chhattisgarh', '22', 0, 1, '2019-04-19 17:49:30', 1, '2019-04-19 17:49:30', NULL, NULL),
(6, 'Goa', '30', 0, 1, '2019-04-19 17:49:41', 1, '2019-04-19 17:49:41', NULL, NULL),
(7, 'Gujarat', '24', 0, 1, '2019-04-19 17:49:51', 1, '2019-04-19 17:49:51', NULL, NULL),
(8, 'Haryana', '06', 0, 1, '2019-04-19 17:50:01', 1, '2019-04-19 17:50:01', NULL, NULL),
(9, 'Himachal Pradesh', '02', 0, 1, '2019-04-19 17:50:14', 1, '2019-04-19 17:50:14', NULL, NULL),
(10, 'Jammu and Kashmir', '01', 0, 1, '2019-04-19 17:50:26', 1, '2019-04-19 17:50:26', NULL, NULL),
(11, 'Jharkhand', '20', 0, 1, '2019-04-19 17:50:37', 1, '2019-04-19 17:50:37', NULL, NULL),
(12, 'Karnataka', '29', 0, 1, '2019-04-19 17:51:01', 1, '2019-04-19 17:51:01', NULL, NULL),
(13, 'Kerala', '32', 0, 1, '2019-04-19 17:51:11', 1, '2019-04-19 17:51:11', NULL, NULL),
(14, 'Madya Pradesh', '23', 0, 1, '2019-04-19 17:51:28', 1, '2019-04-19 17:51:28', NULL, NULL),
(15, 'Maharashtra', '27', 0, 1, '2019-04-19 17:51:37', 1, '2019-04-19 17:51:37', NULL, NULL),
(16, 'Manipur', '14', 0, 1, '2019-04-19 17:51:47', 1, '2019-04-19 17:51:47', NULL, NULL),
(17, 'Meghalaya', '17', 0, 1, '2019-04-19 17:51:54', 1, '2019-04-19 17:51:54', NULL, NULL),
(18, 'Mizoram', '15', 0, 1, '2019-04-19 17:52:02', 1, '2019-04-19 17:52:02', NULL, NULL),
(19, 'Nagaland', '13', 0, 1, '2019-04-19 17:52:12', 1, '2019-04-19 17:52:12', NULL, NULL),
(20, 'Orissa', '21', 0, 1, '2019-04-19 17:52:21', 1, '2019-04-19 17:52:21', NULL, NULL),
(21, 'Punjab', '03', 0, 1, '2019-04-19 17:52:30', 1, '2019-04-19 17:52:30', NULL, NULL),
(22, 'Rajasthan', '08', 0, 1, '2019-04-19 17:52:38', 1, '2019-04-19 17:52:38', NULL, NULL),
(23, 'Sikkim', '11', 0, 1, '2019-04-19 17:52:48', 1, '2019-04-19 17:52:48', NULL, NULL),
(24, 'Tamil Nadu', '33', 0, 1, '2019-04-19 17:52:57', 1, '2019-04-19 17:52:57', NULL, NULL),
(25, 'Telagana', '36', 0, 1, '2019-04-19 17:53:07', 1, '2019-04-19 17:53:07', NULL, NULL),
(26, 'Tripura', '16', 0, 1, '2019-04-19 17:53:17', 1, '2019-04-19 17:53:17', NULL, NULL),
(27, 'Uttaranchal', '05', 0, 1, '2019-04-19 17:53:26', 1, '2019-04-19 17:53:26', NULL, NULL),
(28, 'Uttar Pradesh', '09', 0, 1, '2019-04-19 17:53:36', 1, '2019-04-19 17:53:36', NULL, NULL),
(29, 'West Bengal', '19', 0, 1, '2019-04-19 17:53:45', 1, '2019-04-19 17:53:45', NULL, NULL),
(30, 'Chandigarh', '04', 0, 2, '2019-12-02 11:55:50', 2, '2019-12-02 11:55:50', NULL, NULL),
(31, 'Delhi', '07', 0, 2, '2019-12-02 11:56:13', 2, '2019-12-02 11:56:13', NULL, NULL),
(32, 'Daman & Diu', '25', 0, 2, '2019-12-02 11:57:20', 2, '2019-12-02 11:57:20', NULL, NULL),
(33, 'Dadra & Nagar Haveli', '26', 0, 2, '2019-12-02 11:57:28', 2, '2019-12-02 11:57:28', NULL, NULL),
(34, 'Lakshadweep', '31', 0, 2, '2019-12-02 11:57:53', 2, '2019-12-02 11:57:53', NULL, NULL),
(35, 'Puducherry', '34', 0, 2, '2019-12-02 11:58:09', 2, '2019-12-02 11:58:09', NULL, NULL),
(36, 'Andaman & Nicobar Islands', '35', 0, 2, '2019-12-02 11:58:18', 2, '2019-12-02 11:58:18', NULL, NULL),
(37, 'Andrapradesh(New)', '37', 0, 2, '2019-12-02 11:58:31', 2, '2019-12-02 11:58:31', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock_status_change`
--

CREATE TABLE `stock_status_change` (
  `st_change_id` int(11) NOT NULL,
  `st_change_date` date DEFAULT NULL,
  `from_status` tinyint(1) DEFAULT NULL COMMENT '1=In Stock, 2=In WIP, 3=In Work Done',
  `to_status` tinyint(1) DEFAULT NULL COMMENT '1=In Stock, 2=In WIP, 3=In Work Done',
  `item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `tr_type` int(11) DEFAULT NULL COMMENT '1 = Purchase, 2 = Sale',
  `tr_id` int(11) DEFAULT NULL,
  `st_relation_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `sub_cat_id` int(11) NOT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `sub_cat_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_category`
--

INSERT INTO `sub_category` (`sub_cat_id`, `cat_id`, `sub_cat_name`, `created_by`, `created_at`, `updated_by`, `updated_at`, `user_created_by`, `user_updated_by`) VALUES
(1, 1, 'Sub 1', 39, '2019-08-29 14:41:59', 39, '2019-08-29 14:41:59', NULL, NULL),
(2, 1, 'Sub 2', 39, '2019-08-29 14:49:57', 39, '2019-08-29 14:49:57', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_item_add_less_settings`
--

CREATE TABLE `sub_item_add_less_settings` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_level` int(11) NOT NULL DEFAULT '1',
  `item_qty` int(11) DEFAULT NULL,
  `item_unit_id` int(11) DEFAULT NULL,
  `sub_item_id` int(11) DEFAULT NULL,
  `sub_item_add_less` int(11) DEFAULT NULL,
  `sub_item_qty` int(11) DEFAULT NULL,
  `sub_item_unit_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sub_item_add_less_settings_sales_invoice_wise`
--

CREATE TABLE `sub_item_add_less_settings_sales_invoice_wise` (
  `id` int(11) NOT NULL,
  `sales_invoice_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_level` int(11) NOT NULL DEFAULT '1',
  `item_qty` int(11) DEFAULT NULL,
  `item_unit_id` int(11) DEFAULT NULL,
  `sub_item_id` int(11) DEFAULT NULL,
  `sub_item_add_less` int(11) DEFAULT NULL,
  `sub_item_qty` int(11) DEFAULT NULL,
  `sub_item_unit_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_entry`
--

CREATE TABLE `transaction_entry` (
  `transaction_id` int(11) NOT NULL,
  `transaction_date` date DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `from_account_id` int(11) DEFAULT NULL,
  `to_account_id` int(11) DEFAULT NULL,
  `transaction_type` tinyint(2) DEFAULT NULL COMMENT '1 = Payment , 2 = Recepit , 3 = Contra, 4 = havala',
  `receipt_no` varchar(255) DEFAULT NULL COMMENT 'Cheque / Cash Receipt No',
  `amount` double DEFAULT NULL,
  `contra_no` int(11) DEFAULT NULL,
  `note` text,
  `voucher_remote_id` varchar(255) DEFAULT NULL,
  `journal_id` int(11) DEFAULT NULL,
  `is_credit_debit` tinyint(1) DEFAULT NULL COMMENT '1-Credit, 2-Debit',
  `relation_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(222) DEFAULT NULL,
  `address` text,
  `gst_no` varchar(222) DEFAULT NULL,
  `pan` varchar(22) DEFAULT NULL,
  `aadhaar` varchar(22) DEFAULT NULL,
  `phone` varchar(222) DEFAULT NULL,
  `email_ids` varchar(222) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `postal_code` int(22) DEFAULT NULL,
  `contect_person_name` varchar(222) DEFAULT NULL,
  `company_symbol` varchar(222) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_acc_name` varchar(255) DEFAULT NULL,
  `bank_branch` varchar(255) DEFAULT NULL,
  `bank_city` varchar(255) DEFAULT NULL,
  `bank_ac_no` varchar(255) DEFAULT NULL,
  `rtgs_ifsc_code` varchar(255) DEFAULT NULL,
  `isActive` int(11) DEFAULT NULL COMMENT '0 = not active, 1 = active',
  `verify_otp` tinyint(1) DEFAULT '1' COMMENT '0 = Verify, 1 = Not_verify',
  `otp_code` int(11) DEFAULT NULL,
  `userType` varchar(22) NOT NULL DEFAULT 'Company',
  `user` varchar(222) DEFAULT NULL,
  `password` varchar(222) DEFAULT NULL,
  `logo_image` varchar(255) NOT NULL,
  `invoice_no_start_from` int(11) DEFAULT '1',
  `is_letter_pad` int(11) NOT NULL DEFAULT '0',
  `prefix` varchar(255) DEFAULT NULL,
  `is_bill_wise` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = Not Billwise, 1 = Billwise',
  `sales_invoice_notes` text,
  `purchase_rate` tinyint(1) DEFAULT NULL COMMENT '1=Including Tax; 2= Excluding Tax',
  `sales_rate` tinyint(1) DEFAULT NULL COMMENT '1=Including Tax; 2= Excluding Tax',
  `discount_on` tinyint(1) DEFAULT NULL COMMENT '1 = List Price, 2 = MRP',
  `invoice_type` int(11) DEFAULT NULL,
  `is_single_line_item` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-Yes, 0-No',
  `is_view_item_history` tinyint(1) DEFAULT '0' COMMENT '1=Yes, 0=No',
  `last_visited_page` text,
  `company_id` int(11) DEFAULT NULL COMMENT 'Parent User ID',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `address`, `gst_no`, `pan`, `aadhaar`, `phone`, `email_ids`, `state`, `city`, `postal_code`, `contect_person_name`, `company_symbol`, `bank_name`, `bank_acc_name`, `bank_branch`, `bank_city`, `bank_ac_no`, `rtgs_ifsc_code`, `isActive`, `verify_otp`, `otp_code`, `userType`, `user`, `password`, `logo_image`, `invoice_no_start_from`, `is_letter_pad`, `prefix`, `is_bill_wise`, `sales_invoice_notes`, `purchase_rate`, `sales_rate`, `discount_on`, `invoice_type`, `is_single_line_item`, `is_view_item_history`, `last_visited_page`, `company_id`, `created_by`, `created_at`, `updated_by`, `updated_at`, `user_created_by`, `user_updated_by`) VALUES
(2, 'Admin', 'Mahuva Road, Sanghi Arcade, \r\nSavarkundla', '24ABCDK4250B1Z5', '', '', '345345345', 'admin@gmail.com', 7, NULL, 45645, 'ontact Person Name', NULL, '', '', '', '', '', '', 1, 1, 9189, 'Admin', 'admin@gmail.com', '3001ea82a04cbc6098edc6e1f447b5a5', '', 1, 1, NULL, 0, '', 1, 1, 2, NULL, 0, 1, '', NULL, NULL, NULL, NULL, '2021-08-19 17:01:16', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_login_log`
--

CREATE TABLE `user_login_log` (
  `id` int(11) NOT NULL,
  `login_logout` tinyint(1) DEFAULT NULL COMMENT '1 = Log_in, 2 = Log_out',
  `user_id` int(11) DEFAULT NULL,
  `ip_add` varchar(255) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_role_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `website_module_id` int(11) DEFAULT NULL,
  `role_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_role_id`, `user_id`, `website_module_id`, `role_type_id`) VALUES
(5113, 2, 1, 1),
(5114, 2, 1, 2),
(5115, 2, 1, 3),
(5116, 2, 1, 4),
(5117, 2, 45, 123),
(5118, 2, 45, 124),
(5119, 2, 45, 125),
(5120, 2, 45, 126),
(5121, 2, 2, 5),
(5122, 2, 2, 6),
(5123, 2, 2, 7),
(5124, 2, 2, 8),
(5125, 2, 3, 9),
(5126, 2, 4, 10),
(5127, 2, 4, 11),
(5128, 2, 4, 12),
(5129, 2, 4, 13),
(5130, 2, 48, 132),
(5131, 2, 48, 133),
(5132, 2, 48, 134),
(5133, 2, 48, 135),
(5134, 2, 5, 14),
(5135, 2, 5, 15),
(5136, 2, 5, 16),
(5137, 2, 5, 17),
(5138, 2, 6, 18),
(5139, 2, 6, 19),
(5140, 2, 6, 20),
(5141, 2, 6, 21),
(5142, 2, 7, 22),
(5143, 2, 7, 23),
(5144, 2, 7, 24),
(5145, 2, 7, 25),
(5146, 2, 8, 26),
(5147, 2, 8, 27),
(5148, 2, 8, 28),
(5149, 2, 8, 29),
(5150, 2, 9, 30),
(5151, 2, 9, 31),
(5152, 2, 9, 32),
(5153, 2, 9, 33),
(5154, 2, 10, 34),
(5155, 2, 10, 35),
(5156, 2, 10, 36),
(5157, 2, 10, 37),
(5158, 2, 11, 38),
(5159, 2, 11, 39),
(5160, 2, 11, 40),
(5161, 2, 11, 41),
(5162, 2, 12, 42),
(5163, 2, 12, 43),
(5164, 2, 12, 44),
(5165, 2, 12, 45),
(5166, 2, 13, 46),
(5167, 2, 13, 47),
(5168, 2, 13, 48),
(5169, 2, 13, 49),
(5170, 2, 14, 50),
(5171, 2, 14, 51),
(5172, 2, 14, 52),
(5173, 2, 14, 53),
(5174, 2, 47, 128),
(5175, 2, 47, 129),
(5176, 2, 47, 130),
(5177, 2, 47, 131),
(5178, 2, 42, 115),
(5179, 2, 15, 54),
(5180, 2, 15, 55),
(5181, 2, 15, 56),
(5182, 2, 15, 57),
(5183, 2, 16, 58),
(5184, 2, 17, 59),
(5185, 2, 17, 60),
(5186, 2, 17, 61),
(5187, 2, 17, 62),
(5188, 2, 49, 136),
(5189, 2, 49, 137),
(5190, 2, 49, 138),
(5191, 2, 49, 139),
(5192, 2, 18, 63),
(5193, 2, 18, 64),
(5194, 2, 18, 65),
(5195, 2, 18, 66),
(5196, 2, 19, 67),
(5197, 2, 20, 68),
(5198, 2, 20, 69),
(5199, 2, 20, 70),
(5200, 2, 20, 71),
(5201, 2, 20, 119),
(5202, 2, 20, 120),
(5203, 2, 21, 72),
(5204, 2, 21, 73),
(5205, 2, 21, 74),
(5206, 2, 21, 75),
(5207, 2, 22, 76),
(5208, 2, 22, 77),
(5209, 2, 22, 78),
(5210, 2, 22, 79),
(5211, 2, 23, 80),
(5212, 2, 23, 81),
(5213, 2, 23, 82),
(5214, 2, 23, 83),
(5215, 2, 24, 84),
(5216, 2, 24, 85),
(5217, 2, 24, 86),
(5218, 2, 24, 87),
(5219, 2, 25, 88),
(5220, 2, 25, 89),
(5221, 2, 25, 90),
(5222, 2, 25, 91),
(5223, 2, 26, 92),
(5224, 2, 26, 93),
(5225, 2, 26, 94),
(5226, 2, 26, 95),
(5227, 2, 27, 96),
(5228, 2, 27, 97),
(5229, 2, 27, 98),
(5230, 2, 27, 99),
(5231, 2, 27, 100),
(5232, 2, 28, 101),
(5233, 2, 29, 102),
(5234, 2, 30, 103),
(5235, 2, 31, 104),
(5236, 2, 46, 127),
(5237, 2, 32, 105),
(5238, 2, 33, 106),
(5239, 2, 34, 107),
(5240, 2, 35, 108),
(5241, 2, 36, 109),
(5242, 2, 43, 116),
(5243, 2, 37, 110),
(5244, 2, 44, 117),
(5245, 2, 44, 118),
(5246, 2, 38, 111),
(5247, 2, 39, 112),
(5248, 2, 40, 113),
(5249, 2, 41, 114);

-- --------------------------------------------------------

--
-- Table structure for table `website_modules`
--

CREATE TABLE `website_modules` (
  `website_module_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `main_module` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `website_modules`
--

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES
(1, 'Company', '1'),
(2, 'Account', '2'),
(3, 'Master', '3'),
(4, 'Master >> Item Group', '3.1'),
(5, 'Master >> Item Type', '3.2'),
(6, 'Master >> Invoice Type', '3.3'),
(7, 'Master >> State', '3.4'),
(8, 'Master >> City', '3.5'),
(9, 'Master >> Account Group', '3.6'),
(10, 'Master >> Unit', '3.7'),
(11, 'Master >> Import', '3.8'),
(12, 'Master >> HSN', '3.9'),
(13, 'Master >> Category', '3.10'),
(14, 'Master >> Sub Category', '3.11'),
(15, 'Item', '4'),
(16, 'Order / Purchase', '5'),
(17, 'Order', '5.1'),
(18, 'Purchase Invoice', '5.2'),
(19, 'Sales', '6'),
(20, 'Sales >> Invoice', '6.1'),
(21, 'Sales >> Discount', '6.2'),
(22, 'Credit Note', '7'),
(23, 'Debit Note', '8'),
(24, 'Payment', '9'),
(25, 'Receipt', '10'),
(26, 'Contra', '11'),
(27, 'Journal', '12'),
(28, 'Report', '13'),
(29, 'Report >> Stock Register', '13.1'),
(30, 'Report >> Purchase Register', '13.2'),
(31, 'Report >> Sales Register', '13.3'),
(32, 'Report >> Credit Note Register', '13.4'),
(33, 'Report >> Debit Note Register', '13.5'),
(34, 'Report >> Ledger', '13.6'),
(35, 'Report >> Outstanding', '13.7'),
(36, 'Report >> Balance Sheet', '13.8'),
(37, 'Report >> User Log', '13.9'),
(38, 'GSTR1 Excel Export', '14'),
(39, 'GSTR2 Excel Export', '15'),
(40, 'GSTR-3B Excel Export', '16'),
(41, 'Backup DB', '17'),
(42, 'Master >> User Rights', '3.12'),
(43, 'Report >> Profit Loss', '13.8'),
(44, 'Report >> Trial Balance', '13.9'),
(45, 'Company >> Document', '1.2'),
(46, 'Report >> Sales Bill Register', '13.3.1'),
(47, 'Master >> Stock Status Change', '3.11.1'),
(48, 'Master >> User', '3.01'),
(49, 'Order Type 2', '5.1.1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `account_group_id` (`account_group_id`),
  ADD KEY `account_group_id_2` (`account_group_id`),
  ADD KEY `account_state` (`account_state`),
  ADD KEY `account_city` (`account_city`),
  ADD KEY `account_city_2` (`account_city`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `account_group`
--
ALTER TABLE `account_group`
  ADD PRIMARY KEY (`account_group_id`);

--
-- Indexes for table `bank_master`
--
ALTER TABLE `bank_master`
  ADD PRIMARY KEY (`bank_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`),
  ADD KEY `state_id` (`state_id`);

--
-- Indexes for table `company_invoice_prefix`
--
ALTER TABLE `company_invoice_prefix`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`company_settings_id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `credit_note`
--
ALTER TABLE `credit_note`
  ADD PRIMARY KEY (`credit_note_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `against_account_id` (`against_account_id`),
  ADD KEY `account_id_2` (`account_id`),
  ADD KEY `against_account_id_2` (`against_account_id`),
  ADD KEY `account_id_3` (`account_id`),
  ADD KEY `against_account_id_3` (`against_account_id`),
  ADD KEY `account_id_4` (`account_id`),
  ADD KEY `against_account_id_4` (`against_account_id`);

--
-- Indexes for table `debit_note`
--
ALTER TABLE `debit_note`
  ADD PRIMARY KEY (`debit_note_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `against_account_id` (`against_account_id`),
  ADD KEY `account_id_2` (`account_id`),
  ADD KEY `against_account_id_2` (`against_account_id`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`discount_id`);

--
-- Indexes for table `discount_detail`
--
ALTER TABLE `discount_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `hsn`
--
ALTER TABLE `hsn`
  ADD PRIMARY KEY (`hsn_id`);

--
-- Indexes for table `invoice_paid_details`
--
ALTER TABLE `invoice_paid_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `invoice_type`
--
ALTER TABLE `invoice_type`
  ADD PRIMARY KEY (`invoice_type_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `item_group_id` (`item_group_id`),
  ADD KEY `item_type_id` (`item_type_id`);

--
-- Indexes for table `item_current_stock_detail`
--
ALTER TABLE `item_current_stock_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`,`invoice_id`);

--
-- Indexes for table `item_group`
--
ALTER TABLE `item_group`
  ADD PRIMARY KEY (`item_group_id`);

--
-- Indexes for table `item_type`
--
ALTER TABLE `item_type`
  ADD PRIMARY KEY (`item_type_id`);

--
-- Indexes for table `journal`
--
ALTER TABLE `journal`
  ADD PRIMARY KEY (`journal_id`);

--
-- Indexes for table `lineitems`
--
ALTER TABLE `lineitems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `locked_daterange`
--
ALTER TABLE `locked_daterange`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_roles`
--
ALTER TABLE `module_roles`
  ADD PRIMARY KEY (`module_role_id`);

--
-- Indexes for table `pack_unit`
--
ALTER TABLE `pack_unit`
  ADD PRIMARY KEY (`pack_unit_id`);

--
-- Indexes for table `purchase_invoice`
--
ALTER TABLE `purchase_invoice`
  ADD PRIMARY KEY (`purchase_invoice_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `against_account_id` (`against_account_id`);

--
-- Indexes for table `sales_invoice`
--
ALTER TABLE `sales_invoice`
  ADD PRIMARY KEY (`sales_invoice_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `against_account_id` (`against_account_id`),
  ADD KEY `account_id_2` (`account_id`),
  ADD KEY `against_account_id_2` (`against_account_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`state_id`);

--
-- Indexes for table `stock_status_change`
--
ALTER TABLE `stock_status_change`
  ADD PRIMARY KEY (`st_change_id`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`sub_cat_id`);

--
-- Indexes for table `sub_item_add_less_settings`
--
ALTER TABLE `sub_item_add_less_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_item_add_less_settings_sales_invoice_wise`
--
ALTER TABLE `sub_item_add_less_settings_sales_invoice_wise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_entry`
--
ALTER TABLE `transaction_entry`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `account_id_2` (`account_id`),
  ADD KEY `from_account_id` (`from_account_id`),
  ADD KEY `from_account_id_2` (`from_account_id`),
  ADD KEY `to_account_id` (`to_account_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_login_log`
--
ALTER TABLE `user_login_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_role_id`);

--
-- Indexes for table `website_modules`
--
ALTER TABLE `website_modules`
  ADD PRIMARY KEY (`website_module_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_group`
--
ALTER TABLE `account_group`
  MODIFY `account_group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `bank_master`
--
ALTER TABLE `bank_master`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `company_invoice_prefix`
--
ALTER TABLE `company_invoice_prefix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `company_settings_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `credit_note`
--
ALTER TABLE `credit_note`
  MODIFY `credit_note_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `debit_note`
--
ALTER TABLE `debit_note`
  MODIFY `debit_note_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_detail`
--
ALTER TABLE `discount_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hsn`
--
ALTER TABLE `hsn`
  MODIFY `hsn_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_paid_details`
--
ALTER TABLE `invoice_paid_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_type`
--
ALTER TABLE `invoice_type`
  MODIFY `invoice_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_current_stock_detail`
--
ALTER TABLE `item_current_stock_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_group`
--
ALTER TABLE `item_group`
  MODIFY `item_group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `item_type`
--
ALTER TABLE `item_type`
  MODIFY `item_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `journal`
--
ALTER TABLE `journal`
  MODIFY `journal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lineitems`
--
ALTER TABLE `lineitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locked_daterange`
--
ALTER TABLE `locked_daterange`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `module_roles`
--
ALTER TABLE `module_roles`
  MODIFY `module_role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `pack_unit`
--
ALTER TABLE `pack_unit`
  MODIFY `pack_unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `purchase_invoice`
--
ALTER TABLE `purchase_invoice`
  MODIFY `purchase_invoice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_invoice`
--
ALTER TABLE `sales_invoice`
  MODIFY `sales_invoice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `stock_status_change`
--
ALTER TABLE `stock_status_change`
  MODIFY `st_change_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `sub_cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sub_item_add_less_settings`
--
ALTER TABLE `sub_item_add_less_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_item_add_less_settings_sales_invoice_wise`
--
ALTER TABLE `sub_item_add_less_settings_sales_invoice_wise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_entry`
--
ALTER TABLE `transaction_entry`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `user_login_log`
--
ALTER TABLE `user_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `user_role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16749;

--
-- AUTO_INCREMENT for table `website_modules`
--
ALTER TABLE `website_modules`
  MODIFY `website_module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `Fk_AccountGroup` FOREIGN KEY (`account_group_id`) REFERENCES `account_group` (`account_group_id`),
  ADD CONSTRAINT `Fk_CityAccount` FOREIGN KEY (`account_city`) REFERENCES `city` (`city_id`),
  ADD CONSTRAINT `Fk_StateAccount` FOREIGN KEY (`account_state`) REFERENCES `state` (`state_id`),
  ADD CONSTRAINT `Fk_UserIdAccount` FOREIGN KEY (`created_by`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `Fk_StateCity` FOREIGN KEY (`state_id`) REFERENCES `state` (`state_id`);

--
-- Constraints for table `credit_note`
--
ALTER TABLE `credit_note`
  ADD CONSTRAINT `Fk_CreditNoteAccount` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_CreditNoteAgainstAccount` FOREIGN KEY (`against_account_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `debit_note`
--
ALTER TABLE `debit_note`
  ADD CONSTRAINT `Fk_DebitNoteAccount` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_DebitNoteAgainstAccount` FOREIGN KEY (`against_account_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `Fk_CompanyIdDocument` FOREIGN KEY (`company_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `invoice_paid_details`
--
ALTER TABLE `invoice_paid_details`
  ADD CONSTRAINT `Fk_InvPaidDetailAccount` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `Fk_ItemGroupItem` FOREIGN KEY (`item_group_id`) REFERENCES `item_group` (`item_group_id`),
  ADD CONSTRAINT `Fk_ItemTypeItem` FOREIGN KEY (`item_type_id`) REFERENCES `item_type` (`item_type_id`);

--
-- Constraints for table `lineitems`
--
ALTER TABLE `lineitems`
  ADD CONSTRAINT `Fk_ItemLineItem` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`);

--
-- Constraints for table `purchase_invoice`
--
ALTER TABLE `purchase_invoice`
  ADD CONSTRAINT `Fk_PurchaseInvoiceAccount` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_PurchaseInvoiceAgainstAccount` FOREIGN KEY (`against_account_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `sales_invoice`
--
ALTER TABLE `sales_invoice`
  ADD CONSTRAINT `Fk_SalesInvoiceAccount` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `transaction_entry`
--
ALTER TABLE `transaction_entry`
  ADD CONSTRAINT `Fk_TransAccount` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_TransFromAccount` FOREIGN KEY (`from_account_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_TransToAccount` FOREIGN KEY (`to_account_id`) REFERENCES `account` (`account_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
