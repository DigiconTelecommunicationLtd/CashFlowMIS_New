<?php

App::uses('AppController', 'Controller');

/**
 * Deliveries Controller
 *
 * @property Delivery $Delivery
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class DeliveriesController extends AppController {

    public function add() { 
        if ($this->request->is('post')) {
            #set the date and date type
            $date =$this->request->data['Delivery']['date'];
            #set the contract/lot id/submission type            
            $contract_id =$this->request->data['Delivery']['contract_id'];
            $lot_id =$this->request->data['Delivery']['lot_id'];
            $FormType =$this->request->data['Delivery']['FormType'];
            $product_category_id=$this->request->data['Delivery']['product_category_id'];
            
            #check the below valiable value set  
            if (!$contract_id || !$lot_id||!$product_category_id) {
                $this->Session->setFlash(__('PO/Lot/Product Category is required!. Please, try again.'));
                return $this->redirect($this->referer());
            }
            #option for search from inspection table
            $option['Inspection.contract_id'] = $contract_id;
            $option['Inspection.product_category_id'] = $product_category_id;            
            $option['Inspection.lot_id'] = (string) $lot_id; 
            
            #option for delivery to check delivery quantity
            $option_del['Delivery.contract_id'] = $contract_id;
            $option_del['Delivery.product_category_id'] = $product_category_id;            
            $option_del['Delivery.lot_id'] = (string) $lot_id;  
            
            #set default lot no
            $lots[$lot_id] = $lot_id; 
            
            #if form is submitted then product information will be inserted into delivery table
            if ($FormType == 'submit'){
                #check the Actual date set
                
                if(!$date){
                   $this->Session->setFlash(__('Actaul Delivery Date is required!. Please, try again.'));
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
                $pquantitys = $this->request->data['Delivery']['quantity'];
                
                #$actual=$this->request->data['Delivery']['actual'];
                $user = $this->Session->read('UserAuth');
                $UserID = $user['User']['username'];
                
               
               
                        $planned_delivery_date = str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($date)));
                        if ($planned_delivery_date == "1970-01-01") {
                            $this->Session->setFlash(__('Wrong format of Delivery Date!. Please, try again(Date Format is YYYY-MM-DD).'));
                            return $this->redirect($this->referer());
                        }
                
                    #Calculated Planned PLI date
                    $planned_date_of_pli = $contract['Contract']['pli_pac'] * 86400 + strtotime($planned_delivery_date);
                    $planned_date_of_pli = date('Y-m-d', $planned_date_of_pli);
                    #Calculated Planned PLI Approval date
                    $planned_date_of_pli_approval = strtotime($planned_date_of_pli) + $contract['Contract']['pli_aproval'] * 86400;
                    $planned_date_of_pli_approval = date('Y-m-d', $planned_date_of_pli_approval);
                    #planned_rr_collection_date
                    $rr_collection_progressive=strtotime($planned_date_of_pli_approval)+$contract['Contract']['rr_collection_progressive']* 86400;
                    $rr_collection_progressive= date('Y-m-d', $rr_collection_progressive);
                    #Planned invoice_submission_progressive
                    $planned_submission_date = strtotime($rr_collection_progressive)+ $contract['Contract']['invoice_submission_progressive'] * 86400;
                    $planned_submission_date = date('Y-m-d', $planned_submission_date);                        
                    #Planned payment_cheque_collection_progressive
                    $planned_payment_certificate_or_cheque_collection_date=strtotime($planned_submission_date)+$contract['Contract']['payment_cheque_collection_progressive'] * 86400;
                    $planned_payment_certificate_or_cheque_collection_date = date('Y-m-d', $planned_payment_certificate_or_cheque_collection_date);
                    #Planned payment_credited_to_bank_progressive
                    $payment_credited_to_bank_progressive=strtotime($planned_payment_certificate_or_cheque_collection_date)+$contract['Contract']['payment_credited_to_bank_progressive'] * 86400;
                    $payment_credited_to_bank_progressive = date('Y-m-d', $payment_credited_to_bank_progressive);
                    #End of Progressive payment date of assumption 
                    $total_qty=0;
                    $total_Delivery_value=0;
                foreach ($pquantitys as $key => $quantity) {

                    /*****************of check lot qty and production qty ******************* */
                    #if qty and date not set properly
                    if (!$quantity || $quantity <= 0) {
                        continue;
                    }
                    #*********** find the inspection Qty with search criteria
                      $option['Inspection.product_id']=$key;
                      $option_del['Delivery.product_id']= $key;
                      
                      $this->loadModel('Inspection');   
                      $this->Inspection->recursive=-1;
                      #echo '<pre>';print_r($option);
                      $inspection_qty=$this->Inspection->find('first',array('conditions'=>array($option),'fields'=>array('SUM(Inspection.quantity) as ins_qty')));
                      #echo '<pre>';print_r($inspection_qty);
                      $inspection_qty=($inspection_qty[0]['ins_qty']>0)?$inspection_qty[0]['ins_qty']:0;

                      #check inspection qty with product id lot number
                   
                       
                      $this->Delivery->recursive=-1;
                      $delivery_qty=$this->Delivery->find('first',array(
                      'conditions'=>array($option_del)
                      ,
                      'fields'=>array(
                      'SUM(Delivery.quantity) as quantity'
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
                      $this->Session->setFlash(__('Delivery Quantity is greater than Inspection Quantity!. Please, try again.'));
                      return $this->redirect($this->referer());
                      }
                  #Store sumbitted value in array

                    $quantity = trim($quantity);
                    $total_qty+=$quantity;
                    $total_Delivery_value+=$quantity*$unit_price[$key];
                    $cur=$currency[$key];
                    $saveData[] = array(
                        'Delivery' => array(
                            'contract_id' => $contract_id,
                            'lot_id' => $lot_id,
                            'product_category_id' => $category[$key],
                            'product_id' => $key,
                            'quantity' =>$quantity,
                            'unit_price' => $unit_price[$key],
                            'delivery_value' =>$quantity*$unit_price[$key],
                            'planned_invoice_amount'=>$quantity*$unit_price[$key]*$contract['Contract']['billing_percent_progressive']*0.01,
                            'currency' => $currency[$key],
                            'uom' => $uom[$key],
                            'planned_delivery_date' => $planned_delivery_date,
                            'actual_delivery_date' => ($planned_delivery_date) ? $planned_delivery_date : '0000-00-00',
                            'planned_pli_date' => ($planned_date_of_pli) ? $planned_date_of_pli : '0000-00-00',
                            'planned_date_of_pli_aproval' => ($planned_date_of_pli_approval) ? $planned_date_of_pli_approval : '0000-00-00',
                            'planned_date_of_installation' => ($planned_date_of_pli) ? $planned_date_of_pli : '0000-00-00',
                            'planned_date_of_client_receiving' => ($planned_date_of_pli_approval) ? $planned_date_of_pli_approval : '0000-00-00',
                            
                            'planned_rr_collection_date' => ($rr_collection_progressive) ? $rr_collection_progressive : '0000-00-00',
                            'invoice_submission_progressive' => ($planned_submission_date) ? $planned_submission_date : '0000-00-00',
                            'payment_cheque_collection_progressive' => ($planned_payment_certificate_or_cheque_collection_date) ? $planned_payment_certificate_or_cheque_collection_date : '0000-00-00',
                            'payment_credited_to_bank_progressive' => ($payment_credited_to_bank_progressive) ? $payment_credited_to_bank_progressive: '0000-00-00',
                            
                            'unit_weight' => $unit_weight[$key],
                            'unit_weight_uom' => $unit_weight_uom[$key],
                            'added_by' => $UserID,
                            'clientid' => $clientid,
                            'unitid' => $unitid
                        )
                    );
                    #$option_del='';
                }
                
                 $summaryData= array(
                        'DeliverySummary' => array(
                            'contract_id' => $contract_id,
                            'lot_id' => $lot_id,
                            'product_category_id' =>$product_category_id,                            
                            'quantity' =>$total_qty,                           
                            'delivery_amount' =>$total_Delivery_value,
                            'planned_invoice_amount'=>$total_Delivery_value*$contract['Contract']['billing_percent_progressive']*0.01,
                            'currency' => $cur,
                            'actual_delivery_date' => ($planned_delivery_date) ? $planned_delivery_date : '0000-00-00',
                            'planned_delivery_date' => $planned_delivery_date,                             
                            'planned_pli_date' => ($planned_date_of_pli) ? $planned_date_of_pli : '0000-00-00',
                            'planned_date_of_pli_aproval' => ($planned_date_of_pli_approval) ? $planned_date_of_pli_approval : '0000-00-00',
                            'planned_date_of_installation' => ($planned_date_of_pli) ? $planned_date_of_pli : '0000-00-00',
                            'planned_date_of_client_receiving' => ($planned_date_of_pli_approval) ? $planned_date_of_pli_approval : '0000-00-00',                            
                            'planned_rr_collection_date' => ($rr_collection_progressive) ? $rr_collection_progressive : '0000-00-00',
                            'invoice_submission_progressive' => ($planned_submission_date) ? $planned_submission_date : '0000-00-00',
                            'payment_cheque_collection_progressive' => ($planned_payment_certificate_or_cheque_collection_date) ? $planned_payment_certificate_or_cheque_collection_date : '0000-00-00',
                            'payment_credited_to_bank_progressive' => ($payment_credited_to_bank_progressive) ? $payment_credited_to_bank_progressive: '0000-00-00',
                            'added_date' => date('Y-m-d'),        
                            'added_by' => $UserID,                             
                        )
                    );
                #***************Save Multiple Delivery data*************** */
		 
                $count_product = count($saveData);
                if ($saveData > 0) {
                    $this->Delivery->create();
                    if ($this->Delivery->saveMany($saveData)) {
                        $this->Session->setFlash(__($count_product . ' products has been saved successfully.'));
                        #Load delivery Summary Model
                        $this->loadModel('DeliverySummary');
                        $this->DeliverySummary->create();
                        $this->DeliverySummary->save($summaryData);
                        return $this->redirect($this->referer());
                    } else {
                        $this->Session->setFlash(__('Product could not saved successfully.Please, try again.'));
                        return $this->redirect($this->referer());
                    }
                } else {
                    $this->Session->setFlash(__('There is no product for saved.Please, try again.'));
                    return $this->redirect($this->referer());
                }                
            /* * *************end of save Delivery data*************** */
            }# End of Sumbit Form
            else {
               $sql="SELECT ins.uom,ins.unit_weight,unit_weight_uom,ins.product_id,ins.product_category_id,(select name from products as p where ins.product_id=p.id) product_name,(select name from product_categories as pc where ins.product_category_id=pc.id) as category_name,SUM(ins.quantity) as ins_qty,(select sum(quantity) as quantity from deliveries as d where ins.product_id=d.product_id and ins.contract_id=d.contract_id and ins.lot_id=d.lot_id group by d.product_id)as delivery_qty from inspections as ins WHERE ins.product_category_id=".$product_category_id." AND ins.contract_id=".$contract_id." AND ins.lot_id='".$lot_id."' GROUP BY ins.product_id";
               $inspection_results=  $this->Delivery->query($sql);
              }                
            }
         #ProductCategory list box
        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list');
        #check if product category is set
        if($product_category_id){
             $sql="SELECT c.id,c.contract_no,ins.ins_qty as ins_qty,d.delivery_qty as delivery_qty FROM contracts AS c LEFT JOIN ( SELECT SUM(quantity) as ins_qty,contract_id,product_category_id FROM inspections GROUP BY product_category_id,contract_id ) as ins ON c.id=ins.contract_id LEFT JOIN ( SELECT SUM(quantity) as delivery_qty,contract_id,product_category_id FROM deliveries GROUP BY product_category_id,contract_id ) as d ON c.id=d.contract_id where ins.product_category_id=$product_category_id ORDER BY ins.contract_id DESC";
             $cons=  $this->Delivery->query($sql); 
             
             foreach ($cons as $key => $value) {
                 #echo '<pre>';print_r($value);exit;
                 $balance=$value['ins']['ins_qty']-$value['d']['delivery_qty'];
                 $contracts[$value['c']['id']]=$value['c']['contract_no'].'('.$balance.')';
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
        #set the result
        $this->set(compact('product_category_id','date', 'date_type', 'contracts', 'lots', 'contract_id', 'lot_id', 'inspection_results', 'product_categories'));
    }

    public function __getDeliveryProducts(&$option) {
        if (empty($option)) {
            return '';
        }
        $condition = array('conditions' => array($option), 'fields' => array('Delivery.product_id', 'SUM(Delivery.quantity) as quantity', /* 'MAX(Delivery.planned_completion_date) planned_completion_date', 'MAX(Delivery.actual_completion_date) actual_completion_date', */ 'Delivery.lot_id'), 'group' => array('Delivery.contract_id', 'Delivery.lot_id', 'Delivery.product_id'), 'order' => array('Delivery.product_id' => 'ASC'));
        $pmt_products = $this->Delivery->find('all', $condition);
        $data = array();
        foreach ($pmt_products as $key => $value) {
            $data[$value['Delivery']['product_id']] = $value[0]['quantity'];
            /*  $data['pd_' . $value['Delivery']['product_id']] = $value[0]['planned_completion_date'];
              $data['ad_' . $value['Delivery']['product_id']] = $value[0]['actual_completion_date']; */
        }
        return $data;
    }

    public function actual_delivery_date_editing() {
        $this->autoRender = FALSE;
        if ($this->request->data) {
            $user = $this->Session->read('UserAuth');
            $UserID = $user['User']['username'];
            $this->layout = 'ajax';
            $id = $this->request->data['id'];
            if (isset($this->request->data['actual_date_update'])) {
                $actual_date_update = str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['actual_date_update'])));
            }
            $app_conl = new AppController();
            $check = $app_conl->validateDate($actual_date_update);
            $message = "Wrong:Date Format!";
            //check already exist
            if (!$this->Delivery->exists($id)) {
                throw new NotFoundException(__('Invalid Delivery'));
            } else {
                $contractID = $this->request->data['contractID'];
                App::uses('ContractsController', 'Controller');

                $contract = new ContractsController();
                $contract_assumption = $contract->__getAssumptionByContract($contractID);

                $planned_date_of_pli = $contract_assumption['Contract']['pli_pac'] * 86400 + strtotime($actual_date_update);
                $planned_date_of_pli = date('Y-m-d', $planned_date_of_pli);

                $planned_date_of_pli_approval = strtotime($planned_date_of_pli) + $contract_assumption['Contract']['pli_aproval'] * 86400;
                $planned_date_of_pli_approval = date('Y-m-d', $planned_date_of_pli_approval);
            }


            if ($check && $id):
                // $actual_date_update=$this->request->data['actual_date_update'];
                $this->Delivery->id = $id;
                $this->beforeRender();
                if ($this->Delivery->saveField('actual_delivery_date', $actual_date_update, false)) {

                    $this->Delivery->saveField('planned_pli_date', $planned_date_of_pli, false);
                    $this->Delivery->saveField('planned_date_of_pli_aproval', $planned_date_of_pli_approval, false);

                    /* client receiveing */
                    $this->Delivery->saveField('planned_date_of_installation', $planned_date_of_pli, false);
                    $this->Delivery->saveField('planned_date_of_client_receiving', $planned_date_of_pli_approval, false);

                    $this->Delivery->saveField('modified_by', $UserID, false);
                    $this->Delivery->saveField('modified_date', date('Y-m-d h:m:i'), false);
                    $message = "Record updated successfully.";
                } else {
                    $message = "Error:There is an error while record updating!";
                }
            endif;
            echo $message;

            // $this->set(compact('actual_date_update','message'));
        }
    }

    public function actual_delivery_date_editing_all() {
        $this->layout = 'ajax';
        $this->request->accepts('application/json');
        $data = $this->request->input('json_decode', true);
        $user = $this->Session->read('UserAuth');
        $UserID = $user['User']['username'];
        $sql = '';
        if ($data) {

            $contractID = $data[0]['contract_id'];

            App::uses('ContractsController', 'Controller');

            $contract = new ContractsController();
            $contract_assumption = $contract->__getAssumptionByContract($contractID);



            foreach ($data as $value) {
                $id = $value['id'];
                $actual_date = $value['actual_date'];
                if ($id && $actual_date && $contractID) {

                    $actual_date = str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($actual_date)));

                    $planned_date_of_pli = $contract_assumption['Contract']['pli_pac'] * 86400 + strtotime($actual_date);
                    $planned_date_of_pli = date('Y-m-d', $planned_date_of_pli);

                    $planned_date_of_pli_approval = strtotime($planned_date_of_pli) + $contract_assumption['Contract']['pli_aproval'] * 86400;
                    $planned_date_of_pli_approval = date('Y-m-d', $planned_date_of_pli_approval);
                    
                    
                     //planned_rr_collection_date
                    $rr_collection_progressive=strtotime($planned_date_of_pli_approval)+$contract_assumption['Contract']['rr_collection_progressive']* 86400;
                    $rr_collection_progressive= date('Y-m-d', $rr_collection_progressive);
                    //invoice_submission_progressive
                    $planned_submission_date = strtotime($rr_collection_progressive)+ $contract_assumption['Contract']['invoice_submission_progressive'] * 86400;
                    $planned_submission_date = date('Y-m-d', $planned_submission_date);                        
                    //payment_cheque_collection_progressive
                    $planned_payment_certificate_or_cheque_collection_date=strtotime($planned_submission_date)+$contract_assumption['Contract']['payment_cheque_collection_progressive'] * 86400;
                    $planned_payment_certificate_or_cheque_collection_date = date('Y-m-d', $planned_payment_certificate_or_cheque_collection_date);
                    //payment_credited_to_bank_progressive
                    $payment_credited_to_bank_progressive=strtotime($planned_payment_certificate_or_cheque_collection_date)+$contract_assumption['Contract']['payment_cheque_collection_progressive'] * 86400;
                    $payment_credited_to_bank_progressive = date('Y-m-d', $payment_credited_to_bank_progressive);
                        
                        $sql.="UPDATE deliveries SET "
                            . "actual_delivery_date='" . $actual_date . "',"
                            . "planned_pli_date='" . $planned_date_of_pli . "',"
                            . "planned_date_of_pli_aproval='" . $planned_date_of_pli_approval . "',"
                            . "planned_date_of_installation='" . $planned_date_of_pli . "',"
                            . "planned_date_of_client_receiving='" . $planned_date_of_pli_approval . "',"
                            . "planned_rr_collection_date='" . $rr_collection_progressive . "',"
                            . "invoice_submission_progressive='" . $planned_submission_date . "',"
                            . "payment_cheque_collection_progressive='" . $planned_payment_certificate_or_cheque_collection_date . "',"
                            . "payment_credited_to_bank_progressive='" . $payment_credited_to_bank_progressive . "',"
                            . "modified_by= '" . $UserID . "',"
                            . " modified_date= '" . date('Y-m-d H:m:s') . "'"
                            . " WHERE id =$id;";
                }
            }

            if ($this->Delivery->query($sql)) {
                echo'1';
            } else {
                echo'2';
            }
        }
        $this->autoRender = FALSE;
    }

   public function post_landing_inspection() {

        if ($this->request->is('post')) {
            #echo '<pre>';print_r($this->request->data);exit;
            #set the date and date type
            $actual_pli_date =$this->request->data['Delivery']['date'];
            $actual_pli_aproval =$this->request->data['Delivery']['date1'];
            #set the contract/lot id/submission type            
            $contract_id =$this->request->data['Delivery']['contract_id'];
            $lot_id =$this->request->data['Delivery']['lot_id'];
            $FormType =$this->request->data['Delivery']['FormType'];
            $product_category_id=$this->request->data['Delivery']['product_category_id'];
            
            #check the below valiable value set  
            if (!$contract_id || !$lot_id||!$product_category_id) {
                $this->Session->setFlash(__('PO/Lot/Product Category is required!. Please, try again.'));
                return $this->redirect($this->referer());
            } 
            #check if for is submitting for update value
             if ($FormType == 'submit'){
                  #Receive the submit form value
                #$this->Delivery->recursive=-1;
                $pquantitys = $this->request->data['Delivery']['quantity'];
                
                #$actual=$this->request->data['Delivery']['actual'];
                $user = $this->Session->read('UserAuth');
                $UserID = $user['User']['username']; 
               
                $actual_pli_date = str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($actual_pli_date)));
                if ($actual_pli_date == "1970-01-01") {
                    $this->Session->setFlash(__('Wrong format of Actual Date!. Please, try again(Date Format is YYYY-MM-DD).'));
                    return $this->redirect($this->referer());
                }

                $actual_pli_aproval = str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($actual_pli_aproval)));
                if ($actual_pli_aproval == "1970-01-01") {
                    $this->Session->setFlash(__('Wrong format of Actual PLI Date Approval!. Please, try again(Date Format is YYYY-MM-DD).'));
                    return $this->redirect($this->referer());
                }
                $sql="";
                $count_product=0;
                foreach ($pquantitys as $key => $quantity) {
                    /*****************of check Delivery qty and PLI qty ******************* */
                    #if qty and date not set properly
                    if (!$quantity || $quantity <= 0) {
                        continue;
                    }
                    if($key&&$quantity){
                    $sql_pli="SELECT quantity,pli_qty,product_id FROM deliveries as d WHERE d.id= $key;";
                    $previous_qty_pli=$this->Delivery->query($sql_pli);
                    $product_id=$previous_qty_pli[0]['d']['product_id'];
                    #echo '<pre>';print_r($product_id);exit;
                    $previous_pli=($previous_qty_pli[0]['d']['pli_qty'])?$previous_qty_pli[0]['d']['pli_qty']:0;
                    $deli_qty=($previous_qty_pli[0]['d']['quantity'])?$previous_qty_pli[0]['d']['quantity']:0;
                    
                    $sql_previous_deliver_pli="SELECT SUM(d.quantity) as delivery_qty,SUM(d.pli_qty) as pli_qty from deliveries as d WHERE d.product_category_id=".$product_category_id." AND d.contract_id=".$contract_id." AND d.lot_id='".$lot_id."' AND product_id=".$product_id."";
                    $previous_delivery=  $this->Delivery->query($sql_previous_deliver_pli);
                    
                    $pre_pli_qty=$previous_delivery[0][0]['pli_qty'];
                    $pre_deli_qty=$previous_delivery[0][0]['delivery_qty'];
                    
                    
                    # echo '<pre>';print_r($previous_delivery);exit;
                    
                  
                    
                    $total_pli=$pre_pli_qty+$quantity;
                    
                     if($total_pli>$pre_deli_qty)
                      {
                      $this->Session->setFlash(__('PLI Quantity is greater than Delivery Quantity!. Please, try again.'));
                      return $this->redirect($this->referer());
                      }
                     $count_product+=1; 
                     $update_pli_qty=$previous_pli+$quantity;
                    $sql.="UPDATE deliveries SET pli_qty =$update_pli_qty,actual_pli_date='".$actual_pli_date."',actual_date_of_pli_aproval='".$actual_pli_aproval."',actual_date_of_installation='".$actual_pli_date."',actual_date_of_client_receiving='".$actual_pli_aproval."' WHERE id= $key;";
                    }
                    
                }
                if($sql){
                        $this->Delivery->query($sql);
                        $this->Session->setFlash(__($count_product . ' products has been saved successfully.'));                        
                        return $this->redirect($this->referer());
                        }
                     else {
                          $this->Session->setFlash(__('PLI Product Qty could not saved successfully.Please, try again.'));
                          return $this->redirect($this->referer());
                     }
                      }
                      
            else{
                #find product for PLI                
                $sql="SELECT d.actual_delivery_date,d.planned_pli_date,d.planned_date_of_pli_aproval,d.id,d.uom,d.unit_weight,d.unit_weight_uom,d.product_id,d.product_category_id,(select name from products as p where d.product_id=p.id) product_name,(select name from product_categories as pc where d.product_category_id=pc.id) as category_name,SUM(d.quantity) as delivery_qty,SUM(d.pli_qty) as pli_qty from deliveries as d WHERE d.product_category_id=".$product_category_id." AND d.contract_id=".$contract_id." AND d.lot_id='".$lot_id."' GROUP BY d.product_id order by product_id asc";
                $inspection_results=  $this->Delivery->query($sql);
                 
            }
        }
        #ProductCategory list box
        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list');
        #check if product category is set
        if($product_category_id){
             $sql="SELECT (select id from contracts as c where c.id=d.contract_id) as id,(select contract_no from contracts as con_no where con_no.id=d.contract_id) as contract_no,SUM(d.quantity) as delivery_qty,SUM(d.pli_qty) as pli_qty from deliveries as d WHERE d.product_category_id=$product_category_id GROUP BY d.contract_id ORDER BY d.contract_id DESC";
             $cons=  $this->Delivery->query($sql);             
             foreach ($cons as $key => $value) {
                 #echo '<pre>';print_r($value);exit;
                 $balance=$value[0]['delivery_qty']-$value[0]['pli_qty'];
                 $contracts[$value[0]['id']]=$value[0]['contract_no'].'('.$balance.')';
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
        #set the result
#set the result
        $this->set(compact('product_category_id','date', 'date_type', 'contracts', 'lots', 'contract_id', 'lot_id', 'inspection_results', 'product_categories'));
    }

    public function actual_pli_date_editing() {
        $this->autoRender = FALSE;
        if ($this->request->data) {

            #initialize user name
            $user = $this->Session->read('UserAuth');
            $UserID = $user['User']['username'];
            $this->layout = 'ajax';

            #get the form submitted value            
            $id = $this->request->data['id'];
            $pli_qty = trim($this->request->data['pli_qty']);
            $rr_collection = $this->request->data['rr_collection'];
            #check the actual date in form 
            if (isset($this->request->data['actual_date_update'])) {
                $actual_date_update = str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['actual_date_update'])));
            } else {
                return "1.Wrong:Date Format!";
                exit;
            }

            #check the actual date aproval in form
            if (isset($this->request->data['actual_date_update_1'])) {
                $actual_date_update_1 = str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['actual_date_update_1'])));
            } else {
                return "2.Wrong:Date Format!";
                exit;
            }
            #check qty in form
            if ($pli_qty <= 0) {
                return "3.Invalid Qty!";
                exit;
            }
            $app_conl = new AppController();
            $check = $app_conl->validateDate($actual_date_update);
            if (!$check) {
                return "4.Wrong:Date Format!";
                exit;
            }
            $check_1 = $app_conl->validateDate($actual_date_update_1);
            if (!$check_1) {
                return "5.Wrong:Date Format!";
                exit;
            }

            //check already exist
            if (!$this->Delivery->exists($id)) {
                //throw new NotFoundException(__('Invalid Delivery'));
                return "6.Invalid Request!";
                exit;
            }
            $message = "7.Error:There is an error while record updating!";

            if ($check && $id && $check_1):
                // $actual_date_update=$this->request->data['actual_date_update'];
                $this->Delivery->id = $id;
                $this->beforeRender();
                if ($id) {
                    $this->Delivery->saveField('actual_pli_date', $actual_date_update, false);
                    $this->Delivery->saveField('actual_date_of_pli_aproval', $actual_date_update_1, false);
                    $rr_collection = strtotime($actual_date_update_1) + $rr_collection * 86400;
                    $rr_collection = date('Y-m-d', $rr_collection);
                    $this->Delivery->saveField('planned_rr_collection_date', $rr_collection, false);

                    $this->Delivery->saveField('actual_date_of_installation', $actual_date_update, false);
                    $this->Delivery->saveField('actual_date_of_client_receiving', $actual_date_update_1, false);
                    $this->Delivery->saveField('modified_by', $UserID, false);
                    $this->Delivery->saveField('modified_date', date('Y-m-d h:m:i'), false);

                    if ($this->Delivery->saveField('pli_qty', $pli_qty, false)):
                        $message = "Record updated successfully.";
                    endif;
                } else {
                    $message = "8.Error:There is an error while record updating!";
                }
            endif;
            echo $message;

            // $this->set(compact('actual_date_update','message'));
        }
    }

    public function actual_pli_date_editing_all() {
        $this->layout = 'ajax';
        $this->request->accepts('application/json');
        $data = $this->request->input('json_decode', true);
        $user = $this->Session->read('UserAuth');
        $UserID = $user['User']['username'];
        $sql = '';
        if ($data) {
            foreach ($data as $value) {
                $id = $value['id'];
                $actual_date = $value['actual_date'];
                $actual_date1 = $value['actual_date_1'];
                $pli_qty = $value['pli_qty'];
                $pli_qty_already = $value['pli_qty_already'];
                $pli_qty+=$pli_qty_already;
                $rr_collection = $value['rr_collection'];
                if ($id && $actual_date && $actual_date1 && $pli_qty>0) {

                    $rr_collection = strtotime($actual_date1) + $rr_collection * 86400;
                    $rr_collection = date('Y-m-d', $rr_collection);

                    $sql.="UPDATE deliveries SET pli_qty='" . $pli_qty . "',"
                            . "actual_pli_date='" . $actual_date . "',"
                            . "actual_date_of_pli_aproval='" . $actual_date1 . "',"
                            . "planned_rr_collection_date='" . $rr_collection . "',"
                            . "actual_date_of_installation='" . $actual_date . "',"
                            . "actual_date_of_client_receiving='" . $actual_date1 . "',"
                            . "modified_by= '" . $UserID . "', "
                            . " modified_date= '" . date('Y-m-d H:m:i') . "'"
                            . " WHERE id =$id;";
                }
            }
            $this->beforeRender();
            if ($this->Delivery->query($sql)) {
                echo'1';
            } else {
                echo'2';
            }
        }
        $this->autoRender = FALSE;
    }

    public function delete() {
        $this->layout = 'ajax';
        $this->autoRender = false;
        #find the Delivery option
        $this->Delivery->id = $id = $this->request->data['id'];
        if (!$this->Delivery->exists()) {
            //throw new NotFoundException(__('Invalid production product'));
            echo "0";
            exit();
        } else {# valid id is found
            $this->Delivery->recursive = -1;
            $product = $this->Delivery->findById($id);

            $product_id = $product['Delivery']['product_id'];
            $lot_id = $product['Delivery']['lot_id'];
            $actual_delivery_date = $product['Delivery']['actual_delivery_date'];
            if ($actual_delivery_date == "0000-00-00") {
                $this->request->allowMethod('post', 'delete');
                if ($this->Delivery->delete()) {
                    echo "1";
                } else {
                    echo "0";
                }
            } else {
                $option1 = array(
                    'conditions' => array(
                        'Delivery.product_id' => $product_id,
                        'Delivery.lot_id' => $lot_id,
                        'Delivery.actual_delivery_date NOT LIKE' => "0000-00-00",
                        'Delivery.actual_pli_date NOT LIKE' => "0000-00-00"
                    )
                );
                $this->Delivery->recursive = -1;
                if (!$this->Delivery->find('first', $option1)):#check the product of that lot is in delivery        
                    $this->request->allowMethod('post', 'delete');
                    if ($this->Delivery->delete()) {
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
        $product_category_id=$this->request->data['Delivery']['product_category_id'];
        if($product_category_id){
        $sql="SELECT c.id,c.contract_no,ins.ins_qty as ins_qty,d.delivery_qty as delivery_qty FROM contracts AS c LEFT JOIN ( SELECT SUM(quantity) as ins_qty,contract_id,product_category_id FROM inspections GROUP BY product_category_id,contract_id ) as ins ON c.id=ins.contract_id LEFT JOIN ( SELECT SUM(quantity) as delivery_qty,contract_id,product_category_id FROM deliveries GROUP BY product_category_id,contract_id ) as d ON c.id=d.contract_id where ins.product_category_id=$product_category_id ORDER BY ins.contract_id DESC";
        $contracts=  $this->Delivery->query($sql);  
        }
        $this->set(compact('contracts'));
    }
    
     public function getContractByCategoryPli()
    {
        $this->layout = 'ajax';
        $product_category_id=$this->request->data['Delivery']['product_category_id'];
        if($product_category_id){
        $sql="SELECT (select id from contracts as c where c.id=d.contract_id) as id,(select contract_no from contracts as con_no where con_no.id=d.contract_id) as contract_no,SUM(d.quantity) as delivery_qty,SUM(d.pli_qty) as pli_qty from deliveries as d WHERE d.product_category_id=$product_category_id GROUP BY d.contract_id ORDER BY d.contract_id DESC";
        $contracts=  $this->Delivery->query($sql);  
        }
        $this->set(compact('contracts'));
    }

}
