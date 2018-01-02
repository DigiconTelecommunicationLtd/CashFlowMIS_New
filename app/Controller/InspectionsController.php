<?php

App::uses('AppController', 'Controller');

/**
 * Inspections Controller
 *
 * @property Inspection $Inspection
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class InspectionsController extends AppController {

    public function add() {
        if ($this->request->is('post')) {
            #echo '<pre>';print_r($this->request->data);exit;
            #set the date and date type
            $date =$this->request->data['Inspection']['date'];
            #set the contract/lot id/submission type            
            $contract_id =$this->request->data['Inspection']['contract_id'];
            $lot_id =$this->request->data['Inspection']['lot_id'];
            $FormType =$this->request->data['Inspection']['FormType'];
            $product_category_id=$this->request->data['Inspection']['product_category_id'];
            
            #check the below valiable value set  
            if (!$contract_id || !$lot_id||!$product_category_id) {
                $this->Session->setFlash(__('PO/Lot/Product Category is required!. Please, try again.'));
                return $this->redirect($this->referer());
            }
            #option for search from inspection table
            $option['LotProduct.contract_id'] = $contract_id;
            $option['LotProduct.product_category_id'] = $product_category_id;
            $option['LotProduct.contract_id'] = $contract_id;
            $option['LotProduct.lot_id'] = (string) $lot_id; 
            
            #option for delivery to check delivery quantity
            $option_del['Inspection.contract_id'] = $contract_id;
            $option_del['Inspection.product_category_id'] = $product_category_id;
            $option_del['Inspection.contract_id'] = $contract_id;
            $option_del['Inspection.lot_id'] = (string) $lot_id;  
            
            #set default lot no
            $lots[$lot_id] = $lot_id; 
            
            #if form is submitted then product information will be inserted into delivery table
            if ($FormType == 'submit'){
                #check the Actual date set
                
                if(!$date){
                   $this->Session->setFlash(__('Inspection Date is required!. Please, try again.'));
                   return $this->redirect($this->referer()); 
                }
                #find the client and unit id from contracts             
                $this->loadModel('Contract');
                $this->Contract->unbindModel(array('hasMany'=>array('Collection')));
                $contract = $this->Contract->find('first', array( 'conditions' => array( 'Contract.id' => $contract_id ) ));
                #echo '<pre>';print_r($contract);exit;
                #set client and unit id to deliveries
                $clientid = ($contract['Contract']['client_id']) ? $contract['Contract']['client_id'] : "NULL";
                $unitid = ($contract['Contract']['unit_id']) ? $contract['Contract']['unit_id'] : "NULL";

                #find the currency of delivery products from contract products 
                foreach ($contract['ContractProduct'] as $value) {
                    #echo '<pre>';print_r($value);exit;
                    #product category
                    $category[$value['product_id']] =$value['product_category_id'];
                    #Currency
                    $currency[$value['product_id']] =$value['currency'];
                    #Unit Price
                    $unit_price[$value['product_id']] =$value['unit_price'];
                    #UOM
                    $uom[$value['product_id']] =$value['uom'];
                    #Unit weight
                    $unit_weight[$value['product_id']] =$value['unit_weight'];
                    #Unit weight uom
                    $unit_weight_uom[$value['product_id']] =$value['unit_weight_uom'];
                }

                #Receive the submit form value
                #$this->Delivery->recursive=-1;
                $pquantitys = $this->request->data['Inspection']['quantity'];
                
                #$actual=$this->request->data['Delivery']['actual'];
                $user = $this->Session->read('UserAuth');
                $UserID = $user['User']['username'];
                
                $planned_delivery_date = str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($date)));
                if ($planned_delivery_date == "1970-01-01") {
                    $this->Session->setFlash(__('Wrong format of Inspection Date!. Please, try again(Date Format is YYYY-MM-DD).'));
                    return $this->redirect($this->referer());
                }
               
                $total_qty=0;
                $total_Delivery_value=0;
                foreach ($pquantitys as $key => $quantity) {

                    /*****************of check lot qty and production qty ******************* */
                    #if qty and date not set properly
                    if (!$quantity || $quantity <= 0) {
                        continue;
                    }
                    #*********** find the inspection Qty with search criteria
                      $option['LotProduct.product_id']=$key;
                      $option_del['Inspection.product_id']= $key;
                      
                      $this->loadModel('LotProduct');   
                      $this->LotProduct->recursive=-1;
                      #echo '<pre>';print_r($option);
                      $inspection_qty=$this->LotProduct->find('first',array('conditions'=>array($option),'fields'=>array('SUM(LotProduct.quantity) as ins_qty')));
                      #echo '<pre>';print_r($inspection_qty);
                      $inspection_qty=($inspection_qty[0]['ins_qty']>0)?$inspection_qty[0]['ins_qty']:0;

                      #check inspection qty with product id lot number
                   
                       
                      $this->Inspection->recursive=-1;
                      $delivery_qty=$this->Inspection->find('first',array(
                      'conditions'=>array($option_del)
                      ,
                      'fields'=>array(
                      'SUM(Inspection.quantity) as quantity'
                      )
                      ));
                       #echo '<pre>';print_r($delivery_qty);
                      $delivery_qty=($delivery_qty[0]['quantity']>0)?$delivery_qty[0]['quantity']:0;
                      #echo 'ddd'.$delivery_qty;
                      #sum the previous delivery qty and submitted qty
                      $delivery_qty+=$quantity;
                      #echo $quantity;exit;
                      #compare lot size with production size
                      if($delivery_qty>$inspection_qty)
                      {
                      $this->Session->setFlash(__('Inspection Quantity is greater than Lot Quantity!. Please, try again.'));
                      return $this->redirect($this->referer());
                      }
                      #Store sumbitted value in array
                    $saveData[]=array(
                        'Inspection'=>array(
                                'contract_id'=>$contract_id,
                                'lot_id'=>$lot_id,
                                'product_category_id'=>$product_category_id,
                                'product_id'=>$key,
                                'quantity'=>trim($quantity),
                                'uom'=>$uom[$key],
                                'planned_inspection_date'=>($planned_delivery_date)?$planned_delivery_date:'0000-00-00',
                                'actual_inspection_date'=>($planned_delivery_date)?$planned_delivery_date:'0000-00-00',
                                'unit_weight'=>$unit_weight[$key],
                                'unit_weight_uom'=>$unit_weight_uom[$key],
                                'added_by'=>$UserID
                        )
                    );                    
                }
                #echo '<pre>';print_r($saveData);exit;
                /***************save production data****************/
                $count_product= count($saveData);
                if($saveData>0){
                    $this->Inspection->create();
                    if($this->Inspection->saveMany($saveData))
                    {
                        $this->Session->setFlash(__($count_product.' products has been saved successfully.'));
                        return $this->redirect($this->referer());
                    }
                    else{
                        $this->Session->setFlash(__('Product could not saved successfully.Please, try again.'));
                        return $this->redirect($this->referer());
                    }
                }
                else{
                    $this->Session->setFlash(__('There is no product for saved.Please, try again.'));
                    return $this->redirect($this->referer());
                }
                /* * *************end of save Delivery data*************** */
            }# End of Sumbit Form
            else{
              $sql="SELECT ins.uom,ins.unit_weight,ins.unit_weight_uom,ins.product_id,ins.product_category_id,(select name from products as p where ins.product_id=p.id) product_name,(select name from product_categories as pc where ins.product_category_id=pc.id) as category_name,SUM(ins.quantity) as ins_qty,(select sum(quantity) as quantity from inspections as d where ins.product_id=d.product_id and ins.contract_id=d.contract_id and ins.lot_id=d.lot_id group by d.product_id)as delivery_qty from lot_products as ins WHERE ins.product_category_id=".$product_category_id." AND ins.contract_id=".$contract_id." AND ins.lot_id='".$lot_id."' GROUP BY ins.product_id";
              $inspection_results=$this->Inspection->query($sql);
              #echo '<pre>';print_r($inspection_results);exit;
             }
        }
       
       if($contract_id){
            $this->loadModel('Lot');
            $lots_nos=  $this->Lot->find('list',array('conditions'=>array('Lot.contract_id'=>$contract_id))); 
            foreach ($lots_nos as $lots_no)
            {
                $lots[$lots_no]=$lots_no;
            }
            #echo '<pre>';print_r($lots);exit;
        }
          #check if product category is set
        if($product_category_id){
             $sql="SELECT c.id,c.contract_no,lot.lot_qty as lot_qty,ins.ins_qty as ins_qty FROM contracts AS c LEFT JOIN ( SELECT SUM(quantity) as lot_qty,contract_id,product_category_id FROM lot_products GROUP BY product_category_id,contract_id ) as lot ON c.id=lot.contract_id LEFT JOIN ( SELECT SUM(quantity) as ins_qty,contract_id,product_category_id FROM inspections GROUP BY product_category_id,contract_id ) as ins ON c.id=ins.contract_id WHERE lot.product_category_id=$product_category_id ORDER BY lot.contract_id DESC";
             $cons=  $this->Inspection->query($sql); 
             
             foreach ($cons as $key => $value) {
                 #echo '<pre>';print_r($value);exit;
                 $balance=$value['lot']['lot_qty']-$value['ins']['ins_qty'];
                 $contracts[$value['c']['id']]=$value['c']['contract_no'].'('.$balance.')';
             }
        }  
            #ProductCategory list box
            $this->loadModel('ProductCategory');
            $product_categories = $this->ProductCategory->find('list');
            
            $this->set(compact('date','date_type','contracts','lots','contract_id','lot_id','inspection_results','result','actual_date_results','product_categories','product_category_id'));
       
    }
    
    public function __getInspectionProducts(&$option)
    {  
        if(empty($option)){
            return '';
        }        
        $condition = array('conditions' => array($option), 'fields' => array('Inspection.product_id', 'SUM(Inspection.quantity) as quantity',/* 'MAX(Inspection.planned_completion_date) planned_completion_date', 'MAX(Inspection.actual_completion_date) actual_completion_date',*/'Inspection.lot_id'), 'group' => array('Inspection.contract_id', 'Inspection.lot_id', 'Inspection.product_id'),'order'=>array('Inspection.product_id'=>'ASC'));
        $pmt_products = $this->Inspection->find('all', $condition);
        $data = array();
        foreach ($pmt_products as $key => $value) {
            $data[$value['Inspection']['product_id']] = $value[0]['quantity'];
          /*  $data['pd_' . $value['Inspection']['product_id']] = $value[0]['planned_completion_date'];
            $data['ad_' . $value['Inspection']['product_id']] = $value[0]['actual_completion_date'];*/
        }
        return $data;
    }
  
    public function actual_inspection_date_editing(){
        $this->autoRender = FALSE;
        if ($this->request->data) {
            $user = $this->Session->read('UserAuth');
            $UserID =$user['User']['username'];
            $this->layout = 'ajax';
            $id = $this->request->data['id'];
            if (isset($this->request->data['actual_date_update'])) {
                $actual_date_update =str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['actual_date_update'])));
            }
            $app_conl=new AppController();
            $check=$app_conl->validateDate($actual_date_update);
             $message = "Wrong:Date Format!";
            if($check):
                // $actual_date_update=$this->request->data['actual_date_update'];
                $this->Inspection->id = $id;
                 $this->beforeRender();
                if ($this->Inspection->saveField('actual_inspection_date', $actual_date_update, false)) {
                    $this->Inspection->saveField('modified_by', $UserID, false);
                    $this->Inspection->saveField('modified_date', date('Y-m-d h:m:i'), false);
                    $message = "Record updated successfully.";
                } else {
                    $message = "Error:There is an error while record updating!";
                }
           endif;
            echo $message;
           
            // $this->set(compact('actual_date_update','message'));
        }
    }
    
     public function actual_inspection_date_editing_all()
    {        
      $this->layout='ajax';
      $this->request->accepts('application/json');
      $data=$this->request->input ('json_decode', true);
      $user=$this->Session->read('UserAuth');
      $UserID=$user['User']['username'];
      $sql='';
      if($data)
      {
          foreach ($data as $value){
            $id=$value['id'];
            $actual_date=$value['actual_date'];
                if($id&&$actual_date){
                $sql.="UPDATE inspections SET actual_inspection_date='".$actual_date."', modified_by= '".$UserID."', modified_date= '".date('Y-m-d H:m:s')."' WHERE id =$id;";
            }
          }          
         if($this->Inspection->query($sql)){
              echo'1';
          }
          else{
              echo'2';
          }
      }
     $this->autoRender = FALSE;      
    }
    
    public function __getInspectionProductForDelivery(&$option)
    {
     if(empty($option)){
            return '';
        }
        $condition = array('conditions' => array($option), 'fields' => array('Inspection.product_id', 'Product.name', 'SUM(Inspection.quantity) as quantity','Inspection.unit_weight','Inspection.unit_weight_uom','Inspection.uom','Inspection.lot_id','ProductCategory.name'), 'group' => array('Inspection.contract_id', 'Inspection.lot_id', 'Inspection.product_id'));
        $pmt_products = $this->Inspection->find('all', $condition); 
        return $pmt_products;   
    }
     public function delete() {
        $this->layout = 'ajax';
        $this->autoRender=false;
        #find the product option
        $this->Inspection->id = $id=$this->request->data['id'];
        if (!$this->Inspection->exists()) {
            //throw new NotFoundException(__('Invalid production product'));
            echo "0";
            exit();
        }
        else{# valid id is found
            $this->Inspection->recursive=-1;
            $product=  $this->Inspection->findById($id);

            $product_id=$product['Inspection']['product_id'];
            $lot_id=$product['Inspection']['lot_id'];
            $actual_inspection_date=$product['Inspection']['actual_inspection_date'];
            if($actual_inspection_date=="0000-00-00")
            {
                $this->request->allowMethod('post', 'delete');
                if ($this->Inspection->delete()) {
                    echo "1";
                } else {
                     echo "0";
                }
            }
            else{
             $option1=array(
                'conditions'=>array(
                    'Delivery.product_id'=>$product_id,
                    'Delivery.lot_id'=>$lot_id
                )
            );
                $this->loadModel('Delivery');
                $this->Delivery->recursive=-1;

                if(!$this->Delivery->find('first',$option1)):#check the product of that lot is in delivery        
                    $this->request->allowMethod('post', 'delete');
                    if ($this->Inspection->delete()) {
                        echo "1";
                    } else {
                         echo "0";
                    }
                else:
                    echo '0';
                endif;  #/check the product of that lot is in inspection 
            }
        }#/valid id is found
    }
    
  public function getContractByCategory()
    {
        $this->layout = 'ajax';
        $product_category_id=$this->request->data['Inspection']['product_category_id'];
        if($product_category_id){
        $sql="SELECT c.id,c.contract_no,lot.lot_qty as lot_qty,ins.ins_qty as ins_qty FROM contracts AS c LEFT JOIN ( SELECT SUM(quantity) as lot_qty,contract_id,product_category_id FROM lot_products GROUP BY product_category_id,contract_id ) as lot ON c.id=lot.contract_id LEFT JOIN ( SELECT SUM(quantity) as ins_qty,contract_id,product_category_id FROM inspections GROUP BY product_category_id,contract_id ) as ins ON c.id=ins.contract_id WHERE lot.product_category_id=$product_category_id ORDER BY lot.contract_id DESC";
        $contracts=  $this->Inspection->query($sql);  
        #echo '<pre>';print_r($contracts);exit;
        }
        $this->set(compact('contracts'));
    }
	 /* this function transfer the product from one lot to another */
  public function product_transfer_lot_to_lot() {
        if ($this->request->is('post')) {
            $contract_id = $this->request->data['Inspection']['contract_id'];
            $lot_id = $this->request->data['Inspection']['lot_id'];
            $product_category_id = $this->request->data['Inspection']['product_category_id'];
            $check_form_submit = $this->request->data['Inspection']['check_form_submit'];

            $user = $this->Session->read('UserAuth');
            $UserID = $user['User']['username'];

            if (!$contract_id || !$lot_id || !$product_category_id) {
                $this->Session->setFlash(__('PO/LOT/Category input fields are required.Please, try again.'));
                $this->redirect($this->referer());
            }
            if ($check_form_submit == "submitted") {
                //Transfer lot number
                $transfer_lot = $this->request->data['Inspection']['transfer_lot'];

                if (!$contract_id || !$lot_id || !$product_category_id || !$transfer_lot) {
                    $this->Session->setFlash(__('1.PO/LOT/Category input fields are required.Please, try again.'));
                    $this->redirect($this->referer());
                }
              
                
                $product_ids = $this->request->data['Inspection']['product_ids'];
                $product_ids = explode("-", $product_ids);

                $data = '';
                //inspection product sum with filter options
                $model = "Inspection";
                $condition = array(
                    'conditions' => array(
                        "$model.contract_id" => $contract_id,
                        "$model.lot_id" => $lot_id,
                        "$model.product_category_id" => $product_category_id,
                        "$model.product_id" => $product_ids
                    ),
                    'fields' => array("$model.product_id", "SUM($model.quantity) as quantity"),
                    'group' => array("$model.product_id")
                );

                $this->loadModel($model);
                $inspections = $this->$model->find('all', $condition);
               
                //initialization
                foreach ($inspections as $value) {
                    $data[$model][$value[$model]['product_id']] = $value[0]['quantity'];
                }
                 
                //end inspection
                //production product sum with filter options
                $model = "Delivery";
                $condition = array(
                    'conditions' => array(
                        "$model.contract_id" => $contract_id,
                        "$model.lot_id" => $lot_id,
                        "$model.product_category_id" => $product_category_id,
                        "$model.product_id" => $product_ids
                    ),
                    'fields' => array("$model.product_id", "SUM($model.quantity) as quantity"),
                    'group' => array("$model.product_id")
                );

                $this->loadModel($model);
                $productions = $this->$model->find('all', $condition);
                //initalization
                foreach ($productions as $value) {
                    $data[$model][$value[$model]['product_id']] = $value[0]['quantity'];
                }               
                //end Delivery summation 
                //sql query initialization 
                $sql_product_update = '';
                $sql_new_production_entry = '';
                foreach ($product_ids as $value) {

                    #Product Balance
                    $product_id = $value;
                    $update_qty = ($data['Delivery'][$product_id] > 0) ? $data['Delivery'][$product_id] : 0;

                    $pro_bal = $data['Inspection'][$product_id] - $update_qty;
                    $pro_bal = $new_qty = ($pro_bal > 0) ? $pro_bal : 0;
                    if ($pro_bal > 0) { //if cond_1 check the balace qty is greater than 0
                        // All production production  List with filter options which will be updated
                        $model = "Inspection";
                        $condition = array(
                            'conditions' => array(
                                "$model.contract_id" => $contract_id,
                                "$model.lot_id" => $lot_id,
                                "$model.product_category_id" => $product_category_id,
                                "$model.product_id" => $product_id
                            ),
                            'order' => array("$model.quantity" => "DESC")
                        );

                        $this->$model->recursive = -1;
                        $productions_all = $this->$model->find('all', $condition);

                        foreach ($productions_all as $value) {
                            $id = $value[$model]['id'];
                            $uom = $value[$model]['uom'];
                            $unit_weight = $value[$model]['unit_weight'];
                            $unit_weight_uom = $value[$model]['unit_weight_uom'];
                            $planned_completion_date = $value[$model]['planned_inspection_date'];
                            $actual_completion_date = $value[$model]['actual_inspection_date'];
                            $quantity = $value[$model]['quantity'];

                            //check balance quantity and production quantity of single row
                            if ($quantity >= $pro_bal) {
                                $update_qty = $quantity - $pro_bal;
                                //update product qty
                                if ($id) {
                                    $sql_product_update.="UPDATE inspections SET quantity =$update_qty WHERE id=  $id;";
                                }
                                $pro_bal = $quantity - $pro_bal;
                                break;
                            } else {
                                $pro_bal = $pro_bal - $quantity; //reduce the balace and set row qty is 0
                                
                                if ($id) {
                                    $sql_product_update.="UPDATE inspections SET quantity =0 WHERE id=  $id;";
                                }
                            }
                        }
                        #check the existing new lot and product in production table
                        $option = array(
                            'conditions' => array(
                                'Inspection.contract_id' => $contract_id,
                                'Inspection.lot_id' => $transfer_lot, //new lot id where product will be transfered
                                'Inspection.product_category_id' => $product_category_id,
                                'Inspection.product_id' => $product_id
                            )
                        );
                        $this->Inspection->recursive = -1;
                        $chek_new_pro_with_lot = $this->Inspection->find('first', $option);

                        if ($chek_new_pro_with_lot) {//update
                            $new_qty = $chek_new_pro_with_lot['Inspection']['quantity'] + $new_qty; //addition 
                            $id = $chek_new_pro_with_lot['Inspection']['id'];
                            if ($id) {
                                $sql_product_update.="UPDATE inspections SET quantity= $new_qty WHERE id=$id;";
                            }
                        } else { //new product entry with balance quantity
                            $sql_new_production_entry.="INSERT INTO inspections VALUES (NULL, '" . $contract_id . "', '" . $transfer_lot . "', '" . $product_category_id . "', '" . $product_id . "', '" . $new_qty . "', '" . $uom . "', '" . $unit_weight . "', '" . $unit_weight_uom . "', '" . $planned_completion_date . "', '" . $actual_completion_date . "', '','" . $UserID . "', '" . date('Y-m-d H:m:s') . "', '', '0000-00-00 00:00:00');";
                        }
                    }//end of if cond_1 check the balace qty is greater than 0
                }
                //execute the query
                 
               
                if($sql_product_update){
                $this->Inspection->query($sql_product_update);
                }
                if($sql_new_production_entry){
                $this->Inspection->query($sql_new_production_entry);
                }
 
                
             //production 
        $model = "Inspection";
        $condition = array(
            'conditions' => array(
                "$model.contract_id" => $contract_id,
                "$model.lot_id" => $lot_id,
                "$model.product_category_id" => $product_category_id
            ),
            'fields' => array("$model.product_id", "SUM($model.quantity) as quantity"),
            'group' => array("$model.product_id")
        );

        $this->loadModel($model);
        $productions = $this->$model->find('all', $condition);
        foreach ($productions as $value) {
            $data[$model][$value[$model]['product_id']] = $value[0]['quantity'];
        }

        //LOT Products
        $model = "LotProduct";
        $condition = array(
            'conditions' => array(
                "$model.contract_id" => $contract_id,
                "$model.lot_id" => $lot_id,
                "$model.product_category_id" => $product_category_id
            ),
            'fields' => array("$model.product_id", "$model.quantity", "$model.id", "$model.product_category_id", "$model.product_id", "$model.uom", "$model.unit_weight", "$model.unit_weight_uom"),
            'group' => array("$model.product_id")
        );

        $this->loadModel($model);
        $lot_products = $this->$model->find('all', $condition);
        
        $sql_product_update='';
        $sql_new_production_entry='';
        
        foreach ($lot_products as $value) {

            $product_id = $value['LotProduct']['product_id'];
            $id = $value['LotProduct']['id'];
            $uom = $value['LotProduct']['uom'];
            $unit_weight = $value['LotProduct']['unit_weight'];
            $unit_weight_uom = $value['LotProduct']['unit_weight_uom'];

            #LOT Balance
            $pro_bal = $value['LotProduct']['quantity'] - $data['Inspection'][$product_id];
            $pro_bal = ($pro_bal > 0) ? $pro_bal : 0; 
            if ($pro_bal > 0) {
                $update_qty = ($data['Inspection'][$product_id] > 0) ? $data['Inspection'][$product_id] : 0;
                if ($id) {
                    $sql_product_update.="UPDATE lot_products SET quantity= $update_qty WHERE id=$id;";
                    #check the existing new lot and product
                    $option = array(
                        'conditions' => array(
                            'LotProduct.contract_id' => $contract_id,
                            'LotProduct.lot_id' => $transfer_lot, //new lot id where product will be transfered
                            'LotProduct.product_category_id' => $product_category_id,
                            'LotProduct.product_id' => $product_id
                        )
                    );
                    $this->LotProduct->recursive = -1;
                    $chek_new_pro_with_lot = $this->LotProduct->find('first', $option);
                    if ($chek_new_pro_with_lot) {//update
                        $new_qty = $chek_new_pro_with_lot['LotProduct']['quantity'] + $pro_bal; //addition 
                        $id = $chek_new_pro_with_lot['LotProduct']['id'];
                        if ($id) {
                            $sql_product_update.="UPDATE lot_products SET quantity= $new_qty WHERE id=$id;";
                        }
                    } else { //insert into lot products with lot no and quantity
                        $sql_new_production_entry.="INSERT INTO lot_products VALUES (NULL, '" . $contract_id . "', '" . $transfer_lot . "', '" . $product_category_id . "', '" . $product_id . "', '" . $pro_bal . "', '0', '0', '" . $uom . "', '" . $unit_weight . "', '" . $unit_weight_uom . "', '', '" . $UserID . "', '" . date('Y-m-d H:m:s') . "', '', '0000-00-00 00:00:00');";
                    }
                }
            }/* lot calculation end here */
        }
        //execute the query
        
        if($sql_product_update){
        $this->Inspection->query($sql_product_update);
        }
        if($sql_new_production_entry){
        $this->Inspection->query($sql_new_production_entry);
        }
        $this->Session->setFlash(__('Product Transfer From One Lot To Another has been successfull.'));
        $this->redirect(array('action'=>'product_transfer_lot_to_lot'))  ;
            }

            $data = '';
            /* inspection */
            $model = "Inspection";
            $condition = array(
                'conditions' => array(
                    "$model.contract_id" => $contract_id,
                    "$model.lot_id" => $lot_id,
                    "$model.product_category_id" => $product_category_id
                ),
                'fields' => array("$model.product_id", "SUM($model.quantity) as quantity"),
                'group' => array("$model.product_id")
            );

            $this->loadModel($model);
            $inspections = $this->$model->find('all', $condition);
            foreach ($inspections as $value) {
                $data[$model][$value[$model]['product_id']] = $value[0]['quantity'];
            }
            /* production */
            $model = "Delivery";
            $condition = array(
                'conditions' => array(
                    "$model.contract_id" => $contract_id,
                    "$model.lot_id" => $lot_id,
                    "$model.product_category_id" => $product_category_id
                ),
                'fields' => array("$model.product_id", "SUM($model.quantity) as quantity"),
                'group' => array("$model.product_id")
            );

            $this->loadModel($model);
            $productions = $this->$model->find('all', $condition);
            foreach ($productions as $value) {
                $data[$model][$value[$model]['product_id']] = $value[0]['quantity'];
            } 
            /* LOT Products */
            $model = "LotProduct";
            $condition = array(
                'conditions' => array(
                    "$model.contract_id" => $contract_id,
                    "$model.lot_id" => $lot_id,
                    "$model.product_category_id" => $product_category_id
                ),
                'fields' => array("$model.product_id", "$model.quantity", 'Product.name', "$model.id", "$model.product_category_id", "$model.product_id", "$model.uom", "$model.unit_weight", "$model.unit_weight_uom"),
                'group' => array("$model.product_id")
            );

            $this->loadModel($model);
            $lot_products = $this->$model->find('all', $condition);

            /* Find the lot no by contract ID */
            $this->loadModel('Lot');
            $lots = $this->Lot->find('list', array('conditions' => array('Lot.contract_id' => $contract_id), 'fields' => array('Lot.lot_no', 'Lot.lot_no')));
            /* Find the Transfered lot no by contract ID */
            
            $lot_trans = $this->Lot->find('list', array('conditions' => array('Lot.contract_id' => $contract_id,'Lot.lot_no NOT LIKE' => $lot_id), 'fields' => array('Lot.lot_no', 'Lot.lot_no')));
        }
        
        
        
         if($product_category_id){
             $sql="SELECT c.id,c.contract_no,lot.lot_qty as lot_qty,ins.ins_qty as ins_qty FROM contracts AS c LEFT JOIN ( SELECT SUM(quantity) as lot_qty,contract_id,product_category_id FROM lot_products GROUP BY product_category_id,contract_id ) as lot ON c.id=lot.contract_id LEFT JOIN ( SELECT SUM(quantity) as ins_qty,contract_id,product_category_id FROM inspections GROUP BY product_category_id,contract_id ) as ins ON c.id=ins.contract_id WHERE lot.product_category_id=$product_category_id ORDER BY lot.contract_id DESC";
             $cons=  $this->Inspection->query($sql); 
             
             foreach ($cons as $key => $value) {
                 #echo '<pre>';print_r($value);exit;
                 $balance=$value['lot']['lot_qty']-$value['ins']['ins_qty'];
                 $contracts[$value['c']['id']]=$value['c']['contract_no'].'('.$balance.')';
             }
        }  

        #ProductCategory list box
        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list');

        $this->set(compact('contracts','product_category_id', 'contract_id', 'lot_id', 'lots', 'data', 'lot_products', 'product_categories','lot_trans'));
    }

}
