<?php 
class Blogs_model extends CI_Model {
	private $database;
	private $table;
	function __construct() {
		parent::__construct();
		$this->database = $this->load->database('login', TRUE);
	}

	 // Unique to models with multiple tables
	function set_table($table) {
		$this->table = $table;
	}
	
	// Get table from table property
    function get_table() {
		$table = $this->table;
		return $table;
    }

    // Retrieve all data from database and order by column return query
    function get($order_by) {
		$db = $this->database;
		$table = $this->get_table();
		$db->order_by($order_by);
		$query=$db->get($table);
		return $query;
    }

	// Limit results, then offset and order by column return query
    function get_with_limit($limit, $offset, $order_by) {
		$db = $this->database;
		$table = $this->get_table();
		$db->limit($limit, $offset);
		$db->order_by($order_by);
		$query=$db->get($table);
		return $query;
    }

	// Get where column id is ... return query
    function get_where($id) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where('id', $id);
		$query=$db->get($table);
		//print_r($query->row_array());exit;
		return $query->row_array();
    }

	// Get where custom column is .... return query
    function get_where_custom($col, $value) {
		$db = $this->database;
		$table = $this->get_table();
		$db->where($col, $value);
		$query=$db->get($table);
		//print_r($query->result_array());exit;
		return $query;
    }

	function get_list($id = NULL) {
		$db = $this->database;
		$table = $this->get_table();
		if (empty($id)) {
            $query = $db->get($table);
            return $query->result_array();
        }

        $query = $db->get_where($table, array('id' => $id));
        return $query->row_array();
	}

	function get_blogs_category_list($id=null) {
		$db = $this->database;
		$condition['is_active'] = true;
		
		$db->select('id,category_name')->where('`id` NOT IN (SELECT blogs_category_id from `blogs`)');
		$query = $db->get_where('blogs_categories', $condition);
		//print_r($db->last_query());
		return $query->result_array();
	}

	function get_distinct_categorylist_for_blogs() {
		$condition['is_active'] = true;
		$db = $this->database;
		$table = $this->get_table();
		$db->select(['DISTINCT(blogs_categories.id)', 'blogs_categories.category_name']);
		$db->join('blogs', 'blogs.blogs_category_id = blogs_categories.id');
		$query = $db->get($table);
		return $query->result_array(); 
		}

		function get_category_dropdown_list_for_category($id = NULL) {
		$db = $this->database;
        $table = $this->get_table();
		$condition['is_active'] = TRUE;
		//$db->select('id,category_name');
		$db->select('id', 'DISTINCT(category_name)');
        $query = $db->get_where('blogs_categories', $condition);
        return $query->result_array();
	}


	function get_city_list($id=null) {
		$db = $this->database;
		$condition['blogs_cities.is_active'] = true;
		$condition['blogs_cities.blogs_id'] = $id;
		$db->select(['blogs_cities.*', 'cities.city_name']);
		//$db->select(['blogs.*', 'states.state_name']);
		$db->join('cities', 'cities.id = blogs_cities.city_id');
		//$db->join('states', 'states.id = blogs.state_id', 'left');

		//$db->join()
		//$db->select('id,category_name')->where('`id` NOT IN (SELECT blogs_category_id from `blogs`)');
		$query = $db->get_where('blogs_cities', $condition);
		//print_r($db->last_query());
		return $query->result_array();
	}

	function get_state_list($id=null) {
		$db = $this->database;
		$condition['blogs.is_active'] = true;
		$condition['blogs.id'] = $id;
		$db->select(['blogs.*', 'states.state_name']);
		//$db->select(['blogs.*', 'states.state_name']);
		$db->join('states', 'states.id = blogs.state_id', 'left');
		//$db->join('states', 'states.id = blogs.state_id', 'left');

		//$db->join()
		//$db->select('id,category_name')->where('`id` NOT IN (SELECT blogs_category_id from `blogs`)');
		$query = $db->get_where('blogs', $condition);
		//print_r($db->last_query());
		return $query->result_array();
	}

	function get_category_dropdown_list($id = NULL) {
		$db = $this->database;
		$condition['is_active'] = TRUE;
		$db->select('id,category_name');
        $query = $db->get_where('blogs_categories', $condition);
        return $query->result_array();
	}

	function get_category_list($condition = []){
    	$db = $this->database;
		$table = $this->get_table();
		//$condition[$table.'.is_active'] = true;
//db->join($table.' c2', 'c2.parent_id='.$table.'.id', 'left');
		$db->select($table.'.*, (select category_name from blogs_categories nc where nc.id='.$table.'.parent_id) as parent');
		$db->order_by($table.'.is_active desc, '.$table.'.id DESC');
		//$db->limit(3, 0);
		$query = $db->get_where($table, $condition);
		//print_r($db->last_query());
		return $query->result_array();
    }

	function get_categorylist_for_blogs() {
		$db = $this->database;
		$condition['is_active'] = true;
		//$sql = 'Select id, category_name from blogs_categories where id in (Select distinct blogs_category_id from blogs) union Select id, category_name from blogs_categories where id Not in (Select distinct blogs_category_id from blogs)';
		//$db->select('id,category_name')->where('id not IN(SELECT distinct `parent_id` from `blogs_categories`)');
		$query = $db->get_where('blogs_categories', $condition);
		return $query->result_array();
	}

	function _insert($data) {
		$response['status'] = 'failed';
		$db = $this->database;
		$table = $this->get_table();
		$res = $db->insert($table, $data);
		//print_r($res);
		if($res){
			$response['status'] = 'success';
			$response['id'] = $db->insert_id();
		}else{
			echo $db->_error_message();
		}
   		return  $response;
    }

    function _insert_multiple($data) {
    	//echo '<pre>';print_r($data);
    	$db = $this->database;
    	$table = $this->get_table();
    	$num_rows = $db->insert_batch($table,$data);
    	//echo "inserted";
    	//print_r($db->last_query());exit;
    	return $num_rows;
    }

    function update_blogs_categories($id, $data) {
    	$db = $this->database;
    	$table = $this->get_table();
    	$db->where('id', $id);
    	$update = $db->update('blogs_categories', $data);
    	//print_r($db->last_query());
    	return $update;
    }

   	function _update($id, $data) {
    	$db = $this->database;
    	$table = $this->get_table();
    	$db->where('id', $id);
    	$update = $db->update($table, $data);
    	return $update;
    } 

    function _update_multiple($field, $data) {
		$db = $this->database;
		$table = $this->get_table();
		$updt = $db->update_batch($table, $data, $field);
		return $updt;
	}

	function get_active_list($id = NULL) {
		$db = $this->database;
		$table = $this->get_table();
		$condition['is_active'] = TRUE;
		if (empty($id))
        {
			//print_r("reached here");exit;
            $query = $this->db->get_where('blogs', $condition);
            return $query->result_array();
        }
        $condition['id'] = $id;
        $query = $this->db->get_where('blogs', $condition);
       //print_r($db->last_query());
        return $query->row_array();
	}

	function get_blogs_list($params){
		$db = $this->database;
		$table = $this->get_table();
		$db->select(['blogs.*', 'blogs_categories.category_name', 'concat(login.first_name, " ", login.surname) as user', 'states.state_name']);
		$db->join('blogs_categories', 'blogs_categories.id = blogs.blogs_category_id');
		$db->join('login', 'login.employee_id = blogs.user_id', 'left');
		$db->join('states', 'states.id = blogs.state_id', 'left');
		if(isset($params['condition'])){
			foreach ($params['condition'] as $key => $condition) {
				$db->where($key, $condition);
			}
		}

		if(isset($params['order'])){
			$db->order_by($params['order']);
		}else{
			$db->order_by('id DESC');
		}

		if(isset($params['limit'])){
			$db->limit($params['limit']);
		}else{
			$db->limit(21);
		}
		$query = $db->get($table);
		//echo $db->last_query();
		return $query->result_array(); 
	}

	function delete($id) {

		$db = $this->database;
		$table = $this->get_table();
		$query = $db->delete($table, array('blogs_id' => $id));
		return $query;
    }

    function get_section_wise_category() {
    	$db = $this->database;
		$table = $this->get_table();
		$db->select(['blogs_category_sections.*','sections.title as section_title', 'blogs_categories.category_name', 'concat(login.first_name, " ", login.surname) as user']);
		$db->join('blogs_categories', 'blogs_categories.id = blogs_category_sections.blogs_category_id');
		$db->join('sections', 'sections.id = blogs_category_sections.section_id');
		$db->join('login', 'login.id = blogs.user_id', 'left');

		$db->order_by('blogs.published_on desc');

		$query = $db->get($table);
		return $query->result_array(); 
    }

    function get_category_wise_blogs($key,$value) {

		$db = $this->database;
		$table = $this->get_table();
		$condition[$table.$key] = $value;
 		$db->select(['blogs.*','blogs_categories.category_name', 'concat(login.first_name, " ", login.surname) as user', '(select GROUP_CONCAT(cities.city_name SEPARATOR ", ") from blogs_cities INNER JOIN cities on cities.id = blogs_cities.city_id where blogs_cities.blogs_id = blogs.id group by blogs_cities.blogs_id) as city', 'states.state_name']);
		$db->join('blogs','blogs_categories.id = blogs.blogs_category_id');
		$db->join('states', 'states.id = blogs.state_id', 'left');

		$db->join('login', 'login.id = blogs.user_id', 'left');
		$db->order_by('blogs.id desc');
		$query = $db->get_where($table, array($key=>$value, 'blogs.published_on<'=>date('Y-m-d 23:59:59')));
		//$query = $db->like($table, array($key=>$value));

		//print_r($db->last_query());exit;
		return $query->result_array(); 
	}

	function get_categories_wise_blogs($key, $value) {
    	//echo $key.$value;exit;
    	$db = $this->database;
		$table = $this->get_table();
		$db->select(['blogs.*', 'blogs_categories.category_name', '(select GROUP_CONCAT(cities.city_name SEPARATOR ", ") from blogs_cities INNER JOIN cities on cities.id = blogs_cities.city_id where blogs_cities.blogs_id = blogs.id group by blogs_cities.blogs_id) as city', 'concat(login.first_name, " ", login.surname) as user', 'states.state_name']);
		$db->join('blogs_categories', 'blogs.blogs_category_id = blogs_categories.id');
		$db->join('states', 'states.id = blogs.state_id', 'left');
		$db->join('login', 'login.id = blogs.user_id', 'left');
                $db->order_by('blogs.id desc');
		$query = $db->get_where($table, array($key=>$value));
		//print_r($db->last_query());exit;
		return $query->result_array(); 
    }

    function get_section_wise_blogs($params) {
    	$db = $this->database;
		$table = $this->get_table();
		$db->select(['blogs.*', 'blogs_categories.category_name', 'blogs_categories.category_name as category_slug', 'sections.id as section_id', 'sections.page', 'sections.title as section_title', '(select GROUP_CONCAT(cities.city_name SEPARATOR ", ") from blogs_cities INNER JOIN cities on cities.id = blogs_cities.city_id where blogs_cities.blogs_id = blogs.id group by blogs_cities.blogs_id) as city', 'concat(login.first_name, " ", login.surname) as user']);
		$db->join('blogs_categories', 'blogs.blogs_category_id = blogs_categories.id');
		$db->join('blogs_category_sections', 'blogs_category_sections.blogs_category_id = blogs_categories.id');
		$db->join('login', 'login.id = blogs.user_id', 'left');


		$db->join('sections', 'sections.id = blogs_category_sections.section_id');
		if(isset($params['limit']))
			$db->limit($params['limit']);

		if($params['order'])
			$db->order_by($params['order']);

		$query = $db->get_where($table, $params['condition']);
		//print_r($db->last_query());exit;
		//print_r($query->result_array());
		return $query->result_array(); 
    }

    function get_slugwise_state($key, $value){
    	$db = $this->database;
		$table = $this->get_table();
		$condition['is_active'] = TRUE;
		$condition[$key] = $value;
        $query = $this->db->get_where($table, $condition);
        return $query->row_array();
    }

    function get_state_wise_blogs($params){
    	$db = $this->database;
		$table = $this->get_table();
		$db->select(['blogs.*', 'blogs_categories.category_name', 'states.state_name', '(select GROUP_CONCAT(cities.city_name SEPARATOR ", ") from blogs_cities INNER JOIN cities on cities.id = blogs_cities.city_id where blogs_cities.blogs_id = blogs.id group by blogs_cities.blogs_id) as city', 'concat(login.first_name, " ", login.surname) as user']);
		$db->join('blogs_categories', 'blogs.blogs_category_id = blogs_categories.id');
		$db->join('states', 'states.id = blogs.state_id', 'left');
		$db->join('login', 'login.id = blogs.user_id', 'left');
		if(isset($params['limit']))
			$db->limit($params['limit']);

		if(isset($params['order']))
			$db->order_by($params['order']);

		$query = $db->get_where($table, $params['condition']);
		//print_r($db->last_query());exit;
		//print_r($query->result_array());
		return $query->result_array();
    }

    function multiple_category_blogs($params){
    	$db = $this->database;
		$table = $this->get_table();
		$db->select(['blogs.*', 'blogs_categories.category_name', 'states.state_name', '(select GROUP_CONCAT(cities.city_name SEPARATOR ", ") from blogs_cities INNER JOIN cities on cities.id = blogs_cities.city_id where blogs_cities.blogs_id = blogs.id group by blogs_cities.blogs_id) as city', 'concat(login.first_name, " ", login.surname) as user']);
		$db->join('blogs_categories', 'blogs.blogs_category_id = blogs_categories.id', 'inner');
		$db->join('states', 'states.id = blogs.state_id', 'left');
		$db->join('login', 'login.id = blogs.user_id', 'left');
		if(isset($params['limit']))
			$db->limit($params['limit']);

		if(isset($params['order']))
			$db->order_by($params['order']);

		if(isset($params['IN']))
			$db->where_in('blogs_categories.category_name', $params['IN']['blogs_category']);
		
		$query = $db->get_where($table, $params['condition']);
		//print_r($db->last_query());exit;
		//print_r($query->result_array());
		return $query->result_array();
    }
}
