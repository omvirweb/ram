-- Parag : 2022_02_22 11:17 AM

CREATE TABLE `quotation` ( `quotation_id` INT NOT NULL AUTO_INCREMENT , `quotation_no` INT NULL , `account_id` INT NULL COMMENT 'From Account Table' , `quotation_date` DATE NULL , `qty_total` DOUBLE NULL , `pure_amount_total` DOUBLE NULL , `discount_total` DOUBLE NULL , `cgst_amount_total` DOUBLE NULL , `sgst_amount_total` DOUBLE NULL , `igst_amount_total` DOUBLE NULL , `other_charges_total` DOUBLE NULL , `round_off_amount` DOUBLE NULL , `amount_total` DOUBLE NULL , `created_by` INT NULL , `created_at` DATETIME NULL , `updated_by` INT NULL , `updated_at` DATETIME NULL , `quotation_type` TINYINT NULL COMMENT '1=Sell,2=Purchase' , PRIMARY KEY (`quotation_id`), INDEX `account_id` (`account_id`)) ENGINE = InnoDB;

ALTER TABLE `quotation` CHANGE `quotation_type` `quotation_type` TINYINT(4) NULL DEFAULT NULL COMMENT '1=Sell,2=Purchase' AFTER `quotation_id`;

ALTER TABLE `lineitems` ADD `item_code` VARCHAR(255) NULL AFTER `item_mrp`, ADD `internal_code` VARCHAR(255) NULL AFTER `item_code`, ADD `hsn` VARCHAR(255) NULL AFTER `internal_code`, ADD `free_qty` VARCHAR(255) NULL AFTER `hsn`, ADD `no1` VARCHAR(255) NULL AFTER `free_qty`, ADD `no2` VARCHAR(255) NULL AFTER `no1`, ADD `net_rate` VARCHAR(255) NULL AFTER `no2`;

ALTER TABLE `lineitems` CHANGE `module` `module` INT(11) NULL DEFAULT NULL COMMENT 'purchase_invoice = 1, sales_invoice = 2, credit_note = 3, debit_note = 4,\r\nsales_quotation = 5,\r\npurchse_quotation = 6';

-- Parag : 2022_02_22 02:51 PM

