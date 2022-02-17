-- Ishita : 2018_04_09 11:00 AM
ALTER TABLE `user` ADD `prefix` VARCHAR(255) NULL DEFAULT NULL AFTER `is_letter_pad`;
ALTER TABLE `sales_invoice` ADD `prefix` VARCHAR(255) NULL DEFAULT NULL AFTER `sales_invoice_id`;

-- Chirag : 2018_04_19 06:34 PM
INSERT INTO `settings` (`setting_id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES ('3', 'round_off_apply', '0', NULL, NULL);
ALTER TABLE `user` CHANGE `verify_otp` `verify_otp` TINYINT(1) NULL DEFAULT '1' COMMENT '0 = Verify, 1 = Not_verify';
UPDATE `user` SET `verify_otp`= 1;

-- Dharmik : 2019_04_19 06:00 PM
TRUNCATE `city`;
TRUNCATE `state`;

INSERT INTO `state` (`state_id`, `state_name`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Andra Pradesh', 0, 1, '2019-04-19 17:48:30', 1, '2019-04-19 17:48:30'),
(2, 'Arunachal Pradesh', 0, 1, '2019-04-19 17:48:44', 1, '2019-04-19 17:48:44'),
(3, 'Assam', 0, 1, '2019-04-19 17:49:01', 1, '2019-04-19 17:49:01'),
(4, 'Bihar', 0, 1, '2019-04-19 17:49:14', 1, '2019-04-19 17:49:14'),
(5, 'Chhattisgarh', 0, 1, '2019-04-19 17:49:30', 1, '2019-04-19 17:49:30'),
(6, 'Goa', 0, 1, '2019-04-19 17:49:41', 1, '2019-04-19 17:49:41'),
(7, 'Gujarat', 0, 1, '2019-04-19 17:49:51', 1, '2019-04-19 17:49:51'),
(8, 'Haryana', 0, 1, '2019-04-19 17:50:01', 1, '2019-04-19 17:50:01'),
(9, 'Himachal Pradesh', 0, 1, '2019-04-19 17:50:14', 1, '2019-04-19 17:50:14'),
(10, 'Jammu and Kashmir', 0, 1, '2019-04-19 17:50:26', 1, '2019-04-19 17:50:26'),
(11, 'Jharkhand', 0, 1, '2019-04-19 17:50:37', 1, '2019-04-19 17:50:37'),
(12, 'Karnataka', 0, 1, '2019-04-19 17:51:01', 1, '2019-04-19 17:51:01'),
(13, 'Kerala', 0, 1, '2019-04-19 17:51:11', 1, '2019-04-19 17:51:11'),
(14, 'Madya Pradesh', 0, 1, '2019-04-19 17:51:28', 1, '2019-04-19 17:51:28'),
(15, 'Maharashtra', 0, 1, '2019-04-19 17:51:37', 1, '2019-04-19 17:51:37'),
(16, 'Manipur', 0, 1, '2019-04-19 17:51:47', 1, '2019-04-19 17:51:47'),
(17, 'Meghalaya', 0, 1, '2019-04-19 17:51:54', 1, '2019-04-19 17:51:54'),
(18, 'Mizoram', 0, 1, '2019-04-19 17:52:02', 1, '2019-04-19 17:52:02'),
(19, 'Nagaland', 0, 1, '2019-04-19 17:52:12', 1, '2019-04-19 17:52:12'),
(20, 'Orissa', 0, 1, '2019-04-19 17:52:21', 1, '2019-04-19 17:52:21'),
(21, 'Punjab', 0, 1, '2019-04-19 17:52:30', 1, '2019-04-19 17:52:30'),
(22, 'Rajasthan', 0, 1, '2019-04-19 17:52:38', 1, '2019-04-19 17:52:38'),
(23, 'Sikkim', 0, 1, '2019-04-19 17:52:48', 1, '2019-04-19 17:52:48'),
(24, 'Tamil Nadu', 0, 1, '2019-04-19 17:52:57', 1, '2019-04-19 17:52:57'),
(25, 'Telagana', 0, 1, '2019-04-19 17:53:07', 1, '2019-04-19 17:53:07'),
(26, 'Tripura', 0, 1, '2019-04-19 17:53:17', 1, '2019-04-19 17:53:17'),
(27, 'Uttaranchal', 0, 1, '2019-04-19 17:53:26', 1, '2019-04-19 17:53:26'),
(28, 'Uttar Pradesh', 0, 1, '2019-04-19 17:53:36', 1, '2019-04-19 17:53:36'),
(29, 'West Bengal', 0, 1, '2019-04-19 17:53:45', 1, '2019-04-19 17:53:45');

-- Dharmik : 2019_04_20 03:10 PM
CREATE TABLE `invoice_type` (
  `invoice_type_id` int(11) NOT NULL,
  `invoice_type` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `invoice_type`
  ADD PRIMARY KEY (`invoice_type_id`);

ALTER TABLE `invoice_type`
  MODIFY `invoice_type_id` int(11) NOT NULL AUTO_INCREMENT;

	
-- Chirag : 2019_04_20 04:21 PM
ALTER TABLE `user` ADD `is_bill_wise` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = Not Billwise, 1 = Billwise' AFTER `prefix`;
ALTER TABLE `user` ADD `sales_invoice_notes` TEXT NULL DEFAULT NULL AFTER `is_bill_wise`;

-- Chirag : 2019_04_22 11:30 AM
CREATE TABLE `invoice_paid_details` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `updated_by` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `invoice_paid_details`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `invoice_paid_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Chirag : 2019_04_22 07:00 PM
ALTER TABLE `sales_invoice` ADD `invoice_type` INT(11) NULL DEFAULT NULL AFTER `data_lock_unlock`;


-- Khushali : 2019_04_24 1:47 PM

CREATE TABLE IF NOT EXISTS `company_settings` (
  `company_settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `module_name` tinyint(1) DEFAULT NULL COMMENT '1=Sales Invoice Main Fields; 2=Sales Invoice Line Item Fields',
  `setting_key` varchar(255) DEFAULT NULL,
  `setting_value` tinyint(1) DEFAULT NULL COMMENT '1=Show;2=Not Show',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`company_settings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- Khushali : 2019_04_24 2:49 PM

CREATE TABLE IF NOT EXISTS `item_group` (
  `item_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_group_name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE  `item` ADD  `item_group_id` INT NULL AFTER  `item_type_id` ;

-- Khushali : 2019_04_25 8:41 AM

ALTER TABLE  `sales_invoice` ADD  `shipment_invoice_no` INT NULL AFTER  `invoice_type` ,
ADD  `shipment_invoice_date` DATE NULL AFTER  `shipment_invoice_no` ,
ADD  `sbill_no` VARCHAR( 255 ) NULL AFTER  `shipment_invoice_date` ,
ADD  `sbill_date` DATE NULL AFTER  `sbill_no` ,
ADD  `origin_port` VARCHAR( 255 ) NULL AFTER  `sbill_date` ,
ADD  `port_of_discharge` VARCHAR( 255 ) NULL AFTER  `origin_port` ,
ADD  `container_size` VARCHAR( 255 ) NULL AFTER  `port_of_discharge` ,
ADD  `container_bill_no` VARCHAR( 255 ) NULL AFTER  `container_size` ,
ADD  `container_date` DATE NULL AFTER  `container_bill_no` ,
ADD  `container_no` VARCHAR( 255 ) NULL AFTER  `container_date` ,
ADD  `vessel_name_voy` VARCHAR( 255 ) NULL AFTER  `container_no` ,
ADD  `print_date` DATE NULL AFTER  `vessel_name_voy` ;

-- Khushali : 2019_04_25 2:41 PM

--
-- Table structure for table `company_invoice_prefix`
--

CREATE TABLE IF NOT EXISTS `company_invoice_prefix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `prefix_name` varchar(15) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL COMMENT '0 = Not , 1 = Default',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- Khushali : 2019_04_25 3:20 PM

ALTER TABLE  `company_settings` CHANGE  `setting_value`  `setting_value` VARCHAR( 255 ) NULL DEFAULT NULL ;

-- Khushali : 2019_04_26 8:29 AM

ALTER TABLE  `company_settings` CHANGE  `module_name`  `module_name` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=Sales Invoice Main Fields; 2=Sales Invoice Line Item Fields; 3=Sales Invoice Prefix Setting';

-- Khushali : 2019_04_27 7:47 AM

ALTER TABLE  `lineitems` ADD  `item_group_id` INT NULL AFTER  `item_id` ;

-- Khushali : 2019_04_29 9:07 AM
ALTER TABLE  `state` ADD  `state_code` VARCHAR( 15 ) NULL AFTER  `state_name` ;

-- Chirag : 2019_04_29 01:55 PM
ALTER TABLE `invoice_type` ADD `delete_allow` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = Yes, 1 = No' AFTER `invoice_type`;

INSERT INTO `invoice_type` (`invoice_type_id`, `invoice_type`, `delete_allow`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(3, 'Freight Invoice', 1, 2, '2019-04-29 13:47:25', 2, '2019-04-29 13:47:25'),
(4, 'Reimbursement', 1, 2, '2019-04-29 13:48:29', 2, '2019-04-29 13:48:29'),
(5, 'Invoice', 1, 2, '2019-04-29 13:48:43', 2, '2019-04-29 13:48:43');

-- Chirag : 2019_04_30 02:40 PM
ALTER TABLE `sales_invoice` CHANGE `shipment_invoice_no` `shipment_invoice_no` VARCHAR(255) NULL DEFAULT NULL;

-- Chirag : 2019_05_01 01:00 PM
ALTER TABLE `sales_invoice` ADD `your_invoice_no` VARCHAR(255) NULL DEFAULT NULL AFTER `vessel_name_voy`;

-- Khushali : 2019_05_13 11:09 AM
ALTER TABLE  `account` ADD  `is_tally` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '0=not from tally; 1=from tally' AFTER  `credit_debit` ;
ALTER TABLE  `account` ADD  `created_at` DATETIME NULL ,
ADD  `updated_by` INT NULL ,
ADD  `updated_at` DATETIME NULL ;

-- Khushali : 2019_05_14 9:20 AM
ALTER TABLE  `sales_invoice` ADD  `print_type` TINYINT NOT NULL DEFAULT  '0' COMMENT  '0=Original;1=Duplicate;2=Triplicate' AFTER  `print_date` ;

-- Khushali : 2019_05_14 12:59 AM
ALTER TABLE  `transaction_entry` ADD  `voucher_remote_id` VARCHAR( 255 ) NULL AFTER  `note` ;

-- Khushali : 2019_05_14 4:16 PM
ALTER TABLE  `purchase_invoice` ADD  `invoice_type` TINYINT( 1 ) NOT NULL DEFAULT  '1' COMMENT  '1=Order;2=Purchase' AFTER  `data_lock_unlock` ;

-- Ishita : 2019_05_31 12:46 PM
ALTER TABLE `item` ADD `minimum` INT(11) NULL DEFAULT NULL AFTER `sales_rate`, ADD `maximum` INT(11) NULL DEFAULT NULL AFTER `minimum`, ADD `reorder_stock` INT(11) NULL DEFAULT NULL AFTER `maximum`;

-- Ishita : 2019_05_31 02:10 PM
ALTER TABLE `transaction_entry` CHANGE `transaction_type` `transaction_type` TINYINT(2) NULL DEFAULT NULL COMMENT '1 = Payment , 2 = Recepit , 3 = Contra, 4 = journal';

-- Khushali : 2019_06_01 9:29 AM
ALTER TABLE  `user` ADD  `purchase_rate` TINYINT( 1 ) NULL COMMENT  '1=Including Tax; 2= Excluding Tax',
ADD  `sales_rate` TINYINT( 1 ) NULL COMMENT  '1=Including Tax; 2= Excluding Tax';

-- Khushali : 2019_06_01 10:34 AM
ALTER TABLE  `user` ADD  `invoice_type` INT NULL ;

-- Ishita : 2019_06_01 12:20 PM
-- Table structure for table `hsn`
--

CREATE TABLE `hsn` (
  `hsn_id` int(11) NOT NULL,
  `hsn` varchar(255) DEFAULT NULL,
  `hsn_discription` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `hsn`
  ADD PRIMARY KEY (`hsn_id`);

ALTER TABLE `hsn`
  MODIFY `hsn_id` int(11) NOT NULL AUTO_INCREMENT;

-- Khushali : 2019_06_01 2:16 PM
ALTER TABLE  `item` ADD  `list_price` DOUBLE NULL AFTER  `hsn_code` ,
ADD  `mrp` DOUBLE NULL AFTER  `list_price` ,
ADD  `opening_qty` DOUBLE NULL AFTER  `mrp` ,
ADD  `opening_amount` DOUBLE NULL AFTER  `opening_qty` ;

-- Ishita : 2019_06_01 03:02 PM
-- Table structure for table `discount`

CREATE TABLE `discount` (
  `discount_id` int(11) NOT NULL,
  `account_id` varchar(255) DEFAULT NULL,
  `item_group_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `discount`
  ADD PRIMARY KEY (`discount_id`);

ALTER TABLE `discount`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT;

-- Dharmik : 2019_06_05 06:03 PM
ALTER TABLE `item` ADD `shortname` VARCHAR(255) NULL DEFAULT NULL AFTER `reorder_stock`;

-- Ishita : 2019_06_07 12:30 PM
ALTER TABLE `item` ADD `discount_on` TINYINT(1) NULL DEFAULT NULL COMMENT '1 = List Price, 2 = MRP' AFTER `shortname`, ADD `discount_per` DOUBLE NULL DEFAULT NULL AFTER `discount_on`, ADD `exempted_from_gst` TINYINT(1) NULL DEFAULT NULL AFTER `discount_per`;

--
-- Table structure for table `category`
-- Ishita : 2019_06_07 01:20 PM

CREATE TABLE `category` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `category`
  ADD PRIMARY KEY (`cat_id`);

ALTER TABLE `category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Table structure for table `sub_category`
-- Ishita : 2019_06_07 02:02 PM

CREATE TABLE `sub_category` (
  `sub_cat_id` int(11) NOT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `sub_cat_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`sub_cat_id`);

ALTER TABLE `sub_category`
  MODIFY `sub_cat_id` int(11) NOT NULL AUTO_INCREMENT;

-- Ishita : 2019_06_07 03:00 PM
ALTER TABLE `lineitems` ADD `cat_id` INT(11) NULL DEFAULT NULL AFTER `id`, ADD `sub_cat_id` INT(11) NULL DEFAULT NULL AFTER `cat_id`;

-- Ishita : 2019_06_07 04:00 PM
INSERT INTO `company_settings` (`company_id`, `module_name`, `setting_key`, `setting_value`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 'shipment_invoice_no', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 1, 'shipment_invoice_date', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 1, 'sbill_no', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 1, 'sbill_date', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 1, 'origin_port', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 1, 'port_of_discharge', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 1, 'container_size', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 1, 'container_bill_no', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 1, 'container_date', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 1, 'container_no', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 1, 'vessel_name_voy', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 1, 'print_date', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 1, 'your_invoice_no', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 1, 'display_dollar_sign', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 2, 'item_group', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 2, 'unit', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 2, 'discount', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 2, 'basic_amount', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 2, 'cgst_per', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 2, 'cgst_amt', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 2, 'sgst_per', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 2, 'sgst_amt', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 2, 'igst_per', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 2, 'igst_amt', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 2, 'other_charges', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 2, 'amount', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 2, 'note', '0', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 3, 'invoice_no_digit', '', '2019-05-31 14:54:20', 1, NULL, NULL),
(1, 3, 'year_post_fix', '0', '2019-05-31 14:54:20', 1, NULL, NULL);

-- Avinash : 2019_06_07 06:30 PM
INSERT INTO `company_settings` (`company_id`, `module_name`, `setting_key`, `setting_value`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 2, 'category', '0', '2019-06-06 14:54:20', 1, NULL, NULL),
(1, 2, 'sub_category', '0', '2019-06-06 14:54:20', 1, NULL, NULL),
(1, 2, 'short_name', '0', '2019-06-06 14:54:20', 1, NULL, NULL);

-- Avinash : 2019_06_07 07:50 PM
ALTER TABLE `user` ADD `discount_on` TINYINT(1) NULL DEFAULT NULL COMMENT '1 = List Price, 2 = MRP' AFTER `sales_rate`;
ALTER TABLE `item_group` ADD `discount_on` TINYINT(1) NULL DEFAULT NULL COMMENT '1 = List Price, 2 = MRP' AFTER `item_group_name`;

-- Avinash : 2019_06_07 07:50 PM
ALTER TABLE `user` ADD `created_by` INT(11) NULL DEFAULT NULL AFTER `invoice_type`, ADD `created_at` DATETIME NULL DEFAULT NULL AFTER `created_by`, ADD `updated_by`INT(11) NULL DEFAULT NULL AFTER `created_at`, ADD `updated_at` DATETIME NULL DEFAULT NULL AFTER `updated_by`;

-- Chirag : 2019_06_08 04:05 PM
ALTER TABLE `item` ADD `category_id` INT(11) NULL DEFAULT NULL AFTER `item_name`, ADD `sub_category_id` INT(11) NULL DEFAULT NULL AFTER `category_id`;

-- Shailesh : 2019_06_12 07:00 PM
ALTER TABLE `user` ADD `last_visited_page` TEXT NULL AFTER `invoice_type`;

-- Prashant : 2019_06_15 : 5:31 PM
DELETE FROM `account_group` WHERE `account_group`.`account_group_id` = 43;
DELETE FROM `account_group` WHERE `account_group`.`account_group_id` = 44;
UPDATE `pack_unit` SET `pack_unit_name` = 'Nos' WHERE `pack_unit`.`pack_unit_id` = 4;

-- Khushali : 2019_06_18 9:48 AM
ALTER TABLE  `item` ADD  `purchase_rate_val` DOUBLE NULL AFTER  `sales_rate` , ADD  `sales_rate_val` DOUBLE NULL AFTER  `purchase_rate_val` ;

-- Khushali : 2019_06_18 10:38 AM
ALTER TABLE  `sales_invoice` ADD  `is_shipping_same_as_billing_address` TINYINT( 1 ) NULL COMMENT  '0=same;1=different' AFTER  `print_type` ;
ALTER TABLE  `sales_invoice` ADD  `shipping_address` TEXT NULL AFTER  `is_shipping_same_as_billing_address` ;

INSERT INTO `company_settings` (`company_settings_id`, `company_id`, `module_name`, `setting_key`, `setting_value`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (NULL, '1', '2', 'separate', '0', '2019-06-18 00:00:00', NULL, NULL, NULL);

-- Shailesh : 2019_06_19 12:38 AM
ALTER TABLE `item` ADD `current_stock_qty` DOUBLE NULL AFTER `mrp`;

CREATE TABLE IF NOT EXISTS `item_current_stock_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `is_sales_or_purchase` enum('sales','purchase') DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`,`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- Shailesh : 2019_06_19 17:30 AM
ALTER TABLE `item_current_stock_detail` CHANGE `is_sales_or_purchase` `is_sales_or_purchase` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;


-- Shailesh : 2019_10_19 12:15 PM
ALTER TABLE `user` ADD `is_single_line_item` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1-Yes, 0-No' AFTER `invoice_type`;

-- Shailesh : 2019_10_21 11:00 AM
ALTER TABLE `account` ADD `consider_in_pl` TINYINT(1) NULL DEFAULT '0' COMMENT '1-Yes, 0-No' AFTER `credit_debit`;
INSERT INTO `item` (`item_id`, `item_name`, `category_id`, `sub_category_id`, `hsn_code`, `list_price`, `mrp`, `current_stock_qty`, `opening_qty`, `opening_amount`, `cgst_per`, `sgst_per`, `igst_per`, `cess`, `alternate_unit_id`, `pack_unit_id`, `item_type_id`, `item_group_id`, `item_desc`, `purchase_rate`, `sales_rate`, `purchase_rate_val`, `sales_rate_val`, `minimum`, `maximum`, `reorder_stock`, `shortname`, `discount_on`, `discount_per`, `exempted_from_gst`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(NULL, 'Debit Note (Purchase Return)', NULL, NULL, NULL, 0, 0, -1, 0, 0, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', 2, 1, 0, 0, 0, 0, 0, '', 2, 0, 0, 2, '2019-10-19 13:50:07', 2, '2019-10-19 13:50:07'),
(NULL, 'Credit Note (Sales Return)', NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', 2, 1, 0, 0, 0, 0, 0, '', 2, 0, 0, 2, '2019-10-19 13:49:37', 2, '2019-10-19 13:49:37'),
(NULL, 'Purchase Invoice', NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '', NULL, NULL, NULL, 4, '', 2, 1, 0, 0, 0, 0, 0, '', 2, 0, 0, 2, '2019-10-19 13:49:09', 2, '2019-10-19 13:49:09'),
(NULL, 'Sales Invoice', NULL, NULL, NULL, 0, 0, -3, 0, 0, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', 2, 1, 0, 0, 0, 0, 0, '', 2, 0, 0, 2, '2019-10-19 13:48:47', 2, '2019-10-19 13:48:47');

-- Shailesh : 2019_10_26 11:00 AM
ALTER TABLE `transaction_entry` ADD `journal_id` INT NULL DEFAULT NULL AFTER `voucher_remote_id`, ADD `is_credit_debit` TINYINT(1) NULL DEFAULT NULL COMMENT '1-Credit, 2-Debit' AFTER `journal_id`;
--
-- Table structure for table `journal`
--
CREATE TABLE IF NOT EXISTS `journal` (
  `journal_id` int(11) NOT NULL AUTO_INCREMENT,
  `journal_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`journal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- Shailesh : 2019_10_26 03:00 PM
ALTER TABLE `sales_invoice` ADD `against_account_id` INT(11) NULL DEFAULT NULL AFTER `account_id`;
ALTER TABLE `purchase_invoice` ADD `against_account_id` INT(11) NULL DEFAULT NULL AFTER `account_id`;
ALTER TABLE `credit_note` ADD `against_account_id` INT(11) NULL DEFAULT NULL AFTER `account_id`;
ALTER TABLE `debit_note` ADD `against_account_id` INT(11) NULL DEFAULT NULL AFTER `account_id`;


INSERT INTO `account_group` (`account_group_id`, `parent_group_id`, `account_group_name`, `sequence`, `is_deletable`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(50, 0, 'Sales Bill', 1, 0, 0, 2, '2019-10-26 15:16:34', 2, '2019-10-26 15:16:34');
INSERT INTO `account_group` (`account_group_id`, `parent_group_id`, `account_group_name`, `sequence`, `is_deletable`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(51, 0, 'Purchase Bill', 1, 0, 0, 2, '2019-10-26 15:16:34', 2, '2019-10-26 15:16:34');
INSERT INTO `account_group` (`account_group_id`, `parent_group_id`, `account_group_name`, `sequence`, `is_deletable`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(52, 0, 'Credit Note Bill (Sales Return)', 1, 0, 0, 2, '2019-10-26 15:16:34', 2, '2019-10-26 15:16:34');
INSERT INTO `account_group` (`account_group_id`, `parent_group_id`, `account_group_name`, `sequence`, `is_deletable`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(53, 0, 'Debit Note Bill (Purchase Return)', 1, 0, 0, 2, '2019-10-26 15:16:34', 2, '2019-10-26 15:16:34');

INSERT INTO `account` (`account_id`, `account_name`, `account_phone`, `account_email_ids`, `account_address`, `account_state`, `account_city`, `account_postal_code`, `account_gst_no`, `account_pan`, `account_aadhaar`, `account_contect_person_name`, `account_group_id`, `opening_balance`, `credit_debit`, `consider_in_pl`, `is_tally`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES 
(NULL, 'Sales Account', '', '', '', NULL, NULL, 0, '', '', '', '', 50, 0, 1, 0, 0, 2, NULL, NULL, NULL);
INSERT INTO `account` (`account_id`, `account_name`, `account_phone`, `account_email_ids`, `account_address`, `account_state`, `account_city`, `account_postal_code`, `account_gst_no`, `account_pan`, `account_aadhaar`, `account_contect_person_name`, `account_group_id`, `opening_balance`, `credit_debit`, `consider_in_pl`, `is_tally`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES 
(NULL, 'Purchase Account', '', '', '', NULL, NULL, 0, '', '', '', '', 51, 0, 1, 0, 0, 2, NULL, NULL, NULL);
INSERT INTO `account` (`account_id`, `account_name`, `account_phone`, `account_email_ids`, `account_address`, `account_state`, `account_city`, `account_postal_code`, `account_gst_no`, `account_pan`, `account_aadhaar`, `account_contect_person_name`, `account_group_id`, `opening_balance`, `credit_debit`, `consider_in_pl`, `is_tally`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES 
(NULL, 'Sales Return Account', '', '', '', NULL, NULL, 0, '', '', '', '', 52, 0, 1, 0, 0, 2, NULL, NULL, NULL);
INSERT INTO `account` (`account_id`, `account_name`, `account_phone`, `account_email_ids`, `account_address`, `account_state`, `account_city`, `account_postal_code`, `account_gst_no`, `account_pan`, `account_aadhaar`, `account_contect_person_name`, `account_group_id`, `opening_balance`, `credit_debit`, `consider_in_pl`, `is_tally`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES 
(NULL, 'Purchase Return Account', '', '', '', NULL, NULL, 0, '', '', '', '', 53, 0, 1, 0, 0, 2, NULL, NULL, NULL);


-- Shailesh : 2019_11_04 07:00 PM
ALTER TABLE `credit_note` ADD INDEX(`account_id`);
ALTER TABLE `credit_note` ADD INDEX(`against_account_id`);
ALTER TABLE `credit_note` ADD CONSTRAINT `Fk_CreditNoteAccount` FOREIGN KEY (`account_id`) REFERENCES `account`(`account_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `credit_note` ADD CONSTRAINT `Fk_CreditNoteAgainstAccount` FOREIGN KEY (`against_account_id`) REFERENCES `account`(`account_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `debit_note` ADD INDEX(`account_id`);
ALTER TABLE `debit_note` ADD INDEX(`against_account_id`);
ALTER TABLE `debit_note` ADD CONSTRAINT `Fk_DebitNoteAccount` FOREIGN KEY (`account_id`) REFERENCES `account`(`account_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `debit_note` ADD CONSTRAINT `Fk_DebitNoteAgainstAccount` FOREIGN KEY (`against_account_id`) REFERENCES `account`(`account_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `sales_invoice` ADD INDEX(`account_id`);
ALTER TABLE `sales_invoice` ADD INDEX(`against_account_id`);
ALTER TABLE `sales_invoice` ADD CONSTRAINT `Fk_SalesInvoiceAccount` FOREIGN KEY (`account_id`) REFERENCES `account`(`account_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `sales_invoice` ADD CONSTRAINT `Fk_SalesInvoiceAgainstAccount` FOREIGN KEY (`against_account_id`) REFERENCES `account`(`account_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `purchase_invoice` ADD INDEX(`account_id`);
ALTER TABLE `purchase_invoice` ADD INDEX(`against_account_id`);
ALTER TABLE `purchase_invoice` ADD CONSTRAINT `Fk_PurchaseInvoiceAccount` FOREIGN KEY (`account_id`) REFERENCES `account`(`account_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `purchase_invoice` ADD CONSTRAINT `Fk_PurchaseInvoiceAgainstAccount` FOREIGN KEY (`against_account_id`) REFERENCES `account`(`account_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `invoice_paid_details` ADD INDEX(`account_id`);
ALTER TABLE `invoice_paid_details` ADD CONSTRAINT `Fk_InvPaidDetailAccount` FOREIGN KEY (`account_id`) REFERENCES `account`(`account_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `transaction_entry` ADD INDEX(`account_id`);
ALTER TABLE `transaction_entry` ADD CONSTRAINT `Fk_TransAccount` FOREIGN KEY (`account_id`) REFERENCES `account`(`account_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `transaction_entry` ADD INDEX(`from_account_id`);
ALTER TABLE `transaction_entry` ADD CONSTRAINT `Fk_TransFromAccount` FOREIGN KEY (`from_account_id`) REFERENCES `account`(`account_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `transaction_entry` ADD INDEX(`to_account_id`);
ALTER TABLE `transaction_entry` ADD CONSTRAINT `Fk_TransToAccount` FOREIGN KEY (`to_account_id`) REFERENCES `account`(`account_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `account` ADD INDEX(`account_group_id`);
ALTER TABLE `account` ADD  CONSTRAINT `Fk_AccountGroup` FOREIGN KEY (`account_group_id`) REFERENCES `account_group`(`account_group_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `item` ADD INDEX(`item_group_id`);
ALTER TABLE `item` ADD CONSTRAINT `Fk_ItemGroupItem` FOREIGN KEY (`item_group_id`) REFERENCES `item_group`(`item_group_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `item` ADD INDEX(`item_type_id`);
ALTER TABLE `item` ADD CONSTRAINT `Fk_ItemTypeItem` FOREIGN KEY (`item_type_id`) REFERENCES `item_type`(`item_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `account` ADD INDEX(`account_state`);
ALTER TABLE `account` ADD CONSTRAINT `Fk_StateAccount` FOREIGN KEY (`account_state`) REFERENCES `state`(`state_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `account` ADD INDEX(`account_city`);
ALTER TABLE `account` ADD CONSTRAINT `Fk_CityAccount` FOREIGN KEY (`account_city`) REFERENCES `city`(`city_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `city` ADD INDEX(`state_id`);
ALTER TABLE `city` ADD CONSTRAINT `Fk_StateCity` FOREIGN KEY (`state_id`) REFERENCES `state`(`state_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `account` ADD INDEX(`created_by`);
ALTER TABLE `account` ADD CONSTRAINT `Fk_UserIdAccount` FOREIGN KEY (`created_by`) REFERENCES `user`(`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `lineitems` ADD INDEX(`item_id`);
ALTER TABLE `lineitems` ADD CONSTRAINT `Fk_ItemLineItem` FOREIGN KEY (`item_id`) REFERENCES `item`(`item_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;


-- Chirag : 2019_11_04 07:10 PM
CREATE TABLE `module_roles` (
  `module_role_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `role_name` varchar(255) DEFAULT NULL,
  `website_module_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `user_roles` (
  `user_role_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `website_module_id` int(11) DEFAULT NULL,
  `role_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `website_modules` (
  `website_module_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `main_module` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `module_roles`
  ADD PRIMARY KEY (`module_role_id`);

ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_role_id`);

ALTER TABLE `website_modules`
  ADD PRIMARY KEY (`website_module_id`);

ALTER TABLE `module_roles`
  MODIFY `module_role_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user_roles`
  MODIFY `user_role_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `website_modules`
  MODIFY `website_module_id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('1', 'Company', '1');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '1');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '1');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '1');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '1');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('2', 'Account', '2');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '2');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '2');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '2');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '2');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('3', 'Master', '3');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '3');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('4', 'Master >> Item Group', '3.1');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '4');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '4');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '4');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '4');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('5', 'Master >> Item Type', '3.2');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '5');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '5');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '5');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '5');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('6', 'Master >> Invoice Type', '3.3');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '6');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '6');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '6');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '6');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('7', 'Master >> State', '3.4');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '7');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '7');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '7');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '7');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('8', 'Master >> City', '3.5');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '8');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '8');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '8');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '8');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('9', 'Master >> Account Group', '3.6');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '9');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '9');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '9');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '9');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('10', 'Master >> Unit', '3.7');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '10');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '10');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '10');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '10');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('11', 'Master >> Import', '3.8');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '11');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '11');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '11');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '11');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('12', 'Master >> HSN', '3.9');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '12');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '12');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '12');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '12');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('13', 'Master >> Category', '3.10');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '13');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '13');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '13');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '13');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('14', 'Master >> Sub Category', '3.11');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '14');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '14');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '14');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '14');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('15', 'Item', '4');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '15');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '15');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '15');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '15');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('16', 'Order / Purchase', '5');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '16');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('17', 'Order', '5.1');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '17');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '17');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '17');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '17');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('18', 'Purchase Invoice', '5.2');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '18');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '18');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '18');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '18');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('19', 'Sales', '6');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '19');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('20', 'Sales >> Invoice', '6.1');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '20');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '20');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '20');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '20');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('21', 'Sales >> Discount', '6.2');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '21');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '21');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '21');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '21');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('22', 'Credit Note', '7');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '22');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '22');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '22');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '22');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('23', 'Debit Note', '8');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '23');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '23');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '23');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '23');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('24', 'Payment', '9');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '24');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '24');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '24');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '24');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('25', 'Receipt', '10');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '25');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '25');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '25');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '25');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('26', 'Contra', '11');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '26');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '26');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '26');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '26');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('27', 'Journal', '12');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '27');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '27');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '27');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '27');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'journal type 2', 'journal type 2', '27');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('28', 'Report', '13');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '28');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('29', 'Report >> Stock Register', '13.1');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '29');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('30', 'Report >> Purchase Register', '13.2');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '30');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('31', 'Report >> Sales Register', '13.3');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '31');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('32', 'Report >> Credit Note Register', '13.4');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '32');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('33', 'Report >> Debit Note Register', '13.5');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '33');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('34', 'Report >> Ledger', '13.6');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '34');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('35', 'Report >> Summary', '13.7');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '35');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('36', 'Report >> Balance Sheet', '13.8');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '36');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('37', 'Report >> User Log', '13.9');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '37');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('38', 'GSTR1 Excel Export', '14');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '38');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('39', 'GSTR2 Excel Export', '15');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '39');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('40', 'GSTR-3B Excel Export', '16');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '40');

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('41', 'Backup DB', '17');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '41');

