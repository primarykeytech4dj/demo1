<?php 
class Cron extends MY_Controller {
	function __construct() {
		parent::__construct();
	}

    function generateEveningReport(){
        $sql = 'SELECT ol.* FROM order_logs ol LEFT JOIN order_logs o2 ON (ol.order_id = o2.order_id AND ol.id < o2.id) WHERE o2.id IS NULL AND ol.order_status_id=5 and ol.created like "'.date('Y-m-d').'%"';
        //echo $sql;
        $logs = $this->pktdblib->custom_query($sql);
        /*echo '<pre>';
        print_r($logs);*/
        $products = [];
        if(count($logs)>0){
            $delivery = [];
            
            foreach($logs as $key=>$log){
                $orders = $this->pktdblib->custom_query('Select od.product_id, (od.qty-od.return_quantity) as qty, p.base_uom, od.variation, e.employee_id, p.is_pack from order_details od inner join products p on p.id=od.product_id inner join deliveryboy_order e on e.order_id=od.order_id where od.order_id="'.$log['order_id'].'" and od.is_active=true');
                
                foreach($orders as $oKey=>$order){
                    $products[] = $order['product_id'];
                    $attributeId = json_decode($order['variation'], true);
                    if((NULL!==$attributeId['attribute']['product_attribute_id'] || $attributeId['attribute']['product_attribute_id']!==0) && $order['base_uom']>0){
                        $attribute = $this->pktdblib->custom_query('Select a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$attributeId['attribute']['product_attribute_id'].'"');
                        $baseUom = explode(" ", $order['base_uom']);
                        $conversion = $this->pktlib->unit_convertion($attribute[0]['uom'], trim($baseUom[1]));
                        if(array_key_exists($order['product_id'], $delivery)){
                            $delivery[$order['product_id']]['qty'] = $delivery[$order['product_id']]['qty']+($order['qty']*$conversion*$attribute[0]['unit']);
                        }else{
                            $delivery[$order['product_id']]['qty'] = $order['qty']*$conversion*$attribute[0]['unit'];   
                        }
                        //$delivery[$order['product_id']]['product_id'] = $order['product_id'];
                    }else{
                        if(array_key_exists($order['product_id'], $delivery)){
                            
                            $delivery[$order['product_id']]['qty'] = $delivery[$order['product_id']]['qty']+($order['qty']);
                        }else{
                            $delivery[$order['product_id']]['qty'] = $order['qty'];
                        }
                        
                    }
                    $delivery[$order['product_id']]['product_id'] = $order['product_id'];
                    $delivery[$order['product_id']]['type'] = 'out';
                    $delivery[$order['product_id']]['created'] = date('Y-m-d H:i:s');
                    $delivery[$order['product_id']]['created_by'] = 1;
                    $delivery[$order['product_id']]['employee_id'] = $order['employee_id'];
                }
            }
            
            if(count($delivery)>0){
                $delivery = array_values($delivery);
                $this->pktdblib->set_table('daily_user_stocks');
                $ins = $this->pktdblib->_insert_multiple($delivery);
            }
        }
        
    }
    
    function generateMorningReport(){
        $date = '2020-11-18';//date('Y-m-d', strtotime('-1 day'));
        //echo $date;
        $sql = 'Select o.date,od.product_id, od.qty, p.base_uom, od.variation, e.employee_id, p.is_pack from order_details od inner join orders o on o.id=od.order_id inner join products p on p.id=od.product_id inner join deliveryboy_order e on e.order_id=od.order_id where od.is_active=true and o.order_status_id=9 and o.date like "'.$date.'%"';
        //echo $sql;
        $orders = $this->pktdblib->custom_query($sql);
        if(count($orders)==0){
            return false;
        }
        
        $delivery = [];
        //echo '<pre>';print_r($orders);
        foreach($orders as $oKey=>$order){
            if(!$order['is_pack']){
                $attributeId = json_decode($order['variation'], true);
                if((NULL!==$attributeId['attribute']['product_attribute_id'] || $attributeId['attribute']['product_attribute_id']!==0) && $order['base_uom']>0){
                    $attribute = $this->pktdblib->custom_query('Select a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$attributeId['attribute']['product_attribute_id'].'"');
                    $baseUom = explode(" ", $order['base_uom']);
                    $conversion = $this->pktlib->unit_convertion($attribute[0]['uom'], trim($baseUom[1]));
                    if(array_key_exists($order['product_id'], $delivery)){
                        $delivery[$order['product_id']]['qty'] = $delivery[$order['product_id']]['qty']+($order['qty']*$conversion*$attribute[0]['unit']);
                    }else{
                        $delivery[$order['product_id']]['qty'] = $order['qty']*$conversion*$attribute[0]['unit'];   
                    }
                }else{
                    if(array_key_exists($order['product_id'], $delivery)){
                        
                        $delivery[$order['product_id']]['qty'] = $delivery[$order['product_id']]['qty']+($order['qty']);
                    }else{
                        $delivery[$order['product_id']]['qty'] = $order['qty'];
                    }
                    
                }
                $stockOut[0]['out_qty'] = 0;
                $stockIn[0]['inward_qty'] = 0;
                $stockIn = $this->pktdblib->custom_query('Select sum(qty) as inward_qty from daily_user_stocks where employee_id='.$order['employee_id'].' and product_id='.$order['product_id'].' and created like "'.$date.'%" and type="in"');
                $stockOut = $this->pktdblib->custom_query('Select sum(qty) as out_qty from daily_user_stocks where employee_id='.$order['employee_id'].' and product_id='.$order['product_id'].' and created like "'.$date.'%" and type="out"');
                /*if(count($stockIn)>0 || count){
                    if(($stockIn[0]['inward_qty']-$stockOut[0]['out_qty'])>0)*/
                        $delivery[$order['product_id']]['qty'] = $delivery[$order['product_id']]['qty']-($stockIn[0]['inward_qty']-$stockOut[0]['out_qty']);
                    /*else{
                        unset($delivery[$order['product_id']]);
                        continue;
                    }
                }*/
                if($delivery[$order['product_id']]['qty']<=0){
                    unset($delivery[$order['product_id']]);
                    continue;
                }
                $delivery[$order['product_id']]['product_id'] = $order['product_id'];
                $delivery[$order['product_id']]['type'] = 'in';
                $delivery[$order['product_id']]['created'] = date('Y-m-d H:i:s');
                $delivery[$order['product_id']]['created_by'] = 1;
                $delivery[$order['product_id']]['employee_id'] = $order['employee_id'];
            }else{
                //code as per pack products. to be done later
                //$packProduct = $this->
                $attributeId = json_decode($order['variation'], true);
                if((NULL!==$attributeId['attribute']['product_attribute_id'] || $attributeId['attribute']['product_attribute_id']!==0) && $order['base_uom']>0){
                    $attribute = $this->pktdblib->custom_query('Select a.unit, a.uom from product_attributes pa inner join attributes a on a.id=pa.attribute_id where pa.id="'.$attributeId['attribute']['product_attribute_id'].'"');
                    $baseUom = explode(" ", $order['base_uom']);
                    $conversion = $this->pktlib->unit_convertion($attribute[0]['uom'], trim($baseUom[1]));
                    if(array_key_exists($order['product_id'], $delivery)){
                        $delivery[$order['product_id']]['qty'] = $delivery[$order['product_id']]['qty']+($order['qty']*$conversion*$attribute[0]['unit']);
                    }else{
                        $delivery[$order['product_id']]['qty'] = $order['qty']*$conversion*$attribute[0]['unit'];   
                    }
                    //$delivery[$order['product_id']]['product_id'] = $order['product_id'];
                }else{
                    if(array_key_exists($order['product_id'], $delivery)){
                        
                        $delivery[$order['product_id']]['qty'] = $delivery[$order['product_id']]['qty']+($order['qty']);
                    }else{
                        $delivery[$order['product_id']]['qty'] = $order['qty'];
                    }
                    
                }
                $delivery[$order['product_id']]['product_id'] = $order['product_id'];
                $delivery[$order['product_id']]['type'] = 'out';
                $delivery[$order['product_id']]['created'] = date('Y-m-d H:i:s');
                $delivery[$order['product_id']]['created_by'] = 1;
                $delivery[$order['product_id']]['employee_id'] = $order['employee_id'];
            }
        }
        /*echo '<pre>';
        print_r($delivery);
        exit;*/
        if(count($delivery)>0){
            $delivery = array_values($delivery);
            $this->pktdblib->set_table('daily_user_stocks');
            $ins = $this->pktdblib->_insert_multiple($delivery);
        }
    }
}
	