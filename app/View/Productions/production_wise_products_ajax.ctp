<div class="x_panel"> 
    <?php if ($this->Session->check('Message.flash')): ?> 
    <div class="x_title">       
            <div role="alert" class="alert alert-success alert-dismissible fade in">
                <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span>
                </button>
                <strong><?php echo $this->Session->flash(); ?></strong>
            </div>        
        <div class="clearfix"></div>
    </div>
    <?php endif; ?>
    <div class="x_content">
        <?php if ($productions): ?>
            <table  class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Contract/PO.No</th>
                        <th>Lot No.</th>
                        <th>Product</th>                    
                        <th>Quantity</th>
                        <th>Unit Weight</th>
                        <th>Weight</th>
                         <th>Planned Completion Date</th>
                        <th>Actual Completion Date</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productions as $production): ?>
                        <tr>

                            <td> <?php echo $production['Contract']['contract_no']; ?> </td>
                            <td> <?php echo $production['Production']['lot_id']; ?> </td>
                            <td> <?php echo $production['Product']['name']; ?> </td>
                            <td><?php echo h($production['Production']['quantity']); ?>&nbsp;<?php echo h($production['Production']['uom']); ?></td>
                            <td><?php echo h($production['Production']['unit_weight']); ?>&nbsp;</td>
                            <td><?php echo ($production['Production']['unit_weight'] != 'N/A') ? h($production['Production']['unit_weight'] * $production['Production']['quantity']) . ' ' . $production['Production']['uom'] : 'N/A'; ?>&nbsp;</td>
                            <td> <?php echo $production['Production']['planned_completion_date']; ?> </td> 
                            <td> <?php echo $production['Production']['actual_completion_date']; ?> </td> 
                            <td> <?php echo $production['Production']['remarks']; ?> </td> 
                            <td class="actions">
                                <button onclick="delete_production_product('<?php echo $production['Production']['id']; ?>','<?php echo $production['Production']['contract_id']; ?>','<?php echo $production['Production']['lot_id']; ?>');"><i class="fa fa-remove"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div> 
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').fadeOut('fast');
        }, 1500); // <-- time in milliseconds
    });
</script>
