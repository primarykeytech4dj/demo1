<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package     CodeIgniter
 * @author      PKT
 * @copyright   Copyright (c) 2017, Primary Key Technologies.
 * @license     
 * @link        http://www.primarykey.in
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Anil Labs core CodeIgniter class
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    PKT's Library
 * @author      Deepak Jha
 * @link        http://www.primarykey.in
 */

class Mlm_lib {
    protected $CI;
    public function __construct($params = array())
    {
        $this->CI =& get_instance();

        $this->CI->load->helper('url');
        $this->CI->config->item('base_url');
        $this->CI->load->database('login', TRUE);
        $this->CI->load->library('pktlib');
    }

    function checkPackage($invoiceAmt=0) {

      $sql = 'Select * from packages where '.$invoiceAmt.' BETWEEN min_amt AND max_amt';
      $packages = $this->CI->pktlib->custom_query($sql);
      return $packages[0];
    }

    function sponsor($data) {
      //print_r($data);
      $packages = $this->checkPackage();
      if(!empty($packages)>0)
        $data['package_id'] = $packages['id'];
      else
        $data['package_id'] = 1;

      $data['created'] = date('Y-m-d H:i:s');
      $data['modified'] = date('Y-m-d H:i:s');
      //print_r($data);
      $this->CI->pktlib->set_table('customer_references');
      $checkSponsor = $this->CI->pktlib->get_where_custom('customer_id', $data['customer_id']);
      //print_r($checkSponsor->row_array());exit;
      if(count($checkSponsor->row_array())>0){
        $sponsor = $checkSponsor->row_array();
        return $sponsor;
      }else{
        $customerReferences = $this->CI->pktlib->_insert($data);
        return $customerReferences;
      }
    }

    function get_sponsor($data){
      //print_r($data);
      $punder = $this->CI->pktlib->createquery([
        'table'=>'customer_references',
        'conditions'=>[
          //'introducer_type'=>$data['user_type'], 
          'customer_id'=>$data['customer_id'],
        ]
      ]);
      //print_r($punder);
      return $punder[0];
    }

    function get_user_package($data){
      //print_r($data);
      $conditions = [];
      foreach ($data as $key => $value) {
        $conditions[$key] = $value;
      }
      $userPackage = $this->CI->pktlib->createquery([
        'table'=>'customer_references',
        'conditions'=>$conditions
      ]);
      //print_r($userPackage);
      return $userPackage[0];
    }

    function punderbinary($data) {
      //print_r($data);
      $data['created'] = date('Y-m-d H:i:s');
      $data['modified'] = date('Y-m-d H:i:s');
      $punder = $this->CI->pktlib->createquery([
        'table'=>'person_under',
        'conditions'=>[
          'parent_id'=>$data['parent_id'], 
          'user_type'=>$data['user_type'],
          'placement'=>$data['placement']
        ]
      ]);
      //print_r($punder);exit;
      if(!$punder){ 
         $parent = $this->get_parent($data['user_type'], $data['parent_id'], $data['placement']);
        if(!$parent)
          $data['level'] = 1;
        else
          $data['level'] = $parent['level']+1;

        $this->CI->pktlib->set_table('person_under');
        $customerPackages = $this->CI->pktlib->_insert($data);
      } else if (count($punder)==1){
        //if($punder[0]['customer_id']) 
        /*print_r($data);
        print_r($punder[0]);*/
        if($data['customer_id']==$punder[0]['customer_id'])
          return true;
        
        $data['parent_id'] = $punder[0]['customer_id'];
        //if()
        $this->binaryRecursion($data);
      } 
    }

    function binaryRecursion($data){
      //print_r($data);
      $data['created'] = date('Y-m-d H:i:s');
      $data['modified'] = date('Y-m-d H:i:s');
      $punder = $this->CI->pktlib->createquery([
        'table'=>'person_under',
        'conditions'=>[
          'parent_id'=>$data['parent_id'], 
          'user_type'=>$data['user_type'],
          'placement'=>$data['placement']
        ]
      ]);
      //print_r($punder);exit;
      if(!$punder){
        $parent = $this->get_parent($data['user_type'], $data['parent_id'], $data['placement']);
        //print_r($parent);
        if(!$parent)
          $data['level'] = 1;
        else
          $data['level'] = $parent['level']+1;

        $this->CI->pktlib->set_table('person_under');
        $customerPackages = $this->CI->pktlib->_insert($data);
      } else if (count($punder)==1){

        if($punder[0]['customer_id']==$data['customer_id'])
          return true;

        $data['parent_id'] = $punder[0]['customer_id'];
        $this->binaryRecursion($data);
       
      }
      return true; 

    }

    function update_Package($customerId, $invoiceAmt){
      $this->CI->pktlib->set_table('customer_references');
      $query = $this->CI->pktlib->get_where_custom('customer_id', $customerId);
      $customerPackage = $query->row_array();

      $checkPackage = $this->checkPackage($invoiceAmt);
      //print_r($checkPackage);
      //echo $checkPackage['id']." ".$customerPackage['package_id'];exit;
      if($checkPackage['id']>$customerPackage['package_id']){
        //echo "reached inside";
        $this->CI->pktlib->set_table('customer_references');

        $updateCustomerPackageArray = ['package_id'=>$checkPackage['id'], 'modified'=>date('Y-m-d H:i:s')];
        return $this->CI->pktlib->_update($customerPackage['id'], $updateCustomerPackageArray);
      }
      return true;
    }

    function get_parent($type, $customerId, $placement = ''){
      //echo $customerId;
      $parent = $this->CI->pktlib->createquery([
        'table'=>'person_under',
        'conditions'=>[
          'customer_id'=>$customerId, 
          'user_type'=>$type,
          //'placement'=>$placement
        ]
      ]);
      /*echo "parent";
      print_r($parent);*/
      if(count($parent))
        return $parent[0];
    }

    function income_type() {
      $income = $this->CI->pktlib->createquery([
        'conditions'=>['is_active'=>true],
        'table'=>'mlm_income_type'
      ]);
      return $income;
    }

    function amountConversion($param1, $param2, $type) {
      //echo $param1." ".$param2." ".$type;
      //param 1 should be total amount, param2 must be percent or amount to be converted, $type must be percent or Rs 
      //echo $param1." ".$param2." ".$type;
      if($type=='percent'){
        return ($param2/100.00)* $param1;
      }elseif($type=='rupees'){
        return ($param2/$param1)* 100.00;

      }
    }

    function checkCaping($data) {
      $capping = $this->CI->pktlib->createquery([
        'table'=>'mlm_income_type_cappings',
        'conditions'=>[
          'mlm_income_type_id'=>$data['mlm_income_type_id'], 
          'package_id'=>$data['package_id'],
          //'placement'=>$placement
        ]
      ]);

      return $capping[0];
    }

    function fill_wallet($data) {
      $data['created'] = date('Y-m-d H:i:s');
      $data['modified'] = date('Y-m-d H:i:s');
      $this->CI->pktlib->set_table('wallets');
      //print_r($data);
      return $this->CI->pktlib->_insert($data);
    }

    function fill_wallet_multiple($data) {
      
      $this->CI->pktlib->set_table('wallets');
      //print_r($data);
      return $this->CI->pktlib->_insert_multiple($data);
    }

