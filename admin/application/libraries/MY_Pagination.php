<?php 

class MY_Pagination extends CI_Pagination {

    public function __construct() {
        parent::__construct();
    }

    public function current_place($recordFrom = NULL, $recordTo = NULL) {
    	if($recordFrom == NULL || $recordTo == NULL)
        	return 'showing '.$this->cur_page.' out of '.ceil(($this->total_rows/$this->per_page)). ' results';
    	else
        	return 'showing '.$recordFrom.' - '.$recordTo.' of '.ceil(($this->total_rows)). ' results';

    }
}

?>