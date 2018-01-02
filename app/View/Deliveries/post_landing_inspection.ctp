<div class="x_panel">
     <div class="x_content">
        <?php echo $this->Form->create('Delivery', array('action' => 'post_landing_inspection', 'class' => 'form-horizontal form-label-left', 'id' => 'DeliverySearch')); ?>
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
                <?php echo $this->Form->submit('Search', array('class' => 'btn btn-success', 'id' => 'DeliverySearchSubmit')); ?>
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
        <div class="x_content search-table-outter wrapper">
            <table  class="search-table inner">
                <thead>
                    <tr>
                        <td colspan="12"style="text-align:center;font-weight: bold;color:#FFF;background-color:#2A3F54;">PLI quantity & Date From::)</td>
                    </tr>
                     <?php echo $this->Form->create('Delivery', array('action' => 'post_landing_inspection', 'class' => 'form-horizontal form-label-left', 'id' => 'PLISave')); ?>
                    <tr>
                        <td colspan="3" style="text-align:right;color:red;font-weight: bold">Actual PLI Date[Y-M-D]</td>
                       <td colspan="2">
                       <?php echo $this->Form->input("date", array("type" => "text", 'label' => false, 'div' => false, 'autocomplete'=>'off', 'class' => "form-control col-md-1 col-xs-12 single_cal3",'required'=>TRUE)) ?>
                        </td>
                       <td colspan="3" style="text-align:right;color:red;font-weight: bold">Actual PLI Approval Date[Y-M-D]</td>
                       <td colspan="2">
                       <?php echo $this->Form->input("date1", array("type" => "text", 'label' => false, 'div' => false, 'autocomplete'=>'off', 'class' => "form-control col-md-1 col-xs-12 single_cal3",'required'=>TRUE)) ?>
                        </td>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td>Product</td>                    
                        <td><a data-toggle="tooltip"  title="Lot quantity">Deli.Qty</a></td>
                        <td><a data-toggle="tooltip"  title="Previous Inspection quantity">Prev.PLI.Qty</a></td>
                        <td>Balance.Qty</td>
                        <td><a data-toggle="tooltip"  title="Unit of Mesurement">UOM</a></td>
                        <td><a data-toggle="tooltip"  title="Unit Weight">U.Weight</a></td>
                        <td><a data-toggle="tooltip"  title="Total Weight">T.Weight</a></td>
                        <td><a data-toggle="tooltip"  title="PLI Quantity">PLI.Qty</a></td>
                        <td><a data-toggle="tooltip"  title="Actual Delivery Date">Actual.Deli.Date</a></td>
                        <td><a data-toggle="tooltip"  title="Planned Invoice Date">Planned PLI Date</a></td>
                        <td><a data-toggle="tooltip"  title="Planned Invoice Date">Planned PLI Approval Date</a></td>
                    </tr>
                </thead>
                <tbody>           
                   
                    <?php
                     $total_qty=0;
                    foreach ($inspection_results as $value):
                        $lot_qty = h($value[0]['delivery_qty']);
                        $pro_qty = isset($value[0]['pli_qty']) ? $value[0]['pli_qty'] : 0;
                        $balance=$lot_qty - $pro_qty;
                        if($balance>0){
                             $total_qty+=$balance;
                        ?>
                        <tr>
                            <td><?php echo $value[0]['category_name']; ?> </td>
                            <td><?php echo $value[0]['product_name']; ?> </td>
                            <td style="text-align:right"><?php echo $lot_qty = h($value[0]['delivery_qty']); ?></td>
                            <td style="text-align:right"><?php
                            $pro_qty = isset($value[0]['pli_qty']) ? $value[0]['pli_qty'] : 0;
                            echo h($pro_qty>0)?$pro_qty:0; 
                            ?></td>
                            <td style="text-align:right"><?php echo $balance=$lot_qty - $pro_qty;
                            /*check  balance quantity is greater than zero  if one product's balace qty is greater than zero then submit button will be visible*/
                           ?> </td>
                            <td><?php echo h($value['d']['uom']); ?></td>
                            <td style="text-align:right"><?php echo ($value['d']['unit_weight'] != 'N/A'&&$value['d']['unit_weight_uom']!= 'N/A') ? h($value['d']['unit_weight']).' '.$value['d']['unit_weight_uom'] : 'N/A'; ?>&nbsp;</td>
                            <td style="text-align:right"><?php echo ($value['d']['unit_weight'] != 'N/A'&&$value['d']['unit_weight_uom']!= 'N/A') ? h($value['d']['unit_weight'] * $lot_qty).' '.$value['d']['unit_weight_uom'] : 'N/A'; ?>&nbsp;</td>
                            <td>                               
                                <?php echo $this->Form->input("quantity][".$value['d']['id']."]", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12 numeric_number", 'value' =>($balance>0)?$balance:'','type'=>'number','style'=>'width:70px;', 'min'=>1,'max'=>$balance,'step'=>'any', 'onkeyup'=>"this.value = minmax(this.value, 0, $balance)")); ?>
                                 
                            </td> 
                           <td><?php echo h($value['d']['actual_delivery_date']); ?></td>
                           <td><?php echo h($value['d']['planned_pli_date']); ?></td>
                           <td><?php echo h($value['d']['planned_date_of_pli_aproval']); ?></td>
                        </tr>
                    <?php } endforeach; ?>
                      <?php if($total_qty>0){?>
                    <tr>
                        <td align="right" colspan="9" class="right">
                             
                            <?php echo $this->Form->hidden("product_category_id", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12", 'required' =>false, 'value' =>$product_category_id)); ?>
                            <?php echo $this->Form->hidden("contract_id", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12", 'required' => 'required', 'value' => isset($contract_id) ? $contract_id : '')); ?>
                            <?php echo $this->Form->hidden("lot_id", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12", 'required' => 'required', 'value' => isset($lot_id) ? $lot_id : '')); ?>
                            <?php echo $this->Form->hidden("FormType", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12", 'required' => 'required', 'value' => 'submit')); ?>
                            <?php echo $this->Form->submit('Save PLI', array('class' => 'btn btn-success', 'id' => 'DeliverySaveSubmit')); ?>
                            <?php echo $this->Form->end(); ?>
                        </td>
                        <td align="right" colspan="3" class="right">&nbsp;</td>
                    </tr> 
                    <?php } else{
                          ?>
                        <tr>
                            <td colspan="12" align="center"><strong><span style="color:red;">There is no remaining PLI product against this PO.</span></strong></td>
                        </tr>
                     <?php
                      }?>
                </tbody>
            </table>
        </div>
    <?php else :
        if(isset($inspection_results)):
        ?>
        <div role="alert" class="alert alert-success alert-dismissible fade in">
            <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
            <strong>There is no lot Delivery Product with your search filter options.Please try again.</strong>
        </div>
    <?php endif; endif; ?>  
</div>
<?php
$this->Js->get('#product_category_id')->event('change', $this->Js->request(array(
            'controller' => 'deliveries',
            'action' => 'getContractByCategoryPli',            
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
            'model' => 'Delivery'
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