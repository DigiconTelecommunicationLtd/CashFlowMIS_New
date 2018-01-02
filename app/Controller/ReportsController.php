<?php

App::uses('AppController', 'Controller');

/**
 * Report Controller
 *
 * @property Report $Report
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ReportsController extends AppController {
    public $components = array('ExportXls');
    #*********************Planned date wise with collection report used by finance team
    
    public function invoice_planned_date_delivery_wise_with_collection()
    {
        if($this->request->is('post')) {
            
            $option['Delivery.actual_delivery_date NOT LIKE']="0000-00-00";
            
            if($this->request->data['Report']['date_from']){
                 $this->request->data['Report']['date_from']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_from'])));
                if ($this->request->data['Report']['date_from'] == "1970-01-01") {
                    $this->Session->setFlash(__('Wrong format of Date From!. Please, try again(Date Format is YYYY-MM-DD).'));
                    return $this->redirect($this->referer());
                }
            }
            if($this->request->data['Report']['date_to']){
                $this->request->data['Report']['date_to']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_to'])));
                if ($this->request->data['Report']['date_to'] == "1970-01-01") {
                    $this->Session->setFlash(__('Wrong format of Date To!. Please, try again(Date Format is YYYY-MM-DD).'));
                    return $this->redirect($this->referer());
                } 
            }
            if ($this->request->data['Report']['product_category_id']) {
               $option['Delivery.product_category_id']=$this->request->data['Report']['product_category_id'];                
            }
            
            if ($this->request->data['Report']['contract_id']) {
               $option['Delivery.contract_id']=$this->request->data['Report']['contract_id'];                
            }
            if ($this->request->data['Report']['unit_id']) {
                  $option['Delivery.unitid']=$this->request->data['Report']['unit_id'];                
               }
            if ($this->request->data['Report']['client_id']) {
               $option['Delivery.clientid']=$this->request->data['Report']['client_id'];                
            }
             if ($this->request->data['Report']['currency']) {
               $option['Delivery.currency']=$this->request->data['Report']['currency'];                
            }
           
             if ($this->request->data['Report']['date_from']&&$this->request->data['Report']['date_to']) {
                $option['Delivery.payment_cheque_collection_progressive BETWEEN ? AND ?']=array($this->request->data['Report']['date_from'],$this->request->data['Report']['date_to']);                
            }
            else if($this->request->data['Report']['date_from']||$this->request->data['Report']['date_to']){
                $option['Delivery.payment_cheque_collection_progressive <=']=isset($this->request->data['Report']['date_from'])?$this->request->data['Report']['date_from']:$this->request->data['Report']['date_to'];
            }
           
            $conditions=array(
                'conditions'=>$option,
                    'fields'=>array(
                        'Delivery.product_category_id',
                        'Delivery.contract_id',
                        'Delivery.unitid',
                        'Delivery.clientid',
                        'Delivery.currency',
                        'SUM(Delivery.quantity) as quantity ',
			 //'SUM(Delivery.quantity*Delivery.unit_price) as delivery_amount',
                        'SUM(Delivery.delivery_value) as delivery_amount',
                        'Delivery.uom',
                        'Delivery.actual_delivery_date',
                        'Delivery.lot_id',
                        'Delivery.unit_price',
                        'Delivery.invoice_submission_progressive',
                        'Delivery.payment_cheque_collection_progressive',
                        'Delivery.payment_credited_to_bank_progressive',
                        'MAX(Delivery.actual_delivery_date) as actual_delivery_date',
                        'Delivery.added_date',
                        'Contract.contract_no',
                        'Contract.pli_pac',
                        'Contract.pli_aproval',
                        'Contract.rr_collection_progressive',
                        'Contract.billing_percent_progressive',
                        'Contract.invoice_submission_progressive',
                        'Contract.payment_cheque_collection_progressive',
                        'Contract.payment_credited_to_bank_progressive',
                        'ProductCategory.name'
                        
                        
                    ),
                'group'=>array(
                    'Delivery.actual_delivery_date',
                    'Delivery.product_category_id',
                    'Delivery.contract_id',
                    'Delivery.unitid',
                    'Delivery.clientid',
                    'Delivery.currency',
                    'Delivery.uom',
                    'Delivery.lot_id'
                ),
                'order'=>array(
                    'Delivery.actual_delivery_date'=>"DESC",
                )
                 
            );
            $this->loadModel('Delivery');
            $results=$this->Delivery->find('all',$conditions);
            #echo '<pre>';print_r($results);exit;
        }
        #ProductCategory list box
        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list'); 
       
        #contract list box         
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);
        #Client        
        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');

        #Unit
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');
        #currency
        $currencies = array('USD' => 'USD', 'BDT' => 'BDT');
         
        $this->set(compact('product_categories','contracts','clients','units','results','currencies','date_from','date_to','both_date'));
    }
    
    public function finance_invoice_planned_date_delivery_wise_with_collection()
        {
        #ProductCategory list box
        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list');
        
        #Client        
        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');

        #Unit
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');    
            
        if($this->request->is('post')) {
            
             if($this->request->data['Report']['date_from']){
                 $this->request->data['Report']['date_from']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_from'])));
                        if ($this->request->data['Report']['date_from'] == "1970-01-01") {
                            $this->Session->setFlash(__('Wrong format of Date From!. Please, try again(Date Format is YYYY-MM-DD).'));
                            return $this->redirect($this->referer());
                        }
            }
        if($this->request->data['Report']['date_to']){
            $this->request->data['Report']['date_to']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_to'])));
                        if ($this->request->data['Report']['date_to'] == "1970-01-01") {
                            $this->Session->setFlash(__('Wrong format of Date To!. Please, try again(Date Format is YYYY-MM-DD).'));
                            return $this->redirect($this->referer());
                        } 
        }
             if (!$this->request->data['Report']['date_from']||!$this->request->data['Report']['date_to']||!$this->request->data['Report']['balance_carry']) {
                  $this->Session->setFlash(__('Carry option, Date From or Date To is required!,Please Try Again'));
                $this->redirect($this->referer());
             }
            $option['Delivery.actual_delivery_date NOT LIKE']="0000-00-00";
            $option_1['Collection.collection_type']="Progressive";
            
            if ($this->request->data['Report']['product_category_id']) {
               $option['Delivery.product_category_id']=$this->request->data['Report']['product_category_id']; 
               $option_1['Collection.product_category_id']=$this->request->data['Report']['product_category_id'];
            }
            
            if ($this->request->data['Report']['contract_id']) {
               $option['Delivery.contract_id']=$this->request->data['Report']['contract_id'];
               $option_1['Collection.contract_id']=$this->request->data['Report']['contract_id'];
            }
            if ($this->request->data['Report']['unit_id']) {
                  $option['Delivery.unitid']=$this->request->data['Report']['unit_id'];
                  $option_1['Collection.unitid']=$this->request->data['Report']['unit_id'];    
               }
            if ($this->request->data['Report']['client_id']) {
               $option['Delivery.clientid']=$this->request->data['Report']['client_id']; 
               $option_1['Collection.clientid']=$this->request->data['Report']['client_id']; 
            }
             if ($this->request->data['Report']['currency']) {
               $option['Delivery.currency']=$this->request->data['Report']['currency']; 
               $option_1['Collection.currency']=$this->request->data['Report']['currency']; 
            }
           if ($this->request->data['Report']['balance_carry']=='no') {                 
                   $option['Delivery.payment_cheque_collection_progressive BETWEEN ? AND ?']=array($this->request->data['Report']['date_from'],$this->request->data['Report']['date_to']); 
                   $option_1['Collection.planned_payment_certificate_or_cheque_collection_date BETWEEN ? AND ?']=array($this->request->data['Report']['date_from'],$this->request->data['Report']['date_to']); 
            }
               else if($this->request->data['Report']['balance_carry']=='yes'){
                   $option['Delivery.payment_cheque_collection_progressive BETWEEN ? AND ?']=array('0000-00-00',$this->request->data['Report']['date_to']);
                   $option_1['Collection.planned_payment_certificate_or_cheque_collection_date BETWEEN ? AND ?']=array('0000-00-00',$this->request->data['Report']['date_to']);
               }
          
            
            $balance_greater_than=($this->request->data['Report']['balance_greater_than']>0)?trim($this->request->data['Report']['balance_greater_than']):0;
             
            $conditions=array(
                'conditions'=>$option,
                    'fields'=>array(
                        'Delivery.product_category_id',
                        'Delivery.contract_id',
                        'Delivery.unitid',
                        'Delivery.clientid',
                        'Delivery.currency',
                        'SUM(Delivery.quantity) as quantity ',
			//'SUM(Delivery.quantity*Delivery.unit_price) as delivery_amount',
                        'SUM(Delivery.delivery_value) as delivery_amount',
                        //'Delivery.unit_price',
                        'Delivery.uom',
                        'Delivery.actual_delivery_date',
                        'Delivery.added_date',
                        'Delivery.lot_id',
                        'Delivery.unit_price',
                        'Delivery.invoice_submission_progressive',
                        'Delivery.payment_cheque_collection_progressive',
                        'Delivery.payment_credited_to_bank_progressive',
                        'MAX(Delivery.actual_delivery_date) as actual_delivery_date',   
                        'Contract.contract_no',
                        'Contract.pli_pac',
                        'Contract.pli_aproval',
                        'Contract.rr_collection_progressive',
                        'Contract.billing_percent_progressive',
                        'Contract.invoice_submission_progressive',
                        'Contract.payment_cheque_collection_progressive',
                        'Contract.payment_credited_to_bank_progressive',
                        'ProductCategory.name'
                        
                        
                    ),
                'group'=>array(
                    'Delivery.payment_cheque_collection_progressive',
                    'Delivery.product_category_id',
                    'Delivery.contract_id',
                    'Delivery.unitid',
                    'Delivery.clientid',
                    'Delivery.currency',
                    'Delivery.uom',
                    'Delivery.lot_id'
                ),
                'order'=>array(
                    'Delivery.actual_delivery_date'=>"DESC",
                )
                 
            );
            $this->loadModel('Delivery');
            $results=$this->Delivery->find('all',$conditions);
            
        }
        #contract list box         
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);
        
        #currency
        $currencies = array('USD' => 'USD', 'BDT' => 'BDT');
         
        $this->set(compact('product_categories','contracts','clients','units','results','currencies','date_from','date_to','both_date','data','balance_greater_than'));
    
        } 
#Cheque payment received report -done
    public function cheque_payment_received_report()
    {   
                #Client        
                $this->loadModel('Client');
                $this->Client->recursive = -1;
                $clients = $this->Client->find('list');
                
                 #Unit
                $this->loadModel('Unit');
                $this->Unit->recursive = -1;
                $units = $this->Unit->find('list');
                
                
                if ($this->request->is('post')) {
                    
                     if($this->request->data['Report']['date_from']){
                 $this->request->data['Report']['date_from']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_from'])));
                        if ($this->request->data['Report']['date_from'] == "1970-01-01") {
                            $this->Session->setFlash(__('Wrong format of Date From!. Please, try again(Date Format is YYYY-MM-DD).'));
                            return $this->redirect($this->referer());
                        }
            }
                    if($this->request->data['Report']['date_to']){
                        $this->request->data['Report']['date_to']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_to'])));
                            if ($this->request->data['Report']['date_to'] == "1970-01-01") {
                                $this->Session->setFlash(__('Wrong format of Date To!. Please, try again(Date Format is YYYY-MM-DD).'));
                                return $this->redirect($this->referer());
                            } 
                    }
        
                    if($this->request->data['Report']['invoice_ref_no'])
                    {
                        $option['CollectionDetail.invoice_ref_no LIKE']=$this->request->data['Report']['invoice_ref_no']."%";
                    }
                    if($this->request->data['Report']['contract_id'])
                    {
                        $option['CollectionDetail.contract_id']=$this->request->data['Report']['contract_id'];
                    }
                    if($this->request->data['Report']['currency'])
                    {
                        $option['CollectionDetail.currency']=$this->request->data['Report']['currency'];
                    }
                     if($this->request->data['Report']['collection_type'])
                    {
                        $option['CollectionDetail.collection_type']=$this->request->data['Report']['collection_type'];
                    }
                    
                     if($this->request->data['Report']['client_id'])
                    {
                        $option['Contract.client_id']=$this->request->data['Report']['client_id'];
                    }
                    
                     if($this->request->data['Report']['unit_id'])
                    {
                        $option['Contract.unit_id']=$this->request->data['Report']['unit_id'];
                    }
                    
                     if($this->request->data['Report']['product_category_id'])
                    {
                        $option['ProductCategory.id']=$this->request->data['Report']['product_category_id'];
                    }
                    
                    
                     if($this->request->data['Report']['date_from']||$this->request->data['Report']['date_to']||$this->request->data['Report']['date_type'])
                    {
                         if($this->request->data['Report']['date_type'])
                            {
                               $date_type=$this->request->data['Report']['date_type']; 
                               $option["CollectionDetail.$date_type NOT LIKE"]="NULL";
                                if($this->request->data['Report']['date_from']&&$this->request->data['Report']['date_to'])
                                {
                                    $option["CollectionDetail.$date_type BETWEEN ? AND ?"]=array(date('Y-m-d',  strtotime($this->request->data['Report']['date_from'])),date('Y-m-d',  strtotime($this->request->data['Report']['date_to'])));
                                }
                                else if($this->request->data['Report']['date_from'])
                                {
                                    $option["CollectionDetail.$date_type <="]=date('Y-m-d',  strtotime($this->request->data['Report']['date_from']));
                                }
                                 else if($this->request->data['Report']['date_to'])
                                {
                                    $option["CollectionDetail.$date_type <="]=date('Y-m-d',  strtotime($this->request->data['Report']['date_to']));
                                }
                                else{
                                    $this->Session->setFlash(__('Please Give Date From or Date To Filed in search form,Please Try Again'));
                                    $this->redirect($this->referer());  
                                }
                            }
                            else{
                                    $this->Session->setFlash(__('Please Choose Date Type,Please Try Again'));
                                    $this->redirect($this->referer());  
                                }
                             
                    }
                    $this->loadModel('CollectionDetail');
                    #echo '<pre>';print_r($option);exit;
                    $conditions=array(
                          'conditions'=>$option
                      );
                    #$this->CollectionDetail->unbindModel(array('belongsTo'=>array('Collection')));
                    $collectionDetails=  $this->CollectionDetail->find('all',$conditions); 
                    #echo '<pre>';print_r($collectionDetails);exit;
                     
                    if($this->request->data['Report']['showreport']=="download"){
                        $this->autoRender=false;
                        $this->layout = false;
                        $fileName = "received_payment_report_".date("d-m-y:h:s").".xls";
                       
                        
                         foreach ($collectionDetails as $collectionDetail)
                            {                            
                                $data[]=array(
                                    h($collectionDetail['Contract']['contract_no']),
                                    h($clients[$collectionDetail['Contract']['client_id']]),
                                    h($units[$collectionDetail['Contract']['unit_id']]),
                                    h($collectionDetail['ProductCategory']['name']),
                                    h($collectionDetail['CollectionDetail']['collection_type']),
                                    h($collectionDetail['CollectionDetail']['invoice_ref_no']),
                                    h($collectionDetail['CollectionDetail']['cheque_amount']),
                                    h($collectionDetail['CollectionDetail']['amount_received']),
                                    h($collectionDetail['CollectionDetail']['ajust_adv_amount']),
                                    h($collectionDetail['CollectionDetail']['ait']),
                                    h($collectionDetail['CollectionDetail']['vat']),
                                    h($collectionDetail['CollectionDetail']['ld']),
                                    h($collectionDetail['CollectionDetail']['other_deduction']),
                                    h($collectionDetail['CollectionDetail']['currency']),
                                    h($collectionDetail['Collection']['planned_payment_certificate_or_cheque_collection_date']),
                                    h($collectionDetail['Collection']['payment_credited_to_bank_date']),
                                    h($collectionDetail['CollectionDetail']['cheque_or_payment_certification_date']),
                                    h($collectionDetail['CollectionDetail']['actual_payment_certificate_or_cheque_collection_date']),
                                    h($collectionDetail['CollectionDetail']['forecasted_payment_collection_date']),
                                    h($collectionDetail['CollectionDetail']['payment_credited_to_bank_date']),
                                    h($collectionDetail['CollectionDetail']['added_by']),
                                    h($collectionDetail['CollectionDetail']['added_date']),
                                     h($collectionDetail['CollectionDetail']['payment_by']),
                                    h($collectionDetail['CollectionDetail']['payment_date']),
                                );
                            }
                         $headerRow = array("PO/Contract No","Client","Unit/Company","Category/Product","Collection Type","Invoice Ref No","Cheque Amount","Received Amount","Adj. Adv. Amount","AIT","VAT","L.D","Other Deduction","Currency","Planned Certificate/Cheque Collection Date","Planned Payment Credited To Bank Date","Payment Certification/Cheque Date","Actual Payment Certification/Cheque Collection Date","Forecasted Payment Collection Date","Payment (Credited to Bank) date","Cheque Entry By","Cheque Entry Date","Payment Entry By","Payment Entry Date");
                         #echo '<pre>';print_r($data);exit;
                         
                        $this->ExportXls->export($fileName, $headerRow, $data);               
                        }
                       
                }  
                
                #contract list box         
                $this->loadModel('Contract');
                $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
                $this->Contract->recursive = -1;
                $contracts = $this->Contract->find('list', $options);                
                #currency
                $currencies = array('USD' => 'USD', 'BDT' => 'BDT');
                //collection types
                $collection_types = array(
                    'Advance' => 'Advance',
                    'Progressive' => 'Progressive',
                    'Retention(1st)' => 'Retention(1st)',
                    'Retention(2nd)' => 'Retention(2nd)'
                );
                $date_types = array(        
                    'payment_credited_to_bank_date' => 'Payment (Credited To Bank) Date',
                    'forecasted_payment_collection_date' => 'Forecasted Payment Collection Date',
                    'cheque_or_payment_certification_date' => 'Payment Certificate/Cheque Date',
                    'actual_payment_certificate_or_cheque_collection_date' => 'Actual Payment Certificate/Cheque Collection Date'
                );
                //reference no
                #contract list box         
                $this->loadModel('CollectionDetail');
                $options = array('fields' => array('CollectionDetail.invoice_ref_no', 'CollectionDetail.invoice_ref_no'), 'order' => array('CollectionDetail.id' => 'DESC'));
                $this->CollectionDetail->recursive = -1;
                $invoice_ref_nos = $this->CollectionDetail->find('list', $options); 
               
                #ProductCategory list box
                $this->loadModel('ProductCategory');
                $product_categories = $this->ProductCategory->find('list'); 
                 
                
                $this->set(compact('collectionDetails','contracts','currencies','collection_types','date_types','invoice_ref_nos','clients','units','product_categories'));   
	}    
 public function invoice_planned_date_delivery_wise()
    {
        if($this->request->is('post')) {
            
            $option['Delivery.actual_delivery_date NOT LIKE']="0000-00-00";
            
             if($this->request->data['Report']['date_from']){
                 $this->request->data['Report']['date_from']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_from'])));
                if ($this->request->data['Report']['date_from'] == "1970-01-01") {
                    $this->Session->setFlash(__('Wrong format of Date From!. Please, try again(Date Format is YYYY-MM-DD).'));
                    return $this->redirect($this->referer());
                }
            }
        if($this->request->data['Report']['date_to']){
            $this->request->data['Report']['date_to']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_to'])));
            if ($this->request->data['Report']['date_to'] == "1970-01-01") {
                $this->Session->setFlash(__('Wrong format of Date To!. Please, try again(Date Format is YYYY-MM-DD).'));
                return $this->redirect($this->referer());
            } 
        }
            
            if ($this->request->data['Report']['product_category_id']) {
               $option['Delivery.product_category_id']=$this->request->data['Report']['product_category_id'];                
            }
            
            if ($this->request->data['Report']['contract_id']) {
               $option['Delivery.contract_id']=$this->request->data['Report']['contract_id'];                
            }
            if ($this->request->data['Report']['unit_id']) {
                  $option['Delivery.unitid']=$this->request->data['Report']['unit_id'];                
               }
            if ($this->request->data['Report']['client_id']) {
               $option['Delivery.clientid']=$this->request->data['Report']['client_id'];                
            }
             if ($this->request->data['Report']['currency']) {
               $option['Delivery.currency']=$this->request->data['Report']['currency'];                
            }
            
             if ($this->request->data['Report']['date_from']&&$this->request->data['Report']['date_to']) {
               $option['Delivery.payment_cheque_collection_progressive BETWEEN ? AND ?']=array($this->request->data['Report']['date_from'],$this->request->data['Report']['date_to']);                
            }
            else if($this->request->data['Report']['date_from']||$this->request->data['Report']['date_to']){
                $option['Delivery.payment_cheque_collection_progressive <=']=isset($this->request->data['Report']['date_from'])?$this->request->data['Report']['date_from']:$this->request->data['Report']['date_to'];
            }
            
            $conditions=array(
                'conditions'=>$option,
                    'fields'=>array(
                        'Delivery.product_category_id',
                        'Delivery.contract_id',
                        'Delivery.unitid',
                        'Delivery.clientid',
                        'Delivery.product_id',
                        'Delivery.currency',
                        'SUM(Delivery.quantity) as quantity ',                      
                        'Delivery.uom',
                        'Delivery.lot_id',
                        //'Delivery.unit_price',
                        'SUM(Delivery.delivery_value) as delivery_amount',
                        'MAX(Delivery.actual_delivery_date) as actual_delivery_date',
                        'MAX(Delivery.invoice_submission_progressive) as invoice_submission_progressive',
                        'MAX(Delivery.payment_cheque_collection_progressive) as payment_cheque_collection_progressive',
                        'MAX(Delivery.payment_credited_to_bank_progressive) as payment_credited_to_bank_progressive',
                        
                        'Contract.contract_no',
                        'Contract.billing_percent_progressive',
                        'ProductCategory.name'
                        
                        
                    ),
                'group'=>array(
                    'Delivery.actual_delivery_date',
                    'Delivery.product_category_id',
                    'Delivery.contract_id',
                    'Delivery.unitid',
                    'Delivery.clientid',
                    //'Delivery.product_id',
                    'Delivery.currency',
                    'Delivery.uom',
                    'Delivery.lot_id'
                ),
                'order'=>array(
                    'Delivery.actual_delivery_date'=>"DESC",
                )
                 
            );
            $this->loadModel('Delivery');
            $results=$this->Delivery->find('all',$conditions);
            #echo '<pre>';print_r($results);exit;
        }
        #ProductCategory list box
        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list'); 
       
        #contract list box         
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);
        #Client        
        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');

        #Unit
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');
        #currency
        $currencies = array('USD' => 'USD', 'BDT' => 'BDT');		
        $this->set(compact('product_categories','contracts','clients','units','results','currencies'));
    }    
    
   public function invoice_list() {
        if ($this->request->is('post')) {
            
            if(!$this->request->data['Report']['date']||!$this->request->data['Report']['date_from']||!$this->request->data['Report']['date_to'])
            {
               $this->Session->setFlash(__('Date From, Date TO or Date Type is Required! Please try Again'));
                return $this->redirect($this->referer());  
            }
            
            $this->request->data['Report']['date_from']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_from'])));
           if ($this->request->data['Report']['date_from'] == "1970-01-01") {
               $this->Session->setFlash(__('Wrong format of Date From!. Please, try again(Date Format is YYYY-MM-DD).'));
               return $this->redirect($this->referer());
              }
              
            $this->request->data['Report']['date_to']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_to'])));
            if ($this->request->data['Report']['date_to'] == "1970-01-01") {
                $this->Session->setFlash(__('Wrong format of Date To!. Please, try again(Date Format is YYYY-MM-DD).'));
                return $this->redirect($this->referer());
            } 
              
              if ($this->request->data['Report']['date_from'] && $this->request->data['Report']['date_to']&&$this->request->data['Report']['date']) {
              $start_date = $this->request->data['Report']['date_from'];
              $end_date = $this->request->data['Report']['date_to'];
              
	      $date_type=$this->request->data['Report']['date'];
              $options["SUBSTRING(Collection.$date_type,1,10) BETWEEN ? AND ?"] = array($start_date, $end_date);
              }    
              if ($this->request->data['Report']['collection_type']) {
              $options['Collection.collection_type  LIKE '] = $this->request->data['Report']['collection_type'];
              }
            if ($this->request->data['Report']['invoice_ref_no']) {
                $data['invoice_ref_no'] = $this->request->data['Report']['invoice_ref_no'];
                $options['Collection.invoice_ref_no LIKE '] = $this->request->data['Report']['invoice_ref_no'] . "%";
            }

            if ($this->request->data['Report']['currency']) {
                $data['currency'] = $this->request->data['Report']['currency'];
                $options['Collection.currency'] = $this->request->data['Report']['currency'];
            }
            if ($this->request->data['Collection']['product_category_id']) {
                $data['product_category_id'] = $this->request->data['Report']['product_category_id'];
                $options['Collection.product_category_id'] = $this->request->data['Report']['product_category_id'];
            }
            if ($this->request->data['Report']['client_id']) {
                $data['client_id'] = $this->request->data['Report']['client_id'];
                $options['Collection.clientid'] = $this->request->data['Report']['client_id'];
            }
            if ($this->request->data['Report']['unit_id']) {
                $data['unit_id'] = $this->request->data['Report']['unit_id'];
                $options['Collection.unitid'] = $this->request->data['Report']['unit_id'];
            }
            if ($this->request->data['Report']['contract_id'] || $this->request->data['Report']['lc_ref']) {
                $data['contract_id'] = ($this->request->data['Report']['contract_id']) ? $this->request->data['Report']['contract_id'] : '';
                $data['lc_ref'] = ($this->request->data['Report']['lc_ref']) ? $this->request->data['Report']['lc_ref'] : '';
                $options['Collection.contract_id'] = ($this->request->data['Report']['contract_id']) ? $this->request->data['Report']['contract_id'] : $this->request->data['Report']['lc_ref'];
            }
            $this->loadModel('Collection');
            $this->Collection->recursive = 0;
            $options = array('conditions' => $options, 'order' => array('Collection.id' => 'DESC'));
            $collections = $this->Collection->find('all', $options);
            #echo '<pre>';print_r($collections);exit;
        }
        
        #product category list
        $this->loadModel('ProductCategory');
        $product_category = $this->ProductCategory->find('list');
        #contract list box
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);
        #lc refe
		/*
        $options = array('conditions' => array('Contract.lc_ref !=' => ''), 'fields' => array('Contract.id', 'Contract.lc_ref'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $lc_refs = $this->Contract->find('list', $options);
		*/
        #Client list box
        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');
        #Unit list box
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');

        $currencies = array('USD' => 'USD', 'BDT' => 'BDT');
        $collection_types = array(
            'Advance' => 'Advance',
            'Progressive' => 'Progressive',
            'Retention%' => 'Retention',
             
        );
   $dates = array(
            'planned_submission_date' => 'Planned Invoice Submission Date',
            'actual_submission_date' => 'Actual Invoice Submission Date',
            'planned_payment_certificate_or_cheque_collection_date' => 'Planned Payment Certificate/Cheque Collection Date',
            'planned_payment_collection_date' => 'Planned Payment Credited To Bank Date',
           
        );
        $this->set(compact('lc_refs', 'collection_types', 'contracts', 'clients', 'units', 'currencies', 'collections', 'start_date', 'end_date', 'product_category', 'data','dates'));
    }  
    
    