-- Chirag : 2019_11_05 11:35 AM
INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('42', 'Master >> User Rights', '3.12');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '42');

-- Shailesh : 2019_11_05 11:35 AM
ALTER TABLE `account_group` ADD `display_in_balance_sheet` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1-Yes, 0-No' AFTER `sequence`;
INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('43', 'Report >> Profit Loss', '13.8');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '43');

-- Shailesh : 2019_11_05 06:35 PM
ALTER TABLE `account` CHANGE `credit_debit` `credit_debit` TINYINT(1) NULL DEFAULT NULL COMMENT '1-Credit, 2-Debit';

-- Shailesh : 2019_11_11 08:00 PM
INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('44', 'Report >> Trial Balance', '13.9');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '44');

-- Shailesh : 2019_11_14 02:30 PM
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Transport Name', 'Transport Name', '20');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'LR No', 'LR No', '20');

-- Shailesh : 2019_11_20 02:30 PM
-- Sales  Feature = Purchase Feature
ALTER TABLE `purchase_invoice` ADD `transport_name` VARCHAR(255) NULL DEFAULT NULL  AFTER `invoice_type`,  ADD `lr_no` VARCHAR(255) NULL DEFAULT NULL  AFTER `transport_name`,  ADD `shipment_invoice_no` VARCHAR(255) NULL DEFAULT NULL  AFTER `lr_no`,  ADD `shipment_invoice_date` DATE NULL DEFAULT NULL  AFTER `shipment_invoice_no`,  ADD `sbill_no` VARCHAR(255) NULL DEFAULT NULL  AFTER `shipment_invoice_date`,  ADD `sbill_date` DATE NULL DEFAULT NULL  AFTER `sbill_no`,  ADD `origin_port` VARCHAR(255) NULL DEFAULT NULL  AFTER `sbill_date`,  ADD `port_of_discharge` VARCHAR(255) NULL DEFAULT NULL  AFTER `origin_port`,  ADD `container_size` VARCHAR(255) NULL DEFAULT NULL  AFTER `port_of_discharge`,  ADD `container_bill_no` VARCHAR(255) NULL DEFAULT NULL  AFTER `container_size`,  ADD `container_date` DATE NULL DEFAULT NULL  AFTER `container_bill_no`,  ADD `container_no` VARCHAR(255) NULL DEFAULT NULL  AFTER `container_date`,  ADD `vessel_name_voy` VARCHAR(255) NULL DEFAULT NULL  AFTER `container_no`,  ADD `your_invoice_no` VARCHAR(255) NULL DEFAULT NULL  AFTER `vessel_name_voy`, ADD `print_date` DATE NULL DEFAULT NULL AFTER `your_invoice_no`, ADD `print_type` TINYINT(4) NULL DEFAULT '0' AFTER `print_date`, ADD `is_shipping_same_as_billing_address` TINYINT(1) NULL DEFAULT NULL AFTER `print_type`, ADD `shipping_address` TEXT NULL DEFAULT NULL AFTER `is_shipping_same_as_billing_address`;