    function directIncome($order, $income) {

      $checkWallet = $this->CI->pktlib->custom_query('select * from wallets where income_type=1 and income_sub_type_id=0 and trans_id='.$order['id'].' and trans_type="orders"');
      if(count($checkWallet)>0)
        return TRUE;
      $sponsor = $this->get_sponsor(['customer_id'=>$order['customer_id']]);
      //print_r($this->get_user_package(['introducer_id'=>$sponsor['introducer_id']]));exit;
      $checkUserPackage = $this->get_user_package(['customer_id'=>$sponsor['introducer_id']]);
        //print_r($checkUserPackage);//exit;
        if($checkUserPackage['package_id']==1)
            return TRUE;
            
        $capping = $this->checkCaping(['mlm_income_type_id'=>$income['id'], 'package_id'=>$checkUserPackage['package_id']]);

      if(!$capping){
      } else {
        if($order['amount_after_tax']>$capping['capping_amount']){
          $order['amount_after_tax'] = $capping['capping_amount'];
        }
      }
      $incomeAmt = $this->amountConversion($order['amount_after_tax'], $income['distribution'], $income['distribution_type']);
      $walletArray = [
        'wallet_type'=>'Direct Income',
        'user_id' =>  $sponsor['introducer_id'],
        'user_type' => $sponsor['introducer_type'],
        'income_type' => $income['id'],
        'trans_id' => $order['id'],
        'amount' => $incomeAmt-($incomeAmt*0.05),
        'remark' => 'Direct Income',
        'processing_fee' => 5,
        'is_reedemable' => ($checkUserPackage['package_id']>1)?true:false,
      ];
      //print_r($walletArray);//exit;
      $fillWallet = $this->fill_wallet($walletArray);
      if($fillWallet['status']=='success')
        return json_encode(['status'=>'success', 'refrence_no'=>$fillWallet['id'], 'wallet'=>$walletArray, 'msg'=>'successfull']);
      else
        return json_encode(['status'=>'error', 'msg'=>$fillWallet['message']]);
     
    }

    function Daily($incomeAmt, $cappingAmt, $incomeId, $userId, $orderDate){
      $orderDate = date('Y-m-d');
      $cappingIncome = 0;
      //echo $incomeAmt." ".$cappingAmt." ".$incomeId." ".$userId.'<br>';
      //echo "SELECT sum(amount) as income from wallets inner join orders on orders.id=wallets.trans_id AND orders.date like '".$orderDate."' where wallets.user_id = '".$userId."' and wallets.trans_type='orders' AND wallets.income_type=".$incomeId;
      $query = $this->CI->pktlib->custom_query("SELECT sum(amount) as income from wallets inner join orders on orders.id=wallets.trans_id AND orders.date like '".$orderDate."' where wallets.user_id = '".$userId."' and wallets.trans_type='orders' AND wallets.income_type=".$incomeId);
      $totalIncome = $incomeAmt+(int)$query[0]['income'];
      /*print_r($totalIncome);
      exit;*/
      if($totalIncome>$cappingAmt){
        $cappingIncome = $incomeAmt-($totalIncome-$cappingAmt);
      }else{
        $cappingIncome = $incomeAmt;

      }
      return $cappingIncome;
    }

    function Monthly($incomeAmt, $cappingAmt, $incomeId, $userId, $orderDate){
      $cappingIncome = 0;
      $date = explode('-', $orderDate);
      //echo $incomeAmt." ".$cappingAmt." ".$incomeId." ".$userId;
      $query = $this->CI->pktlib->custom_query("SELECT sum(amount) as income from wallets inner join orders on orders.id=wallets.trans_id AND orders.date like '".$date[0]."-".$date[1]."-%' where wallets.user_id = '".$userId."' AND wallets.income_type=".$incomeId);
      //print_r($query[0]);
      $totalIncome = $incomeAmt+$query[0]['income'];
      if($totalIncome>$cappingAmt){
        $cappingIncome = $incomeAmt-($totalIncome-$cappingAmt);
      }else{
        $cappingIncome = $incomeAmt;

      }
      return $cappingIncome;
    }

    function binaryIncomeRecursion($order, $income, $customerId) {
      /*print_r($order);
      print_r($income);*/
      $parent = $this->get_parent('customers', $customerId, '');
      if($parent['parent_id']==0)
        return TRUE;
      $incomeAmt = $this->amountConversion($order['amount_after_tax'], $income['distribution'], $income['distribution_type']);
      $checkUserPackage = $this->get_user_package(['customer_id'=>$parent['parent_id']]);

      $capping = $this->checkCaping(['mlm_income_type_id'=>$income['id'], 'package_id'=>$checkUserPackage['package_id']]);
      //print_r($capping);exit;
      if(!$capping){
      } else {
        switch ($capping['frequency']) {
          case 'Daily':
            $incomeAmt = $this->Daily($incomeAmt, $capping['capping_amount'], $income['id'], $parent['parent_id'], $order['date']);
            break;
          case 'Monthly':
            $incomeAmt = $this->Monthly($incomeAmt, $capping['capping_amount'], $income['id'], $parent['parent_id'], $order['date']);
            break;
          
          default:
            # code...
            break;
        }
        
      }

      $walletArray = [
        'user_id' =>  $parent['parent_id'],
        'user_type' => $parent['user_type'],
        'income_type' => $income['id'],
        'trans_id' => $order['id'],
        'amount' => $incomeAmt/2,
        'wallet_type' => 'Non Shopping Wallet',
        'is_reedemable' => false,
      ];
      
      $fillWallet = $this->fill_wallet($walletArray);
      $query = $this->CI->pktlib->custom_query("select * from person_under WHERE parent_id='".$parent['parent_id']."'");

      $minPackageWallet = 0;
      if(count($query)==2){

        $customerPackageLeft = $this->CI->pktlib->custom_query('select * from customer_references where customer_id='.$query[0]['customer_id']);

        $customerPackageRight = $this->CI->pktlib->custom_query('select * from customer_references where customer_id='.$query[1]['customer_id']);
        $packageId = 0;
        $updateWallet = 0;
        if(($customerPackageLeft[0]['package_id']==$customerPackageRight[0]['package_id']) && $customerPackageLeft[0]['package_id']>1){
          $updateWallet = $this->CI->pktlib->_update($fillWallet['id'], ['is_reedemable'=>true]);

        }elseif (($customerPackageLeft[0]['package_id']<$customerPackageRight[0]['package_id']) && $customerPackageLeft[0]['package_id']>1){
          $packageId = $customerPackageLeft[0]['package_id'];
          $packageDetails = $this->CI->pktlib->custom_query('Select * from packages where id='.$packageId);
          $orderLeft = $this->CI->pktlib->custom_query('Select * from orders where customer_id="'.$customerPackageLeft[0]['customer_id'].'" AND  amount_after_tax BETWEEN '.$packageDetails[0]['min_amt'].' AND '.$packageDetails[0]['max_amt']);
          //echo '<br>Update wallets set is_reedemable=true where user_id='.$parent['parent_id'].' AND trans_id='.$orderLeft[0]['id'];

          $updateWallet = $this->CI->pktlib->custom_query_independent_of_id('Update wallets set is_reedemable=true where user_id='.$parent['parent_id'].' AND trans_id='.$orderLeft[0]['id']);
        } elseif (($customerPackageLeft[0]['package_id']>$customerPackageRight[0]['package_id']) && $customerPackageRight[0]['package_id']>1){
          $packageId = $customerPackageRight[0]['package_id'];
          $packageDetails = $this->CI->pktlib->custom_query('Select * from packages where id='.$packageId);
          $orderRight = $this->CI->pktlib->custom_query('Select * from orders where customer_id="'.$customerPackageRight[0]['customer_id'].'" AND  amount_after_tax BETWEEN '.$packageDetails[0]['min_amt'].' AND '.$packageDetails[0]['max_amt']);
          //echo '<br>Update wallets set is_reedemable=true where user_id='.$parent['parent_id'].' AND trans_id='.$orderRight[0]['id'];
          $updateWallet = $this->CI->pktlib->custom_query_independent_of_id('Update wallets set is_reedemable=true where user_id='.$parent['parent_id'].' AND trans_id='.$orderRight[0]['id']);
        }
        
      }
      $this->binaryIncomeRecursion($order, $income, $parent['parent_id']);

      return TRUE;
    }

