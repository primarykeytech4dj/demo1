<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-10-25 12:32:23 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 3200, 3200, 204, '2021-10-25', '21-22', 1, NULL, 0, 'E/O/21-22/0013707', '2021-10-25 12:32:23', '2021-10-25 12:32:23')
ERROR - 2021-10-25 12:32:23 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
ERROR - 2021-10-25 12:33:14 --> Query error: Duplicate entry 'E/O/21-22/0013708' for key 'order_code' - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 3200, 3200, 204, '2021-10-25', '21-22', 1, 144, 144, 'E/O/21-22/0013708', '2021-10-25 12:33:13', '2021-10-25 12:33:13')
ERROR - 2021-10-25 12:33:14 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
ERROR - 2021-10-25 14:43:43 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', '525.0', '525.0', 478, '2021-10-25', '21-22', 1, NULL, 0, 'E/O/21-22/0013741', '2021-10-25 14:43:43', '2021-10-25 14:43:43')
ERROR - 2021-10-25 14:43:43 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