-- Update state code for GST
UPDATE `state` SET `state_code` = '37' WHERE `state`.`state_name` = 'Andhra Pradesh';
UPDATE `state` SET `state_code` = '12' WHERE `state`.`state_name` = 'Arunachal Pradesh';
UPDATE `state` SET `state_code` = '18' WHERE `state`.`state_name` = 'Assam';
UPDATE `state` SET `state_code` = '10' WHERE `state`.`state_name` = 'Bihar';
UPDATE `state` SET `state_code` = '22' WHERE `state`.`state_name` = 'Chhattisgarh';
UPDATE `state` SET `state_code` = '07' WHERE `state`.`state_name` = 'Delhi';
UPDATE `state` SET `state_code` = '30' WHERE `state`.`state_name` = 'Goa';
UPDATE `state` SET `state_code` = '24' WHERE `state`.`state_name` = 'Gujarat';
UPDATE `state` SET `state_code` = '06' WHERE `state`.`state_name` = 'Haryana';
UPDATE `state` SET `state_code` = '02' WHERE `state`.`state_name` =  'Himachal Pradesh';
UPDATE `state` SET `state_code` = '01' WHERE `state`.`state_name` =  'Jammu and Kashmir';
UPDATE `state` SET `state_code` = '20' WHERE `state`.`state_name` =  'Jharkhand';
UPDATE `state` SET `state_code` = '29' WHERE `state`.`state_name` =  'Karnataka';
UPDATE `state` SET `state_code` = '32' WHERE `state`.`state_name` =  'Kerala';
UPDATE `state` SET `state_code` = '31' WHERE `state`.`state_name` =  'Lakshadweep Islands';
UPDATE `state` SET `state_code` = '23' WHERE `state`.`state_name` =  'Madya Pradesh';
UPDATE `state` SET `state_code` = '27' WHERE `state`.`state_name` =  'Maharashtra';
UPDATE `state` SET `state_code` = '14' WHERE `state`.`state_name` =  'Manipur';
UPDATE `state` SET `state_code` = '17' WHERE `state`.`state_name` =  'Meghalaya';
UPDATE `state` SET `state_code` = '15' WHERE `state`.`state_name` =  'Mizoram';
UPDATE `state` SET `state_code` = '13' WHERE `state`.`state_name` =  'Nagaland';
UPDATE `state` SET `state_code` = '21' WHERE `state`.`state_name` =  'Orissa';
UPDATE `state` SET `state_code` = '34' WHERE `state`.`state_name` =  'Pondicherry';
UPDATE `state` SET `state_code` = '03' WHERE `state`.`state_name` =  'Punjab';
UPDATE `state` SET `state_code` = '08' WHERE `state`.`state_name` =  'Rajasthan';
UPDATE `state` SET `state_code` = '11' WHERE `state`.`state_name` =  'Sikkim';
UPDATE `state` SET `state_code` = '33' WHERE `state`.`state_name` =  'Tamil Nadu';
UPDATE `state` SET `state_code` = '36' WHERE `state`.`state_name` =  'Telagana';
UPDATE `state` SET `state_code` = '16' WHERE `state`.`state_name` =  'Tripura';
UPDATE `state` SET `state_code` = '09' WHERE `state`.`state_name` =  'Uttar Pradesh';
UPDATE `state` SET `state_code` = '05' WHERE `state`.`state_name` =  'Uttaranchal';
UPDATE `state` SET `state_code` = '19' WHERE `state`.`state_name` =  'West Bengal';

