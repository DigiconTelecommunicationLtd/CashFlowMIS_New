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
                    <th>Advance Amount</th>
                    <th>Progressive Amount</th>
                    <th>Retention Amount</th>
                    <th>AIT</th>
                    <th>VAT</th>
                    <th>L.D</th>
                    <th>Other Deduction</th>
                    <th>Adv. Adjusted</th>                   
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
                        $contract_value=$value['con']['contract_value'];
                        $adv_amount=($value['advc']['adv_amount'])?$value['advc']['adv_amount']:0;
                        $pro_amount=($value['pro']['pro_amount'])?$value['pro']['pro_amount']:0;
                        $reten_amount=($value['reten']['reten_amount'])?$value['reten']['reten_amount']:0;
                        
                        $ait=($value['avlo']['ait'])?$value['avlo']['ait']:0;
                        $vat=($value['avlo']['vat'])?$value['avlo']['vat']:0;
                        $ld=($value['avlo']['ld'])?$value['avlo']['ld']:0;
                        $other_deduction=($value['avlo']['other_deduction'])?$value['avlo']['other_deduction']:0;
                        $ajust_adv_amount=($value['avlo']['ajust_adv_amount'])?$value['avlo']['ajust_adv_amount']:0;
                         
                        $total_collection=$adv_amount+$pro_amount+$reten_amount+$ait+$vat+$ld+$other_deduction;
                        $total_collection_credit=$pro_amount+$reten_amount+$ait+$vat+$ld+$other_deduction;
                        ?>
                        <tr>
                            <td><?php echo h($value['con']['contract_no']); ?></td>
                            <td><?php echo h($value['u']['name']); ?></td>
                            <td><?php echo h($value['client']['name']); ?></td>
                            <td><?php echo h($value[0]['pc_name']); ?></td>                          
                            <td align="right"><?php echo $this->Number->currency($delivery, $options); ?></td> 
                            <td align="right"><?php echo $this->Number->currency($adv_amount, $options); ?></td>
                            <td align="right"><?php echo $this->Number->currency($pro_amount, $options); ?></td>
                            <td align="right"><?php echo $this->Number->currency($reten_amount, $options); ?></td>
                            <td align="right"><?php echo $this->Number->currency($ait, $options); ?></td>
                            <td align="right"><?php echo $this->Number->currency($vat, $options); ?></td>
                            <td align="right"><?php echo $this->Number->currency($ld, $options); ?></td>
                            <td align="right"><?php echo $this->Number->currency($other_deduction, $options); ?></td>
                            <td align="right"><?php echo $this->Number->currency($ajust_adv_amount, $options); ?></td>
                            <td align="right"><?php echo $this->Number->currency($total_collection, $options); ?></td>
                             
                            <td align="right">
                           <?php                            
                            //echo $this->Number->currency(($delivery+$adv_amount-(($delivery*$adv_amount)/$contract_value)) -((($delivery*$adv_amount)/$contract_value)+$total_collection-$adv_amount), $options); 
                           if($delivery) :
                           echo $this->Number->currency($delivery-($total_collection_credit+(round(($adv_amount*$delivery)/$contract_value,3))), $options); 
                           else:
                                echo $this->Number->currency($adv_amount, $options); 
                           endif;
                           
                            ?>
                            </td>
                            <td><?php echo h(isset($value['con']['currency'])?$value['con']['currency']:$value['con']['currency']); ?></td>   
                        </tr>
                        <?php  endforeach;   endif; ?>

            </tbody>
        </table>
    </div>
</div>

