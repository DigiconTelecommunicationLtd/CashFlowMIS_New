<div class="x_panel">
    <div class="x_content">
        <?php echo $this->Form->create('Inspection', array('action' => 'add/ ', 'class' => 'form-horizontal form-label-left', 'id' => 'InspectionSearch')); ?>
        
        
        <div class="item form-group">
             <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product Category <span class="required">*</span>
            </label>
             <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("product_category_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'product_category_id', 'required' => true, 'options' => $product_categories)) ?>
            </div>             
        </div>
        
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Contract/PO. No <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("contract_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'required' => 'required', 'tabindex' => -1, 'empty' => '', 'id' => 'contract_id', 'data-placeholder' => 'Choose Contract/PO.NO')) ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div id="loading_1" style="display: none;">
                    <?php echo $this->Html->image('loading.gif',array('alt'=>'Please Wait ...','height'=>"15",'width'=>"15")); ?>
                    Please Wait ...
                </div>
            </div> 
        </div>       
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Lot No.<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->hidden("FormType", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12", 'required' => 'required', 'value' => 'search')); ?>
                <?php echo $this->Form->input("lot_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'required' => 'required', 'tabindex' => -1, 'id' => 'lot_id', 'data-placeholder' => 'Choose Lot Number', 'data-default' =>isset($lot_id)?$lot_id:null)) ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div id="loading" style="display: none;">
                    <?php echo $this->Html->image('loading.gif',array('alt'=>'Please Wait ...','height'=>"15",'width'=>"15")); ?>
                    Please Wait ...
                </div>
            </div>           
        </div>
         <label class="control-label col-md-6 col-sm-6 col-xs-12" for="name">
            </label>
         <div class="item form-group">                        
               <div class="col-md-3 col-sm-3 col-xs-12"> 
                <?php echo $this->Form->submit('Search', array('class' => 'btn btn-success', 'id' => 'InspectionSearchSubmit')); ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>      
    </div>
<div class="clearfix"></div>
<?php if ($this->Session->check('Message.flash')): ?>
         <div role="alert" class="alert alert-success alert-dismissible fade in">
            <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span>
            </button>
            <strong><?php echo $this->Session->flash(); ?></strong>
        </div>
    <?php endif; ?>     
    <?php if($inspection_results): ?>
        <div class="x_content">
            <table  class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <td colspan="9"style="text-align:center;font-weight: bold;color:#FFF;background-color:#2A3F54;">Inspection quantity & Date From::)</td>
                    </tr>
                     <?php echo $this->Form->create('Inspection', array('action' => 'add/ ', 'class' => 'form-horizontal form-label-left', 'id' => 'InspectionSave')); ?>
                    <tr>
                        <td colspan="7" style="text-align:right;color:red;font-weight: bold">Inspection Date[Y-M-D]</td>
                        <td colspan="2">
                <?php echo $this->Form->input("date", array("type" => "text", 'label' => false, 'div' => false, 'autocomplete'=>'off', 'class' => "form-control col-md-1 col-xs-12 single_cal3",'required'=>TRUE)) ?>
           </td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td>Product</td>                    
                        <td><a data-toggle="tooltip"  title="Lot quantity">Lot. Qty</a></td>
                        <td><a data-toggle="tooltip"  title="Previous Inspection quantity">P.ins.Qty</a></td>
                        <td>Balance.Qty</td>
                        <td>UOM</td>
                        <td><a data-toggle="tooltip"  title="Unit Weight">U.Weight</a></td>
                        <td><a data-toggle="tooltip"  title="Total Weight">T.Weight</a></td>
                        <td>Ins.Qty</td>                                           
                    </tr>
                </thead>
                <tbody>           
                   
                    <?php                    
                    foreach ($inspection_results as $value): ?>
                        <tr>
                            <td><?php echo $value[0]['category_name']; ?> </td>
                            <td><?php echo $value[0]['product_name']; ?> </td>
                            <td style="text-align:right"><?php echo $lot_qty = h($value[0]['ins_qty']); ?></td>
                            <td style="text-align:right"><?php
                            $pro_qty = isset($value[0]['delivery_qty']) ? $value[0]['delivery_qty'] : '';
                            echo h($pro_qty>0)?$pro_qty:0; 
                            ?></td>
                            <td style="text-align:right"><?php echo $balance=$lot_qty - $pro_qty;
                            /*check  balance quantity is greater than zero  if one product's balace qty is greater than zero then submit button will be visible*/
                           ?> </td>
                            <td><?php echo h($value['ins']['uom']); ?></td>
                            <td style="text-align:right"><?php echo ($value['ins']['unit_weight'] != 'N/A'&&$value['ins']['unit_weight_uom']!= 'N/A') ? h($value['ins']['unit_weight']).' '.$value['ins']['unit_weight_uom'] : 'N/A'; ?>&nbsp;</td>
                            <td style="text-align:right"><?php echo ($value['ins']['unit_weight'] != 'N/A'&&$value['ins']['unit_weight_uom']!= 'N/A') ? h($value['ins']['unit_weight'] * $lot_qty).' '.$value['ins']['unit_weight_uom'] : 'N/A'; ?>&nbsp;</td>
                            <td>                               
                                <?php echo $this->Form->input("quantity][".$value['ins']['product_id']."]", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12 numeric_number", 'value' =>($balance>0)?$balance:'','type'=>'number','style'=>'width:70px;', 'min'=>1,'max'=>$balance,'step'=>'any', 'onkeyup'=>"this.value = minmax(this.value, 0, $balance)")); ?>
                                 
                            </td> 
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td align="right" colspan="10" class="right">
                             
                            <?php echo $this->Form->hidden("product_category_id", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12", 'required' =>false, 'value' =>$product_category_id)); ?>
                            <?php echo $this->Form->hidden("contract_id", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12", 'required' => 'required', 'value' => isset($contract_id) ? $contract_id : '')); ?>
                            <?php echo $this->Form->hidden("lot_id", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12", 'required' => 'required', 'value' => isset($lot_id) ? $lot_id : '')); ?>
                            <?php echo $this->Form->hidden("FormType", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12", 'required' => 'required', 'value' => 'submit')); ?>
                            <?php echo $this->Form->submit('Save Inspection', array('class' => 'btn btn-success', 'id' => 'DeliverySaveSubmit')); ?>
                            <?php echo $this->Form->end(); ?>
                        </td>
                    </tr>          
                </tbody>
            </table>
        </div>
    <?php else :
        if(isset($inspection_results)):
        ?>
        <div role="alert" class="alert alert-success alert-dismissible fade in">
            <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
            <strong>There is no lot product with your search filter options.Please try again.</strong>
        </div>
    <?php endif; endif; ?>  
</div>
<?php
$this->Js->get('#product_category_id')->event('change', $this->Js->request(array(
            'controller' => 'inspections',
            'action' => 'getContractByCategory',            
                ), array(
            'update' => '#contract_id',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'before' => "$('#loading_1').fadeIn();",
            'complete' => "$('#loading_1').fadeOut();",        
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);
?>
<?php
$this->Js->get('#contract_id')->event('change', $this->Js->request(array(
            'controller' => 'lot_products',
            'action' => 'getLotByContract',
            'model' => 'Inspection'
                ), array(
            'update' => '#lot_id',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'before' => "$('#loading').fadeIn();",
            'complete' => "$('#loading').fadeOut();",        
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);
?>