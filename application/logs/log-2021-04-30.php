<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-04-30 10:30:18 --> Module controller failed to run: companies/setup
ERROR - 2021-04-30 10:30:18 --> Module controller failed to run: roles/setup
ERROR - 2021-04-30 10:30:43 --> Module controller failed to run: companies/setup
ERROR - 2021-04-30 10:30:43 --> Module controller failed to run: roles/setup
ERROR - 2021-04-30 10:30:43 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'is_active=true' at line 1 - Invalid query: Select * FROM login where username="8850307053" and otp="519355" is_active=true
ERROR - 2021-04-30 10:30:43 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /home2/primaryk/emarkit.patanjaliudaipur.com/application/modules/login/controllers/Login.php 2708
ERROR - 2021-04-30 13:46:51 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', '960.0', '960.0', 253, '2021-04-30', '21-22', 1, NULL, 0, 'E/O/21-22/0000798', '2021-04-30 13:46:51', '2021-04-30 13:46:51')
ERROR - 2021-04-30 13:46:51 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home2/primaryk/emarkit.patanjaliudaipur.com/application/libraries/Pktdblib.php 114
ERROR - 2021-04-30 20:17:32 --> Module controller failed to run: companies/setup
ERROR - 2021-04-30 20:17:32 --> Module controller failed to run: roles/setup
