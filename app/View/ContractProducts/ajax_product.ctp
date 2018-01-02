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
        <?php if ($contractProducts): ?>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>SL.NO:</th>
                            <th>Category</th>
                            <th>Product</th>                    
                            <th>Quantity</th>
                            <th>UOM</th>
                            <th>U.Price</th>
                            <th>T.Amount</th> 
                            <th>Currency</th>
                            <th>U.Weigh</th>
                            <th>T.Weight</th> 
                            <th>U.W.UOM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl=1;
                        $total_value=0;
                        $total_qty=0;
                        $total_weight=0;
                        foreach ($contractProducts as $contractProduct): ?>
                            <tr>
                                <td> <?php echo $sl++; ?> </td>
                                <td> <?php echo $contractProduct['ProductCategory']['name']; ?></td>
                                <td> <?php echo $contractProduct['Product']['name']; ?></td>
                                <td align="right"><?php echo h($contractProduct['ContractProduct']['quantity']);$total_qty+=$contractProduct['ContractProduct']['quantity']; ?></td>
                                <td><?php echo h($contractProduct['ContractProduct']['uom']); ?></td>
                                <td align="right"><?php echo h($contractProduct['ContractProduct']['unit_price']);?></td>
                                <td align="right"><?php echo h($amount=$contractProduct['ContractProduct']['unit_price'] * $contractProduct['ContractProduct']['quantity']); $total_value+=$amount;?></td>
                                <td><?php echo h($contractProduct['ContractProduct']['currency']); ?></td>
                                <td><?php echo h($contractProduct['ContractProduct']['unit_weight']); ?></td>
                                <td align="right"><?php echo $weight=($contractProduct['ContractProduct']['unit_weight']!='N/A'&&$contractProduct['ContractProduct']['unit_weight_uom']!='N/A')? h($contractProduct['ContractProduct']['unit_weight'] * $contractProduct['ContractProduct']['quantity']):'N/A';$total_weight+=$weight; ?></td>
                                <td><?php echo ($contractProduct['ContractProduct']['unit_weight_uom']); ?></td>
                                <!--
                                <td class="actions">
                                    <?php //if(substr($contractProduct['ContractProduct']['added_date'],0,10)==date('Y-m-d')): ?>
                                    <button onclick="delete_product('<?php //echo $contractProduct['ContractProduct']['id']; ?>','<?php //echo $contractProduct['ContractProduct']['contract_id']; ?>');"><i class="fa fa-remove"></i></button>
                                    <?php //endif;?>
                                </td> -->
                            </tr>
                        <?php endforeach; ?>   
                            <tr>
                                <td colspan="3" align="right">Total</td>
                                <td  align="right"><?php echo $total_qty;?></td>
                                <td colspan="2" align="right"></td>
                                <td align="right"><?php echo $total_value;?></td>
                                <td colspan="2" align="right"></td>
                                <td align="right"><?php echo $total_weight;?></td>
                                <td align="right"></td>
                            </tr>
                    </tbody>
                </table>
            <?php endif; ?>
    </div>
</div> 

<?php
$data = $this->Js->get('.delete')->serializeForm(array('isForm' => true, 'inline' => true));
$this->Js->get('.delete')->event(
        'submit', $this->Js->request(
                array('controller' => 'contract_products', 'action' => 'delete'), array(
            'update' => '#contractProductUpdate',
            'data' => $data,
            'async' => true,
            'dataExpression' => true,
            'method' => 'POST',
            'before' => "$('#loading').fadeIn();$('#ContractProductSubmit').attr('disabled','disabled');",
            'complete' => "$('#loading').fadeOut();$('#ContractProductSubmit').removeAttr('disabled');",
                )
        )
);
?>

<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').fadeOut('fast');
        }, 1500); // <-- time in milliseconds
    });
</script>