    function binaryIncome($order, $income, $customerId) {
      $parent = $this->get_parent('customers', $customerId, '');

      if($parent['parent_id']==0)
        return TRUE;

      $incomeAmt = $this->amountConversion($order['amount_after_tax'], $income['distribution'], $income['distribution_type']);
      $checkUserPackage = $this->get_user_package(['customer_id'=>$parent['parent_id']]);

      $capping = $this->checkCaping(['mlm_income_type_id'=>$income['id'], 'package_id'=>$checkUserPackage['package_id']]);
      //print_r($capping);exit;
      if(!$capping){
      } else {
        switch ($capping['frequency']) {
          case 'Daily':
            $incomeAmt = $this->Daily($incomeAmt, $capping['capping_amount'], $income['id'], $parent['parent_id'], $order['date']);
            break;
          case 'Monthly':
            $incomeAmt = $this->Monthly($incomeAmt, $capping['capping_amount'], $income['id'], $parent['parent_id'], $order['date']);
            break;
          
          default:
            # code...
            break;
        }
        
      }

      $walletArray = [
        'user_id' =>  $parent['parent_id'],
        'user_type' => $parent['user_type'],
        'income_type' => $income['id'],
        'trans_id' => $order['id'],
        'amount' => $incomeAmt/2,
        'wallet_type' => 'Non Shopping Wallet',
        'is_reedemable' => false,
      ];
      
      $fillWallet = $this->fill_wallet($walletArray);
      $query = $this->CI->pktlib->custom_query("select * from person_under WHERE parent_id='".$parent['parent_id']."'");
      //echo 'packageID';
      //print_r($query);exit;
      $minPackageWallet = 0;
      if(count($query)==2){

        $customerPackageLeft = $this->CI->pktlib->custom_query('select * from customer_references where customer_id='.$query[0]['customer_id']);

        $customerPackageRight = $this->CI->pktlib->custom_query('select * from customer_references where customer_id='.$query[1]['customer_id']);
        $packageId = 0;
        $updateWallet = 0;
        if(($customerPackageLeft[0]['package_id']==$customerPackageRight[0]['package_id']) && $customerPackageLeft[0]['package_id']>1){
          $updateWallet = $this->CI->pktlib->_update($fillWallet['id'], ['is_reedemable'=>true]);

        } elseif (($customerPackageLeft[0]['package_id']<$customerPackageRight[0]['package_id']) && $customerPackageLeft[0]['package_id']>1){
          $packageId = $customerPackageLeft[0]['package_id'];
          $packageDetails = $this->CI->pktlib->custom_query('Select * from packages where id='.$packageId);
          $orderLeft = $this->CI->pktlib->custom_query('Select * from orders where customer_id="'.$customerPackageLeft[0]['customer_id'].'" AND  amount_after_tax BETWEEN '.$packageDetails[0]['min_amt'].' AND '.$packageDetails[0]['max_amt']);
          //echo '<br>Update wallets set is_reedemable=true where user_id='.$parent['parent_id'].' AND trans_id='.$orderLeft[0]['id'];
          if(!empty($orderLeft))
            $updateWallet = $this->CI->pktlib->custom_query_independent_of_id('Update wallets set is_reedemable=true where user_id='.$parent['parent_id'].' AND trans_id='.$orderLeft[0]['id']);
        } elseif (($customerPackageLeft[0]['package_id']>$customerPackageRight[0]['package_id']) && $customerPackageRight[0]['package_id']){
          $packageId = $customerPackageRight[0]['package_id'];
          $packageDetails = $this->CI->pktlib->custom_query('Select * from packages where id='.$packageId);
          $orderRight = $this->CI->pktlib->custom_query('Select * from orders where customer_id="'.$customerPackageRight[0]['customer_id'].'" AND  amount_after_tax BETWEEN '.$packageDetails[0]['min_amt'].' AND '.$packageDetails[0]['max_amt']);
          //echo '<br>Update wallets set is_reedemable=true where user_id='.$parent['parent_id'].' AND trans_id='.$orderRight[0]['id'];
          if(!empty($orderRight))
            $updateWallet = $this->CI->pktlib->custom_query_independent_of_id('Update wallets set is_reedemable=true where user_id='.$parent['parent_id'].' AND trans_id='.$orderRight[0]['id']);
        }
        //echo "reached here";
      }
      $this->binaryIncomeRecursion($order, $income, $parent['parent_id']);

      return TRUE;
    }

    function checkCapping($income, $packageId, $incomeAmt, $order, $customerId){
      $capping = $this->checkCaping(['mlm_income_type_id'=>$income['id'], 'package_id'=>$packageId]);
      //print_r($capping);
      //exit;
      if(!$capping){
      } else {
        switch ($capping['frequency']) {
          case 'Daily':
            $incomeAmt = $this->Daily($incomeAmt, $capping['capping_amount'], $income['id'], $customerId, $order['date']);
            break;
          case 'Monthly':
            $incomeAmt = $this->Monthly($incomeAmt, $capping['capping_amount'], $income['id'], $customerId, $order['date']);
            break;
          
          default:
          
            break;
        }
        
      }

      return $incomeAmt;
    }

    function get_child($parentId){
      $this->CI->pktlib->set_table('person_under');
      $child = $this->CI->pktlib->get_where_custom('parent_id', $parentId);
      return $child->result_array();
    }

    function binaryIncome2($order, $income, $customerId){
      //echo '<pre>';
      $parent = $this->get_parent('customers', $customerId, '');
      if($customerId==0)
        return TRUE;

      $childs = $this->get_child($parent['parent_id']);
      foreach ($childs as $key => $child) {
        $orders = $this->CI->pktlib->custom_query('select sum(amount_after_tax) from orders where customer_id='.$child['customer_id'].' and order_status_id<>2');
        print_r($orders);
      }

      $rec = $this->binaryIncome2Recursion($order, $income, $parent['parent_id']);
      return TRUE;
      //print_r($parent);
    }

