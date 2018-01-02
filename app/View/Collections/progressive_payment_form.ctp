<div class="x_panel">
     <div class="x_title">
        <h2>Progressive Payment:</h2>
        <div class="clearfix"></div>
    </div>
  <div class="x_content">
        <?php echo $this->Form->create('Collection', array('action' => 'progressive_payment_form/ ', 'class' => 'form-horizontal form-label-left', 'id' => 'Collection')); ?>
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
      <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Currency <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("currency", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control", 'required' => 'required', 'tabindex' => -1, 'empty' => '')) ?>
            </div>
        </div> 
         <label class="control-label col-md-6 col-sm-6 col-xs-12" for="name">
            </label>
         <div class="item form-group">                        
               <div class="col-md-3 col-sm-3 col-xs-12"> 
                <?php echo $this->Form->submit('Search', array('class' => 'btn btn-success', 'id' => 'CollectionSubmit')); ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>      
    </div>  
    
    <!--if one-->
    <?php if($deliveries):?>
    <table class="table table-striped table-bordered">
        <tr>
            <th>SL.No:</th>
            <th>Category</th>
            <th>Product</th>
            <th>Delivery Qty</th>
            <th>PLI Qty</th>
            <th>Previous Invoice Qty</th>            
            <th>Balance Qty</th>            
            <th>Unit Price</th>
            <th>Invoice Qty</th>
        </tr>
       <?php echo $this->Form->create('Collection', array('action' => 'progressive_payments', 'class' => 'form-horizontal form-label-left','id'=>'ProgressivePayment')); ?>
      <?php
      $sl=1;
      $total_product=0;
      foreach ($deliveries as $delivery):
          $dp=$delivery[0]['quantity'];
          $pli_qty=$delivery[0]['pli_qty'];
          $pp=isset($alr_pro_products[$delivery['Delivery']['product_id']])?$alr_pro_products[$delivery['Delivery']['product_id']]:0;
          $balance=$pli_qty-$pp;
          if($balance>0){
              $total_product+=$balance;
          ?>
          <tr>
            <td><?php echo $sl++;?></td>
            <td><?php echo $delivery['ProductCategory']['name'];?></td>
            <td><?php echo $delivery['Product']['name'];?></td>
            <td><?php echo $dp=$delivery[0]['quantity'];?>&nbsp;<?php echo $delivery['Delivery']['uom'];?></td>
            <td><?php echo $pli_qty=$delivery[0]['pli_qty'];?>&nbsp;<?php echo $delivery['Delivery']['uom'];?></td>
            <td><?php echo $pp=isset($alr_pro_products[$delivery['Delivery']['product_id']])?$alr_pro_products[$delivery['Delivery']['product_id']]:0; ?>&nbsp;<?php echo $delivery['Delivery']['uom'];?></td>            
            <td><?php echo $balance=$pli_qty-$pp;?>&nbsp;<?php echo $delivery['Delivery']['uom'];?></td>             
            <td><?php echo ($con_unit_price[$delivery['Delivery']['product_id']]>0)?$con_unit_price[$delivery['Delivery']['product_id']]:"0.00";?></td>
            <td>                 
                 <?php echo $this->Form->input("quantity][".$delivery['Delivery']['product_id']."]", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12 numeric_number", 'value' =>($balance>0)?$balance:'','type'=>'number','style'=>'width:70px;', 'min'=>1,'max'=>$balance,'step'=>'any', 'onkeyup'=>"this.value = minmax(this.value, 0, $balance)")); ?>
                
            </td>
            
        </tr>
          <?php } endforeach;
          
          if($total_product>0){
          ?>   
        <tr>
            <td align="right" colspan="9">
                 <?php echo $this->Form->hidden("currency", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12", 'required' =>false, 'value' => isset($currency)?$currency : '')); ?>
                  <?php echo $this->Form->hidden("contract_id", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12", 'required' => 'required', 'value' => isset($contract_id) ? $contract_id : '')); ?>
                  <?php echo $this->Form->hidden("product_category_id", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12", 'required' => 'required', 'value' => isset($product_category_id) ? $product_category_id : '')); ?>
                  <?php echo $this->Form->hidden("lot_id", array('label' => false, 'div' => false, 'class' => "form-control col-md-1 col-xs-12", 'required' => 'required', 'value' => isset($lot_id) ? $lot_id : '')); ?>
                  <?php echo $this->Form->submit('Submit', array('class' => 'btn btn-success', 'id' => 'InvoiceSubmit')); ?>
                  <?php echo $this->Form->end(); ?>
        </tr>
          <?php } else {
              ?>
        <td align="center" colspan="9"><strong><span style="color:red;">There is no Product for progressive Invoice!</span></strong></td>
           <?php   
          }?>
        </form>  
    </table>
        
    <?php endif; ?>
    <!--/if one-->
</div>  

<?php
$this->Js->get('#product_category_id')->event('change', $this->Js->request(array(
            'controller' => 'collections',
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
<!--  product by contract -->
<?php
$this->Js->get('#contract_id')->event('change', $this->Js->request(array(
            'controller' => 'lot_products',
            'action' => 'getLotByContract',
            'model' => 'Collection'
                ), array(
            'update' => '#lot_id',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'before' => "$('#loading').fadeIn();$('#CollectionSubmit').attr('disabled','disabled');",
            'complete' => "$('#loading').fadeOut();$('#CollectionSubmit').removeAttr('disabled');",        
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);
?>
<!--  /product by contract -->