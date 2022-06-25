<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-09-20 07:37:16 --> Module controller failed to run: companies/setup
ERROR - 2021-09-20 07:37:16 --> Module controller failed to run: roles/setup
ERROR - 2021-09-20 07:37:18 --> Module controller failed to run: companies/setup
ERROR - 2021-09-20 07:37:18 --> Module controller failed to run: roles/setup
ERROR - 2021-09-20 07:37:18 --> Module controller failed to run: menus/get_rolewise_menus
ERROR - 2021-09-20 10:27:11 --> Module controller failed to run: companies/setup
ERROR - 2021-09-20 10:27:11 --> Module controller failed to run: roles/setup
ERROR - 2021-09-20 10:27:35 --> Module controller failed to run: companies/setup
ERROR - 2021-09-20 10:27:35 --> Module controller failed to run: roles/setup
ERROR - 2021-09-20 10:27:35 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'is_active=true' at line 1 - Invalid query: Select * FROM login where username="9987392121" and otp="535053" is_active=true
ERROR - 2021-09-20 10:27:35 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /home/patanja1/public_html/emarkit/application/modules/login/controllers/Login.php 2708
ERROR - 2021-09-20 14:50:06 --> Module controller failed to run: companies/setup
ERROR - 2021-09-20 14:50:06 --> Module controller failed to run: roles/setup
ERROR - 2021-09-20 14:50:29 --> Module controller failed to run: companies/setup
ERROR - 2021-09-20 14:50:29 --> Module controller failed to run: roles/setup
ERROR - 2021-09-20 14:50:29 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'is_active=true' at line 1 - Invalid query: Select * FROM login where username="9284792325" and otp="175782" is_active=true
ERROR - 2021-09-20 14:50:29 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /home/patanja1/public_html/emarkit/application/modules/login/controllers/Login.php 2708
ERROR - 2021-09-20 20:35:36 --> Severity: Warning --> array_walk_recursive() expects parameter 2 to be a valid callback, class 'SimpleXMLElement' does not have a method 'news' /home/patanja1/public_html/emarkit/application/libraries/Pktlib.php 884
ERROR - 2021-09-20 20:40:30 --> Module controller failed to run: companies/setup
ERROR - 2021-09-20 20:40:30 --> Module controller failed to run: roles/setup
ERROR - 2021-09-20 22:33:44 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 2698, 2698, 266, '2021-09-20', '21-22', 1, NULL, 0, 'E/O/21-22/0009876', '2021-09-20 22:33:44', '2021-09-20 22:33:44')
ERROR - 2021-09-20 22:33:45 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