    function binaryIncome2Recursion($order, $income, $customerId){
      $parent = $this->get_parent('customers', $customerId, '');
      if($customerId==0)
        return TRUE;
      
      $incomeAmt = $this->amountConversion($order['amount_after_tax'], $income['distribution'], $income['distribution_type']);
      //echo "<pre>"; print_r($incomeAmt);exit;
      $checkUserPackage = $this->get_user_package(['customer_id'=>$parent['parent_id']]);
      $incomeAmt = $this->checkCapping($income, $checkUserPackage['package_id'], $incomeAmt, $order, $parent['parent_id']);
      if($incomeAmt<=0)
        return TRUE;

      $childs = $this->CI->pktlib->custom_query("Select * from person_under where parent_id=".$parent['parent_id'].' order by placement asc');
      if(count($childs)>=2){
        //echo $checkUserPackage['package_id'].'<br>';
        //print_r($childs);
        $wallet = [];
        foreach ($childs as $key => $child) {
          $userId[] = $child['customer_id'];
          $amt = [];
          echo 'select sum(amount) as amount from wallets where user_id='.$child['customer_id'].' and income_type='.$income['id'].'<br>';
          $walletAmt = $this->CI->pktlib->custom_query('select sum(amount) as amount from wallets where user_id='.$child['customer_id'].' and income_type='.$income['id']);
          //echo $child['customer_id'].'<br>';
          //print_r($walletAmt);
        }
        //print_r($userId);
      }

      $rec = $this->binaryIncome2Recursion($order, $income, $parent['parent_id']);
    }

    
    function repurchaseIncome($order, $income, $customerId) {
      return TRUE;
    }

    function digitalIncome($order, $income, $customerId){
      //echo '<pre>';
      /*print_r($income);
      print_r($order);*/
      //$this->CI->pktlib->set_table('user_social_media_share_details');
      $query = $this->CI->pktlib->custom_query('Select s.sms_type, o.* from user_social_media_shares o inner join packagewise_sms s on s.id=o.packagewise_sms_id where o.id='.$order['id']);
      //print_r($query);exit;
      $query = $query[0];
      if($query['sms_type']=="link"){
          $processingFee = number_format((($income['processing_fee']/100.00)*$income['distribution']), 2);
          //print_r($processingFee);
          $walletAmt = $income['distribution']-$processingFee;
          $shoppingWallet = number_format((0.2*$walletAmt), 2);
          $nonShoppingWallet = $walletAmt-$shoppingWallet;
          $walletArray[] = [
            'user_id' =>  $this->CI->session->userdata('employee_id'),
            'user_type' => 'customers',
            'income_type' => $income['id'],
            'income_sub_type_id' => 1,
            'trans_id' => $order['id'],
            'trans_type' => 'sms',
            'amount' => $shoppingWallet,
            'wallet_type' => 'Shopping Wallet',
            'processing_fee' => $income['processing_fee'],
            'is_reedemable' => true,
            'remark' => 'link sharing',
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s')
          ];
          $walletArray[] = [
            'user_id' =>  $this->CI->session->userdata('employee_id'),
            'user_type' => 'customers',
            'income_type' => $income['id'],
            'income_sub_type_id' => 1,
            'trans_id' => $order['id'],
            'trans_type' => 'sms',
            'amount' => $nonShoppingWallet,
            'wallet_type' => 'Social Media Sharing',
            'processing_fee' => $income['processing_fee'],
            'is_reedemable' => true,
            'remark' => 'link sharing',
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s')
          ];
          /*echo '<pre>';
          print_r($walletArray);
          exit;*/
          $fillWallet = $this->fill_wallet_multiple($walletArray);
        
      }else{
        if($query['sms_type']=="video"){
            $income['distribution'] = 100.00;
          }

          $processingFee = number_format((($income['processing_fee']/100.00)*$income['distribution']), 2);
          //print_r($processingFee);
          $walletAmt = $income['distribution']-$processingFee;
          $shoppingWallet = number_format((0.2*$walletAmt), 2);
          $nonShoppingWallet = $walletAmt-$shoppingWallet;
          $walletArray[] = [
            'user_id' =>  $this->CI->session->userdata('employee_id'),
            'user_type' => 'customers',
            'income_type' => $income['id'],
            'income_sub_type_id' => 2,
            'trans_id' => $order['id'],
            'trans_type' => 'sms',
            'amount' => $shoppingWallet,
            'wallet_type' => 'Shopping Wallet',
            'processing_fee' => $income['processing_fee'],
            'is_reedemable' => true,
            'remark' => 'video sharing',
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s')
          ];
          $walletArray[] = [
            'user_id' =>  $this->CI->session->userdata('employee_id'),
            'user_type' => 'customers',
            'income_type' => $income['id'],
            'income_sub_type_id' => 1,
            'trans_id' => $order['id'],
            'trans_type' => 'sms',
            'amount' => $nonShoppingWallet,
            'wallet_type' => 'Social Media Sharing',
            'processing_fee' => $income['processing_fee'],
            'is_reedemable' => true,
            'remark' => 'video sharing',
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s')
          ];
          
          $fillWallet = $this->fill_wallet_multiple($walletArray);
      }

      return true;
      //print_r(count($query));
      //exit;
    }

    function commissionDistribution($orderId, $is_new = false, $commissionType = 'order'){
      $walletDetails = [];
      $is_new = true;
      //echo '<pre>';
      $order = [];
      if($commissionType=="order"){
        $this->CI->pktlib->set_table('orders');
        $order = $this->CI->pktlib->get_where($orderId);
        $updateCustomerPackage = $this->update_Package($order['customer_id'], $order['amount_after_tax']);
      }else{
        $this->CI->pktlib->set_table('user_social_media_shares');
        $order = $this->CI->pktlib->get_where($orderId);
      }

      $incomes = $this->income_type();

      //print_r($incomes);
      foreach ($incomes as $key => $income) {
        switch ($income['id']) {
          case '1':
            if($is_new && $commissionType=='order')
              $this->directIncome($order, $income);
            break;
          case '2':
            if($commissionType=='order')
              $this->binaryIncome($order, $income, $order['customer_id']);

            break;
          case '3':
            if($commissionType=='order')
              $this->repurchaseIncome($order, $income, $order['customer_id']);

            break;
          case '4':
            if($commissionType=='digital')
              $this->digitalIncome($order, $income, $this->CI->session->userdata('user_id'));

            break;
          default:
            # code...
            break;
        }
      }
      //exit;
  }

  function get_positionwisedownlineRecursionCount($conditions, $countArray, $parentPlacement) {
    $sql = 'Select * from person_under where 1=1';
    foreach ($conditions as $key => $condition) {
      $sql.=' AND '.$key.'="'.$condition.'"';
    }
    /*print_r($countArray);
    print_r($parentPlacement);
    exit;*/
    $punder = $this->CI->pktlib->custom_query($sql);
    foreach ($punder as $key => $downline) {
      $countArray[$parentPlacement] = $countArray[$parentPlacement]+1;
      $conditions['parent_id'] = $downline['customer_id'];
      $countArray[$downline['placement']] = $this->get_positionwisedownlineRecursionCount($conditions, $countArray, $parentPlacement);
    }
    return $countArray[$parentPlacement];
  }

  function get_positionwisedownlineCount($conditions) {
    $this->CI->pktlib->set_table('person_under');
    $sql = 'Select * from person_under where 1=1';
    foreach ($conditions as $key => $condition) {
      $sql.=' AND '.$key.'="'.$condition.'"';
    }

    $punder = $this->CI->pktlib->custom_query($sql);
    $result = ['left'=>0, 'right'=>0];
    foreach ($punder as $key => $downline) {
      $result[$downline['placement']] = 1;
      $conditions['parent_id'] = $downline['customer_id'];
      $result[$downline['placement']] = $this->get_positionwisedownlineRecursionCount($conditions, $result, $downline['placement']);
    }
    
    return $result;
  }

