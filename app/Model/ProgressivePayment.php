<?php
App::uses('AppModel', 'Model');
/**
 * ProgressivePayment Model
 *
 * @property Contract $Contract
 * @property Lot $Lot
 * @property Product $Product
 * @property Collection $Collection
 */
class ProgressivePayment extends AppModel {

public $validate = array(
        'product_id' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Product Name is Required!',
                'allowEmpty' => false,
                'required' => true,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
     'lot_id' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Lot No. is Required!',
                'allowEmpty' => false,
                'required' => true,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    'contract_id' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Contract/PO No is Required!',
                'allowEmpty' => false,
                'required' => true,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
         'quantity' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Product quantity is Required!',
                'allowEmpty' => false,
                'required' => true,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    'uom' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Unit of Measurement is Required!',
                'allowEmpty' => false,
                'required' => true,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    'unit_weight' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Unit Weight is Required!',
                'allowEmpty' => false,
                'required' => true,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        )
    );
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Contract' => array(
			'className' => 'Contract',
			'foreignKey' => 'contract_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
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

}
