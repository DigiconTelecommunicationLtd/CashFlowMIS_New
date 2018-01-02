<div class="x_panel">
    <div class="x_content">        
        <?php echo $this->Form->create('Report', array('controller' =>'reports', 'action' => 'planned_target_report', 'class' => 'form-horizontal form-label-left')); ?>
        <div class="form-group">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>Date From</label>
                <?php echo $this->Form->input("date_from", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control single_cal3", 'required' => false, 'aria-describedby' => "inputSuccess2Status3", 'id' => 'date_from', 'required' => true)) ?>                
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label>Date To</label>
                <?php echo $this->Form->input("date_to", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control single_cal3", 'required' => false, 'aria-describedby' => "inputSuccess2Status3", 'id' => 'date_to', 'required' => true)) ?>                
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
        <table id="datatable-buttons"  class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Company/Unit</th>
                    <th>Carry Forward</th>
                    <th>Planned Target</th>
                    <th>Total Target</th>
                    <th>Planned Collection</th>
                    <th>Balance</th>	
                    <th>Currency</th>	
                </tr>
            </thead>
            <tbody>
                <?php 
              if($data_carry||$data_delivery||$payment_delivery){
                foreach ($units as $key => $unit) {
                        
                    ?>
                  <tr>                      
                        <td><?php echo $unit; ?></td>
                        <td><?php echo $carray=$data_carry[$key]['BDT']; ?></td>
                        <td><?php echo $value=$data_delivery[$key]['BDT']; ?></td>
                        <td><?php echo $total=$value+$carray; ?></td>
                        <td><?php echo $payment_delivery[$key]['BDT']; ?></td>
                        <td><?php echo $total-$payment_delivery[$key]['BDT']; ?></td>
                        <td>BDT</td>                         
                    </tr>
                <tr>                      
                        <td><?php echo $unit; ?></td>
                        <td><?php echo $carray=$data_carry[$key]['USD']; ?></td>
                        <td><?php echo $value=$data_delivery[$key]['USD']; ?></td>
                        <td><?php echo $total=$value+$carray; ?></td>
                        <td><?php echo $payment_delivery[$key]['USD']; ?></td>
                        <td><?php echo $total-$payment_delivery[$key]['USD']; ?></td>
                        <td>USD</td>                         
                    </tr>   
                <?php
                }
              }
                ?>               
            </tbody>
             
        </table>
    </div>
</div>
 