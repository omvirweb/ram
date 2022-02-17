-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 03, 2019 at 03:19 PM
-- Server version: 5.7.25-0ubuntu0.16.04.2
-- PHP Version: 7.0.33-0ubuntu0.16.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `saas`
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
  `credit_debit` tinyint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
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
  `is_deletable` int(11) NOT NULL DEFAULT '1' COMMENT '1 = Deletable, 0 = Not Deletable',
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '1=deleted,0=not deleted',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_group`
--

INSERT INTO `account_group` (`account_group_id`, `parent_group_id`, `account_group_name`, `sequence`, `is_deletable`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 10, 'Expenses (Direct)', 2, 0, 0, 1, '2017-08-04 07:40:24', 1, '2017-08-04 07:44:08'),
(2, 10, 'Trading Account', 0, 0, 0, 1, '2017-08-04 07:40:48', 1, '2017-08-04 07:46:54'),
(3, 2, 'General Trading Account', 99, 0, 0, 1, '2017-08-04 07:41:06', 1, '2017-08-04 07:41:06'),
(4, 10, 'Income (Trading)', 2, 0, 0, 1, '2017-08-04 07:41:33', 1, '2017-08-04 07:46:28'),
(5, 10, 'Jobwork Expense', 3, 0, 0, 1, '2017-08-04 07:41:50', 1, '2017-08-04 07:45:38'),
(6, 10, 'Jobwork Income (Trading)', 3, 0, 0, 1, '2017-08-04 07:42:06', 1, '2017-08-04 07:45:45'),
(7, 10, 'Purchase Account', 1, 0, 0, 1, '2017-08-04 07:42:18', 1, '2017-08-04 07:45:52'),
(8, 10, 'Sales Account', 1, 0, 0, 1, '2017-08-04 07:42:27', 1, '2017-08-04 07:45:58'),
(9, 11, 'Expense Account', 1, 0, 0, 1, '2017-08-04 07:42:43', 1, '2017-08-04 07:45:28'),
(10, 0, 'Trading', 0, 0, 0, 1, '2017-08-04 07:43:54', 1, '2017-08-04 07:43:54'),
(11, 0, 'Profit & Loss', 0, 0, 0, 1, '2017-08-04 07:45:17', 1, '2017-08-04 07:45:17'),
(12, 0, 'Balance Sheet', 0, 0, 0, 1, '2017-08-04 07:47:13', 1, '2017-08-04 07:47:13'),
(13, 9, 'Financial Expense', 3, 0, 0, 1, '2017-08-04 07:47:42', 1, '2017-08-04 09:16:27'),
(14, 11, 'Income', 22, 0, 0, 1, '2017-08-04 07:48:00', 1, '2017-08-19 17:00:49'),
(15, 11, 'Income (Other Then Sales)', 3, 0, 0, 1, '2017-08-04 07:48:51', 1, '2017-08-04 07:48:51'),
(16, 11, 'Partner Interest', 4, 0, 0, 1, '2017-08-04 09:17:14', 1, '2017-08-04 09:17:14'),
(17, 11, 'Partner Remuneration', 4, 0, 0, 1, '2017-08-04 09:17:31', 1, '2017-08-04 09:17:31'),
(18, 11, 'Revenue Accounts', 1, 0, 0, 1, '2017-08-04 09:17:59', 1, '2017-08-04 09:17:59'),
(19, 9, 'Salary Expense', 2, 0, 0, 1, '2017-08-04 09:18:27', 1, '2017-08-04 09:18:27'),
(20, 12, 'Current Assets', 1, 0, 0, 1, '2017-08-04 09:18:47', 1, '2017-08-04 09:18:47'),
(21, 20, 'Bank Accounts (Banks)', 8, 0, 0, 1, '2017-08-04 09:19:11', 1, '2017-08-04 09:19:11'),
(22, 12, 'Loans (Liability)', 9, 0, 0, 1, '2017-08-04 09:19:45', 1, '2017-08-04 09:19:45'),
(23, 22, 'Bank OCC a/c', 4, 0, 0, 1, '2017-08-04 09:20:08', 1, '2017-08-04 09:20:08'),
(24, 12, 'Capital Account', 1, 0, 0, 1, '2017-08-04 09:20:23', 1, '2017-08-04 09:20:23'),
(25, 12, 'Cash Ledger A/C.', 98, 0, 0, 1, '2017-08-04 09:20:40', 1, '2017-08-04 09:20:40'),
(26, 20, 'Cash-in-hand', 9, 0, 0, 1, '2017-08-04 09:21:01', 1, '2017-08-04 09:21:01'),
(27, 12, 'Current Liabilities', 5, 0, 0, 1, '2017-08-04 09:21:18', 1, '2017-08-04 09:21:18'),
(28, 20, 'Deposits (Asset)', 4, 0, 0, 1, '2017-08-04 09:21:41', 1, '2017-08-04 09:21:41'),
(29, 27, 'Duties & Taxes', 6, 0, 0, 1, '2017-08-04 09:22:07', 1, '2017-08-04 09:22:07'),
(30, 12, 'Fixed Assets', 2, 0, 0, 1, '2017-08-04 09:22:21', 1, '2017-08-04 09:22:21'),
(31, 12, 'Investments', 3, 0, 0, 1, '2017-08-04 09:22:42', 1, '2017-08-04 09:22:42'),
(32, 20, 'Loans & Advances (Asset)', 5, 0, 0, 1, '2017-08-04 09:23:01', 1, '2017-08-04 09:23:01'),
(33, 12, 'Misc. Expenses (Asset)', 6, 0, 0, 1, '2017-08-04 09:23:52', 1, '2017-08-04 09:23:52'),
(34, 12, 'Profit & Loss A/c', 99, 0, 0, 1, '2017-08-04 09:24:33', 1, '2017-08-04 09:24:33'),
(35, 27, 'Provisions', 7, 0, 0, 1, '2017-08-04 09:24:47', 1, '2017-08-04 09:24:47'),
(36, 24, 'Reserves & Surplus', 2, 0, 0, 1, '2017-08-04 09:25:05', 1, '2017-08-04 09:25:05'),
(37, 22, 'Secured Loans', 10, 0, 0, 1, '2017-08-04 09:25:59', 1, '2017-08-04 09:25:59'),
(38, 12, 'Stock-in-hand', 10, 0, 0, 1, '2017-08-04 09:26:11', 1, '2017-08-04 09:26:11'),
(39, 27, 'Sundry Creditors', 11, 0, 0, 1, '2017-08-04 09:26:32', 1, '2017-08-04 09:26:32'),
(40, 27, 'Sundry Creditors (Others)', 12, 0, 0, 1, '2017-08-04 09:26:52', 1, '2017-08-04 09:26:52'),
(41, 27, 'Sundry Creditors (Salary)', 13, 0, 0, 1, '2017-08-04 09:27:26', 1, '2017-08-04 09:27:26'),
(42, 20, 'Sundry Debtors', 7, 0, 0, 1, '2017-08-04 09:27:44', 1, '2017-08-04 09:27:44'),
(43, 20, 'Sundry Debtors(Eicher Dealer)', 7, 0, 0, 1, '2017-08-04 09:28:00', 1, '2017-08-04 09:28:00'),
(44, 20, 'Sundry Debtors(Mechanic Club)', 0, 0, 0, 1, '2017-08-04 09:28:23', 1, '2017-08-04 09:28:23'),
(45, 12, 'Suspense Account', 8, 0, 0, 1, '2017-08-04 09:28:34', 1, '2017-08-04 09:28:34'),
(46, 22, 'Unsecured Loans', 3, 0, 0, 1, '2017-08-04 09:28:47', 1, '2017-08-04 09:28:47'),
(47, 27, 'Staff', 99, 0, 0, 1, '2017-08-04 09:45:18', 1, '2017-08-04 09:45:18'),
(48, 27, 'Supplier', 99, 0, 0, 1, '2017-08-04 09:45:26', 1, '2017-08-04 09:45:26'),
(49, 20, 'Customer', 99, 0, 0, 1, '2017-08-04 09:45:47', 1, '2017-08-04 09:45:47');

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
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`city_id`, `state_id`, `city_name`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 1, 'Rajkot', 0, 1, '2017-07-18 09:41:17', 1, '2017-07-18 09:41:17'),
(2, 2, 'Nagpur', 0, 1, '2017-07-18 09:41:29', 1, '2017-07-18 09:41:53'),
(4, 1, 'Ahamd', 0, 1, '2017-07-18 09:42:07', 1, '2017-07-18 09:42:07'),
(5, 1, 'baroda', 1, 1, '2017-07-18 09:42:14', 1, '2017-07-18 09:42:14'),
(6, 2, 'pune', 0, 1, '2017-07-18 09:42:19', 1, '2017-07-18 09:42:26'),
(7, 1, 'Jamnagar', 0, 1, '2017-07-18 09:42:35', 1, '2017-08-19 17:04:10'),
(8, 1, 'baroda', 0, 1, '2017-08-10 12:28:13', 1, '2017-08-10 12:28:13'),
(10, 1, 'Gondal', 0, 1, '2017-08-19 17:04:00', 1, '2017-08-19 17:04:00'),
(11, 8, 'Satrling', 0, 2, '2017-08-23 10:43:25', 2, '2017-08-23 10:43:25');

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
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`country_id`, `country_name`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'india', 0, 1, '2017-09-27 00:00:00', 1, NULL);

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
  `credit_note_date` date DEFAULT NULL,
  `credit_note_desc` text NOT NULL,
  `qty_total` double DEFAULT NULL,
  `pure_amount_total` double DEFAULT NULL,
  `discount_total` double DEFAULT NULL,
  `cgst_amount_total` double DEFAULT NULL,
  `sgst_amount_total` double DEFAULT NULL,
  `igst_amount_total` double DEFAULT NULL,
  `amount_total` double DEFAULT NULL,
  `data_lock_unlock` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 0 = Unlock, 1 = Lock',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
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
  `debit_note_date` date DEFAULT NULL,
  `debit_note_desc` text NOT NULL,
  `qty_total` double DEFAULT NULL,
  `pure_amount_total` double DEFAULT NULL,
  `discount_total` double DEFAULT NULL,
  `cgst_amount_total` double DEFAULT NULL,
  `sgst_amount_total` double DEFAULT NULL,
  `igst_amount_total` double DEFAULT NULL,
  `amount_total` double DEFAULT NULL,
  `data_lock_unlock` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 0 = Unlock, 1 = Lock',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `hsn_code` double DEFAULT NULL,
  `cgst_per` double DEFAULT NULL,
  `sgst_per` double DEFAULT NULL,
  `igst_per` double DEFAULT NULL,
  `cess` varchar(222) DEFAULT NULL,
  `alternate_unit_id` int(11) DEFAULT NULL,
  `pack_unit_id` int(11) DEFAULT NULL,
  `item_type_id` int(11) DEFAULT NULL,
  `item_desc` text,
  `purchase_rate` int(11) DEFAULT NULL COMMENT '1 = Including Tax, 2 = Excluding Tax.',
  `sales_rate` int(11) DEFAULT NULL COMMENT '1 = Including Tax, 2 = Excluding Tax.',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_type`
