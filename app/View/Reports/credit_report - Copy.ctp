<div class="x_panel">
    <div class="x_content">     
        <?php echo $this->Form->create('Report', array('controller' => 'reports', 'action' => 'credit_report/ ', 'class' => 'form-horizontal form-label-left', 'id' => 'Report')); ?>
        <div class="form-group">  
           <div class="col-md-3 col-sm-3 col-xs-12">
                <label>Contract/PO.No:</label>
                <?php echo $this->Form->input("contract_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'required' => false)) ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                <label>Unit</label>
                <?php echo $this->Form->input("unit_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'unit_id', 'required' => false)) ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                <label>Client</label>
                <?php echo $this->Form->input("client_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'client_id', 'required' => false)) ?> 
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                <label>Category</label>
                <?php echo $this->Form->input("product_category_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'product_category_id', 'required' => false,'options'=>$product_categories)) ?>
            </div>           
            <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                <label>Currency</label> 
                <?php echo $this->Form->input("currency", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'currency', 'required' => false)) ?>
            </div>
            <div class="col-md-1 col-sm-1 col-xs-12 form-group">
                <label>&nbsp;</label>
                <?php echo $this->Form->submit('Search', array('class' => 'btn btn-success', 'id' => 'ReportSubmit')); ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>

    <div class="x_content">    
        <table id="datatable-buttons" class="table table-striped table-bordered">
            <thead>
                <tr>                    
                    <th>PO. NO:</th>
                    <th>Unit</th> 
                    <th>Client</th>
                    <th>Category</th>
                    <th>Delivery Value</th>
                    <th>Received Amount</th>                     
                    <th>AIT</th>
                    <th>VAT</th>
                    <th>L.D</th>
                    <th>Other Deduction</th>
                    <th>Adv. Adjusted</th>
                    <th>Adv. Amount</th>
                    <th>Total Collection</th>
                    <th>Credit/AR</th> 
                    <th>Currency</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($data)):
                    $options = array(
                        'wholeSymbol' => '',
                        'wholePosition' => 'before',
                        'fractionSymbol' => false,
                        'fractionPosition' => 'after',
                        'zero' => 0,
                        'places' => 2,
                        'thousands' => ',',
                        'decimals' => '.',
                        'negative' => '()',
                        'escape' => true,
                        'fractionExponent' => 2
                    );
                    foreach ($data as $value):
                        $delivery=($value['d']['delivery_value']);
                        $contract_value=($value['cp']['contract_value']);
                    
                        $amount_received=($value['c']['amount_received'])?$value['c']['amount_received']:0;
                        $ait=($value['c']['ait'])?$value['c']['ait']:0;
                        $vat=($value['c']['vat'])?$value['c']['vat']:0;
                        $ld=($value['c']['ld'])?$value['c']['ld']:0;
                        $other_deduction=($value['c']['other_deduction'])?$value['c']['other_deduction']:0;
                        $ajust_adv_amount=($value['c']['ajust_adv_amount'])?$value['c']['ajust_adv_amount']:0;
                        $adv_amount=($value['advc']['adv_amount'])?$value['advc']['adv_amount']:0;
                        $total_collection=$amount_received+$ait+$vat+$ld+$other_deduction;
                        ?>
                        <tr>
                            <td><?php echo h($value['con']['contract_no']); ?></td>
                            <td><?php echo h($value['u']['name']); ?></td>
                            <td><?php echo h($value['client']['name']); ?></td>
                            <td><?php echo h($value[0]['pc_name']); ?></td>                          
                             <td align="right"><?php echo $this->Number->currency($delivery, $options); ?></td> 
                             <td align="right"><?php echo $this->Number->currency($amount_received, $options); ?></td>
                             
                            <td align="right"><?php echo $this->Number->currency($ait, $options); ?></td>
                            <td align="right"><?php echo $this->Number->currency($vat, $options); ?></td>
                            <td align="right"><?php echo $this->Number->currency($ld, $options); ?></td>
                            <td align="right"><?php echo $this->Number->currency($other_deduction, $options); ?></td>
                            <td align="right"><?php echo $this->Number->currency($ajust_adv_amount, $options); ?></td>
                            <td align="right"><?php echo $this->Number->currency($adv_amount, $options); ?></td>
                            
                            <td align="right"><?php echo $this->Number->currency($total_collection, $options); ?></td>
                             
                            <td align="right">
                           <?php                            
                            //echo $this->Number->currency(($delivery+$adv_amount-(($delivery*$adv_amount)/$contract_value)) -((($delivery*$adv_amount)/$contract_value)+$total_collection-$adv_amount), $options); 
                            echo $this->Number->currency(($delivery+$adv_amount)-($total_collection+$ajust_adv_amount), $options); 
                           
                            ?>
                            </td>
                            <td><?php echo h(isset($value['d']['currency'])?$value['d']['currency']:$value['c']['currency']); ?></td>   
                        </tr>
                        <?php  endforeach;   endif; ?>

            </tbody>
        </table>
    </div>
</div>