  function get_positionwisedownlineDateWiseRecursion($conditions, $countArray, $parentPlacement) {
    $sql = 'Select * from person_under where 1=1';
    foreach ($conditions as $key => $condition) {
      $sql.=' AND '.$key.'="'.$condition.'"';
    }
    //echo $sql;
    $punder = $this->CI->pktlib->custom_query($sql);
    foreach ($punder as $key => $downline) {
      if(date('Y-m-d', strtotime($downline['created'])) == date('Y-m-d')){
        if(isset($countArray[$parentPlacement]))
          $countArray[$parentPlacement] = $countArray[$parentPlacement]+1;
        else
          $countArray[$parentPlacement] = 1;

      }else{
        $countArray[$parentPlacement] = 0;
      }
      $conditions['parent_id'] = $downline['customer_id'];
      $countArray[$downline['placement']] = $this->get_positionwisedownlineDateWiseRecursion($conditions, $countArray, $parentPlacement);
    }

    return isset($countArray[$parentPlacement])?$countArray[$parentPlacement]:0;
  }

  function get_positionwisedownlineDateWise($conditions) {
    $this->CI->pktlib->set_table('person_under');
    $sql = 'Select * from person_under where 1=1';
    foreach ($conditions as $key => $condition) {
      $sql.=' AND '.$key.'="'.$condition.'"';
    }

    //print_r($sql);//exit;
    $punder = $this->CI->pktlib->custom_query($sql);
    //print_r($punder->num_rows());
    $result = ['left'=>0, 'right'=>0];
    foreach ($punder as $key => $downline) {
      if(date('Y-m-d', strtotime($downline['created'])) == date('Y-m-d'))
        $result[$downline['placement']] = 1;

      $conditions['parent_id'] = $downline['customer_id'];
      $result[$downline['placement']] = $this->get_positionwisedownlineDateWiseRecursion($conditions, $result, $downline['placement']);
    }
    
    return $result;
  }

  function get_downlineOrders($conditions) {
    $this->CI->pktlib->set_table('person_under');
    $sql = 'Select * from person_under where 1=1';
    foreach ($conditions as $key => $condition) {
      $sql.=' AND '.$key.'="'.$condition.'"';
    }

    //print_r($sql);//exit;
    $punder = $this->CI->pktlib->custom_query($sql);
    //print_r($punder->num_rows());
    $result = ['left'=>0, 'right'=>0];
    foreach ($punder as $key => $downline) {
      if(date('Y-m-d', strtotime($downline['created'])) == date('Y-m-d'))
        $result[$downline['placement']] = 1;

      $conditions['parent_id'] = $downline['customer_id'];
      $result[$downline['placement']] = $this->get_positionwisedownlineDateWiseRecursion($conditions, $result, $downline['placement']);
    }
    
    return $result;
  }

  function genealogyRecursion($parentId, $level='', $joiningDate='', $upgradeDate='') {
    //echo '<pre>';
    //echo $parentId;
    $sql = 'select u.*, concat(c.first_name, " ", c.middle_name, " ", c.surname) as fullname, c.emp_code, p.package_name, r.introducer_id, concat(i.first_name, " ", i.middle_name, " ", i.surname) as introducername, i.emp_code as sponsor_id, c.contact_1, c.joining_date, concat(pa.first_name, " ", pa.middle_name, " ", pa.surname) as parentname, pa.emp_code as parent_code from person_under u inner join customers c on c.id=u.customer_id inner join customer_references r on r.customer_id=c.id inner join packages p on p.id=r.package_id inner join customers i on i.id=r.introducer_id left join customers pa on pa.id=u.parent_id where u.parent_id='.$parentId;
    $result = [];
    $query = $this->CI->pktlib->custom_query($sql);
    $result = $query;
    //print_r($result);
    foreach ($query as $key => $value) {
      if($value['introducer_id']!=0){
        $res = $this->genealogyRecursion($value['customer_id']);
        //print_r($res);
        $result = array_merge($result, $res);
      }
    }

    /*print_r($result);
    exit;*/
    return $result;
    /*echo '<pre>';
    print_r($query);
    exit;*/
  }

  function genealogy($parentId, $placement='', $level='', $joiningDate='', $upgradeDate='') {
    //echo '<pre>';
    $result = [];
    $sql = 'select u.*, concat(c.first_name, " ", c.middle_name, " ", c.surname) as fullname, c.emp_code, p.package_name, r.introducer_id, concat(i.first_name, " ", i.middle_name, " ", i.surname) as introducername, i.emp_code as sponsor_id, c.contact_1, c.joining_date, concat(pa.first_name, " ", pa.middle_name, " ", pa.surname) as parentname, pa.emp_code as parent_code from person_under u inner join customers c on c.id=u.customer_id inner join customer_references r on r.customer_id=c.id inner join packages p on p.id=r.package_id inner join customers i on i.id=r.introducer_id left join customers pa on pa.id=u.parent_id where 1=1 and u.parent_id='.$parentId;
    if($placement !='')
      $sql.=' AND u.placement="'.$placement.'"';

    //echo $sql;
    $query = $this->CI->pktlib->custom_query($sql);
    $result = $query;
    foreach ($query as $key => $value) {
      if($value['introducer_id']!=0){ 
        $res = $this->genealogyRecursion($value['customer_id'], $level, $joiningDate, $upgradeDate);
        /*echo "hello";
        print_r($res);exit;*/
        $result = array_merge($result, $res);
      }
    }
    /*echo '<pre>';
    print_r($result);
    exit;*/
    return $result;
    /*echo '<pre>';
    print_r($query);
    exit;*/
  } 



  function direct($parentId) {
    //echo '<pre>';
    $result = [];
    $query = $this->CI->pktlib->custom_query('select u.*, concat(c.first_name, " ", c.middle_name, " ", c.surname) as fullname, c.emp_code, p.package_name, r.introducer_id, concat(i.first_name, " ", i.middle_name, " ", i.surname) as introducername, i.emp_code as sponsor_id, c.contact_1, concat(pa.first_name, " ", pa.middle_name, " ", pa.surname) as parentname, pa.emp_code as parent_code from person_under u left join customers c on c.id=u.customer_id inner join customer_references r on r.customer_id=c.id left join packages p on p.id=r.package_id inner join customers i on i.id=r.introducer_id left join customers pa on pa.id=u.parent_id where r.introducer_id='.$parentId);
    $result = $query;
    
    return $result;
  }

  function mydirect($parentId) {
    //echo '<pre>';
    $result = [];
    $query = $this->CI->pktlib->custom_query('select u.*, concat(c.first_name, " ", c.middle_name, " ", c.surname) as fullname, c.emp_code, p.package_name, r.introducer_id, concat(i.first_name, " ", i.middle_name, " ", i.surname) as introducername, i.emp_code as sponsor_id, c.contact_1, concat(pa.first_name, " ", pa.middle_name, " ", pa.surname) as parentname, pa.emp_code as parent_code from person_under u inner join customers c on c.id=u.customer_id inner join customer_references r on r.customer_id=c.id inner join packages p on p.id=r.package_id inner join customers i on i.id=r.introducer_id left join customers pa on pa.id=u.parent_id where r.introducer_id='.$parentId);
    $result = $query;
    
    return $result;
    
  }

  function placementwiseWalletDetailsRecursion($id){
    $query = $this->CI->pktlib->custom_query('Select * from person_under where parent_id = '.$id);
    print_r($query);
    $result = $query;
    /*foreach ($query as $walletKey => $wallet) {
      //$
    }*/
    $result = $query;
  }