-- Shailesh : 2019_11_23 12:30 PM
ALTER TABLE `account` ADD `added_from` TEXT NULL DEFAULT NULL COMMENT 'Account added from' AFTER `is_tally`;

-- Shailesh : 2019_11_29 01:00 PM
CREATE TABLE IF NOT EXISTS `documents` (
  `document_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `document_link` text,
  `remark` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('45', 'Company >> Document', '1.2');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'View', 'view', '45');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Add', 'add', '45');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Edit', 'edit', '45');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Delete', 'delete', '45');

ALTER TABLE `documents` ADD INDEX(`company_id`);
ALTER TABLE `documents` ADD CONSTRAINT `Fk_CompanyIdDocument` FOREIGN KEY (`company_id`) REFERENCES `user`(`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- Shailesh : 2019_12_16 04:00 PM
ALTER TABLE `account` ADD `account_mobile_numbers` VARCHAR(255) NULL DEFAULT NULL AFTER `account_email_ids`;

-- Shailesh : 2019_12_19 10:45 AM
ALTER TABLE `sales_invoice` ADD `other_charges_total` DOUBLE NULL DEFAULT NULL AFTER `igst_amount_total`;
ALTER TABLE `purchase_invoice` ADD `other_charges_total` DOUBLE NULL DEFAULT NULL AFTER `igst_amount_total`;
ALTER TABLE `credit_note` ADD `other_charges_total` DOUBLE NULL DEFAULT NULL AFTER `igst_amount_total`;
ALTER TABLE `debit_note` ADD `other_charges_total` DOUBLE NULL DEFAULT NULL AFTER `igst_amount_total`;

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('46', 'Report >> Sales Bill Register', '13.3.1');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'View', 'view', '46');

