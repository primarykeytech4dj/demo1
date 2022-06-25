<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-10-04 16:45:16 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', '1800.0', '1800.0', 2063, '2021-10-04', '21-22', 1, NULL, 0, 'E/O/21-22/0011390', '2021-10-04 16:45:15', '2021-10-04 16:45:15')
ERROR - 2021-10-04 16:45:16 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