  function getImmediateDownline($id) {
    $this->CI->pktlib->set_table('person_under');
    $query = $this->CI->pktlib->get_where_custom('parent_id', $id);
    return $query->result_array();
  }

  function placementwiseWalletDetails($id) {
    echo '<pre>';

    $downline = $this->getImmediateDownline($id);
    print_r($downline);
    $placementArr  = [];
    foreach ($downline as $key => $line) {
      $placementArr[$line['placement']] = $line['customer_id'];
    }
    $query = $this->CI->pktlib->custom_query('Select w.*, o.order_code, u.parent_id, u.placement from wallets w inner join orders o on o.id=w.trans_id inner join person_under u on u.customer_id=o.customer_id where w.income_type=2 AND w.user_id='.$id.'');
    $result = $query;
    foreach ($query as $walletKey => $wallet) {
      if($wallet['parent_id']!=$id && $wallet['parent_id']!=0){
        $recursion = $this->placementwiseWalletDetailsRecursion($wallet['parent_id']);
        if(!empty($recursion)){

        }
      }
    }
    
    print_r($query);
    exit;

  } 

  public function mlmBusinessRecursion($customerId, $position, $result = [], $resultType = 'date'){
    //echo $customerId."<br>";
    $sql = 'select * from person_under where parent_id='.$customerId;
    $personUnders = $this->CI->pktlib->custom_query($sql);
   // echo "<pre>"; print_r($personUnders);exit;
    //$this->CI->pktlib->set_table('orders');
    foreach ($personUnders as $personUnderKey => $personUnder) {
      $orders = $this->CI->pktlib->custom_query("select orders.*, customers.first_name, customers.middle_name, customers.surname, customers.emp_code, order_status.status from orders inner join customers on customers.id=orders.customer_id inner join order_status on order_status.id=orders.order_status_id where customer_id=".$personUnder['customer_id'].' and order_status_id<>2');
      //print_r($orders);
      foreach ($orders as $orderKey => $order) {
        $orderDetails = $this->CI->pktlib->custom_query("select order_details.*, products.product from order_details inner join products on products.id=order_details.product_id AND pack_product_id=0 where order_id='".$order['id']."' UNION select order_details.*, products.product from order_details inner join products on products.id=order_details.product_id AND pack_product_id!=0 where order_id='".$order['id']."'");
        if($resultType == 'date')
        {
          $result[$order['date']][$personUnder['placement']][$orderKey]['order'] = $order;
          $result[$order['date']][$personUnder['placement']][$orderKey]['orderDetails'] = $orderDetails;
        }
        elseif($resultType == 'placement')
        {
          $result[$personUnder['placement']][$orderKey]['order'] = $order;
          $result[$personUnder['placement']][$orderKey]['orderDetails'] = $orderDetails;
        }
        else
        {
          $result[$orderKey]['order'] = $order;
          $result[$orderKey]['orderDetails'] = $orderDetails;
        }
      }
      $result = $this->mlmBusinessRecursion($personUnder['customer_id'], $position, $result, $resultType);
    }

    return $result;
  }

  function mlmBusiness($customerId, $position = '', $result = [], $resultType = 'date'){
    //echo $customerId."<br>";
    $sql = 'select * from person_under where parent_id='.$customerId;
    $personUnders = $this->CI->pktlib->custom_query($sql);
   // echo "<pre>"; print_r($personUnders);exit;
    //$this->CI->pktlib->set_table('orders');
    foreach ($personUnders as $personUnderKey => $personUnder) {
      $orders = $this->CI->pktlib->custom_query("select orders.*, customers.first_name, customers.middle_name, customers.surname, customers.emp_code, order_status.status from orders inner join customers on customers.id=orders.customer_id inner join order_status on order_status.id=orders.order_status_id where customer_id=".$personUnder['customer_id'].' and order_status_id<>2');
      //print_r($orders);
      foreach ($orders as $orderKey => $order) {
        $orderDetails = $this->CI->pktlib->custom_query("select order_details.*, products.product from order_details inner join products on products.id=order_details.product_id AND pack_product_id=0 where order_id='".$order['id']."' UNION select order_details.*, products.product from order_details inner join products on products.id=order_details.pack_product_id AND pack_product_id<>0 where order_id='".$order['id']."'");
        if($resultType == 'date')
        {
          $result[$order['date']][$personUnder['placement']][$orderKey]['order'] = $order;
          $result[$order['date']][$personUnder['placement']][$orderKey]['orderDetails'] = $orderDetails;
        }
        elseif($resultType == 'placement')
        {
          $result[$personUnder['placement']][$orderKey]['order'] = $order;
          $result[$personUnder['placement']][$orderKey]['orderDetails'] = $orderDetails;
        }
        else
        {
          $result[$orderKey]['order'] = $order;
          $result[$orderKey]['orderDetails'] = $orderDetails;
        }
      }
      $result = $this->mlmBusinessRecursion($personUnder['customer_id'], $personUnder['placement'], $result, $resultType);
    }

    return $result;
  }

  public function mlmBusinessCountRecursion($customerId, $position, $result = [], $resultType = 'date', $conditionString){
    //echo $customerId."<br>";
    $sql = 'select * from person_under where parent_id='.$customerId;
    $personUnders = $this->CI->pktlib->custom_query($sql);
   // echo "<pre>"; print_r($personUnders);exit;
    //$this->CI->pktlib->set_table('orders');
    foreach ($personUnders as $personUnderKey => $personUnder) {
      $sql = "select * from orders where customer_id=".$personUnder['customer_id']." and order_status_id<>2";
      if('' !== $conditionString)
        $sql.= " AND ".$conditionString;
      $orders = $this->CI->pktlib->custom_query($sql);
      //print_r($orders);
      foreach ($orders as $orderKey => $order) {
        if($resultType == 'date')
        {
          if(isset($result[$order['date']][$personUnder['placement']]))
            $result[$order['date']][$personUnder['placement']] = $result[$order['date']][$personUnder['placement']]+1;
          else
            $result[$order['date']][$personUnder['placement']] = 1;
        }
        elseif($resultType == 'placement')
        {
          if(isset($result[$personUnder['placement']]))
            $result[$personUnder['placement']] = $result[$personUnder['placement']]+1;
          else
            $result[$personUnder['placement']] = 1;
        }
        elseif($resultType == 'amount')
        {
          if(isset($result[$personUnder['placement']]))
            $result[$personUnder['placement']] = $result[$personUnder['placement']]+$order['amount_after_tax'];
          else
            $result[$personUnder['placement']] = $order['amount_after_tax'];
        }
        else
        {
          if(isset($result))
            $result = $result+1;
          else
            $result = 1;
        }
        
      }
      $result = $this->mlmBusinessCountRecursion($personUnder['customer_id'], $position, $result, $resultType, $conditionString);
    }

    return $result;
  }

