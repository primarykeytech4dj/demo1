<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-05-08 09:13:17 --> Module controller failed to run: companies/setup
ERROR - 2021-05-08 09:13:17 --> Module controller failed to run: roles/setup
ERROR - 2021-05-08 09:13:17 --> Module controller failed to run: companies/setup
ERROR - 2021-05-08 09:13:17 --> Module controller failed to run: roles/setup
ERROR - 2021-05-08 09:14:11 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 1112, 1112, 903, '2021-05-08', '21-22', 1, NULL, 0, 'E/O/21-22/0001012', '2021-05-08 09:14:11', '2021-05-08 09:14:11')
ERROR - 2021-05-08 09:14:11 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home2/primaryk/emarkit.patanjaliudaipur.com/application/libraries/Pktdblib.php 114