--

INSERT INTO `item_type` (`item_type_id`, `item_type_name`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Goods', 1, '2017-08-22 13:10:24', 1, '2017-08-22 13:10:24'),
(2, 'Services', 1, '2017-08-22 13:10:49', 2, '2017-08-23 13:12:33');

-- --------------------------------------------------------

--
-- Table structure for table `lineitems`
--

CREATE TABLE `lineitems` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_qty` double DEFAULT NULL,
  `pure_amount` double DEFAULT NULL,
  `item_qty2` double DEFAULT NULL COMMENT 'item_qty * item standerd_pack',
  `unit_id` int(11) DEFAULT NULL,
  `price_type` int(11) DEFAULT NULL COMMENT 'Purchase Rate = 1, Sell Rate = 2, List Price = 3, DLP = 4, MRP = 5',
  `price` double DEFAULT NULL,
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
  `updated_at` datetime DEFAULT NULL
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
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_invoice`
--

CREATE TABLE `purchase_invoice` (
  `purchase_invoice_id` int(11) NOT NULL,
  `purchase_invoice_no` int(11) DEFAULT NULL,
  `bill_no` varchar(255) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL COMMENT 'From Account Table',
  `purchase_invoice_date` date DEFAULT NULL,
  `purchase_invoice_desc` text NOT NULL,
  `qty_total` double DEFAULT NULL,
  `pure_amount_total` double DEFAULT NULL,
  `discount_total` double DEFAULT NULL,
  `cgst_amount_total` double DEFAULT NULL,
  `sgst_amount_total` double DEFAULT NULL,
  `igst_amount_total` double DEFAULT NULL,
  `amount_total` double DEFAULT NULL,
  `data_lock_unlock` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales_invoice`
--

CREATE TABLE `sales_invoice` (
  `sales_invoice_id` int(11) NOT NULL,
  `sales_invoice_no` int(11) DEFAULT NULL,
  `bill_no` varchar(255) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL COMMENT 'From Account Table',
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
  `amount_total` double DEFAULT NULL,
  `data_lock_unlock` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 0 = Unlock, 1 = Lock',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
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
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'login_logo', 'om.jpg', NULL, NULL),
(2, 'package_name', 'SaaS', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `state_id` int(11) NOT NULL,
  `state_name` varchar(222) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '1=deleted,0=not deleted',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`state_id`, `state_name`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Gujrat', 0, 1, '2017-07-18 09:12:20', 1, '2017-07-18 09:43:01'),
(2, 'Maharastra', 0, 1, '2017-07-18 09:13:34', 1, '2017-07-18 09:13:41'),
(4, 'Rajasthan', 0, 1, '2017-07-18 09:43:10', 1, '2017-07-18 09:43:10'),
(8, 'London', 0, 2, '2017-08-23 10:41:11', 2, '2017-08-23 10:41:11');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_entry`
--

CREATE TABLE `transaction_entry` (
  `transaction_id` int(11) NOT NULL,
  `transaction_date` date DEFAULT NULL,
  `from_account_id` int(11) DEFAULT NULL,
  `to_account_id` int(11) DEFAULT NULL,
  `transaction_type` tinyint(2) DEFAULT NULL COMMENT '1 = Payment , 2 = Recepit , 3 = Contra',
  `amount` double DEFAULT NULL,
  `contra_no` int(11) DEFAULT NULL,
  `note` text,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
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
  `verify_otp` tinyint(1) DEFAULT '0' COMMENT '0 = Verify, 1 = Not_verify',
  `otp_code` int(11) DEFAULT NULL,
  `userType` varchar(22) NOT NULL DEFAULT 'Company',
  `user` varchar(222) DEFAULT NULL,
  `password` varchar(222) DEFAULT NULL,
  `logo_image` varchar(255) NOT NULL,
  `invoice_no_start_from` int(11) DEFAULT '1',
  `is_letter_pad` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `address`, `gst_no`, `pan`, `aadhaar`, `phone`, `email_ids`, `state`, `city`, `postal_code`, `contect_person_name`, `company_symbol`, `bank_name`, `bank_acc_name`, `bank_branch`, `bank_city`, `bank_ac_no`, `rtgs_ifsc_code`, `isActive`, `verify_otp`, `otp_code`, `userType`, `user`, `password`, `logo_image`, `invoice_no_start_from`, `is_letter_pad`) VALUES
(1, 'admin@gmail.com', 'admin', '435', '333', '222', '345345345', 'admin@gmail.com', 1, 1, 45645, 'Contact Person Name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 'Admin', 'admin@gmail.com', '75d23af433e0cea4c0e45a56dba18b30', '', 1, 0);

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `account_group`
--
ALTER TABLE `account_group`
  ADD PRIMARY KEY (`account_group_id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `credit_note`
--
ALTER TABLE `credit_note`
  ADD PRIMARY KEY (`credit_note_id`);

--
-- Indexes for table `debit_note`
--
ALTER TABLE `debit_note`
  ADD PRIMARY KEY (`debit_note_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `item_type`
--
ALTER TABLE `item_type`
  ADD PRIMARY KEY (`item_type_id`);

--
-- Indexes for table `lineitems`
--
ALTER TABLE `lineitems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locked_daterange`
--
ALTER TABLE `locked_daterange`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pack_unit`
--
ALTER TABLE `pack_unit`
  ADD PRIMARY KEY (`pack_unit_id`);

--
-- Indexes for table `purchase_invoice`
--
ALTER TABLE `purchase_invoice`
  ADD PRIMARY KEY (`purchase_invoice_id`);

--
-- Indexes for table `sales_invoice`
--
ALTER TABLE `sales_invoice`
  ADD PRIMARY KEY (`sales_invoice_id`);

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
-- Indexes for table `transaction_entry`
--
ALTER TABLE `transaction_entry`
  ADD PRIMARY KEY (`transaction_id`);

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
  MODIFY `account_group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
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
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_type`
--
ALTER TABLE `item_type`
  MODIFY `item_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
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
-- AUTO_INCREMENT for table `pack_unit`
--
ALTER TABLE `pack_unit`
  MODIFY `pack_unit_id` int(11) NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `transaction_entry`
--
ALTER TABLE `transaction_entry`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user_login_log`
--
ALTER TABLE `user_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