-- Shailesh : 2019_12_23 02:45 PM
ALTER TABLE `sales_invoice` ADD `round_off_amount` DOUBLE NULL DEFAULT NULL AFTER `other_charges_total`;
ALTER TABLE `purchase_invoice` ADD `round_off_amount` DOUBLE NULL DEFAULT NULL AFTER `other_charges_total`;
ALTER TABLE `credit_note` ADD `round_off_amount` DOUBLE NULL DEFAULT NULL AFTER `other_charges_total`;
ALTER TABLE `debit_note` ADD `round_off_amount` DOUBLE NULL DEFAULT NULL AFTER `other_charges_total`;

ALTER TABLE `account` ADD `is_kasar_account` TINYINT(1) NULL DEFAULT '0' COMMENT '1=Yes, 0=No' AFTER `added_from`;
ALTER TABLE `journal` 
ADD `invoice_id` INT NULL AFTER `journal_id`, 
ADD `module` INT NULL COMMENT 'purchase_invoice = 1, sales_invoice = 2, credit_note = 3, debit_note = 4' AFTER `invoice_id`;

-- Shailesh : 2019_12_23 07:19 PM
ALTER TABLE `sales_invoice` ADD `cash_customer` VARCHAR(255) NULL DEFAULT NULL AFTER `account_id`;

