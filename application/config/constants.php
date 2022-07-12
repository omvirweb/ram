<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('BASE_URL',$_SERVER["REQUEST_SCHEME"].'://'.$_SERVER['HTTP_HOST'].'/ram/');

define('MASTER_LIST_TABLE_HEIGHT', 350);//master module table list height

//firm settings
define('FIRM_WISE_PRICE', 'mrp');//all transcection base on this price for this firm

//Account Group Constant
define('SALES_ACCOUNT_GROUP_ID', 1);
define('EXPENSE_ACCOUNT_GROUP_ID', 9);
define('FINANCIAL_EXPENSE_ACCOUNT_GROUP_ID', 13);


//Supplier Account Constant
define('SUPPLIER_ACCOUNT_GROUP_ID', 48);

//Dealer Account Constant
define('DEALER_ACCOUNT_GROUP_ID', 49);

//Dealer Account Constant
define('SOFTWARE_START_YEAR', 2015);

// SMS API
define('SEND_SMS_USER_ID', 'Pandya');
define('SEND_SMS_USERPASSWORD', 'pandya@456');
define('SEND_SMS_SENDER_ID', 'RMCiFi');

// SMS Content For OTP
define('SEND_SMS_FOR_OTP', 'Your OTP for Log In is {{otp_no}} for Saas. Do not Share this OTP with anyone.');
define('SEND_SMS_FOR_INVOICE', 'Your Invoice generated of Amount {{amount}}.');

//Cash  Account Constant
define('CASH_IN_HAND_ACC_GROUP_ID', 26);
define('CASH_ACCOUNT_ID', 227);
//Bank  Account Constant
define('BANK_ACC_GROUP_ID', 21);
//Customer Account Constant
define('CUSTOMER_ACC_GROUP_ID', 49);
define('SUNDRY_DEBTORS_ACC_GROUP_ID', 42);

// Invoice Type //
define('INVOICE_TYPE_FREIGHT_ID', 3);
define('INVOICE_TYPE_REIMBURSEMENT_ID', 4);
define('INVOICE_TYPE_INVOICE_ID', 5);

// Item Type Ids
define('ITEM_TYPE_GOODS_ID', 1);
define('ITEM_TYPE_SERVICES_ID', 2);

// Sales, Purchase, Sales Return, Purchase Return Item Id
define('SALES_INVOICE_ITEM_ID',221);
define('PURCHASE_INVOICE_ITEM_ID',220);
define('CREDIT_NOTE_ITEM_ID',219);
define('DEBIT_NOTE_ITEM_ID',218);

define('SALES_BILL_ACCOUNT_GROUP_ID',50);
define('PURCHASE_BILL_ACCOUNT_GROUP_ID',51);
define('CREDIT_NOTE_BILL_ACCOUNT_GROUP_ID',52);
define('DEBIT_NOTE_BILL_ACCOUNT_GROUP_ID',53);

