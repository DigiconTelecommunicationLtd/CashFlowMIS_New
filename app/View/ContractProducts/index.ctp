<div class="x_panel">    
    <div class="x_content">
        <?php echo $this->Form->create('ContractProduct', array('action' => '/ ', 'class' => 'form-horizontal form-label-left', 'id' => 'ContractProduct')); ?>
        <div class="form-group">           
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>PO. No:</label>
                <?php echo $this->Form->input("contract_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'contract_id', 'required' => false, 'data-placeholder' => 'Choose Contract/PO No:')) ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>Currency:</label>
                <?php echo $this->Form->input("currency", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'currency', 'required' => false, 'data-placeholder' => 'Choose Currency Unit')) ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>Product Category:</label>
                <?php echo $this->Form->input("product_category_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'product_category_id', 'required' => false, 'data-placeholder' => 'Choose Product Category','options'=>$product_categories)) ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                 <label>UOM:</label>
                <?php echo $this->Form->input("uom", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control", 'tabindex' => -1, 'empty' => '', 'required' => false, 'id' => 'uom', 'data-placeholder' => 'Choose Product Unit')) ?>
            </div> 
            
            <!--<div class="col-md-3 col-sm-3 col-xs-12">
                <?php //echo $this->Form->input("product_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'cps_product_id', 'required' => false, 'data-placeholder' => 'Choose Product')) ?>
            </div>  -->
        </div>
        <div class="form-group">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>Entry Date From:</label>
                <?php echo $this->Form->input("added_date_from", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control single_cal3", 'id' => 'added_date_from', 'aria-describedby' => "inputSuccess2Status3", 'required' => false, 'data-placeholder' => 'Added Date From', 'value' => $data_view['start_date'])) ?>                
                
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>Entry Date To:</label>
                <?php echo $this->Form->input("added_date_to", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control single_cal3", 'id' => 'added_date_to', 'aria-describedby' => "inputSuccess2Status3", 'required' => false, 'data-placeholder' => 'Added Date End', 'value' => $data_view['end_date'])) ?>                
                 
            </div>
                      
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>&nbsp;</label>
                <?php echo $this->Form->submit('Search', array('class' => 'btn btn-success disabled_btn', 'id' => 'ContractProductSubmit')); ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>   
        <?php //echo $this->element('sql_dump'); ?>
    </div>
    <div class="x_content" id="contractProductUpdate">
        <?php if ($contractProducts): ?>
            <table id="datatable-buttons" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>PO. NO:</th>
                        <th>Category</th>
                        <th>Product</th>                    
                        <th>Quantity</th>
                        <th>UOM</th>
                        <th>U.Price</th>
                        <th>T.Amount</th>
                         <th>Currency</th>
                        <th>U.Weight</th>
                        <th>T.Weight</th>
                        <th>U.W.UOM</th>                  
                        <th>Added Date</th>
                        <th>Added By</th>
                        <th>Modified Date</th>
                        <th>Modified By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_weight=0;
                    $total_value=0;
                    $total_qty=0;
                    foreach ($contractProducts as $contractProduct): ?>
                        <tr>
                            <td><?php echo h($contractProduct['Contract']['contract_no']);?></td>
                            <td><?php echo $contractProduct['ProductCategory']['name'];?></td>
                            <td><?php echo $contractProduct['Product']['name'];?></td>
                            <td><?php echo h($contractProduct['ContractProduct']['quantity']);$total_qty+=$contractProduct['ContractProduct']['quantity'];?></td>
                            <td><?php echo $uom=h($contractProduct['ContractProduct']['uom']); ?></td>
                            <td><?php echo h($contractProduct['ContractProduct']['unit_price']);?></td>
                            <td><?php echo $amount=h($contractProduct['ContractProduct']['unit_price'] * $contractProduct['ContractProduct']['quantity']);$total_value+=$amount;?></td>
                            <td><?php echo $currency=h($contractProduct['ContractProduct']['currency']); ?></td>
                            <td><?php echo h($contractProduct['ContractProduct']['unit_weight']!="N/A"&&$contractProduct['ContractProduct']['unit_weight_uom']!="N/A")?$contractProduct['ContractProduct']['unit_weight']:'N/A';?></td>
                            <td><?php echo $unit_weight=h($contractProduct['ContractProduct']['unit_weight']!="N/A"&&$contractProduct['ContractProduct']['unit_weight_uom']!="N/A")?($contractProduct['ContractProduct']['unit_weight'] * $contractProduct['ContractProduct']['quantity']):'N/A'; $total_weight+=$unit_weight;?></td>
                            <td><?php echo $contractProduct['ContractProduct']['unit_weight_uom']; ?></td>
                            <td><?php echo h($contractProduct['ContractProduct']['added_date']); ?></td>
                            <td><?php echo h($contractProduct['ContractProduct']['added_by']); ?></td>
                            <td><?php echo ($contractProduct['ContractProduct']['modified_date'] != '0000-00-00 00:00:00') ? h($contractProduct['ContractProduct']['modified_date']):'N/A';?></td>
                            <td><?php echo h($contractProduct['ContractProduct']['modified_by']); ?></td>
                            <td>                            
                                <a href="<?php echo $this->Html->url('/contract_products/edit/' . $contractProduct['ContractProduct']['id']); ?>"><i class="fa fa-edit" title="edit">EDIT</i></a>&nbsp;&nbsp;
                                 <?php 
                                    if(strtotime($contractProduct['ContractProduct']['added_date'])>=  strtotime(date('Y-m-d 00:00:00'))){
                                    echo $this->Html->link('Delete',array('controller'=>'contract_products','action'=>'delete',$contractProduct['ContractProduct']['id'],$contractProduct['ContractProduct']['contract_id']),
                                    array('confirm'=>'Are you sure you want to delete the product?'));
                                    }
                                    ?>
                            </td>                             
                        </tr>
                    <?php endforeach; ?>  
                         <tr>
                            <td></td>
                            <td></td>
                            <td style="font-weight: bold">Total</td>
                            <td style="font-weight: bold"><?php echo $total_qty;?></td>
                            <td style="font-weight: bold"><?php echo $uom;?></td>
                            <td></td>
                            <td style="font-weight: bold"><?php echo $total_value;?></td>
                            <td style="font-weight: bold"><?php echo $currency;?></td>;
                            <td></td>
                            <td style="font-weight: bold"><?php echo $total_weight;?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>  