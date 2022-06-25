<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-05-14 09:37:21 --> Module controller failed to run: companies/setup
ERROR - 2021-05-14 09:37:21 --> Module controller failed to run: roles/setup
ERROR - 2021-05-14 09:38:20 --> Module controller failed to run: companies/setup
ERROR - 2021-05-14 09:38:20 --> Module controller failed to run: roles/setup
ERROR - 2021-05-14 09:38:20 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'is_active=true' at line 1 - Invalid query: Select * FROM login where username="9136099292" and otp="867800" is_active=true
ERROR - 2021-05-14 09:38:20 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /home2/primaryk/emarkit.patanjaliudaipur.com/application/modules/login/controllers/Login.php 2708
ERROR - 2021-05-14 10:22:52 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 290, 290, 102, '2021-05-14', '21-22', 1, NULL, 0, 'E/O/21-22/0001312', '2021-05-14 10:22:52', '2021-05-14 10:22:52')
ERROR - 2021-05-14 10:22:52 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home2/primaryk/emarkit.patanjaliudaipur.com/application/libraries/Pktdblib.php 114
ERROR - 2021-05-14 14:36:07 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'and login_id= and account_type like "customers"' at line 1 - Invalid query: Select * from user_roles where user_id= and login_id= and account_type like "customers"
ERROR - 2021-05-14 14:36:07 --> Severity: error --> Exception: Call to a member function num_rows() on bool /home2/primaryk/emarkit.patanjaliudaipur.com/application/helpers/check_login_helper.php 27
ERROR - 2021-05-14 14:36:11 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'and login_id= and account_type like "customers"' at line 1 - Invalid query: Select * from user_roles where user_id= and login_id= and account_type like "customers"
ERROR - 2021-05-14 14:36:11 --> Severity: error --> Exception: Call to a member function num_rows() on bool /home2/primaryk/emarkit.patanjaliudaipur.com/application/helpers/check_login_helper.php 27
ERROR - 2021-05-14 18:28:34 --> Module controller failed to run: companies/setup
ERROR - 2021-05-14 18:28:34 --> Module controller failed to run: roles/setup
ERROR - 2021-05-14 18:28:35 --> Module controller failed to run: companies/setup
ERROR - 2021-05-14 18:28:35 --> Module controller failed to run: roles/setup
ERROR - 2021-05-14 18:29:06 --> Module controller failed to run: companies/setup
ERROR - 2021-05-14 18:29:06 --> Module controller failed to run: roles/setup
ERROR - 2021-05-14 18:29:06 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'is_active=true' at line 1 - Invalid query: Select * FROM login where username="9820473690" and otp="889119" is_active=true
ERROR - 2021-05-14 18:29:06 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /home2/primaryk/emarkit.patanjaliudaipur.com/application/modules/login/controllers/Login.php 2708
