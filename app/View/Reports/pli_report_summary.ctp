<div class="x_panel">
    <div class="x_title">
        <strong>PLI Report Summary Report:</strong> 
    </div>
    <div class="x_content">        
        <?php echo $this->Form->create('Report', array('controller' =>'reports', 'action' =>'pli_report_summary/ ', 'class' => 'form-horizontal form-label-left', 'id' => 'Report')); ?>
        <div class="form-group">           
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>Date From</label>
                <?php echo $this->Form->input("date_from", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control single_cal3", 'required' => false, 'aria-describedby' => "inputSuccess2Status3", 'id' => 'date_from', 'required' => false)) ?>                
             </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>Date To</label>
                <?php echo $this->Form->input("date_to", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control single_cal3", 'required' => false, 'aria-describedby' => "inputSuccess2Status3", 'id' => 'date_to', 'required' => false)) ?>                
            </div>           
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>Contract/PO.No:</label>
                <?php echo $this->Form->input("contract_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'empty' => '', 'required' => false,'id'=>'contract_id')) ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                <label>Lot NO:</label>
                <?php echo $this->Form->input("lot_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control col-md-7 col-xs-12", 'tabindex' => -1, 'id' => 'lot_id', 'required' => false,'default'=>($lot_id)?$lot_id:'','options'=>$losts)) ?>
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
                    <th>S/N</th>
                    <th>PO.NO</th>
                    <th>Lot.NO</th>
                    <th>Unit</th>
                    <th>Client</th>
                    <th>Category</th>
                    <th>Product</th>
                    <th>PLI QTY</th>                    
                    <th>UOM</th>
                    <th>Unit Weight</th>
                    <th>Total Weight</th>
                    <th>Weight(UOM)</th>
                    <th>PLI VALUE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                 $sl=1;
                 $total_qty=0;
                 $uom='';
                 $total_weight=0;
                 $unit_weight_uom='';
                 $pli_value=0;
                foreach ($results as $result): ?>
                    <tr>
                        <td><?php echo $sl++; ?></td>
                        <td><?php echo h($result['Contract']['contract_no']); ?></td>
                        <td><?php echo h($result['Delivery']['lot_id']); ?></td>
                        <td><?php echo h($result['Unit']['name']); ?></td>
                        <td><?php echo h($result['Client']['name']); ?></td>
                        <td><?php echo h($result['ProductCategory']['name']); ?></td>
                        <td><?php echo h($result['Product']['name']); ?></td>
                        <td><?php echo h($result[0]['pli_qty']);$total_qty+=$result[0]['pli_qty']; ?></td>                        
                        <td><?php echo h($result['Delivery']['uom']);$uom=$result['Delivery']['uom']; ?></td>
                        <td><?php echo ($result['Delivery']['unit_weight']); ?></td>
                        <td><?php echo is_numeric($result['Delivery']['unit_weight'])?$result['Delivery']['unit_weight']*$result[0]['pli_qty']:'N/A';$total_weight+=is_numeric($result['Delivery']['unit_weight'])?$result['Delivery']['unit_weight']*$result[0]['pli_qty']:0; ?></td>
                        <td><?php echo ($result['Delivery']['unit_weight_uom']);$unit_weight_uom=$result['Delivery']['unit_weight_uom']; ?></td>
                       <td><?php echo h($result[0]['pli_value']);$pli_value+=$result[0]['pli_value']; ?></td>
                    </tr>
                <?php endforeach; ?>
                    <tr>
                        <td>Total:</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td> 
                        <td><?php echo $total_qty;?></td>                       
                        <td><?php echo $uom;?></td>
                        <td>&nbsp;</td>
                        <td><?php echo $total_weight;?></td>
                        <td><?php echo $unit_weight_uom;?></td>
                        <td><?php echo $pli_value;?></td>
                    </tr>
            </tbody>
        </table>
    </div>
</div>
<?php
$this->Js->get('#contract_id')->event('change', $this->Js->request(array(
            'controller' => 'lots',
            'action' => 'getLotByContract',
            'model' => 'Report'
                ), array(
            'update' => '#lot_id',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'before' => "$('#loading').fadeIn();",
            'complete' => "$('#loading').fadeOut()",        
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);
?>