  function mlmBusinessCount($customerId, $position = '', $result = [], $resultType = 'date', $conditionString = ''){
    //echo $customerId."<br>";
    $sql = 'select * from person_under where parent_id='.$customerId;
    $personUnders = $this->CI->pktlib->custom_query($sql);
   // echo "<pre>"; print_r($personUnders);exit;
    //$this->CI->pktlib->set_table('orders');
    foreach ($personUnders as $personUnderKey => $personUnder) {
      $sql = "select * from orders where customer_id=".$personUnder['customer_id']." and order_status_id<>2";
      if('' !== $conditionString)
        $sql.= " AND ".$conditionString;
        
        //echo $sql;
      $orders = $this->CI->pktlib->custom_query($sql);
      //print_r($orders);
      foreach ($orders as $orderKey => $order) {
        if($resultType == 'date')
        {
          if(isset($result[$order['date']][$personUnder['placement']]))
            $result[$order['date']][$personUnder['placement']] = $result[$order['date']][$personUnder['placement']]+1;
          else
            $result[$order['date']][$personUnder['placement']] = 1;
        }
        elseif($resultType == 'placement')
        {
          if(isset($result[$personUnder['placement']]))
            $result[$personUnder['placement']] = $result[$personUnder['placement']]+1;
          else
            $result[$personUnder['placement']] = 1;
        }
        elseif($resultType == 'amount')
        {
          if(isset($result[$personUnder['placement']]))
            $result[$personUnder['placement']] = $result[$personUnder['placement']]+$order['amount_after_tax'];
          else
            $result[$personUnder['placement']] = $order['amount_after_tax'];
        }
        else
        {
          if(isset($result))
            $result = $result+1;
          else
            $result = 1;
        }
      }
      $result = $this->mlmBusinessCountRecursion($personUnder['customer_id'], $personUnder['placement'], $result, $resultType, $conditionString);
    }

    return $result;
  }

  function positionWisedirectCountRecursion($introducer, $placement, $directCount, $customerId){
    $punders = $this->CI->pktlib->custom_query("Select * from person_under where parent_id=".$customerId);
    foreach ($punders as $key => $punder) {
      if(in_array($punder['customer_id'], $introducer)){
        $directCount[$punder['placement']] = $directCount[$punder['placement']]+1;
      }

      $directCount = $this->positionWisedirectCountRecursion($introducer, $placement, $directCount, $punder['customer_id']);

    }

    return $directCount; 
  }

  function positionWisedirectCount($customerId){
    $this->CI->pktlib->set_table('customer_references');
    $query = $this->CI->pktlib->get_where_custom('introducer_id', $customerId);
    $introducer = [];
    foreach ($query->result_array() as $key => $sponsor) {
      $introducer[] = $sponsor['customer_id'];
    }
    $punders = $this->CI->pktlib->custom_query("Select * from person_under where parent_id=".$customerId);
    $directCount['left'] = 0;
    $directCount['right'] = 0;
    foreach ($punders as $key => $punder) {
      if(in_array($punder['customer_id'], $introducer)){
        $directCount[$punder['placement']] = $directCount[$punder['placement']]+1;
      }
      $directCount = $this->positionWisedirectCountRecursion($introducer, $punder['placement'], $directCount, $punder['customer_id']);

    }
    return $directCount; 
  }

  function surveyIncome($surveyId){
    //print_r($surveyId);
    //echo 'select i.*, s.point, s.type, s.min_count from mlm_income_type i inner join mlm_income_sub_type s on s.mlm_income_type_id=i.id and s.id=3';
    $checkWallet = $this->CI->pktlib->custom_query('select * from wallets where income_type=4 and income_sub_type_id=3 and trans_id='.$surveyId.' and trans_type="sms"');

    if(count($checkWallet)>0)
      return true;

    $sql = $this->CI->pktlib->custom_query('select i.*, s.point, s.type, s.min_count from mlm_income_type i inner join mlm_income_sub_type s on s.mlm_income_type_id=i.id and s.id=3');

    if(count($sql)==0){
      return false;
    }
    $income = $sql[0];
    $processingFee = number_format((0.05*$income['point']), 2);
          //print_r($processingFee);
          $walletAmt = $income['point']-$processingFee;
          $shoppingWallet = number_format((0.2*$walletAmt), 2);
          $nonShoppingWallet = $walletAmt-$shoppingWallet;
          $walletArray[] = [
            'user_id' =>  $this->CI->session->userdata('employee_id'),
            'user_type' => 'customers',
            'income_type' => 4,
            'income_sub_type_id' => 3,
            'trans_id' => $surveyId,
            'trans_type' => 'sms',
            'amount' => $shoppingWallet,
            'wallet_type' => 'Shopping Wallet',
            'processing_fee' => 5,
            'is_reedemable' => true,
            'remark' => 'survey',
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s')
          ];
          $walletArray[] = [
            'user_id' =>  $this->CI->session->userdata('employee_id'),
            'user_type' => 'customers',
            'income_type' => 4,
            'income_sub_type_id' => 3,
            'trans_id' => $surveyId,
            'trans_type' => 'sms',
            'amount' => $nonShoppingWallet,
            'wallet_type' => 'Social Media Sharing',
            'processing_fee' => 5,
            'is_reedemable' => true,
            'remark' => 'survey',
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s')
          ];
          /*echo '<pre>';
          print_r($walletArray);
          exit;*/
          $fillWallet = $this->fill_wallet_multiple($walletArray);
          return $fillWallet;
    /*print_r($sql);
    exit;*/
  }

  function volumeUpdateRecursion($order, $customerId, $restrictedOrderIds){
    $parent = $this->get_parent('customers', $customerId);
    if(!in_array($order['id'], $restrictedOrderIds)){
        //echo "hello";exit;
        $upd = $this->CI->db->query("update person_under set business_volume=business_volume+".$order['amount_after_tax'].', modified="'.date('Y-m-d H:i:s').'" where customer_id='.$customerId);
    }

    if(count($parent)>0)
      $res = $this->volumeUpdateRecursion($order, $parent['parent_id'], $restrictedOrderIds);
    //print_r($parent);
    return TRUE;
  }

  function volumeUpdate($order, $restrictedOrderIds) {
    /*echo '<pre>';
    print_r($order);
   // echo '<pre>';
    print_r($restrictedOrderIds);
    exit;*/
    $parent = $this->get_parent('customers', $order['customer_id']);
    if(!in_array($order['id'], $restrictedOrderIds)){
        //echo "hii";exit;
      $upd = $this->CI->db->query("update person_under set business_volume=business_volume+".$order['amount_after_tax'].', modified="'.date('Y-m-d H:i:s').'" where customer_id='.$order['customer_id']);
    }
    if(count($parent)>0)
      $res = $this->volumeUpdateRecursion($order, $parent['parent_id'], $restrictedOrderIds);
    //print_r($parent);
    $this->CI->pktlib->set_table('order_volumes');
    $volumeOrders = $this->CI->pktlib->_insert(['order_id'=>$order['id'], 'user_id'=>$order['customer_id'], 'type'=>'customers', 'created'=>date('Y-m-d H:i:s'), 'modified'=>date('Y-m-d H:i:s')]);
    return TRUE;
  }

