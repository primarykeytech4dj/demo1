<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-10-24 12:50:27 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 1100, 1100, 243, '2021-10-24', '21-22', 1, NULL, 0, 'E/O/21-22/0013624', '2021-10-24 12:50:26', '2021-10-24 12:50:26')
ERROR - 2021-10-24 12:50:27 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
