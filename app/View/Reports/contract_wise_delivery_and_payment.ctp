<div class="x_panel">
    <div class="x_title">
        <strong>Contract Wise Delivery & Payment:</strong> 
    </div>
    <div class="x_content">        
        <?php echo $this->Form->create('Report', array('controller' => 'reports', 'action' => 'contract_wise_delivery_and_payment/ ', 'class' => 'form-horizontal form-label-left', 'id' => 'Report')); ?>
        <div class="form-group">           

            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>Contract/PO.No:</label>
                <?php echo $this->Form->input("contract_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'required' => false,'id'=>'contract_id')) ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                <label>Unit</label>
                <?php echo $this->Form->input("unit_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'unit_id', 'required' => false)) ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                <label>Client</label>
                <?php echo $this->Form->input("client_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'id' => 'client_id', 'required' => false)) ?> 
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
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
                    <th>PO.NO</th>                    
                    <th>Unit</th>
                    <th>Client</th>
                    <th>Contract Value</th>                    
                    <th>Delivery Value</th>                    
                    <th>Amount Received</th>
                    <th>AIT</th>
                    <th>VAT</th>	     
                    <th>LD</th>
                    <th>OTHER DEDUCTION</th>
                    <th>ADV. ADJUSTMENT</th>
                    <th>TOTAL COLLECTION</th>
                    <th>BALANCE</th>
                    <th>CURRENCY</th>
                </tr>
            </thead>
            <tbody>
                <?php
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
                
                foreach ($results as $result): $total_collection=0; ?>
                    <tr>

                        <td><?php echo h($result['c']['contract_no']); ?></td>                         
                        <td><?php echo h($result[0]['unit']); ?></td>
                        <td><?php echo h($result[0]['client']); ?></td>
                        <td><?php echo h($this->Number->currency($result[0]['contract_value'], $options)); ?></td>                        						
                        <td><?php echo h($this->Number->currency($result[0]['delivery_value'], $options)); ?></td>                        
                        <td><?php echo h($this->Number->currency($result[0]['amount_received'], $options)); ?></td>						
                        <td><?php echo h($this->Number->currency($result[0]['ait'], $options)); ?></td>
                        <td><?php echo h($this->Number->currency($result[0]['vat'], $options)); ?></td>
                        <td><?php echo h($this->Number->currency($result[0]['ld'], $options)); ?></td>
                        <td><?php echo h($this->Number->currency($result[0]['other_deduction'], $options)); ?></td>
                        <td><?php echo h($this->Number->currency($result[0]['ajust_adv_amount'], $options)); ?></td>
                        <td><?php echo h($this->Number->currency($result[0]['total_collection'], $options)); ?></td>
                        <td><?php echo h($this->Number->currency($result[0]['delivery_value']-$result[0]['total_collection'], $options)); ?></td>                        
                        <td><?php echo h($result['c']['currency']); ?></td>

                    </tr>
                <?php endforeach; ?>                    
            </tbody>             
        </table>
    </div>
</div> 