  function binaryCron($type = 'wallet'){
    $this->CI->pktlib->set_table('person_under');
    $personUnder = $this->CI->pktlib->custom_query('select * from person_under where parent_id=0 order by placement asc');
    //echo '<pre>';
    $walletAmt = [];
    $walletArray = [];
    
    foreach ($personUnder as $key => $child) {
      $punder = $this->CI->pktlib->get_where_custom('customer_id', $child['customer_id']);
      $under = $punder->row_array();
      //print_r($under);
      $walletAmt2[$under['parent_id']][$under['placement']] = $under['business_volume'];
      $walletAmt = $this->binaryCronRecursion($child['customer_id'], $walletAmt2); 
    }
    /*if(count($walletAmt)==2){

    }*/
    if($type=='wallet'){
      $walletCalculation = $this->binaryWalletCalculation($walletAmt);
     
      $counter = 0;
      $this->CI->pktlib->set_table('customer_references');
     // echo '<pre>';
      //print_r($walletCalculation);///exit;
      foreach ($walletCalculation as $userId => $calc) {
        $query = $this->CI->pktlib->get_where_custom('customer_id', $userId);
        $customerPackage = $query->row_array();
        //echo 'select sum(amount) as paidamt from wallets where user_id='.$userId.' and income_type=2';
        $paidAmt = $this->CI->pktlib->custom_query('select sum(amount) as paidamt from wallets where user_id='.$userId.' and income_type=2');
        
        $calc = $calc-$paidAmt[0]['paidamt'];
        //print_r($paidAmt);
        //echo "userid=".$userId." amt=".$calc.'<br>';
        //print_r($customerPackage);
        if($customerPackage['package_id']>1 && $calc>0)
          $walletArray[$counter] = [
          'wallet_type'=>'Binary Income',
          'user_id' =>  $userId,
          'user_type' => 'customers',
          'income_type' => 2,
          'income_sub_type_id' => 0,
          'trans_id' => 0,
          'amount' => $calc-($calc*0.05),
          'trans_type' => 'orders',
          'remark' => 'Binary Income',
          'is_reedemable' => ($customerPackage['package_id']>1)?true:false,
        ];


        $counter = $counter+1;
      }
      //echo '<pre>';
      //print_r($walletArray);//exit;
      if(!empty($walletArray)){
        $this->fill_wallet_multiple($walletArray);
      }
    }
    return $walletArray;
  }

  function walletIncome($incomeType, $incomeSubType = 0, $userId){
    //echo 'select sum(amount) as walletamt from wallets where income_type='.$incomeType.' and income_sub_type_id='.$incomeSubType.' and user_id='.$userId.'<br>';
    $wallet = $this->CI->pktlib->custom_query('select sum(amount) as walletamt from wallets where income_type='.$incomeType.' and income_sub_type_id='.$incomeSubType.' and user_id='.$userId);
    return $wallet[0]['walletamt'];
  }

  function binaryWalletCalculation($businessArray){
    $walletDistribution = [];
    $this->CI->pktlib->set_table('mlm_income_type');
    $query = $this->CI->pktlib->get_where_custom('id', 2);
    $incomeCriteria = $query->row_array();
    //print_r($incomeCriteria);
    $this->CI->pktlib->set_table('customer_references');
    foreach ($businessArray as $key => $business) {
      if(count($business)==2 && isset($business['left']) && $business['left']>0 && isset($business['right']) && $business['right']>0)
      {
        $walletAmt = $this->walletIncome(2, 0, $key);
        //echo $walletAmt.'<br>';
        $query = $this->CI->pktlib->get_where_custom('customer_id', $key);
        $customerPackage = $query->row_array();
        //$walletDistribution[] = $business;
        $capping = $this->checkCaping(['mlm_income_type_id'=>2, 'package_id'=>$customerPackage['package_id']]);
        //print_r($capping['capping_amount']);
        //print_r($business['left']);
        $amt = 0;
        //echo $business['left']." ".$business['right']."<br>";
        if($business['left']<=$business['right']){ 
        	$comAmt = ($business['left']-$walletAmt)*0.15;
          if(($comAmt)<=$capping['capping_amount']){
            $amt = $comAmt;
          }else{
            $amt = $capping['capping_amount'];
          }
        }elseif ($business['right']<$business['left']) { 
        	$comAmt = ($business['right']-$walletAmt)*0.15;
          if (($comAmt)<$capping['capping_amount']) {
            $amt = $comAmt;
          }else{
            $amt = $capping['capping_amount'];
          }
        }
        
        //echo $amt;
        if($amt>0)
        $walletDistribution[$key] = $amt;
      }
    }
    /*echo '<pre>';
    print_r($walletDistribution);
    exit;*/
    return $walletDistribution;
  }

  function binaryCronRecursion($customerId, $walletAmt){
    $this->CI->pktlib->set_table('person_under');
    $personUnder = $this->CI->pktlib->custom_query('select * from person_under where parent_id='.$customerId,' order by placement asc');
   
    //$walletAmt[$customerId]['left'] = 0;
    //$walletAmt[$customerId]['right'] = 0;
    foreach ($personUnder as $key => $child) {
      $punder = $this->CI->pktlib->get_where_custom('customer_id', $child['customer_id']);
      $under = $punder->row_array();
      //print_r($under);
      $walletAmt[$under['parent_id']][$under['placement']] = $under['business_volume'];
      //print_r($walletAmt);
      $walletAmt = $this->binaryCronRecursion($child['customer_id'], $walletAmt); 
    }
    
    return $walletAmt;
  }
  
  function businessCount2($type = 'wallet', $parentId){
    $this->CI->pktlib->set_table('person_under');
    //print_r('select * from person_under where parent_id="'.$parentId.'" order by placement asc');
    $personUnder = $this->CI->pktlib->custom_query('select * from person_under where parent_id="'.$parentId.'" order by placement asc');
    //echo '<pre>';
    $walletAmt = [];
    $walletAmt[$parentId]['left'] = 0;
    $walletAmt[$parentId]['right'] = 0;
    foreach ($personUnder as $key => $child) {
      $punder = $this->CI->pktlib->get_where_custom('customer_id', $child['customer_id']);
      $under = $punder->row_array();
      //print_r($under);
      $walletAmt[$under['placement']] = $under['business_volume'];
      
    }
    return $walletAmt;
  }
  
  function downlineRecursion($customerId, $filterBy, $placement, $result){
    $childs = $this->CI->pktlib->custom_query("select * from person_under where 1=1 and parent_id=".$customerId.' order by placement asc');
    //$result = [];
    foreach ($childs as $key => $child) {
      if($filterBy=='date'){
        if(date('Y-m-d', strtotime($child['created']))==date('Y-m-d'))
          $result[$placement][] = $child;
      }
      else
        $result[$placement][] = $child;
      //print_r($result);
      $result = $this->downlineRecursion($child['customer_id'], $filterBy, $placement, $result);
    }
    return $result;
  }

  function downline($customerId, $filterBy = '')
  { //echo '<pre>';
    $childs = $this->CI->pktlib->custom_query("select * from person_under where 1=1 and parent_id=".$customerId.' order by placement asc');
    $result = [];
    foreach ($childs as $key => $child) {
      if($filterBy=='date'){
        if(date('Y-m-d', strtotime($child['created']))==date('Y-m-d'))
          $result[$child['placement']][] = $child;
      }
      else
        $result[$child['placement']][] = $child;

      //print_r($result);
      $result = $this->downlineRecursion($child['customer_id'], $filterBy, $child['placement'], $result);
    }
    return $result;
  }

  function countDownline($customerId, $filterBy=''){
    $downline = $this->downline($customerId, $filterBy);
    //echo '<pre>';
    //echo count($downline['left'])." ".count($downline['right']);
    $result = ['left'=>0, 'right'=>0];
    foreach ($downline as $key => $res) {
      //print_r($res);
      $result[$key] = count($res);
    }
    //print_r($result);
    return $result;
  }

}
?>
