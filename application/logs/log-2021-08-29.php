<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-08-29 09:08:33 --> Severity: Warning --> array_walk_recursive() expects parameter 2 to be a valid callback, class 'SimpleXMLElement' does not have a method 'news' /home/patanja1/public_html/emarkit/application/libraries/Pktlib.php 884
ERROR - 2021-08-29 09:47:29 --> Severity: Warning --> array_walk_recursive() expects parameter 2 to be a valid callback, class 'SimpleXMLElement' does not have a method 'news' /home/patanja1/public_html/emarkit/application/libraries/Pktlib.php 884
ERROR - 2021-08-29 10:25:13 --> Module controller failed to run: companies/setup
ERROR - 2021-08-29 10:25:13 --> Module controller failed to run: roles/setup
ERROR - 2021-08-29 10:25:41 --> Module controller failed to run: companies/setup
ERROR - 2021-08-29 10:25:41 --> Module controller failed to run: roles/setup
ERROR - 2021-08-29 10:25:42 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'is_active=true' at line 1 - Invalid query: Select * FROM login where username="8976061690" and otp="895299" is_active=true
ERROR - 2021-08-29 10:25:42 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /home/patanja1/public_html/emarkit/application/modules/login/controllers/Login.php 2708
ERROR - 2021-08-29 14:05:49 --> Severity: Warning --> array_walk_recursive() expects parameter 2 to be a valid callback, class 'SimpleXMLElement' does not have a method 'news' /home/patanja1/public_html/emarkit/application/libraries/Pktlib.php 884
ERROR - 2021-08-29 15:02:56 --> Severity: Warning --> array_walk_recursive() expects parameter 2 to be a valid callback, class 'SimpleXMLElement' does not have a method 'news' /home/patanja1/public_html/emarkit/application/libraries/Pktlib.php 884
ERROR - 2021-08-29 18:29:37 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 2130, 2130, 1796, '2021-08-29', '21-22', 1, NULL, 0, 'E/O/21-22/0007661', '2021-08-29 18:29:37', '2021-08-29 18:29:37')
ERROR - 2021-08-29 18:29:37 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
