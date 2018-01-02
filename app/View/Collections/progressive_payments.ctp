<div class="x_panel">
    <!-- contract added product list -->     
    <div class="x_content">            
        <table  class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Contract/PO.No</th>
                    <th>Lot No.</th>
                    <th>Category</th>
                    <th>Product</th>
                    <th>Invoice Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>                             
                    <th>Unit Weight</th>
                    <th>Total Weight</th>                     
                </tr>
            </thead>
            <tbody>
                <?php
                $total_delivery_value = 0.00;
                foreach ($contract['ContractProduct'] as $production): if (in_array($production['product_id'], $product_ids)){
                    ?>
                    <tr>

                        <td> <?php echo $contract['Contract']['contract_no']; ?> </td>
                        <td> <?php echo $lot_id; ?> </td>                        
                        <td> <?php echo $product_categories[$production['product_category_id']]; ?> </td>
                        <td> <?php echo $con_pro[$production['product_id']]; ?> </td>
                        <td><?php echo h($pquantitys[$production['product_id']]); ?>&nbsp;<?php echo h($production['uom']); ?></td>
                        <td> <?php echo $production['unit_price']; ?> </td>
                        <td> <?php echo $amount=$pquantitys[$production['product_id']]*$production['unit_price'];$total_delivery_value+=$amount; ?> </td>
                        <td><?php echo ($production['unit_weight'] != 'N/A'&&$production['unit_weight_uom'] != 'N/A') ? h($production['unit_weight']) . ' ' . $production['unit_weight_uom'] : 'N/A'; ?>&nbsp;</td>
                        <td><?php echo ($production['unit_weight'] != 'N/A'&&$production['unit_weight_uom'] != 'N/A') ? h($production['unit_weight'] * $pquantitys[$production['product_id']]) . ' ' . $production['unit_weight_uom'] : 'N/A'; ?>&nbsp;</td>

                    </tr>
                <?php } endforeach; ?>
            </tbody>
        </table>     
    </div>
<div class="clearfix"></div>

    <div class="x_content">
        <?php echo $this->Form->create('Collection', array('action' => 'save_progressive_payment', 'class' => 'form-horizontal form-label-left', 'id' => 'Collection')); ?>

        
        <?php echo $this->Form->hidden("contract_id", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12", 'required' => 'required', 'value' => $contract_id, 'readOnly' => true)) ?>

        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Delivery Value <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("contract_value", array("type" => "number", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12", 'required' => 'required', 'id' => 'contract_value', 'readOnly' => true, 'value' =>$total_delivery_value)) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Currency
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("currency", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12", 'required' => 'required', 'value' => $currency, 'readOnly' => true)) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><strong>Invoice Ref. No</strong>(<span style="color:red;">Please don't use special characters and must be unique</span>): <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <label><?php echo $invoice_ref;?></label>
                <?php echo $this->Form->input("invoice_ref", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12", 'required' => 'required', 'id' =>'invoice_ref')) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Billing Percent(%) <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("billing_percent", array("type" => "number", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12", 'required' => 'required', 'value'=>$contract['Contract']['billing_percent_progressive'], 'id' =>'billing_percent','onchange'=>"InvoiceValueCalculation(this.value)",'readOnly'=>true)) ?>
            </div>
        </div>
         <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Invoice Value <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("invoice_amount", array("type" => "number", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12", 'required' => 'required', 'id' =>'invoice_amount','readOnly'=>true,'value'=>$total_delivery_value*$contract['Contract']['billing_percent_progressive']*0.01)) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Balance
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("balance", array("type" => "number", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12",'id' =>'balance','readOnly'=>true,'value'=>$total_delivery_value-($total_delivery_value*$contract['Contract']['billing_percent_progressive']*0.01))) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Planned Submission Date<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">                
                <?php echo $this->Form->input("planned_submission_date", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control has-feedback-left single_cal3", 'required' => 'required', 'id' =>'planned_submission_date','readOnly'=>true,'value'=>$invoice_date[0]['invoice_submission_progressive'])) ?>
                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Remaining Days
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("reaming_days", array("type" => "number", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12", 'id' =>'reaming_days','readOnly'=>true,'value'=>(strtotime($invoice_date[0]['invoice_submission_progressive'])- strtotime(date('Y-m-d')))/86400)) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Actual Submission Date<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("actual_submission_date", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control has-feedback-left single_cal3", 'required' => 'required', 'id' =>'actual_submission_date')) ?>
                <?php echo $this->Form->hidden("planned_payment_certificate_or_cheque_collection_date", array('label' => false, 'div' => false, 'class' => "form-control has-feedback-left single_cal3", 'required' => 'required', 'value' =>$invoice_date[0]['payment_cheque_collection_progressive'])) ?>
                <?php echo $this->Form->hidden("lot_id", array('label' => false, 'div' => false,'required' => 'required', 'value' =>$lot_id)); ?>
                <?php echo $this->Form->hidden("contract_id", array('label' => false, 'div' => false,'required' => 'required', 'value' =>$contract_id)); ?>
                <?php echo $this->Form->hidden("product_category_id", array('label' => false, 'div' => false,'required' => 'required', 'value' =>$product_category_id)); ?>
                <?php echo $this->Form->hidden("planned_payment_collection_date", array('label' => false, 'div' => false, 'class' => "form-control has-feedback-left single_cal3", 'required' => 'required', 'value' =>$invoice_date[0]['payment_credited_to_bank_progressive'])) ?>
                <?php echo $this->Form->hidden("payment_credited_to_bank_date", array('label' => false, 'div' => false, 'class' => "form-control has-feedback-left single_cal3", 'required' => 'required', 'value' =>$invoice_date[0]['payment_credited_to_bank_progressive'])) ?>
                
                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            </div>
        </div>         
        <div class="ln_solid"></div>
        <div class="form-group">
            <div class="col-md-6 col-md-offset-8"> 
                <?php echo $this->Form->submit('Submit', array('class' => 'btn btn-success', 'id' => 'CollectionSubmit')); ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>  
    </div>        
</div>
<script>
strLength =document.getElementById("invoice_ref_no").value.length*2;    
document.getElementById("invoice_ref_no").focus();
document.getElementById("invoice_ref_no").setSelectionRange(strLength, strLength);

function InvoiceValueCalculation(billing_percent)
{
    
   var contract_value=parseInt(document.getElementById("contract_value").value);
   var invoice_value=(contract_value*billing_percent)/100;
   document.getElementById("invoice_amount").value=invoice_value;
   document.getElementById("balance").value=contract_value-invoice_value;
}
</script>