/*    

    public function collection_report_details() {
        $options = array();
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
        $action = "collection_report_details/ ";
        $controller = "reports";
        $results = array(); //blank result
        $reports = array(
            'Advance' => 'Advance',
            'Progressive' => 'Progressive',
            'Retention(1st)' => 'Retention(1st)',
            'Retention(2nd)' => 'Retention(2nd)'
        );
        $dates = array(
            'planned_submission_date' => 'Planned Invoice Submission Date',
            'actual_submission_date' => 'Actual Invoice Submission Date',
            'planned_payment_certificate_or_cheque_collection_date' => 'Planned Payment Certificate/Cheque Collection Date',
            'planned_payment_collection_date' => 'Planned Payment Collection Date',
            //'forecasted_payment_collection_date' => 'Forecasted Payment Collection Date',
            //'cheque_or_payment_certification_date' => 'Cheque/Payment Certificate Date',
            //'actual_payment_certificate_or_cheque_collection_date' => 'Actual Payment Certificate/Cheque Collection Date',
            //'payment_credited_to_bank_date' => 'Payment (Credited To Bank) Date'
        );
        if ($this->request->is('post')) {
            if ($this->request->data['Report']['date_from'] || $this->request->data['Report']['date_to'] || $this->request->data['Report']['date']) {
                if ($this->request->data['Report']['date_from'] && $this->request->data['Report']['date_to'] && $this->request->data['Report']['date']) {
                    $options[] = "Collection." . $this->request->data['Report']['date'] . ' BETWEEN ' . "'" . $this->request->data['Report']['date_from'] . "'" . ' AND ' . "'" . $this->request->data['Report']['date_to'] . "'";
                } else {
                    $this->Session->setFlash(__('Date From,Date To and Date Type is Required!,Please Try Again'));
                    $this->redirect($this->referer());
                }
            }
            if ($this->request->data['Report']['report']) {
                $options[] = "Collection.Collection_type='" . $this->request->data['Report']['report'] . "'";
            }
            if ($this->request->data['Report']['contract_id']) {
                $options[] = "Collection.contract_id=" . $this->request->data['Report']['contract_id'];
            }
            if ($this->request->data['Report']['currency']) {
                $options[] = "Collection.currency='" . $this->request->data['Report']['currency'] . "'";
            }
            if ($this->request->data['Report']['unit_id']) {
                $options[] = "contract.unit_id=" . $this->request->data['Report']['unit_id'];
            }
            if ($this->request->data['Report']['client_id']) {
                $options[] = "contract.client_id=" . $this->request->data['Report']['client_id'];
            }
            if ($this->request->data['Report']['client_id']) {
                $options[] = "contract.client_id=" . $this->request->data['Report']['client_id'];
            }
            if ($this->request->data['Report']['product_category_id']) {
                $options[] = "Collection.product_category_id=" . $this->request->data['Report']['product_category_id'];
            }

            // $sql_query="SELECT (select name from clients where clients.id=c.client_id)as client,(select name from units where units.id=c.unit_id) as unit,(select contract_no from contracts c where c.id=co.contract_id) contract_no,co.Collection_type Collection_type  from contracts c,collections as co where co.contract_id=c.id and co.contract_id=2 and c.unit_id ";
            // echo $sql_query="SELECT client.name as client_name,unit.name as unit_name,contract.contract_no as contract_no,Collection.Collection_type Collection_type FROM contracts as contract, clients as client,units as unit,collections as Collection where Collection.contract_id=contract.id ".  implode(' AND ', $options)."";

            if (count($options) > 0) {
                $sql_query = "SELECT (select name from clients where clients.id=contract.client_id)as client_name,(select name from product_categories where product_categories.id=Collection.product_category_id) as product_category,(select name from units where units.id=contract.unit_id) as unit_name,(select contract_no from contracts contract where contract.id=Collection.contract_id) contract_no,Collection.*,cd.payment_credited_to_bank_date,cd.* from contracts contract,collections as Collection,collection_details as cd where Collection.id=cd.collection_id and Collection.contract_id=contract.id and " . implode(' AND ', $options) . "";
                $results = $this->Report->query($sql_query);
            }
        }

        #contract list box
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);
        #currency list box
        $currencies = array('USD' => 'USD', 'BDT' => 'BDT');
        #Client list box
        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');
        #Unit list box
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');
        #ProductCategory list box
        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list');

        $this->set(compact('data', 'dates', 'currencies', 'contracts', 'reports', 'controller', 'action', 'end_date', 'start_date', 'clients', 'units', 'results', 'product_categories'));
    }
*/
    public function collection_report_summary() {

        if ($this->request->is('post')) {
            if ($this->request->data['Report']['contract_id']) {
                $options[] = "Collection.contract_id=" . $this->request->data['Report']['contract_id'];
            }
            if ($this->request->data['Report']['currency']) {
                $options[] = "Collection.currency='" . $this->request->data['Report']['currency'] . "'";
            }
            if ($this->request->data['Report']['unit_id']) {
                $options[] = "Collection.unitid=" . $this->request->data['Report']['unit_id'];
            }
            if ($this->request->data['Report']['client_id']) {
                $options[] = "Collection.clientid=" . $this->request->data['Report']['client_id'];
            }
            if ($this->request->data['Report']['product_category_id']) {
                $options[] = "Collection.product_category_id=" . $this->request->data['Report']['product_category_id'];
            }
            if (count($options) > 0) {
                $sql_query = "SELECT (select name from clients where clients.id=Collection.clientid)as client_name,(select name from product_categories where product_categories.id=Collection.product_category_id) as product_category,(select name from units where units.id=Collection.unitid) as unit_name,(select contract_no from contracts contract where contract.id=Collection.contract_id) contract_no,SUM(invoice_amount) as invoice_amount,SUM(amount_received+ait+vat+ld+other_deduction) as total_collection,currency from collections as Collection where " . implode(' AND ', $options) . " group by Collection.contract_id,Collection.product_category_id,Collection.currency";
                $results = $this->Report->query($sql_query);
            }
        }

        #contract list box
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);
        #currency list box
        $currencies = array('USD' => 'USD', 'BDT' => 'BDT');
        #Client list box
        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');
        #Unit list box
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');
        #Unit list box
        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list');

        $this->set(compact('data', 'dates', 'currencies', 'contracts', 'reports', 'controller', 'action', 'end_date', 'start_date', 'clients', 'units', 'results', 'product_categories'));
    }

    

    public function progressive_payment_product_report() {
        if ($this->request->is('post')) {

            if ($this->request->data['Report']['contract_id']) {
                $options[] = "ProgressivePayment.contract_id=" . $this->request->data['Report']['contract_id'];
            }
            if ($this->request->data['Report']['unit_id']) {
                $options[] = 'ProgressivePayment.unitid=' . $this->request->data['Report']['unit_id'];
            }
            if ($this->request->data['Report']['client_id']) {
                $options[] = 'ProgressivePayment.clientid=' . $this->request->data['Report']['client_id'];
            }
            if ($this->request->data['Report']['currency']) {
                $options[] = 'ProgressivePayment.currency=' . $this->request->data['Report']['currency'];
            }
            if ($this->request->data['Report']['product_category_id']) {
                $options[] = 'ProgressivePayment.product_category_id=' . trim($this->request->data['Report']['product_category_id']) . "%";
            }
            if (count($options) > 0) {
                $sql_query = "SELECT (select name from clients where clients.id=ProgressivePayment.clientid)as client_name,(select name from product_categories where product_categories.id=ProgressivePayment.product_category_id) as product_category,(select name from units where units.id=ProgressivePayment.unitid) as unit_name,(select contract_no from contracts contract where contract.id=ProgressivePayment.contract_id) contract_no,ProgressivePayment.currency,ProgressivePayment.quantity,ProgressivePayment.uom,(select name from products where products.id=ProgressivePayment.product_id) product_name from progressive_payments as ProgressivePayment where " . implode(' AND ', $options) . "";
                $results = $this->Report->query($sql_query);
            }
        }


        #contract list box
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);
        #currency list box
        $currencies = array('USD' => 'USD', 'BDT' => 'BDT');
        #Client list box
        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');
        #Unit list box
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');
        #Unit list box
        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list');

        $this->set(compact('currencies', 'contracts', 'units', 'results', 'product_categories', 'currency', 'clients'));
    }

    public function credit_report() {
        if ($this->request->is('post')) {
            #if no input then redirect to the search page
            $con='';
            if (!$this->request->data['Report']['product_category_id'] && !$this->request->data['Report']['contract_id'] && !$this->request->data['Report']['unit_id'] && !$this->request->data['Report']['client_id'] && !$this->request->data['Report']['currency']) {
                $this->Session->setFlash(__('1.At Least one filter option is needed!,Please Try Again'));
                $this->redirect($this->referer());
            }
            if($this->request->data['Report']['product_category_id'])
            {
                $con.=" AND (d.product_category_id=".$this->request->data['Report']['product_category_id']." OR c.product_category_id=".$this->request->data['Report']['product_category_id'].")";
            }
            if($this->request->data['Report']['currency'])
            {
                $con.=" AND con.currency='".$this->request->data['Report']['currency']."'";
            }
            if($this->request->data['Report']['contract_id'])
            {
                 
                 $con.=" AND con.id=".$this->request->data['Report']['contract_id'];
            }
             if($this->request->data['Report']['unit_id'])
            {
                $con.=" AND con.unit_id=".$this->request->data['Report']['unit_id'];
            }
             if($this->request->data['Report']['client_id'])
            {
                
                $con.=" AND con.client_id=".$this->request->data['Report']['client_id'];
            }
             /*$sql="SELECT
                        con.id,
                        con.contract_no,
                        con.billing_percent_progressive,
                        u.name,
                        client.name,
                        (select name from contract_products as cp,product_categories pc where pc.id=cp.product_category_id and cp.contract_id=con.id limit 1)as pc_name,
                        d.delivery_value,
                        d.currency,
                        c.amount_received,
                        c.ajust_adv_amount,
                        c.ait,
                        c.vat,
                        c.ld,
                        c.other_deduction,
                        c.currency,
                        advc.adv_amount,                         
                        cp.contract_value
                        FROM contracts as con
                        LEFT JOIN (
                        SELECT SUM(quantity*unit_price) AS contract_value,contract_id FROM contract_products  GROUP BY contract_id

                        )AS cp ON con.id=cp.contract_id
                        LEFT JOIN (
                        SELECT SUM(delivery_value) AS delivery_value,contract_id,currency FROM deliveries GROUP BY contract_id

                        )AS d ON con.id=d.contract_id

                        LEFT JOIN (
                        SELECT SUM(amount_received) AS amount_received,SUM(ait) AS ait,SUM(vat) AS vat,SUM(ld) AS ld,SUM(other_deduction) AS other_deduction,SUM(ajust_adv_amount) as ajust_adv_amount,contract_id,currency FROM collections
                         GROUP BY contract_id
                        ) AS c ON con.id=c.contract_id 
                        
                        LEFT JOIN (
                        SELECT SUM(amount_received) AS adv_amount,contract_id,collection_type,currency FROM collections
                        where collection_type='Advance'
                        GROUP BY contract_id
                        ) AS advc ON con.id=advc.contract_id AND advc.collection_type='Advance'
                        
                       LEFT JOIN units as u ON con.unit_id=u.id
                       LEFT JOIN clients as client ON con.client_id=client.id
                                           
                        WHERE 1=1 $con"; */
            
            /* $sql="SELECT
                        con.id,
                        con.contract_no,
                        con.billing_percent_progressive,
                        u.name,
                        client.name,
                        (select name from contract_products as cp,product_categories pc where pc.id=cp.product_category_id and cp.contract_id=con.id limit 1)as pc_name,
                        d.delivery_value,
                        d.currency,
                        c.amount_received,
                        c.ajust_adv_amount,
                        c.ait,
                        c.vat,
                        c.ld,
                        c.other_deduction,
                        c.currency,
                        advc.adv_amount                         
                        
                        FROM contracts as con                        
                        LEFT JOIN (
                        SELECT SUM(delivery_value) AS delivery_value,contract_id,currency FROM deliveries GROUP BY contract_id,currency
                         )AS d ON con.id=d.contract_id

                        LEFT JOIN (
                        SELECT SUM(amount_received) AS amount_received,SUM(ait) AS ait,SUM(vat) AS vat,SUM(ld) AS ld,SUM(other_deduction) AS other_deduction,SUM(ajust_adv_amount) as ajust_adv_amount,contract_id,currency FROM collections
                         GROUP BY contract_id,currency
                        ) AS c ON con.id=c.contract_id 
                        
                        LEFT JOIN (
                        SELECT SUM(amount_received+ait+vat+ld+other_deduction) AS adv_amount,contract_id,collection_type,currency FROM collections
                        where collection_type='Advance'
                        GROUP BY contract_id,currency
                        ) AS advc ON con.id=advc.contract_id AND advc.collection_type='Advance'
                        
                       LEFT JOIN units as u ON con.unit_id=u.id
                       LEFT JOIN clients as client ON con.client_id=client.id
                                           
                        WHERE 1=1 $con group by con.id,con.currency"; */
             
                 $sql="SELECT
                        con.id,
                        con.contract_no,
                        con.contract_value,
                        con.billing_percent_progressive,
                        u.name,
                        client.name,
                        (select name from contract_products as cp,product_categories pc where pc.id=cp.product_category_id and cp.contract_id=con.id limit 1)as pc_name,
                        d.delivery_value,
                        con.currency,
                        pro.pro_amount,
                        reten.reten_amount,
                        advc.adv_amount,
                        avlo.ait,
                        avlo.vat,
                        avlo.ld,
                        avlo.other_deduction,
                        avlo.ajust_adv_amount
                        
                        FROM contracts as con                        
                        LEFT JOIN (
                        SELECT SUM(delivery_value) AS delivery_value,contract_id,currency FROM deliveries  
                        GROUP BY contract_id,currency                         
                        )AS d ON con.id=d.contract_id

                        LEFT JOIN (
                        SELECT SUM(amount_received) AS pro_amount,contract_id,currency FROM collections
                         where collection_type IN ('Progressive') 
                         GROUP BY contract_id,currency
                        ) AS pro ON con.id=pro.contract_id 
                        
                         LEFT JOIN (
                        SELECT SUM(amount_received) AS reten_amount,contract_id,currency FROM collections
                         where collection_type IN ('Retention(1st)','Retention(2nd)')
                         GROUP BY contract_id,currency
                        ) AS reten ON con.id=reten.contract_id 
                        
                        LEFT JOIN (
                        SELECT SUM(amount_received) AS adv_amount,contract_id,collection_type,currency FROM collections
                        where collection_type IN ('Advance')
                        GROUP BY contract_id,currency
                        ) AS advc ON con.id=advc.contract_id                       
                       LEFT JOIN (
                        SELECT SUM(ait) AS ait,SUM(vat) AS vat,SUM(ld) AS ld,SUM(other_deduction) AS other_deduction,SUM(ajust_adv_amount) AS ajust_adv_amount,contract_id,collection_type,currency FROM collections
                         GROUP BY contract_id,currency
                        ) AS avlo ON con.id=avlo.contract_id
                        
                       LEFT JOIN units as u ON con.unit_id=u.id
                       LEFT JOIN clients as client ON con.client_id=client.id
                                           
                        WHERE 1=1 $con group by con.id,con.currency"; 
                  /*AND d.unitid IS NOT NULL AND d.clientid IS NOT NULL*/
                  $data=$this->Report->query($sql);
                 #echo '<pre>';print_r($data);exit;
        }
       
        
        #contract list box

        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);

        #Client

        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');

        #Unit
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');
        #Product category

        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list');

        $currencies = array('USD' => 'USD', 'BDT' => 'BDT');
        $this->set(compact('data', 'con_category', 'contracts', 'clients', 'units', 'product_categories', 'dates', 'results', 'currencies', 'category', 'po'));
    }


    public function delivery_report() {
        if ($this->request->is('post')) {
            
            if($this->request->data['Report']['date_from']){
                 $this->request->data['Report']['date_from']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_from'])));
                if ($this->request->data['Report']['date_from'] == "1970-01-01") {
                    $this->Session->setFlash(__('Wrong format of Date From!. Please, try again(Date Format is YYYY-MM-DD).'));
                    return $this->redirect($this->referer());
                }
            }
            if($this->request->data['Report']['date_to']){
                $this->request->data['Report']['date_to']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_to'])));
                if ($this->request->data['Report']['date_to'] == "1970-01-01") {
                    $this->Session->setFlash(__('Wrong format of Date To!. Please, try again(Date Format is YYYY-MM-DD).'));
                    return $this->redirect($this->referer());
                } 
            }
            
            if (!$this->request->data['Report']['product_category_id'] && !$this->request->data['Report']['contract_id'] && !$this->request->data['Report']['unit_id'] && !$this->request->data['Report']['client_id'] && !$this->request->data['Report']['date_from'] && !$this->request->data['Report']['date_to'] && !$this->request->data['Report']['date']) {
                $this->Session->setFlash(__('At Least one filter option is needed!,Please Try Again'));
                $this->redirect($this->referer());
            }
            $sql = "SELECT 
                Unit.name,
                Client.name,
                Contract.contract_no,
                ProductCategory.name,
                Product.name,
                Delivery.contract_id,
                Delivery.lot_id,
                Delivery.quantity,		
		Delivery.unit_price,
		Delivery.currency,
                Delivery.uom,
                Delivery.unit_weight,
                Delivery.unit_weight_uom,
                CP.unit_weight,
                CP.unit_weight_uom,
                Delivery.planned_delivery_date,
                Delivery.actual_delivery_date,
                Delivery.planned_pli_date,
                Delivery.actual_pli_date,
                Delivery.planned_date_of_pli_aproval,
                Delivery.actual_date_of_pli_aproval,
                Delivery.planned_date_of_installation,
                Delivery.actual_date_of_installation,
                Delivery.planned_date_of_client_receiving,
                Delivery.actual_date_of_client_receiving,
                Delivery.planned_rr_collection_date,
                Delivery.invoice_submission_progressive,
                Delivery.payment_cheque_collection_progressive,
                Delivery.payment_credited_to_bank_progressive,
		Delivery.added_date,
		Delivery.added_by
                
                FROM deliveries AS Delivery
                LEFT JOIN contracts AS Contract
                ON Contract.id=Delivery.contract_id
                LEFT JOIN units AS Unit
                ON Unit.id=Contract.unit_id
                LEFT JOIN clients AS Client
                ON Client.id=Contract.client_id
                LEFT JOIN product_categories AS ProductCategory
                ON ProductCategory.id=Delivery.product_category_id
                LEFT JOIN products AS Product ON Product.id=Delivery.product_id
                LEFt JOIN contract_products as CP ON (CP.contract_id=Delivery.contract_id AND CP.product_id=Delivery.product_id)
                 WHERE 1=1 ";
            if ($this->request->data['Report']['product_category_id']) {
                $sql.=" AND Delivery.product_category_id=" . $this->request->data['Report']['product_category_id'] . "";
            }
            if ($this->request->data['Report']['contract_id']) {
                $contract_id = $this->request->data['Report']['contract_id'];
                $sql.=" AND Delivery.contract_id=" . $this->request->data['Report']['contract_id'] . "";
            }
            if ($this->request->data['Report']['lot_id']) {
                $lot_id = $this->request->data['Report']['lot_id'];
                $sql.=" AND Delivery.lot_id='" . $this->request->data['Report']['lot_id'] . "'";
            }

            if ($this->request->data['Report']['unit_id']) {
                $sql.=" AND Contract.unit_id=" . $this->request->data['Report']['unit_id'] . "";
            }

            if ($this->request->data['Report']['client_id']) {
                $sql.=" AND Contract.client_id=" . $this->request->data['Report']['client_id'] . "";
            }

            if ($this->request->data['Report']['date_from'] || $this->request->data['Report']['date_to'] || $this->request->data['Report']['date']) {
                if ($this->request->data['Report']['date_from'] && $this->request->data['Report']['date_to'] && $this->request->data['Report']['date']) {
                    $sql.=" AND Delivery." . $this->request->data['Report']['date'] . " BETWEEN '" . $this->request->data['Report']['date_from'] . "' AND '" . $this->request->data['Report']['date_to'] . "'" . "";
                    $sql.=" AND Delivery." . $this->request->data['Report']['date'] . " NOT LIKE '0000-00-00'";
                } else {
                    $this->Session->setFlash(__('Date From,Date To and Date Type is Required!,Please Try Again'));
                    $this->redirect($this->referer());
                }
            }
            #execution of sql query
            #echo $sql;exit;        
            $results = $this->Report->query($sql);
            #echo '<pre>';print_r($results);exit;
        }

        #delivry date type
        $dates = array(             
            'actual_delivery_date' => 'Actual Delivery Date',
            'invoice_submission_progressive' => 'Planned Invoice Submission Date',
            'payment_cheque_collection_progressive' => 'Planned Payment/Cheque Collection Date',
            'payment_credited_to_bank_progressive' => 'Planned Payment Credited To Bank',
            
        );
        #contract list box

        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);

        #Client

        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');

        #Unit
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');
        #Product category

        $this->loadModel('Delivery');
        $product_categories = $this->Delivery->ProductCategory->find('list');

        #lot model
        if ($contract_id):
            $this->loadModel('Lot');
            $option = array('conditions' => array(
                    'Lot.contract_id' => $contract_id
                ),
                'fields' => array('Lot.lot_no', 'Lot.lot_no')
            );
            $lots = $this->Lot->find('list', $option);
        endif;


        $this->set(compact('lots', 'lot_id', 'contracts', 'clients', 'units', 'product_categories', 'dates', 'results'));
    }
    
    public function delivery_report_summary() {
        if ($this->request->is('post')) {
            
            if($this->request->data['Report']['date_from']){
                 $this->request->data['Report']['date_from']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_from'])));
                if ($this->request->data['Report']['date_from'] == "1970-01-01") {
                    $this->Session->setFlash(__('Wrong format of Date From!. Please, try again(Date Format is YYYY-MM-DD).'));
                    return $this->redirect($this->referer());
                }
            }
            if($this->request->data['Report']['date_to']){
                $this->request->data['Report']['date_to']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_to'])));
                if ($this->request->data['Report']['date_to'] == "1970-01-01") {
                    $this->Session->setFlash(__('Wrong format of Date To!. Please, try again(Date Format is YYYY-MM-DD).'));
                    return $this->redirect($this->referer());
                } 
            }
            
            if (!$this->request->data['Report']['product_category_id'] && !$this->request->data['Report']['contract_id'] && !$this->request->data['Report']['unit_id'] && !$this->request->data['Report']['client_id'] && !$this->request->data['Report']['date_from'] && !$this->request->data['Report']['date_to']) {
                $this->Session->setFlash(__('At Least one filter option is needed!,Please Try Again'));
                $this->redirect($this->referer());
            }
            $sql = "SELECT 
                Unit.name,
                Client.name,
                Contract.contract_no,
                ProductCategory.name,
                Product.name,
                Delivery.lot_id,                
		SUM(Delivery.quantity) as quantity,		 
                SUM(Delivery.unit_price*Delivery.quantity) as delivery_value,
		Delivery.currency,
                Delivery.uom,
                CP.unit_weight,
                CP.unit_weight_uom,
                Delivery.unit_weight,
                Delivery.unit_weight_uom
                
                FROM deliveries AS Delivery
                LEFT JOIN contracts AS Contract
                ON Contract.id=Delivery.contract_id
                LEFT JOIN units AS Unit
                ON Unit.id=Contract.unit_id
                LEFT JOIN clients AS Client
                ON Client.id=Contract.client_id
                LEFT JOIN product_categories AS ProductCategory
                ON ProductCategory.id=Delivery.product_category_id
                LEFT JOIN products AS Product  ON Product.id=Delivery.product_id
                LEFt JOIN contract_products as CP ON (CP.contract_id=Delivery.contract_id AND CP.product_id=Delivery.product_id)
                WHERE 1=1 ";
            if ($this->request->data['Report']['product_category_id']) {
                $sql.=" AND Delivery.product_category_id=" . $this->request->data['Report']['product_category_id'] . "";
            }
            if ($this->request->data['Report']['contract_id']) {
                $contract_id = $this->request->data['Report']['contract_id'];
                $sql.=" AND Delivery.contract_id=" . $this->request->data['Report']['contract_id'] . "";
            }
            if ($this->request->data['Report']['lot_id']) {
                $lot_id = $this->request->data['Report']['lot_id'];
                $sql.=" AND Delivery.lot_id='" . $this->request->data['Report']['lot_id'] . "'";
            }

            if ($this->request->data['Report']['unit_id']) {
                $sql.=" AND Contract.unit_id=" . $this->request->data['Report']['unit_id'] . "";
            }

            if ($this->request->data['Report']['client_id']) {
                $sql.=" AND Contract.client_id=" . $this->request->data['Report']['client_id'] . "";
            }

            if ($this->request->data['Report']['date_from'] && $this->request->data['Report']['date_to']) {
                
                    $sql.=" AND Delivery.actual_delivery_date BETWEEN '" . $this->request->data['Report']['date_from'] . "' AND '" . $this->request->data['Report']['date_to'] . "'" . "";
               
            }
            $sql.=" GROUP BY Delivery.product_id,Delivery.currency ORDER BY Delivery.product_id ASC";
            #execution of sql query
            #echo $sql;exit;        
            $results = $this->Report->query($sql);
            #echo '<pre>';print_r($results);exit;
        }

        #delivry date type
        $dates = array(             
            'actual_delivery_date' => 'Actual Delivery Date',
            'invoice_submission_progressive' => 'Planned Invoice Submission Date',
            'payment_cheque_collection_progressive' => 'Planned Payment/Cheque Collection Date',
            'payment_credited_to_bank_progressive' => 'Planned Payment Credited To Bank',
            
        );
        #contract list box

        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);

        #Client

        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');

        #Unit
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');
        #Product category

        $this->loadModel('Delivery');
        $product_categories = $this->Delivery->ProductCategory->find('list');

        #lot model
        if ($contract_id):
            $this->loadModel('Lot');
            $option = array('conditions' => array(
                    'Lot.contract_id' => $contract_id
                ),
                'fields' => array('Lot.lot_no', 'Lot.lot_no')
            );
            $lots = $this->Lot->find('list', $option);
        endif;


        $this->set(compact('lots', 'lot_id', 'contracts', 'clients', 'units', 'product_categories', 'dates', 'results'));
    }

    public function production_report() {
        $results = null;
        if ($this->request->is('post')) {
            if (!$this->request->data['Report']['product_category_id'] && !$this->request->data['Report']['contract_id'] && !$this->request->data['Report']['unit_id'] && !$this->request->data['Report']['client_id'] && !$this->request->data['Report']['date_from'] && !$this->request->data['Report']['date_to'] && !$this->request->data['Report']['date']) {
                $this->Session->setFlash(__('At Least one filter option is needed!,Please Try Again'));
                $this->redirect($this->referer());
            }
            $sql = "SELECT 
                Unit.name,Client.name,
                Contract.contract_no,
                ProductCategory.name,
                Product.name,
                Production.lot_id,
                Production.quantity,
                Production.uom,
                Production.unit_weight,
                Production.unit_weight_uom,
                Production.planned_completion_date,
                Production.actual_completion_date 
                
                FROM productions AS Production
                LEFT JOIN contracts AS Contract
                ON Contract.id=Production.contract_id
                LEFT JOIN units AS Unit
                ON Unit.id=Contract.unit_id
                LEFT JOIN clients AS Client
                ON Client.id=Contract.client_id
                LEFT JOIN product_categories AS ProductCategory
                ON ProductCategory.id=Production.product_category_id
                LEFT JOIN products AS Product 
                ON Product.id=Production.product_id WHERE 1=1 ";
            if ($this->request->data['Report']['product_category_id']) {
                $sql.=" AND Production.product_category_id=" . $this->request->data['Report']['product_category_id'] . "";
            }
            if ($this->request->data['Report']['contract_id']) {
                $contract_id = $this->request->data['Report']['contract_id'];
                $sql.=" AND Production.contract_id=" . $this->request->data['Report']['contract_id'] . "";
            }

            if ($this->request->data['Report']['lot_id']) {
                $lot_id = $this->request->data['Report']['lot_id'];
                $sql.=" AND Production.lot_id='" . $this->request->data['Report']['lot_id'] . "'";
            }

            if ($this->request->data['Report']['unit_id']) {
                $sql.=" AND Contract.unit_id=" . $this->request->data['Report']['unit_id'] . "";
            }

            if ($this->request->data['Report']['client_id']) {
                $sql.=" AND Contract.client_id=" . $this->request->data['Report']['client_id'] . "";
            }

            if ($this->request->data['Report']['date_from'] || $this->request->data['Report']['date_to'] || $this->request->data['Report']['date']) {
                if ($this->request->data['Report']['date_from'] && $this->request->data['Report']['date_to'] && $this->request->data['Report']['date']) {
                    $sql.=" AND Production." . $this->request->data['Report']['date'] . " BETWEEN '" . $this->request->data['Report']['date_from'] . "' AND '" . $this->request->data['Report']['date_to'] . "'" . "";
                    $sql.=" AND Production." . $this->request->data['Report']['date'] . " NOT LIKE '0000-00-00'";
                } else {
                    $this->Session->setFlash(__('Date From,Date To and Date Type is Required!,Please Try Again'));
                    $this->redirect($this->referer());
                }
            }
            #execution of sql query
            #echo $sql;exit;        
            $results = $this->Report->query($sql);
            #echo '<pre>';print_r($results);exit;
        }

        #delivry date type
        $dates = array(
            'planned_completion_date' => 'Planned Completion Date',
            'actual_completion_date' => 'Actual Completion Date',
        );
        #contract list box

        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);

        #Client

        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');

        #Unit
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');
        #Product category

        $this->loadModel('Production');
        $product_categories = $this->Production->ProductCategory->find('list');

        #lot model
        if ($contract_id):
            $this->loadModel('Lot');
            $option = array('conditions' => array(
                    'Lot.contract_id' => $contract_id
                ),
                'fields' => array('Lot.lot_no', 'Lot.lot_no')
            );
            $lots = $this->Lot->find('list', $option);
        endif;


        $this->set(compact('lots', 'lot_id', 'contracts', 'clients', 'units', 'product_categories', 'dates', 'results'));
    }

    /*
      public function credit_ar_report(){
      $results=null;
      if ($this->request->is('post')) {
      if(!$this->request->data['Report']['product_category_id']&&!$this->request->data['Report']['contract_id']&&!$this->request->data['Report']['unit_id']&&!$this->request->data['Report']['client_id']&&!$this->request->data['Report']['date_from']&&!$this->request->data['Report']['date_to']&&!$this->request->data['Report']['date'])
      {
      $this->Session->setFlash(__('At Least one filter option is needed!,Please Try Again'));
      $this->redirect($this->referer());
      }
      $sql="SELECT Unit.name,Client.name,
      Contract.contract_no,
      ProductCategory.name,

      SUM(Delivery.quantity) as quantity,
      SUM(Delivery.quantity*Delivery.unit_price) as delivery_value,

      'SUM(Collection.invoice_amount) as invoice_amount',
      'SUM(Collection.amount_received) as amount_received',
      'SUM(Collection.ait) as ait',
      'SUM(Collection.vat) as vat',
      'SUM(Collection.ld) as ld',
      'SUM(Collection.other_deduction) as other_deduction',
      'SUM(Collection.amount_received+Collection.ait+Collection.vat+Collection.ld+Collection.other_deduction) as total_collection'

      FROM deliveries AS Delivery
      LEFT JOIN contracts AS Contract
      ON Contract.id=Delivery.contract_id
      LEFT JOIN units AS Unit
      ON Unit.id=Contract.unit_id
      LEFT JOIN clients AS Client
      ON Client.id=Contract.client_id
      LEFT JOIN product_categories AS ProductCategory
      ON ProductCategory.id=Delivery.product_category_id
      LEFT JOIN collections AS Collection
      ON Contract.id=Collection.contract_id

      WHERE 1=1 ";
      if ($this->request->data['Report']['product_category_id']) {
      $sql.=" AND Delivery.product_category_id=".$this->request->data['Report']['product_category_id']."";
      }
      if ($this->request->data['Report']['contract_id']) {
      $sql.=" AND Delivery.contract_id=".$this->request->data['Report']['contract_id']."";
      }

      if ($this->request->data['Report']['unit_id']) {
      $sql.=" AND Contract.unit_id=".$this->request->data['Report']['unit_id']."";
      }

      if ($this->request->data['Report']['client_id']) {
      $sql.=" AND Contract.client_id=".$this->request->data['Report']['client_id']."";
      }

      if ($this->request->data['Report']['currency']) {
      $sql.=" AND Delivery.currency='".$this->request->data['Report']['currency']."'";
      }

      if($this->request->data['Report']['date_from'])
      {
      $sql.=" AND Delivery.actual_delivery_date <=".$this->request->data['Report']['currency']."";
      $sql.=" AND Delivery.actual_delivery_date NOT LIKE '0000-00-00'%"." ";

      #collection option
      $sql.=" AND Collection.payment_credited_to_bank_date <=".$this->request->data['Report']['currency']."";
      $sql.=" AND Collection.payment_credited_to_bank_date NOT LIKE '0000-00-00'%"." ";

      }
      $sql.=" group by Delivery.Contract_id,Delivery.product_category_id,Contract.client_id,Contract.unit_id,Contract.id,Collection.contract_id,Collection.product_category_id";
      #execution of sql query
      echo $sql;exit;
      $results=  $this->Report->query($sql);
      #echo '<pre>';print_r($results);exit;
      }

      #delivry date type
      $dates = array(
      'planned_completion_date' => 'Planned Completion Date',
      'actual_completion_date' => 'Actual Completion Date'
      );
      #contract list box
      $contracts=  $this->Session->read('contracts');
      if(!$contracts):
      $this->loadModel('Contract');
      $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
      $this->Contract->recursive = -1;
      $contracts = $this->Contract->find('list', $options);
      $this->Session->write('contracts',$contracts);
      endif;
      #Client
      $clients=  $this->Session->read('clients');
      if(!$clients):
      $this->loadModel('Client');
      $this->Client->recursive = -1;
      $clients = $this->Client->find('list');
      $this->Session->write('clients',$clients);
      endif;
      #Unit
      $this->loadModel('Unit');
      $this->Unit->recursive = -1;
      $units = $this->Unit->find('list');
      #Product category
      $product_categories=  $this->Session->read('product_categories');
      if(!$product_categories):
      $this->loadModel('Production');
      $product_categories=$this->Production->ProductCategory->find('list');
      $this->Session->write('product_categories',$product_categories);
      endif;
      $currencies = array('USD' => 'USD', 'BDT' => 'BDT');
      $this->set(compact('contracts','clients','units','product_categories','dates','results','currencies'));
      }

     */

    public function lot_report() {
        $results = null;
        if ($this->request->is('post')) {
            if (!$this->request->data['Report']['contract_id'] && !$this->request->data['Report']['lot_id']) {
                $this->Session->setFlash(__('At Least one filter option is needed!,Please Try Again'));
                $this->redirect($this->referer());
            }
            $sql = "SELECT 
                Unit.name,Client.name,
                Contract.contract_no,
                ProductCategory.name,
                Product.name,
                LotProduct.quantity,
                LotProduct.uom,
                LotProduct.unit_weight,
                LotProduct.unit_weight_uom,
                LotProduct.lot_id
                
                FROM lot_products AS LotProduct
                LEFT JOIN contracts AS Contract
                ON Contract.id=LotProduct.contract_id
                LEFT JOIN units AS Unit
                ON Unit.id=Contract.unit_id
                LEFT JOIN clients AS Client
                ON Client.id=Contract.client_id
                LEFT JOIN product_categories AS ProductCategory
                ON ProductCategory.id=LotProduct.product_category_id
                LEFT JOIN products AS Product 
                ON Product.id=LotProduct.product_id                
                WHERE 1=1 ";

            if ($this->request->data['Report']['contract_id']) {
                $contract_id = $this->request->data['Report']['contract_id'];
                $sql.=" AND LotProduct.contract_id=" . $this->request->data['Report']['contract_id'] . "";
            }

            if ($this->request->data['Report']['lot_id']) {
                $lot_id = $this->request->data['Report']['lot_id'];
                $sql.=" AND LotProduct.lot_id='" . $this->request->data['Report']['lot_id'] . "'";
            }

            #execution of sql query
            #echo $sql;exit;        
            $results = $this->Report->query($sql);
            #echo '<pre>';print_r($results);exit;
            #load lots by contract 
            $options = array('conditions' => array('Lot.contract_id' => $contract_id), 'fields' => array('lot_no', 'lot_no'), 'order' => array('Lot.id' => 'DESC'));
            $this->loadModel('Lot');
            $losts = $this->Lot->find('list', $options);
        }

        #contract list box    
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);

        $this->set(compact('contracts', 'results', 'lot_id', 'losts'));
    }

    public function procurement_report() {
        $results = null;
        if ($this->request->is('post')) {
            if (!$this->request->data['Report']['contract_id'] && !$this->request->data['Report']['lot_id']) {
                $this->Session->setFlash(__('At Least one filter option is needed!,Please Try Again'));
                $this->redirect($this->referer());
            }
            $sql = "SELECT 
                Unit.name,Client.name,
                Contract.contract_no,
                ProductCategory.name,
                Product.name,
                Procurement.quantity,
                Procurement.uom,
                Procurement.unit_weight,
                Procurement.unit_weight_uom,
                Procurement.lot_id
                
                FROM procurements AS Procurement
                LEFT JOIN contracts AS Contract
                ON Contract.id=Procurement.contract_id
                LEFT JOIN units AS Unit
                ON Unit.id=Contract.unit_id
                LEFT JOIN clients AS Client
                ON Client.id=Contract.client_id
                LEFT JOIN product_categories AS ProductCategory
                ON ProductCategory.id=Procurement.product_category_id
                LEFT JOIN products AS Product 
                ON Product.id=Procurement.product_id                
                WHERE 1=1 ";

            if ($this->request->data['Report']['contract_id']) {
                $contract_id = $this->request->data['Report']['contract_id'];
                $sql.=" AND Procurement.contract_id=" . $this->request->data['Report']['contract_id'] . "";
            }

            if ($this->request->data['Report']['lot_id']) {
                $lot_id = $this->request->data['Report']['lot_id'];
                $sql.=" AND Procurement.lot_id='" . $this->request->data['Report']['lot_id'] . "'";
            }

            #execution of sql query
            #echo $sql;exit;        
            $results = $this->Report->query($sql);
            #echo '<pre>';print_r($results);exit;
            #load lots by contract 
            $options = array('conditions' => array('Lot.contract_id' => $contract_id), 'fields' => array('lot_no', 'lot_no'), 'order' => array('Lot.id' => 'DESC'));
            $this->loadModel('Lot');
            $losts = $this->Lot->find('list', $options);
        }

        #contract list box       
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);

        $this->set(compact('contracts', 'results', 'lot_id', 'losts'));
    }

    public function psi_report() {
        $results = null;
        if ($this->request->is('post')) { 
            $sql = "SELECT 
                Unit.name,Client.name,
                Contract.contract_no,
                ProductCategory.name,
                Product.name,
                Inspection.quantity,
                Inspection.uom,
                Inspection.unit_weight,
                Inspection.unit_weight_uom,
                Inspection.lot_id,
                Inspection.actual_inspection_date,
                Inspection.added_date,
                Inspection.added_by
                
                
                FROM inspections AS Inspection
                LEFT JOIN contracts AS Contract
                ON Contract.id=Inspection.contract_id
                LEFT JOIN units AS Unit
                ON Unit.id=Contract.unit_id
                LEFT JOIN clients AS Client
                ON Client.id=Contract.client_id
                LEFT JOIN product_categories AS ProductCategory
                ON ProductCategory.id=Inspection.product_category_id
                LEFT JOIN products AS Product 
                ON Product.id=Inspection.product_id                
                WHERE 1=1 ";
             if ($this->request->data['Report']['product_category_id']) {
                $product_category_id= $this->request->data['Report']['product_category_id'];
                $sql.=" AND Inspection.product_category_id=" . $this->request->data['Report']['product_category_id'] . "";
            }
            if ($this->request->data['Report']['contract_id']) {
                $contract_id = $this->request->data['Report']['contract_id'];
                $sql.=" AND Inspection.contract_id=" . $this->request->data['Report']['contract_id'] . "";
            }

            if ($this->request->data['Report']['lot_id']) {
                $lot_id = $this->request->data['Report']['lot_id'];
                 $lots[$lot_id]=$lot_id;
                $sql.=" AND Inspection.lot_id='" . $this->request->data['Report']['lot_id'] . "'";
            }
            if($this->request->data['Report']['date_from']&&$this->request->data['Report']['date_to']){
                 $this->request->data['Report']['date_from']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_from'])));
                 $this->request->data['Report']['date_to']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_to'])));
                if ($this->request->data['Report']['date_to'] == "1970-01-01"||$this->request->data['Report']['date_from'] == "1970-01-01") {
                    $this->Session->setFlash(__('Wrong format!. Please, try again(Date Format is YYYY-MM-DD).'));
                    return $this->redirect($this->referer());
                } 
                
                 $sql.=" AND Inspection.actual_inspection_date BETWEEN '".$this->request->data['Report']['date_from']."' AND '".$this->request->data['Report']['date_to']."'";
            }
 
            #execution of sql query
            #echo $sql;exit;        
            $results = $this->Report->query($sql);
            #echo '<pre>';print_r($results);exit;
            #load lots by contract 
            if($contract_id){
            $this->loadModel('Lot');
            $lots_nos=  $this->Lot->find('list',array('conditions'=>array('Lot.contract_id'=>$contract_id))); 
            foreach ($lots_nos as $lots_no)
            {
                $lots[$lots_no]=$lots_no;
            }
            #echo '<pre>';print_r($lots);exit;
        }
        }

        #contract list box

        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);
        
        $this->loadModel('Delivery');
        $product_categories = $this->Delivery->ProductCategory->find('list');

        $this->set(compact('contracts', 'results', 'lot_id', 'lots','product_categories'));
    }

     public function pli_report() {
        if ($this->request->is('post')) {
            if (!$this->request->data['Report']['product_category_id'] && !$this->request->data['Report']['contract_id'] && !$this->request->data['Report']['unit_id'] && !$this->request->data['Report']['client_id'] && !$this->request->data['Report']['date_from'] && !$this->request->data['Report']['date_to'] && !$this->request->data['Report']['date']) {
                $this->Session->setFlash(__('At Least one filter option is needed!,Please Try Again'));
                $this->redirect($this->referer());
            }
            $sql = "SELECT 
                Unit.name,
                Client.name,
                Contract.contract_no,
                ProductCategory.name,
                Product.name,
			    Delivery.quantity,
			    Delivery.pli_qty,			 
                Delivery.uom,
                Delivery.unit_weight,
                Delivery.unit_weight_uom,
                Delivery.lot_id,
                Delivery.planned_delivery_date,
                Delivery.actual_delivery_date,
                Delivery.planned_pli_date,
                Delivery.actual_pli_date,
                Delivery.planned_date_of_pli_aproval,
                Delivery.actual_date_of_pli_aproval
                
                FROM deliveries AS Delivery
                LEFT JOIN contracts AS Contract
                ON Contract.id=Delivery.contract_id
                LEFT JOIN units AS Unit
                ON Unit.id=Contract.unit_id
                LEFT JOIN clients AS Client
                ON Client.id=Contract.client_id
                LEFT JOIN product_categories AS ProductCategory
                ON ProductCategory.id=Delivery.product_category_id
                LEFT JOIN products AS Product 
                ON Product.id=Delivery.product_id WHERE 1=1 AND Delivery.pli_qty>0 ";
            if ($this->request->data['Report']['product_category_id']) {
                $sql.=" AND Delivery.product_category_id=" . $this->request->data['Report']['product_category_id'] . "";
            }
            if ($this->request->data['Report']['contract_id']) {
                $contract_id = $this->request->data['Report']['contract_id'];
                $sql.=" AND Delivery.contract_id=" . $this->request->data['Report']['contract_id'] . "";
            }

            if ($this->request->data['Report']['unit_id']) {
                $sql.=" AND Contract.unit_id=" . $this->request->data['Report']['unit_id'] . "";
            }

            if ($this->request->data['Report']['client_id']) {
                $sql.=" AND Contract.client_id=" . $this->request->data['Report']['client_id'] . "";
            }
            if ($this->request->data['Report']['lot_id']) {
                $lot_id = $this->request->data['Report']['lot_id'];
                $sql.=" AND Delivery.lot_id='" . $this->request->data['Report']['lot_id'] . "'";
            }
            if ($this->request->data['Report']['date_from'] && $this->request->data['Report']['date_to']) {
               
             $sql.=" AND Delivery.actual_delivery_date BETWEEN '" . $this->request->data['Report']['date_from'] . "' AND '" . $this->request->data['Report']['date_to'] . "'" . "";
              
            }
            #execution of sql query
            #echo $sql;exit;        
            $results = $this->Report->query($sql);
            #echo '<pre>';print_r($results);exit;
            #load lots by contract 
            $options = array('conditions' => array('Lot.contract_id' => $contract_id), 'fields' => array('lot_no', 'lot_no'), 'order' => array('Lot.id' => 'DESC'));
            $this->loadModel('Lot');
            $losts = $this->Lot->find('list', $options);
        }
        
        #delivry date type
        $dates = array(             
            'planned_pli_date' => 'Planned PLI Date',
            'actual_pli_date' => 'Actual PLI Date',
            'planned_date_of_pli_aproval' => 'Planned PLI Approval Date',
            'actual_date_of_pli_aproval' => 'Actual PLI Approval Date'
        );
        #contract list box         
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);

        #Client        
        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');

        #Unit
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');
        #Product category

        $this->loadModel('Delivery');
        $product_categories = $this->Delivery->ProductCategory->find('list');
        $this->Session->write('product_categories', $product_categories);

        $this->set(compact('losts', 'lot_id', 'contracts', 'clients', 'units', 'product_categories', 'dates', 'results'));
    }
	
	public function pli_report_summary() {
        if ($this->request->is('post')) {
            if (!$this->request->data['Report']['product_category_id'] && !$this->request->data['Report']['contract_id'] && !$this->request->data['Report']['unit_id'] && !$this->request->data['Report']['client_id'] && !$this->request->data['Report']['date_from'] && !$this->request->data['Report']['date_to'] && !$this->request->data['Report']['date']) {
                $this->Session->setFlash(__('At Least one filter option is needed!,Please Try Again'));
                $this->redirect($this->referer());
            }
            $sql = "SELECT 
                Unit.name,
                Client.name,
                Contract.contract_no,
                ProductCategory.name,
                Product.name,
                SUM(Delivery.quantity) as quantity,
		SUM(Delivery.pli_qty) as pli_qty,
                SUM(Delivery.pli_qty*Delivery.unit_price) as pli_value,
                Delivery.uom,
                Delivery.unit_weight,
                Delivery.unit_weight_uom,
                Delivery.lot_id
                
                FROM deliveries AS Delivery
                LEFT JOIN contracts AS Contract
                ON Contract.id=Delivery.contract_id
                LEFT JOIN units AS Unit
                ON Unit.id=Contract.unit_id
                LEFT JOIN clients AS Client
                ON Client.id=Contract.client_id
                LEFT JOIN product_categories AS ProductCategory
                ON ProductCategory.id=Delivery.product_category_id
                LEFT JOIN products AS Product 
                ON Product.id=Delivery.product_id WHERE 1=1 ";
            if ($this->request->data['Report']['product_category_id']) {
                $sql.=" AND Delivery.product_category_id=" . $this->request->data['Report']['product_category_id'] . "";
            }
            if ($this->request->data['Report']['contract_id']) {
                $contract_id = $this->request->data['Report']['contract_id'];
                $sql.=" AND Delivery.contract_id=" . $this->request->data['Report']['contract_id'] . "";
            }

            if ($this->request->data['Report']['unit_id']) {
                $sql.=" AND Contract.unit_id=" . $this->request->data['Report']['unit_id'] . "";
            }

            if ($this->request->data['Report']['client_id']) {
                $sql.=" AND Contract.client_id=" . $this->request->data['Report']['client_id'] . "";
            }
            if ($this->request->data['Report']['lot_id']) {
                $lot_id = $this->request->data['Report']['lot_id'];
                $sql.=" AND Delivery.lot_id='" . $this->request->data['Report']['lot_id'] . "'";
            }
           if ($this->request->data['Report']['date_from'] && $this->request->data['Report']['date_to']) {
               
             $sql.=" AND Delivery.actual_delivery_date BETWEEN '" . $this->request->data['Report']['date_from'] . "' AND '" . $this->request->data['Report']['date_to'] . "'" . "";
              
            }
            #execution of sql query
            #echo $sql;exit;
            $sql.=" GROUP BY Delivery.product_id ORDER BY Delivery.product_id ASC";			
            $results = $this->Report->query($sql);
            #echo '<pre>';print_r($results);exit;
            #load lots by contract 
            $options = array('conditions' => array('Lot.contract_id' => $contract_id), 'fields' => array('lot_no', 'lot_no'), 'order' => array('Lot.id' => 'DESC'));
            $this->loadModel('Lot');
            $losts = $this->Lot->find('list', $options);
        }

        #contract list box         
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);

        #Client        
        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');

        #Unit
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');
        #Product category

        $this->loadModel('Delivery');
        $product_categories = $this->Delivery->ProductCategory->find('list');
        $this->Session->write('product_categories', $product_categories);

        $this->set(compact('losts', 'lot_id', 'contracts', 'clients', 'units', 'product_categories', 'dates', 'results'));
    }
  /*
    public function po_product_summary_report() {
        if($this->request->is('post')) {
            if (!$this->request->data['Report']['contract_id']) {
                $this->Session->setFlash(__('PO. No is required!,Please Try Again'));
                $this->redirect($this->referer());
            }
            if ($this->request->data['Report']['product_category_id']) {
                $option[] = "product_category_id=" . $this->request->data['Report']['product_category_id'];
            }
            if ($this->request->data['Report']['contract_id']) {
                $option[] = "contract_id=" . $this->request->data['Report']['contract_id'];
            }
            $option = implode(" AND ", $option);
            
            #contract products
            $cp_sql = "SELECT "
                    . "cp.contract_id,"
                    . "(select name from products as p where cp.product_id=p.id) name,"
                    . "(select name from product_categories as pc where cp.product_category_id=pc.id) category,"
                    . "cp.product_id,"
                    . "cp.quantity,"
                    . "cp.uom,"
                    . "cp.unit_price,"
                    . "cp.currency,"
                    . "cp.unit_weight,"
                    . "cp.unit_weight_uom"
                    . " FROM contract_products as cp"
                    . " where $option";
            $result = $this->Report->query($cp_sql);
            #echo '<pre>';print_r($result);exit;
            foreach ($result as $value) {
                #store contract and product id
                $data_product[$value['cp']['product_id']] = $value['cp']['contract_id'];
                $data['cp'][$value['cp']['product_id']]['name'] = $value[0]['name'];
                $data['cp'][$value['cp']['product_id']]['category'] = $value[0]['category'];
                $data['cp'][$value['cp']['product_id']]['quantity'] = $value['cp']['quantity'];
                $data['cp'][$value['cp']['product_id']]['uom'] = $value['cp']['uom'];
                $data['cp'][$value['cp']['product_id']]['unit_price'] = $value['cp']['unit_price'];
                $data['cp'][$value['cp']['product_id']]['currency'] = $value['cp']['currency'];
                $data['cp'][$value['cp']['product_id']]['unit_weight'] = $value['cp']['unit_weight'];
                $data['cp'][$value['cp']['product_id']]['unit_weight_uom'] = $value['cp']['unit_weight_uom'];
            }

            #lot products
            $lp_sql = "SELECT contract_id,"
                    . "product_id,"
                    . "sum(quantity) as quantity"
                    . " FROM lot_products as lp "
                    . "WHERE $option "
                    . "group by lp.product_id ";
            $result = $this->Report->query($lp_sql);
            foreach ($result as $value) {
                $data['lp'][$value['lp']['product_id']]['quantity'] = $value[0]['quantity'];
            }
            #group by lot
            $lp_groupbylot_sql = "SELECT contract_id,"
                    . "product_id,"
                    . "lot_id,"
                    . "sum(quantity) as quantity"
                    . " FROM lot_products as lp "
                    . "WHERE $option "
                    . "group by lp.product_id,lp.lot_id";
            $result = $this->Report->query($lp_groupbylot_sql);
            foreach ($result as $value) {
                $lot=explode('Lot-',$value['lp']['lot_id']);                
                $lot_info[$value['lp']['product_id']].='L-'.$lot[1].'#'.$value[0]['quantity'].'<br/>';
            }
            
            #procurement 
            $rm_sql = "SELECT contract_id,"
                    . "product_id,sum(quantity) as quantity"
                    . " FROM procurements rm "
                    . "WHERE $option and rm.actual_arrival_date NOT LIKE '0000-00-00' group by rm.product_id";
            $result = $this->Report->query($rm_sql);
            #echo '<pre>';print_r($result);exit;
            foreach ($result as $value) {
                $data['rm'][$value['rm']['product_id']]['quantity'] = $value[0]['quantity'];
            }
            #production
            $pr_sql = "SELECT contract_id,"
                    . "product_id,"
                    . "sum(quantity) as quantity"
                    . " FROM productions p "
                    . "WHERE $option and actual_completion_date!='0000-00-00' group by p.product_id";
            $result = $this->Report->query($pr_sql);
            foreach ($result as $value) {
                $data['p'][$value['p']['product_id']]['quantity'] = $value[0]['quantity'];
            }
            #Inspection
            $ins_sql = "SELECT contract_id,"
                    . "product_id,"
                    . "sum(quantity) as quantity"
                    . " FROM inspections i "
                    . "WHERE $option and actual_inspection_date!='0000-00-00' group by i.product_id";
            $result = $this->Report->query($ins_sql);
            foreach ($result as $value) {
                $data['i'][$value['i']['product_id']]['quantity'] = $value[0]['quantity'];
            }
            #Inspection
            $deli_sql = "SELECT contract_id,"
                    . "product_id,"
                    . "sum(quantity) as quantity,"
                    . "sum(pli_qty) as pli_qty"
                    . " FROM deliveries d "
                    . "WHERE $option and actual_delivery_date!='0000-00-00' group by d.product_id";
            $result = $this->Report->query($deli_sql);
            foreach ($result as $value) {
                $data['d'][$value['d']['product_id']]['quantity'] = $value[0]['quantity'];
                $data['d'][$value['d']['product_id']]['pli_qty'] = $value[0]['pli_qty'];
            }
            #Progressive payment
            $prog_sql = "SELECT contract_id,"
                    . "product_id,"
                    . "sum(quantity) as quantity"
                    . " FROM progressive_payments pp "
                    . "WHERE $option and sessionid!=0 group by pp.product_id";
            $result = $this->Report->query($prog_sql);
            foreach ($result as $value) {
                $data['pp'][$value['pp']['product_id']]['quantity'] = $value[0]['quantity'];
            }
        }
        #contract list box         
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));

        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);

        #ProductCategory list box
        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list');

        $this->set(compact('data', 'data_product', 'contracts', 'product_categories','lot_info'));
    }
   */ 
    public function yearly_plan_with_work_in_hand()
    {
        if($this->request->is('post')) {            
            if ($this->request->data['Report']['product_category_id']) {
                $product_category_id=$this->request->data['Report']['product_category_id'];
                $option[] = "cp.product_category_id=" . $this->request->data['Report']['product_category_id'];
            }
            if ($this->request->data['Report']['contract_id']) {
                $option[] = "cp.contract_id=" . $this->request->data['Report']['contract_id'];
            }
            if ($this->request->data['Report']['product_id']) {
                $product_id=$this->request->data['Report']['product_id'];
                $option[] = "cp.product_id=" . $this->request->data['Report']['product_id'];
            }
            if ($this->request->data['Report']['date_from']||$this->request->data['Report']['date_to'])
            {
                 if ($this->request->data['Report']['date_from']&&$this->request->data['Report']['date_to'])
                 {
                     $date_from=$this->request->data['Report']['date_from'];
                     $date_to=$this->request->data['Report']['date_to'];
                     $option[] = "SUBSTRING(cp.added_date,1,10) BETWEEN '".$this->request->data['Report']['date_from']."' AND '".$this->request->data['Report']['date_to']."'";
                 }
                 else if($this->request->data['Report']['date_from'])
                 {
                     $date_from=$this->request->data['Report']['date_from'];                    
                     $option[] = "SUBSTRING(cp.added_date,1,10) = '".$this->request->data['Report']['date_from']."'";
                 }
                 else if($this->request->data['Report']['date_to'])
                 {
                     $date_to=$this->request->data['Report']['date_to'];
                     $option[] = "SUBSTRING(cp.added_date,1,10) = '".$this->request->data['Report']['date_to']."'";
                 }
            }            
            
            $option = implode(" AND ", $option); 
            if($option)
            {
                $option=" AND ".$option;
            }
            else{
                $option='';
            }
            
            $sql="SELECT pc.name,
                    p.name,
                    c.contract_no,
                    c.contract_date,
                    cp.quantity,
                    cp.uom,
                    cp.unit_weight,
                    cp.unit_weight_uom,
                    cp.unit_price,
                    cp.currency,
                    cp.added_by,
                    cp.added_date
                    FROM contract_products as cp
                    LEFT JOIN contracts as c ON cp.contract_id=c.id
                    LEFT JOIN product_categories as pc ON cp.product_category_id=pc.id
                    LEFT JOIN products as p ON cp.product_id=p.id
                    WHERE 1=1  $option
                    ORDER BY cp.product_id ASC";
            $results=  $this->Report->query($sql);
            #echo '<pre>';print_r($results);exit;
            
        }
       #ProductCategory list box
       $this->loadModel('ProductCategory');
       $product_categories = $this->ProductCategory->find('list');
       
       #contract list box         
       $this->loadModel('Contract');
       $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
       $this->Contract->recursive = -1;
       $contracts = $this->Contract->find('list', $options);
       if(isset($product_category_id))
       {
           $this->loadModel('Product');
           $option=array(
               'conditions'=>array(
                   'Product.product_category_id'=>$product_category_id
               )
           );
           
           $products=  $this->Product->find('list',$option);
       }
       
       $this->set(compact('results','date_from','date_to','product_categories','contracts','product_id','products')); 
    }
    
    public function work_in_progress_summary_report()
    {
           
         if($this->request->is('post')) {
             
             if ($this->request->data['Report']['product_category_id']) {
               $option[]='product_category_id='.$this->request->data['Report']['product_category_id'];                
            }
            
            if ($this->request->data['Report']['contract_id']) {
               $option[]='contract_id='.$this->request->data['Report']['contract_id'];                
            }
            if ($this->request->data['Report']['unit_id']) {
                  $option[]='po.unit_id='.$this->request->data['Report']['unit_id'];                
               }
            if ($this->request->data['Report']['client_id']) {
               $option[]='po.client_id='.$this->request->data['Report']['client_id'];                
            }
            $option=  implode(" AND ", $option);
            $con='';
            if($option)
            {
                $con=" AND ".$option;
            } 
            $data=array();
            $sql="SELECT pc.name,sum(cp.quantity) as con_qty,cp.uom,cp.product_category_id FROM contract_products as cp,contracts as po, product_categories as pc where po.id=cp.contract_id and pc.id=cp.product_category_id $con GROUP BY cp.product_category_id,cp.uom";  
            $result=$this->Report->query($sql);
            $iteration=array();
            foreach ($result as $value)
            {
               $iteration[$value['cp']['product_category_id'].'-'.$value['cp']['uom']]=$value['cp']['product_category_id'];
               $data['po.qty'][$value['cp']['product_category_id'].'-'.$value['cp']['uom']]=$value[0]['con_qty'];
               $data[$value['cp']['product_category_id']]['name']=$value['pc']['name'];                
            }            
            $sql="SELECT sum(d.quantity) as deli_qty,d.uom,d.product_category_id FROM deliveries as d,contracts as po where po.id=d.contract_id $con GROUP BY d.product_category_id,d.uom";
            $result=$this->Report->query($sql);
            foreach ($result as $value)
            {                
               $data['d.qty'][$value['d']['product_category_id'].'-'.$value['d']['uom']]=$value[0]['deli_qty'];                
            }
         }
         
        #ProductCategory list box
        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list');
       
        #contract list box         
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);
        #Client        
        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');

        #Unit
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');
         
         $this->set(compact('data','iteration','product_categories','contracts','clients','units'));
    }
    
   
    
   
    
    //this function has defined for updating the planned invoice,cheque and payment credited
    public function calculation()
    {
        $this->autoRender=false;
        $this->loadModel('Contract');
        $this->loadModel('Delivery');
        $this->Contract->recursive=-1;
        $contracts=$this->Contract->find('all');
         $this->Delivery->recursive=-1;
        foreach ($contracts as $contract)
        {
            $contract_id=$contract['Contract']['id'];
            
            $pli_pac_con=($contract['Contract']['pli_pac']>0)?$contract['Contract']['pli_pac']:0;
            $pli_aproval_con=($contract['Contract']['pli_aproval']>0)?$contract['Contract']['pli_aproval']:0;
            $rr_collection_progressive_con=($contract['Contract']['rr_collection_progressive']>0)?$contract['Contract']['rr_collection_progressive']:0;
            
            $invoice_submission_progressive_con=($contract['Contract']['invoice_submission_progressive']>0)?$contract['Contract']['invoice_submission_progressive']:0;
            $payment_cheque_collection_progressive_con=($contract['Contract']['payment_cheque_collection_progressive']>0)?$contract['Contract']['payment_cheque_collection_progressive']:0;
            $payment_credited_to_bank_progressive_con=($contract['Contract']['payment_credited_to_bank_progressive']>0)?$contract['Contract']['payment_credited_to_bank_progressive']:0;
            
             $conditions=array(
                'conditions'=>array(
                    'Delivery.contract_id'=>$contract_id,
                    'Delivery.actual_delivery_date NOT LIKE'=>"0000-00-00",
                    //'Delivery.invoice_submission_progressive LIKE'=>"0000-00-00",//this condition may change
                ),
                'group'=>array(
                    'Delivery.contract_id',
                    'Delivery.actual_delivery_date'
                )
            );
            $deliveries=$this->Delivery->find('all',$conditions);
            $sql="";
            foreach ($deliveries as $deliverie)
            {
                 
                $actual_delivery_date=strtotime($deliverie['Delivery']['actual_delivery_date']);
                $id=$deliverie['Delivery']['id'];
                 
                $pli_pac1 = $actual_delivery_date+$pli_pac_con * 86400;
                $pli_pac = date('Y-m-d',$pli_pac1);

                $pli_aproval1 = $pli_pac1 + $pli_aproval_con * 86400;
                $pli_aproval = date('Y-m-d', $pli_aproval1);
                //planned_rr_collection_date
                $rr_collection_progressive1=$pli_aproval1+$rr_collection_progressive_con* 86400;
                $rr_collection_progressive= date('Y-m-d', $rr_collection_progressive1);
                //invoice_submission_progressive
                $invoice_submission_progressive1 =$rr_collection_progressive1+ $invoice_submission_progressive_con * 86400;
                $invoice_submission_progressive = date('Y-m-d', $invoice_submission_progressive1);                    
                //payment_cheque_collection_progressive
                $payment_cheque_collection_progressive1=$invoice_submission_progressive1+$payment_cheque_collection_progressive_con * 86400;
                $payment_cheque_collection_progressive = date('Y-m-d', $payment_cheque_collection_progressive1);
                //payment_credited_to_bank_progressive
                $payment_credited_to_bank_progressive1=$payment_cheque_collection_progressive1+$payment_credited_to_bank_progressive_con * 86400;
                $payment_credited_to_bank_progressive = date('Y-m-d', $payment_credited_to_bank_progressive1);
                
                $sql.="UPDATE deliveries SET invoice_submission_progressive = '".$invoice_submission_progressive."',payment_cheque_collection_progressive='".$payment_cheque_collection_progressive."',payment_credited_to_bank_progressive='".$payment_credited_to_bank_progressive."' WHERE contract_id= $contract_id and actual_delivery_date='".$deliverie['Delivery']['actual_delivery_date']."';";
             }
            if($sql){
               
             $this->Delivery->query($sql);
            }
        } 
    }
    
    //this function has defined for updating the planned invoice,cheque and payment credited
    public function calculation_lot()
    {
        $this->autoRender=false;
        $this->loadModel('Contract');
        $this->loadModel('Delivery');
        $this->Contract->recursive=-1;
        $contracts=$this->Contract->find('all');
         $this->Delivery->recursive=-1;
        foreach ($contracts as $contract)
        {
            $contract_id=$contract['Contract']['id'];            
             $conditions=array(
                'conditions'=>array(
                    'Delivery.contract_id'=>$contract_id,
                    'Delivery.actual_delivery_date NOT LIKE'=>"0000-00-00",
                    //'Delivery.invoice_submission_progressive LIKE'=>"0000-00-00",//this condition may change
                ),
                'fields'=>array(
                    'Delivery.payment_cheque_collection_progressive',
                    'Delivery.actual_delivery_date',
                    'Delivery.product_category_id',
                    'Delivery.contract_id',
                    'Delivery.unitid',
                    'Delivery.clientid',
                    'Delivery.currency',
                    'Delivery.uom',
                    'Delivery.lot_id'
                ),
                'group'=>array(
                    'Delivery.actual_delivery_date',
                    'Delivery.product_category_id',
                    'Delivery.contract_id',
                    'Delivery.unitid',
                    'Delivery.clientid',
                    'Delivery.currency',
                    'Delivery.uom',
                    'Delivery.lot_id'
                )
            );
            $deliveries=$this->Delivery->find('all',$conditions);
            $sql="";
            foreach ($deliveries as $deliverie)
            {
                
                $sql.="UPDATE collections SET lot_id = '".$deliverie['Delivery']['lot_id']."' WHERE contract_id=".$deliverie['Delivery']['contract_id']." and planned_payment_certificate_or_cheque_collection_date='".$deliverie['Delivery']['payment_cheque_collection_progressive']."' and product_category_id=".$deliverie['Delivery']['product_category_id']." and unitid=".$deliverie['Delivery']['unitid']." and clientid=".$deliverie['Delivery']['clientid']." and currency='".$deliverie['Delivery']['currency']."' and collection_type='Progressive';" ;
             }
            if($sql){
               
             $this->Delivery->query($sql);
            }
        } 
    }
    
    
        
       
        
        public function po_product_status(){             
             if($this->request->is('post')) {                  
               $option='';  
               
                if ($this->request->data['Report']['contract_id']) {
               $option.=" AND c.id=".$this->request->data['Report']['contract_id']."";     
               
               #load lots by contract wise
               $lots=$this->requestAction(array('controller'=>'lots','action'=>'LotListBoxByContract',$this->request->data['Report']['contract_id']));
               
            } /* 
            else{
                $this->Session->setFlash(__('PO. No is required!,Please Try Again'));
                $this->redirect($this->referer());
            }*/
               if ($this->request->data['Report']['product_category_id']) {
             
               $option.=" AND lp.product_category_id='".$this->request->data['Report']['product_category_id']."'";
               
            
            }  
             if ($this->request->data['Report']['unit_id']) {
               $option.=" AND c.unit_id=".$this->request->data['Report']['unit_id']."";            
             
            }  
             if ($this->request->data['Report']['client_id']) {
               $option.=" AND c.client_id='".$this->request->data['Report']['client_id']."'";              
            }  
             if ($this->request->data['Report']['lot_id']) {     
               $lot_id=$this->request->data['Report']['lot_id'];  
               $option.=" AND lp.lot_id='".$this->request->data['Report']['lot_id']."'";   
            }  
         $sql="SELECT c.contract_no,
             u.name,
             cl.name,
             lp.lot_id,
    IFNULL(lp.quantity,0) AS lp_quanity,
    IFNULL(ins.quantity,0) AS ins_quanity,
    IFNULL(d.quantity,0) AS deli_quanity,
    IFNULL(pp.quantity,0) AS pp_quanity
    FROM contracts AS c

    LEFT JOIN(
    SELECT product_category_id,contract_id,lot_id,sum(quantity) as quantity FROM lot_products
        GROUP BY lot_id
    ) as lp ON c.id=lp.contract_id

    LEFT JOIN(
    SELECT contract_id,lot_id,sum(quantity) as quantity FROM inspections
        GROUP BY lot_id
    ) as ins ON c.id=ins.contract_id AND ins.lot_id=lp.lot_id

    LEFT JOIN(
    SELECT contract_id,lot_id,sum(quantity) as quantity FROM deliveries
        GROUP BY lot_id
    ) as d ON c.id=d.contract_id AND d.lot_id=lp.lot_id
    LEFT JOIN(
    SELECT contract_id,lot_id,sum(quantity) as quantity FROM progressive_payments
        GROUP BY lot_id
    ) as pp ON c.id=pp.contract_id AND pp.lot_id=lp.lot_id
    LEFT JOIN units as u ON u.id=c.unit_id
    LEFT JOIN clients as cl ON cl.id=c.client_id
     WHERE 1=1 
     $option
";
      $results=  $this->Report->query($sql);     
     if($this->request->data['Report']['showreport']=="download")
    {
         $fileName = "po_product_summary_report_".date("d-m-y:h:s").".xls";
         foreach ($results as $result):
         $data_csv[]=array(
                       h($result['c']['contract_no']), 
                      
                       h($result['u']['name']),
                       h($result['cl']['name']),
                       h($result['lp']['lot_id']), 
                       h($result[0]['lp_quanity']),                        
                       h($result[0]['ins_quanity']),
                       h($result[0]['deli_quanity']),                      
                       h($result[0]['pp_quanity']),
                       h($result[0]['lp_quanity']-$result[0]['deli_quanity']) 
                      );
                  endforeach;
            $headerRow = array("PO.NO","Unit","Client", "LOT NO","LOT","Inspection","Delivery", "Invoice"," Balance(LOT-Delivery)");
            $this->ExportXls->export($fileName, $headerRow, $data_csv); 
    } 
             } 
        #ProductCategory list box
        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list');
        
        #Client        
        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');

        #Unit
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list'); 
        
         #contract list box         
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);
        
        $this->set(compact('product_categories','contracts','clients','units','results','lots','lot_id'));
        }
        
        public function planned_target_report()
        {
        if($this->request->is('post')) {
        if (!$this->request->data['Report']['date_from']||!$this->request->data['Report']['date_to']) {
             $this->Session->setFlash(__('Unit, Date From or Date To is required!,Please Try Again'));
                $this->redirect($this->referer());
         }
         /*
         #Delivary
         $this->loadModel('Delivery');
         #delivary with date
         for($i=1;$i<=7;$i++){
         $deliveries=  $this->Delivery->find('all',array(
             'conditions'=>array('Delivery.unitid'=>$i,'Delivery.payment_cheque_collection_progressive BETWEEN ? AND ?'=>array($this->request->data['Report']['date_from'],$this->request->data['Report']['date_to'])
              )   ,'fields'=>array(
             'SUM(Delivery.quantity*Delivery.unit_price) as delivery_value',
             'Delivery.product_id',     
             'Delivery.currency',
             'Delivery.unitid'     
         ),
         'group'=>array(
               'Delivery.unitid',
               'Delivery.currency',
               'Delivery.product_id',  
         )));
         #delivary with carry
         $deliverie_carries=  $this->Delivery->find('all',array(
             'conditions'=>array('Delivery.unitid'=>$i,'Delivery.payment_cheque_collection_progressive <'=>$this->request->data['Report']['date_from']
              )   ,'fields'=>array(
             'SUM(Delivery.quantity*Delivery.unit_price) as delivery_value',
             'Delivery.product_id',
             'Delivery.currency',
             'Delivery.unitid'     
         ),
         'group'=>array(
               'Delivery.unitid',
               'Delivery.currency',
             'Delivery.product_id',
         )));
         foreach ($deliveries as $deliverie)
         {
             $data[$deliverie['Delivery']['unitid'].''.$deliverie['Delivery']['currency'].'_d']+=$deliverie[0]['delivery_value'];
         }
         foreach ($deliverie_carries as $deliverie)
         {
             $data[$deliverie['Delivery']['unitid'].''.$deliverie['Delivery']['currency'].'_c']+=$deliverie[0]['delivery_value'];
         }
        
         }
          echo '<pre>';print_r($data);exit;
          */
        $data_delivery=array();
        $data_carry=array();
        $payment_delivery=array();
        $payment_carry=array();
        
        #plannned target from delivery
        $planned_target_delivery_sql="SELECT c.unit_id,
                                    c.currency,
                                    round(SUM(delivery_value*billing_percent_progressive*0.01),2)as delivery_value
                                    FROM deliveries as d,contracts as c
                                    WHERE c.id=d.contract_id
                                    AND d.payment_cheque_collection_progressive BETWEEN '".$this->request->data['Report']['date_from']."' AND '".$this->request->data['Report']['date_to']."'
                                    GROUP BY c.unit_id,c.currency
                                    ORDER BY c.unit_id,c.currency ASC";
       $results=$this->Report->query($planned_target_delivery_sql);  
       #echo '<pre>';print_r($results);exit;
       foreach ($results as $result)
        {
            $data_delivery[$result['c']['unit_id']][$result['c']['currency']]+=$result[0]['delivery_value'];
        }
        #echo '<pre>';print_r($data_delivery);
        #plannned target from adv and retention        
        $planned_target_collection_adv_retention="SELECT unitid,currency,SUM(invoice_amount) as invoice_amount FROM collections 
                                                    WHERE planned_payment_certificate_or_cheque_collection_date BETWEEN '".$this->request->data['Report']['date_from']."' AND '".$this->request->data['Report']['date_to']."'
                                                    AND collection_type NOT LIKE 'Progressive'
                                                    GROUP BY unitid,currency
                                                    ORDER BY unitid,currency  ASC";
       $results=$this->Report->query($planned_target_collection_adv_retention);
       #echo '<pre>';print_r($results);exit; 
       foreach ($results as $result)
       {
           $data_delivery[$result['collections']['unitid']][$result['collections']['currency']]+=$result[0]['invoice_amount'];
       }
      #echo '<pre>';print_r($data_delivery);exit; 
        #Target Planned collection
        $planned_target_all_collection_sql="SELECT unitid,currency,SUM(amount_received+ait+vat+ld+other_deduction) as payment FROM collections 
        WHERE planned_payment_certificate_or_cheque_collection_date BETWEEN '".$this->request->data['Report']['date_from']."' AND '".$this->request->data['Report']['date_to']."'
        GROUP BY unitid,currency ORDER BY unitid,currency  ASC";
        
        $results=$this->Report->query($planned_target_all_collection_sql);
           foreach ($results as $result)
           {
               $payment_delivery[$result['collections']['unitid']][$result['collections']['currency']]+=$result[0]['payment'];
           }        
       #echo '<pre>';print_r($results);exit; 
       #2nd part carry collecton
       
       #Carry target from delivery
        $planned_target_delivery_sql="SELECT c.unit_id,
                                    c.currency,
                                    round(SUM(delivery_value*billing_percent_progressive*0.01),2)as delivery_value
                                    FROM deliveries as d,contracts as c
                                    WHERE c.id=d.contract_id
                                    AND d.payment_cheque_collection_progressive < '".$this->request->data['Report']['date_from']."'
                                    GROUP BY c.unit_id,c.currency
                                    ORDER BY c.unit_id,c.currency ASC";
       $results=$this->Report->query($planned_target_delivery_sql);
       #echo '<pre>';print_r($results);exit; 
       foreach ($results as $result)
        {
            $data_carry[$result['c']['unit_id']][$result['c']['currency']]+=$result[0]['delivery_value'];
        }
        
        
         #Carry  from adv and retention        
        $planned_target_collection_adv_retention="SELECT unitid,currency,SUM(invoice_amount) as invoice_amount FROM collections 
                                                    WHERE planned_payment_certificate_or_cheque_collection_date < '".$this->request->data['Report']['date_from']."'
                                                    AND collection_type NOT LIKE 'Progressive'
                                                    GROUP BY unitid,currency ORDER BY unitid,currency  ASC";
       $results=$this->Report->query($planned_target_collection_adv_retention);
      #echo '<pre>';print_r($results);exit; 
       foreach ($results as $result)
       {
           $data_carry[$result['collections']['unitid']][$result['collections']['currency']]+=$result[0]['invoice_amount'];
       }
       
        #Target Planned collection
        $carry_target_all_collection_sql="SELECT unitid,currency,SUM(amount_received+ait+vat+ld+other_deduction) as payment FROM collections 
        WHERE planned_payment_certificate_or_cheque_collection_date < '".$this->request->data['Report']['date_from']."'
        GROUP BY unitid,currency ORDER BY unitid,currency  ASC";
        
        $results=$this->Report->query($carry_target_all_collection_sql);
         #echo '<pre>';print_r($results);exit; 
           foreach ($results as $result)
           {
               $data_carry[$result['collections']['unitid']][$result['collections']['currency']]-=$result[0]['payment'];
           } 
            
        }    
            
        #Unit
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list',array('order'=>array('Unit.id'=>"ASC"))); 
        $this->set(compact('units','results','data_delivery','data_carry','payment_delivery','payment_carry'));   
        }
		
		public function contract_wise_delivery_and_payment()
		{
                  if($this->request->is('post')) {
                    $option='';  
                    if ($this->request->data['Report']['contract_id']) {
                        $option.=" AND c.id=".$this->request->data['Report']['contract_id'];
                     }
                      if ($this->request->data['Report']['unit_id']) {
                        $option.=" AND c.unit_id=".$this->request->data['Report']['unit_id'];
                     }
                      if ($this->request->data['Report']['client_id']) {
                        $option.=" AND c.client_id=".$this->request->data['Report']['client_id'];
                     }
			
			$sql="select c.contract_no,
					(select name from units where units.id=c.unit_id) as unit,
					(select name from clients where clients.id=c.client_id) as client,
					 IFNULL(c.contract_value,0)contract_value,					 
					 IFNULL(d.delivery_value,0)delivery_value,					 
					 IFNULL(co.amount_received,0)amount_received, 
					 IFNULL(co.ait,0)ait,
					 IFNULL(co.vat,0)vat,
					 IFNULL(co.ld,0)ld,
					 IFNULL(co.other_deduction,0)other_deduction,
					 IFNULL(co.total_collection,0)total_collection,
					 IFNULL(co.ajust_adv_amount,0)ajust_adv_amount,
					 c.currency
					 from contracts as c 
					 left join ( select sum(delivery_value) as delivery_value,contract_id,currency from deliveries group by contract_id,currency ) as d ON c.id=d.contract_id
					 left join ( select sum(amount_received) as amount_received,sum(ait) as ait,sum(vat) as vat,sum(ld) as ld,sum(other_deduction) as other_deduction,SUM(amount_received+ait+vat+ld+other_deduction) as total_collection,sum(ajust_adv_amount) as ajust_adv_amount,contract_id,currency from collections group by contract_id,currency ) as co ON c.id=co.contract_id
                                         where 1=1
                                         $option
                                          ORDER BY c.id DESC";
					 
					  $results=$this->Report->query($sql);
					  
                  }
                    #contract list box         
                    $this->loadModel('Contract');
                    $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
                    $this->Contract->recursive = -1;
                    $contracts = $this->Contract->find('list', $options);
                    #Client        
                    $this->loadModel('Client');
                    $this->Client->recursive = -1;
                    $clients = $this->Client->find('list');

                    #Unit
                    $this->loadModel('Unit');
                    $this->Unit->recursive = -1;
                    $units = $this->Unit->find('list');
                     $this->set(compact('results','contracts','clients','units'));  
	}
                
  public function planned_target_with_contract_and_lot()
    { 
        if($this->request->is('post')) {
              $option=''; 
            if(!$this->request->data['Report']['date_from']||!$this->request->data['Report']['date_to'])
            {
              $this->Session->setFlash(__('Date From and Date is required. Please, try again(Date Format is YYYY-MM-DD).'));
              return $this->redirect($this->referer());  
            }
             
            $this->request->data['Report']['date_from']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_from'])));
           if ($this->request->data['Report']['date_from'] == "1970-01-01") {
               $this->Session->setFlash(__('Wrong format of Date From!. Please, try again(Date Format is YYYY-MM-DD).'));
               return $this->redirect($this->referer());
           }
        
            
            $this->request->data['Report']['date_to']= str_replace(array("\r", "\n", "\t"), '', date('Y-m-d', strtotime($this->request->data['Report']['date_to'])));
            if ($this->request->data['Report']['date_to'] == "1970-01-01") {
                $this->Session->setFlash(__('Wrong format of Date To!. Please, try again(Date Format is YYYY-MM-DD).'));
                return $this->redirect($this->referer());
            }  
             $option.=" AND d.payment_cheque_collection_progressive BETWEEN '".$this->request->data['Report']['date_from']."' AND '".$this->request->data['Report']['date_to']."'";
             $delivery="payment_cheque_collection_progressive BETWEEN '".$this->request->data['Report']['date_from']."' AND '".$this->request->data['Report']['date_to']."'";
             $collection="planned_payment_certificate_or_cheque_collection_date BETWEEN '".$this->request->data['Report']['date_from']."' AND '".$this->request->data['Report']['date_to']."'";
        
         if ($this->request->data['Report']['contract_id']) {
             $option.=" AND c.id=".$this->request->data['Report']['contract_id'];
          }
           if ($this->request->data['Report']['unit_id']) {
             $option.=" AND c.unit_id=".$this->request->data['Report']['unit_id'];
          }
           if ($this->request->data['Report']['client_id']) {
             $option.=" AND c.client_id=".$this->request->data['Report']['client_id'];
          }
           if ($this->request->data['Report']['product_category_id']) {
                $option.=" AND d.product_category_id=".$this->request->data['Report']['product_category_id'];                
            }
            
            $sql="select c.contract_no,
                c.billing_percent_progressive,
                (select name from units where units.id=c.unit_id) as unit,
                (select name from clients where clients.id=c.client_id) as client,
                 IFNULL(d.delivery_value,0)delivery_value,
                 IFNULL(d.delivery_quantity,0)delivery_quantity,
                 d.lot_id,
                 d.product_category,
                 d.currency,
                 IFNULL(co.invoice_qty,0)invoice_qty,
                 IFNULL(co.cheque_amount,0)cheque_amount, 
                 IFNULL(co.invoice_amount ,0)invoice_amount , 
                 IFNULL(co.amount_received,0)amount_received, 
                 IFNULL(co.ait,0)ait,
                 IFNULL(co.vat,0)vat,
                 IFNULL(co.ld,0)ld,
                 IFNULL(co.other_deduction,0)other_deduction,
                 IFNULL(co.total_collection,0)total_collection,
                 IFNULL(co.ajust_adv_amount,0)ajust_adv_amount,
                 co.invoice_ref_no,
                 co.currency
                 from contracts as c 
                 left join (select sum(quantity) as delivery_quantity,sum(delivery_value) as delivery_value,contract_id,currency,payment_cheque_collection_progressive,lot_id,product_category_id,(select name from product_categories as pc where deliveries.product_category_id=pc.id)as product_category from deliveries where $delivery group by contract_id,lot_id) as d ON c.id=d.contract_id
                 left join (select collection_type,sum(quantity) as invoice_qty,sum(cheque_amount) as cheque_amount,sum(invoice_amount) as invoice_amount,invoice_ref_no, sum(amount_received) as amount_received,sum(ait) as ait,sum(vat) as vat,sum(ld) as ld,sum(other_deduction) as other_deduction,SUM(amount_received+ait+vat+ld+other_deduction) as total_collection,sum(ajust_adv_amount) as ajust_adv_amount,contract_id,currency,lot_id from collections where collection_type='Progressive' and $collection group by contract_id,lot_id) as co ON c.id=co.contract_id and d.lot_id=co.lot_id and collection_type='Progressive'
                 where 1=1
                 $option                     
                 group by d.contract_id,d.lot_id,co.contract_id,co.lot_id  
                   ";
                 #echo $sql;exit;
                  $results=$this->Report->query($sql);
            
            
             
        }
        #ProductCategory list box
        $this->loadModel('ProductCategory');
        $product_categories = $this->ProductCategory->find('list'); 
       
        #contract list box         
        $this->loadModel('Contract');
        $options = array('fields' => array('Contract.id', 'Contract.contract_no'), 'order' => array('Contract.id' => 'DESC'));
        $this->Contract->recursive = -1;
        $contracts = $this->Contract->find('list', $options);
        #Client        
        $this->loadModel('Client');
        $this->Client->recursive = -1;
        $clients = $this->Client->find('list');

        #Unit
        $this->loadModel('Unit');
        $this->Unit->recursive = -1;
        $units = $this->Unit->find('list');
        #currency
        $currencies = array('USD' => 'USD', 'BDT' => 'BDT');
         
        $this->set(compact('product_categories','contracts','clients','units','results','currencies','date_from','date_to','both_date'));
    }
        
    public function po_summary_report()
    {
        $sql="SELECT  c.contract_no,
        c.id,
        u.name,
        cl.name,
        c.contract_value,
        IFNULL(d.delivery_value,0) as delivery_value,
        IFNULL(co.amount_received,0) as amount_received,
        IFNULL(co.ait,0) as ait,
        IFNULL(co.vat,0) as vat,
        IFNULL(co.ld,0) as ld,
        IFNULL(co.other_deduction,0) as other_deduction,
        IFNULL(co.adjust_adv_amount,0) as adjust_adv_amount,
        c.currency
       FROM contracts AS c        
       LEFT JOIN(
       SELECT contract_id,currency,SUM(quantity*unit_price) as delivery_value FROM deliveries
       GROUP BY contract_id,currency
       )as d ON c.id=d.contract_id      
        LEFT JOIN(
       SELECT contract_id,currency,SUM(amount_received) as amount_received,SUM(ait) as ait,SUM(vat) as vat,SUM(ld) as ld,SUM(other_deduction) as other_deduction,SUM(ajust_adv_amount) as adjust_adv_amount FROM collections
     GROUP BY contract_id,currency       
       )as co ON c.id=co.contract_id 
       LEFT JOIN  units AS u ON u.id=c.unit_id
       LEFT JOIN  clients AS cl ON cl.id=c.unit_id      
       GROUP BY c.currency,c.id ORDER BY c.id DESC";
       $results=$this->Report->query($sql); 
       $this->set(compact('results'));
    }
    
    public function mysql_dump()
    {
       $user = $this->Session->read('UserAuth');
       $UserID= $user['User']['username'];
       if($UserID=="admin")
        require WWW_ROOT.'mysql_database_backup.php';
    }

}