CREATE TABLE `company` ( `id` INT NOT NULL AUTO_INCREMENT , `company_name` VARCHAR(255) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `groups` ( `id` INT NOT NULL AUTO_INCREMENT , `group_name` VARCHAR(255) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

-- Parag : 2022_02_22 03:23 PM

ALTER TABLE `item` ADD `company_id` INT NULL AFTER `exempted_from_gst`, ADD `group_id` INT NULL AFTER `company_id`, ADD `item_code` VARCHAR(255) NULL AFTER `group_id`, ADD `internal_code` VARCHAR(255) NULL AFTER `item_code`, ADD INDEX `company_id` (`company_id`), ADD INDEX `group_id` (`group_id`);

-- Parag : 2022_02_23 12:10 PM

CREATE TABLE `dispatch_invoice` (
  `dispatch_invoice_id` int(11) NOT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `dispatch_invoice_no` int(11) DEFAULT NULL,
  `bill_no` varchar(255) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL COMMENT 'From Account Table',
  `cash_customer` varchar(255) DEFAULT NULL,
  `against_account_id` int(11) DEFAULT NULL,
  `dispatch_invoice_date` date DEFAULT NULL,
  `dispatch_invoice_desc` text NOT NULL,
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
  `data_lock_unlock` tinyint(1) NOT NULL DEFAULT 0 COMMENT ' 0 = Unlock, 1 = Lock',
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
  `print_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=Original;1=Duplicate;2=Triplicate',
  `is_shipping_same_as_billing_address` tinyint(1) DEFAULT NULL COMMENT '0=same;1=different',
  `shipping_address` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_created_by` int(11) DEFAULT NULL,
  `user_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `dispatch_invoice`
  ADD PRIMARY KEY (`dispatch_invoice_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `against_account_id` (`against_account_id`),
  ADD KEY `account_id_2` (`account_id`),
  ADD KEY `against_account_id_2` (`against_account_id`);

ALTER TABLE `dispatch_invoice`
  MODIFY `dispatch_invoice_id` int(11) NOT NULL AUTO_INCREMENT;

-- Parag : 2022_02_24 03:55 PM

ALTER TABLE `purchase_invoice` CHANGE `invoice_type` `invoice_type` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1=Order;2=Purchase,3=Order Type 2, 4=Sales Order';

-- Parag : 2022_03_01 04:27 PM

CREATE TABLE `sites` ( `site_id` INT NOT NULL AUTO_INCREMENT , `site_name` VARCHAR(255) NOT NULL , `created_at` DATETIME NOT NULL , `created_by` INT NOT NULL , `updated_at` DATETIME NOT NULL , `updated_by` INT NOT NULL , PRIMARY KEY (`site_id`)) ENGINE = InnoDB;

ALTER TABLE `transaction_entry` ADD `site_id` INT NOT NULL AFTER `amount`, ADD INDEX `site_id` (`site_id`);

-- Parag : 2022_03_07 06:21 PM

ALTER TABLE `purchase_invoice` ADD `vehicle_no` VARCHAR(255) NULL AFTER `bill_no`;

ALTER TABLE `purchase_invoice` CHANGE `invoice_type` `invoice_type` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1=Order;2=Purchase,3=Order Type 2, 4=Material In ';

ALTER TABLE `lineitems` CHANGE `module` `module` INT(11) NULL DEFAULT NULL COMMENT 'purchase_invoice = 1, sales_invoice = 2, credit_note = 3, debit_note = 4, sales_quotation = 5, purchse_quotation = 6, 7=Dispatch, 8=Material In';

-- Parag : 2022_03_08 10:24 AM

ALTER TABLE `lineitems` ADD `gst` DOUBLE NOT NULL DEFAULT '0' AFTER `note`, ADD `site_id` INT NULL AFTER `gst`, ADD INDEX `site_id` (`site_id`);

--Parag : 2022_03_12 11:27 AM

ALTER TABLE `purchase_invoice` ADD `driver_name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `vehicle_no`;

--Parag : 2022_03_21 11:29 AM

ALTER TABLE `hsn` ADD `gst_per` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `hsn`;

--Parag : 2022_03_25 16:15 PM

ALTER TABLE `settings` CHANGE `setting_id` `setting_id` INT(11) NOT NULL AUTO_INCREMENT;
INSERT INTO `settings` (`setting_id`, `setting_key`, `setting_value`, `created_at`, `updated_at`, `user_created_by`, `user_updated_by`) VALUES (NULL, 'sales_terms', '1. Our risk and responsibility ceases as soon as the goods leave our premises.\r\n2. Goods once sold will not be taken back.', '2022-03-25 13:46:04.000000', '2022-03-25 13:46:04.000000', NULL, NULL);

--Parag : 2022_03_25 19:15 PM
ALTER TABLE `sites` ADD `site_address` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL AFTER `site_name`;

--Parag : 2022_03_26 11:22 AM
ALTER TABLE `account` ADD `bank_name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL AFTER `is_bill_wise`, ADD `bank_acc_name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `bank_name`, ADD `bank_branch` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `bank_acc_name`, ADD `bank_city` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `bank_branch`, ADD `bank_ac_no` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `bank_city`, ADD `rtgs_ifsc_code` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `bank_ac_no`;

--Parag : 2022_03_26 12:37 PM
ALTER TABLE `purchase_invoice` ADD `our_bank_id` INT NOT NULL AFTER `shipping_address`, ADD INDEX (`our_bank_id`);

--Parag : 2022_03_28 06:45 PM
ALTER TABLE `sales_invoice` ADD `our_bank_id` INT NOT NULL AFTER `shipping_address`, ADD INDEX (`our_bank_id`);

--Parag : 2022_04_04 06:20 PM
ALTER TABLE `lineitems` ADD `company_id` INT NULL AFTER `site_id`;

--Parag : 2022_04_04 07:06 PM
ALTER TABLE `sales_invoice` ADD `company_id` INT NULL AFTER `our_bank_id`;

--Parag : 2022_04_04 07:09 PM
ALTER TABLE `purchase_invoice` ADD `company_id` INT NULL AFTER `our_bank_id`;

--Parag : 2022_04_04 07:12 PM
ALTER TABLE `credit_note` ADD `company_id` INT NULL AFTER `data_lock_unlock`;

--Parag : 2022_04_06 12:01 PM
ALTER TABLE `sales_invoice` ADD `sales_type` INT NULL COMMENT '1=sales , 2=sales2' AFTER `invoice_type`;

--Parag : 2022_04_06 03:06 PM
UPDATE `sales_invoice` SET `sales_type` = '1';

--Parag : 2022_04_06 05:11 PM
ALTER TABLE `sales_invoice` ADD `total_pf_amount` FLOAT NULL AFTER `company_id`, ADD `aspergem_service_charge` FLOAT NULL AFTER `total_pf_amount`;

ALTER TABLE `lineitems` ADD `line_item_des` TEXT NULL AFTER `company_id`;

--Parag : 2022_04_07 11:09 PM
ALTER TABLE `sales_invoice` CHANGE `sales_type` `sales_type` INT(11) NULL DEFAULT NULL COMMENT '1=sales , 2=sales2, 3=sales3';

--Parag : 2022_04_07 12:31 PM
ALTER TABLE `sales_invoice` ADD `sales_subject` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `aspergem_service_charge`, ADD `sales_note` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `sales_subject`;

--Parag : 2022_04_07 04:47 PM
ALTER TABLE lineitems DROP FOREIGN KEY Fk_ItemLineItem;
ALTER TABLE `sales_invoice` ADD `book_no` VARCHAR(255) NULL AFTER `aspergem_service_charge`, ADD `ship_party_name` VARCHAR(255) NULL AFTER `book_no`, ADD `ship_party_address` TEXT NULL AFTER `ship_party_name`, ADD `ship_party_gstin` VARCHAR(255) NULL AFTER `ship_party_address`, ADD `ship_party_state` VARCHAR(255) NULL AFTER `ship_party_gstin`, ADD `ship_party_code` VARCHAR(255) NULL AFTER `ship_party_state`;

--Parag : 2022_04_12 05:27 PM
ALTER TABLE `sales_invoice` ADD `prof_tax` FLOAT NULL DEFAULT NULL AFTER `user_updated_by`;

--Parag : 2022_05_09 04:23 PM
ALTER TABLE `lineitems` ADD `price_including_gst` DOUBLE NOT NULL DEFAULT '0' AFTER `price`;
--Parag : 2022_05_09 04:23 PM
ALTER TABLE `sales_invoice` ADD `sales_rate_type` ENUM("0","1") NULL DEFAULT NULL COMMENT '1=Excluding GST, 2=Including GST' AFTER `sales_type`;

--Parag : 2022_06_22 04:54 PM
CREATE TABLE `ram`.`quotation_docs` ( `quotation_docs_id` INT NOT NULL AUTO_INCREMENT ,`quotation_id` INT NULL , `doc_name` VARCHAR(255) NULL , `created_at` DATETIME NULL , `created_by` INT NULL , `user_created_by` INT NULL , `updated_at` DATETIME NULL , `updated_by` INT NULL , `user_updated_by` INT NULL , PRIMARY KEY (`quotation_docs_id`)) ENGINE = InnoDB;

--Parag : 2022_07_05 04:09 PM
ALTER TABLE `quotation_docs` ADD `doc_desc` VARCHAR(255) NULL DEFAULT NULL AFTER `doc_name`;

--Parag : 2022_07_15 12:46 PM
ALTER TABLE `lineitems` ADD `rate_type` INT NULL DEFAULT '1' COMMENT '1=Excluding GST, 2=Including GST' AFTER `price`;

--Parag : 2022_08_31 05:58 PM
ALTER TABLE `quotation` ADD `site_id` INT NULL AFTER `account_id`;

--Parag : 2022_09_02 06:20 PM
ALTER TABLE `sales_invoice` ADD `site_id` INT NULL AFTER `account_id`;

--Parag : 2022_09_05 06:20 PM
ALTER TABLE `lineitems` ADD `l` DOUBLE NULL AFTER `gst`, ADD `b` DOUBLE NULL AFTER `l`, ADD `d` DOUBLE NULL AFTER `b`;