<div class="x_panel">
    <div class="x_title">
       Planned Payment Certificate Report[Contract & LOT wise Progressive]:
        <div class="clearfix"></div>
    </div>
    <div class="x_content">        
        <?php echo $this->Form->create('Report', array('controller' =>'reports', 'action' =>"planned_target_with_contract_and_lot/ ", 'class' => 'form-horizontal form-label-left', 'id' => 'Report', 'onsubmit' => "return validate();")); ?>
        <div class="form-group">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>Date From:<span class="required">*</span></label>
                <?php echo $this->Form->input("date_from", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control single_cal3", 'required' => false, 'aria-describedby' => "inputSuccess2Status3", 'id' => 'date_from', 'required' =>true)) ?>                
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>Date To:<span class="required">*</span></label>
                <?php echo $this->Form->input("date_to", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control single_cal3", 'required' => false, 'aria-describedby' => "inputSuccess2Status3", 'id' => 'date_to', 'required' =>true)) ?>              
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>PO No:</label>
                <?php echo $this->Form->input("contract_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '')) ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                <label>Company/Unit</label>
                <?php echo $this->Form->input("unit_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'unit_id', 'required' => false)) ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                <label>Client</label>
                <?php echo $this->Form->input("client_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'client_id', 'required' => false)) ?> 
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                <label>Product Category</label>
                <?php echo $this->Form->input("product_category_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'product_category_id', 'required' => false,'options'=>$product_categories)) ?>
            </div>
            <!--<div class="col-md-2 col-sm-2 col-xs-12 form-group">
                <label>Currency</label>
                <?php //echo $this->Form->input("currency", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'currency', 'required' => false)) ?>
            </div>-->
            <div class="col-md-1 col-sm-1 col-xs-12 form-group">
                <label>&nbsp;</label>
                <?php echo $this->Form->submit('Search', array('class' => 'btn btn-success', 'id' => 'ReportSubmit')); ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>      
    </div>
    <div class="clearfix"></div>
    <div class="x_content">
        <table id="datatable-buttons" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>PO.NO</th>
                    <th>Lot.NO</th>                    
                    <th>Unit</th>
                    <th>Client</th>                    
                    <th>Product/Category</th>                     
                    <th>Delivery Quantity</th>
                    <th>Actual Invoice Quantity</th>                   
                    <th>Delivery Value</th>  
                    <th>Billing Percent</th>
                    <th>Planned Invoice Amount</th>
                    <th>Actual Invoice Amount</th>                    
                    <th>Cheque Amount</th>                                      
                    <th>Invoice Ref.</th>
                    <th>Amount Received</th>
                    <th>Adv. Adjustment</th>
                    <th>AIT</th>
                    <th>VAT</th>
                    <th>L.D</th>
                    <th>Other Deduction</th>                    
                   <th>Total Collection</th>
                    <th>Balance</th>
                    <th>Currency</th>  
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($results as $result): //echo '<pre>';print_r($result);exit; ?>
                    <tr>                       
                        <td><?php echo h($result['c']['contract_no']);?></td>
                        <td><?php echo h($result['d']['lot_id']);?></td>
                        <td><?php echo h($result[0]['unit']);?></td>
                        <td><?php echo h($result[0]['client']);?></td>
                        <td><?php echo h($result['d']['product_category']);?></td>                        
                        <td><?php echo h($result[0]['delivery_quantity']);?></td>
                        <td><?php echo h($result[0]['invoice_qty']);?></td>  
                        <td><?php echo h($result[0]['delivery_value']);?></td>  
                        <td><?php echo h($result['c']['billing_percent_progressive']);?>%</td>
                        <td><?php echo $invoice_amount=h(round($result[0]['delivery_value']*$result['c']['billing_percent_progressive']*0.01,3));?></td>                        
                        <td><?php echo $actual_invoice_amount=$result['co']['invoice_amount'];?></td>
                        <td><?php echo $cheque_amount=$result['co']['cheque_amount'];?></td>			
                        <td><?php echo h($result['co']['invoice_ref_no']);?></td>
                        <td><?php echo $amount_received=$result[0]['amount_received'];?></td>
                        <td><?php echo $ajust_adv_amount=$result[0]['ajust_adv_amount'];?></td>
                        <td><?php echo $ait=$result[0]['ait'];?></td>
                        <td><?php echo $vat=$result[0]['vat'];?></td>
                        <td><?php echo $ld=$result[0]['ld'];?></td>
                        <td><?php echo $other_deduction=$result[0]['other_deduction'];;?></td>  
                        <td><?php echo $total_collection=$result[0]['total_collection'];;?></td>  
                        <td><?php echo round($invoice_amount-$total_collection,3);?></td>
                        <td><?php echo h($result['d']['currency']);?></td>
                    </tr>			 
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