-- Shailesh : 2019_12_23 07:19 PM
ALTER TABLE `transaction_entry` ADD `receipt_no` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Cheque / Cash Receipt No' AFTER `transaction_type`;

-- Shailesh : 2019_12_28 02:15 PM
ALTER TABLE `sales_invoice` ADD `tax_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1=GST, 2=IGST' AFTER `invoice_type`;

-- Shailesh : 2020_02_04 8:00 PM
CREATE TABLE IF NOT EXISTS `sub_item_add_less_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- Shailesh : 2020_02_16 11:30 AM
CREATE TABLE IF NOT EXISTS `stock_status_change` (
  `st_change_id` int(11) NOT NULL AUTO_INCREMENT,
  `st_change_date` date DEFAULT NULL,
  `from_status` tinyint(1) DEFAULT NULL COMMENT '1=In Stock, 2=In WIP, 3=In Work Done',
  `to_status` tinyint(1) DEFAULT NULL COMMENT '1=In Stock, 2=In WIP, 3=In Work Done',
  `item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`st_change_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- Shailesh : 2020_02_16 03:00 PM
ALTER TABLE `item_current_stock_detail` ADD `st_change_id` INT NULL DEFAULT NULL AFTER `invoice_id`;
INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('47', 'Master >> Stock Status Change', '3.11.1');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'View', 'view', '47');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Add', 'add', '47');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Edit', 'edit', '47');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Delete', 'delete', '47');

