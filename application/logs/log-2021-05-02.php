<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-05-02 00:17:54 --> Module controller failed to run: companies/setup
ERROR - 2021-05-02 00:17:54 --> Module controller failed to run: roles/setup
ERROR - 2021-05-02 00:18:14 --> Module controller failed to run: companies/setup
ERROR - 2021-05-02 00:18:14 --> Module controller failed to run: roles/setup
ERROR - 2021-05-02 00:18:14 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'is_active=true' at line 1 - Invalid query: Select * FROM login where username="7666674264" and otp="960436" is_active=true
ERROR - 2021-05-02 00:18:14 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /home2/primaryk/emarkit.patanjaliudaipur.com/application/modules/login/controllers/Login.php 2708
ERROR - 2021-05-02 10:37:37 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 315, 315, 803, '2021-05-02', '21-22', 1, NULL, 0, 'E/O/21-22/0000830', '2021-05-02 10:37:37', '2021-05-02 10:37:37')
ERROR - 2021-05-02 10:37:37 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home2/primaryk/emarkit.patanjaliudaipur.com/application/libraries/Pktdblib.php 114
ERROR - 2021-05-02 11:27:47 --> Module controller failed to run: companies/setup
ERROR - 2021-05-02 11:27:47 --> Module controller failed to run: roles/setup
ERROR - 2021-05-02 11:28:13 --> Module controller failed to run: companies/setup
ERROR - 2021-05-02 11:28:13 --> Module controller failed to run: roles/setup
ERROR - 2021-05-02 11:28:13 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'is_active=true' at line 1 - Invalid query: Select * FROM login where username="9172073778" and otp="451957" is_active=true
ERROR - 2021-05-02 11:28:13 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /home2/primaryk/emarkit.patanjaliudaipur.com/application/modules/login/controllers/Login.php 2708
ERROR - 2021-05-02 13:17:45 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'and login_id= and account_type like "customers"' at line 1 - Invalid query: Select * from user_roles where user_id= and login_id= and account_type like "customers"
ERROR - 2021-05-02 13:17:45 --> Severity: error --> Exception: Call to a member function num_rows() on bool /home2/primaryk/emarkit.patanjaliudaipur.com/application/helpers/check_login_helper.php 27
