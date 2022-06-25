<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-05-01 00:36:49 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', 1988, 1988, 848, '2021-05-01', '21-22', 1, NULL, 0, 'E/O/21-22/0000815', '2021-05-01 00:36:49', '2021-05-01 00:36:49')
ERROR - 2021-05-01 00:36:49 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home2/primaryk/emarkit.patanjaliudaipur.com/application/libraries/Pktdblib.php 114