-- Chirag : 2020_02_18 05:00 PM
ALTER TABLE `sub_item_add_less_settings` ADD `item_level` INT(11) NOT NULL DEFAULT '1' AFTER `item_id`;

-- Chirag : 2020_02_19 11:00 AM
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
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `sub_item_add_less_settings_sales_invoice_wise`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sub_item_add_less_settings_sales_invoice_wise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Shailesh : 2020_02_20 10:00 AM
ALTER TABLE `user` ADD `company_id` INT NULL DEFAULT NULL COMMENT 'Parent User ID' AFTER `last_visited_page`;
INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('48', 'Master >> User', '3.01');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'View', 'view', '48');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Add', 'add', '48');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Edit', 'edit', '48');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Delete', 'delete', '48');

-- Shailesh : 2020_02_21 01:50 PM
ALTER TABLE `account` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `account_group` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `bank_master` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `category` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `city` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `company_invoice_prefix` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `company_settings` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `country` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `credit_note` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `debit_note` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `discount` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `documents` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `hsn` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `invoice_paid_details` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `invoice_type` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `item` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `item_current_stock_detail` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `item_group` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `item_type` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `journal` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `lineitems` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `locked_daterange` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `pack_unit` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `purchase_invoice` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `sales_invoice` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `settings` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `state` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `stock_status_change` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `sub_category` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `sub_item_add_less_settings` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `sub_item_add_less_settings_sales_invoice_wise` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `transaction_entry` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;
ALTER TABLE `user` ADD `user_created_by` INT NULL DEFAULT NULL AFTER `updated_at`, ADD `user_updated_by` INT NULL DEFAULT NULL AFTER `user_created_by`;

