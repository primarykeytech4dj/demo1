<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-10-28 01:04:55 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 2107, 2107, 848, '2021-10-28', '21-22', 1, NULL, 0, 'E/O/21-22/0014079', '2021-10-28 01:04:55', '2021-10-28 01:04:55')
ERROR - 2021-10-28 01:04:55 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
