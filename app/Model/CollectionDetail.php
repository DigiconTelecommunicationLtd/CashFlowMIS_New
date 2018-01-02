<?php
App::uses('AppModel', 'Model');
/**
 * CollectionDetail Model
 *
 * @property Collection $Collection
 * @property Contract $Contract
 * @property ProductCategory $ProductCategory
 */
class CollectionDetail extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Collection' => array(
			'className' => 'Collection',
			'foreignKey' => 'collection_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Contract' => array(
			'className' => 'Contract',
			'foreignKey' => 'contract_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ProductCategory' => array(
			'className' => 'ProductCategory',
			'foreignKey' => 'product_category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public function getCollectionByTrackID($invoice_ref_no=null)
    {
            
         
        $this->recursive=-1;
        $conditions=array(
            'conditions'=>array(
                 'CollectionDetail.invoice_ref_no LIKE'=>$invoice_ref_no
                
            ),
            'fields'=>array(
                'CollectionDetail.invoice_ref_no',
                'SUM(CollectionDetail.cheque_amount) as cheque_amount',
                //'CollectionDetail.actual_submission_date',
                'CollectionDetail.forecasted_payment_collection_date',
                'CollectionDetail.cheque_or_payment_certification_date',
                'CollectionDetail.actual_payment_certificate_or_cheque_collection_date',
                'CollectionDetail.payment_credited_to_bank_date',               
            ),
            'order'=>array(
                'CollectionDetail.id'=>"DESC"
            )
        );
        
        $data=$this->find('first',$conditions);
         
        if($data){
            return $data;
        }
        else{
            return false;
        }
    }
}