-- Shailesh : 2020_02_21 04:40 PM
INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES ('49', 'Order Type 2', '5.1.1');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'View', 'view', '49');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Add', 'add', '49');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Edit', 'edit', '49');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Delete', 'delete', '49');

-- Shailesh : 2020_02_22 10:30 AM
ALTER TABLE `purchase_invoice` CHANGE `invoice_type` `invoice_type` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1=Order;2=Purchase,3=Order Type 2';

-- Chirag : 2020_02_27 04:30 PM
ALTER TABLE `stock_status_change` ADD `tr_type` INT(11) NULL DEFAULT NULL COMMENT '1 = Purchase, 2 = Sale' AFTER `qty`, ADD `tr_id` INT(11) NULL DEFAULT NULL AFTER `tr_type`;

-- Chirag : 2020_02_28 05:00 PM
ALTER TABLE `stock_status_change` ADD `st_relation_id` INT(11) NULL DEFAULT NULL AFTER `tr_id`;

-- Shailesh : 2020_03_02 10:30 AM
ALTER TABLE `lineitems` ADD `item_mrp` DOUBLE NULL DEFAULT NULL AFTER `price`;

-- Chirag : 2020_03_04 10:30 AM
ALTER TABLE `account` ADD `is_bill_wise` INT(11) NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes' AFTER `is_kasar_account`;
ALTER TABLE `invoice_paid_details` ADD `paid_amount` DOUBLE NULL DEFAULT NULL AFTER `invoice_id`;
ALTER TABLE `invoice_paid_details` CHANGE `updated_at` `updated_at` DATETIME NULL DEFAULT NULL;
ALTER TABLE `invoice_paid_details` CHANGE `updated_by` `updated_by` INT(11) NULL DEFAULT NULL;

-- Chirag : 2020_03_07 04:15 PM
ALTER TABLE `transaction_entry` ADD `relation_id` INT(11) NULL DEFAULT NULL AFTER `is_credit_debit`;
ALTER TABLE `account` ADD `account_type` INT(11) NULL DEFAULT NULL COMMENT '1 = cgst, 2 = cgst_interest, 3 = cgst_penalty, 4 = cgst_fees, 5 = cgst_other_charges, 6 = sgst, 7 = sgst_interest, 8 = sgst_penalty, 9 = sgst_fees, 10 = sgst_other_charges, 11 = igst, 12 = igst_interest, 13 = igst_penalty, 14 = igst_fees, 15 = igst_other_charges, 16 = utgst, 17 = utgst_interest, 18 = utgst_penalty, 19 = utgst_fees, 20 = utgst_other_charges' AFTER `opening_balance`;

-- Shailesh : 2020_03_23 09:30 AM
UPDATE `website_modules` SET `title` = 'Report >> Outstanding' WHERE `website_module_id` = 35;

-- Shailesh : 2020_03_24 09:45 AM
ALTER TABLE `user` ADD `is_view_item_history` TINYINT(1) NULL DEFAULT '0' COMMENT '1=Yes, 0=No' AFTER `is_single_line_item`;

-- Shailesh : 2020_05_19 03:00 PM
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

ALTER TABLE `discount_detail`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `discount_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