// User Rights //
define("MASTER_COMPANY_ID", 1);
define("MASTER_USER_ID", 48);
define("MASTER_ACCOUNT_COMPANY_ID", 2);
define("MASTER_ID", 3);
define("MASTER_ITEM_GROUP_ID", 4);
define("MASTER_ITEM_TYPE_ID", 5);
define("MASTER_INVOICE_TYPE_ID", 6);
define("MASTER_STATE_ID", 7);
define("MASTER_CITY_ID", 8);
define("MASTER_ACCOUNT_GROUP_ID", 9);
define("MASTER_UNIT_ID", 10);
define("MASTER_IMPORT_ID", 11);
define("MASTER_HSN_ID", 12);
define("MASTER_CATEGORY_ID", 13);
define("MASTER_SUB_CATEGORY_ID", 14);
define("MASTER_USER_RIGHTS_ID", 42);
define("MASTER_ITEM_ID", 15);
define("MODULE_ORDER_PURCHASE_ID", 16);
define("MODULE_ORDER_ID", 17);
define("MODULE_ORDER_TYPE_2_ID", 49);
define("MODULE_PURCHASE_INVOICE_ID", 18);
define("MODULE_SALES_ID", 19);
define("MODULE_SALES_INVOICE_ID", 20);
define("MODULE_SALES_DISCOUNT_ID", 21);
define("MODULE_CREDIT_NOTE_ID", 22);
define("MODULE_DEBIT_NOTE_ID", 23);
define("MODULE_PAYMENT_ID", 24);
define("MODULE_RECEIPT_ID", 25);
define("MODULE_CONTRA_ID", 26);
define("MODULE_JOURNAL_ID", 27);
define("MODULE_REPORT_ID", 28);
define("MODULE_STOCK_REGISTER_ID", 29);
define("MODULE_PURCHASE_REGISTER_ID", 30);
define("MODULE_SALES_REGISTER_ID", 31);
define("MODULE_CREDIT_NOTE_REGISTER_ID", 32);
define("MODULE_DEBIT_NOTE_REGISTER_ID", 33);
define("MODULE_LEDGER_ID", 34);
define("MODULE_SUMMARY_ID", 35);
define("MODULE_BALANCE_SHEET_ID", 36);
define("MODULE_USER_LOG_ID", 37);
define("MODULE_GSTR1_EXCEL_EXPORT_ID", 38);
define("MODULE_GSTR2_EXCEL_EXPORT_ID", 39);
define("MODULE_GSTR_3B_EXCEL_EXPORT_ID", 40);
define("MODULE_BACKUP_DB_ID", 41);
define("MODULE_PROFIT_LOSS_ID", 43);
define("MODULE_TRIAL_BALANCE_ID", 44);
define("MODULE_COMPANY_DOCUMENT_ID", 45);
define("MODULE_SALES_BILL_REGISTER_ID", 46);
define("MODULE_STOCK_STATUS_CHANGE_ID", 47);

define('KG_PACK_UNIT_ID',1);

define('QTY_ADD_ID',1);
define('QTY_LESS_ID',2);

define('STOCK_STATUS_IN_STOCK',1);
define('STOCK_STATUS_IN_WIP',2);
define('STOCK_STATUS_IN_WORK_DONE',3);

define('USER_TYPE_ADMIN','Admin');
define('USER_TYPE_COMPANY','Company');
define('USER_TYPE_USER','User');

// stock_status_change // 
define('IN_STOCK_ID', 1);
define('IN_WIP_ID', 2);
define('IN_WORK_DONE_ID', 3);
define('IN_PURCHASE_ID', 4);
define('IN_SALE_ID', 5);
define('IN_ORDER_ID', 6);
define('IN_SIDE_PRODUCT_ID', 7);
define('IN_SUB_ITEM_ID', 8);

// GST account group
define('GST_ACCOUNT_GROUP', 1);

// CGST Account Type
define('CGST_TYPE_ID', 1);
define('CGST_INTEREST_TYPE_ID', 2);
define('CGST_PENALTY_TYPE_ID', 3);
define('CGST_FEES_TYPE_ID', 4);
define('CGST_OTHER_CHARGES_TYPE_ID', 5);

// SGST Account Type
define('SGST_TYPE_ID', 6);
define('SGST_INTEREST_TYPE_ID', 7);
define('SGST_PENALTY_TYPE_ID', 8);
define('SGST_FEES_TYPE_ID', 9);
define('SGST_OTHER_CHARGES_TYPE_ID', 10);

// IGST Account Type
define('IGST_TYPE_ID', 11);
define('IGST_INTEREST_TYPE_ID', 12);
define('IGST_PENALTY_TYPE_ID', 13);
define('IGST_FEES_TYPE_ID', 14);
define('IGST_OTHER_CHARGES_TYPE_ID', 15);

// UTGST Account Type
define('UTGST_TYPE_ID', 16);
define('UTGST_INTEREST_TYPE_ID', 17);
define('UTGST_PENALTY_TYPE_ID', 18);
define('UTGST_FEES_TYPE_ID', 19);
define('UTGST_OTHER_CHARGES_TYPE_ID', 20);


// VUE App URL
define('VUE_BASE_URL', 'http://192.168.0.119:8081/');

// Laravel App URL
define('LARAVEL_BASE_URL', 'http://localhost/ram-laravel/public/');