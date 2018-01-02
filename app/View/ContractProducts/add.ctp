<div class="x_panel">
    <div class="x_content">
        <fieldset>
            <legend><strong>PO. PRODUCT ADD</strong></legend>
        </fieldset>
        <?php echo $this->Form->create('ContractProduct', array('action' => 'add', 'class' => 'form-horizontal form-label-left', 'id' => 'ContractProduct')); ?>
         <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Contract/PO No:<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->hidden("contract_id", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12", 'required' => 'required', 'tabindex' => -1, 'empty' => '', 'id' =>'contract_id','value'=>$contracts['Contract']['id'])) ?>
                <?php echo $this->Form->input("contract_no", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12", 'required' => 'required', 'tabindex' => -1, 'empty' => '', 'id' =>'contract_id','readOnly'=>true,'value'=>$contracts['Contract']['contract_no'])) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product/Category Name <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("product_category_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control", 'required' => 'required', 'tabindex' => -1, 'empty' => '', 'id' => 'product_category_id','data-placeholder'=>'Choose Product Category')) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product Name<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("product_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control", 'required' => 'required', 'tabindex' => -1, 'empty' => '', 'id' => 'product_id','data-placeholder'=>'Choose Product')) ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div id="loading" style="display: none;">
                    <?php echo $this->Html->image('loading.gif',array('alt'=>'Please Wait ...','height'=>"15",'width'=>"15")); ?>
                    Please Wait ...
                </div>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Quantity<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("quantity", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12 numeric_number", 'required' => 'required','id'=>'quantity','step'=>'any')) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">UOM <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("uom", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control", 'required' => 'required', 'id' => 'uomID','data-placeholder'=>'Choose Product Unit')) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Unit Price<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("unit_price", array("type" => "number", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12 numeric_number", 'required' => 'required','id'=>'unit_price','step'=>'any')) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Currency <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("currency", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7", 'required' => 'required','id'=>'currencyID','value'=>$contracts['Contract']['currency'],'readOnly'=>true)) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Unit Weight(KG)
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("unit_weight", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12",'id'=>'unit_weightID','required'=>true)) ?>
            </div>
        </div>     
        <div class="form-group">
            <div class="col-md-6 col-md-offset-3"> 
                <?php echo $this->Form->submit('Add Product', array('class' => 'btn btn-success', 'id' => 'ContractProductSubmit')); ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        <fieldset>
            <legend><h2><strong>PO. PRODUCTS:</strong></h2></legend>
        </fieldset>
<!--        <div class="alert alert-info" role="alert"><i class=" fa fa-spinner fa-spin"></i> Please wait...</div>-->
    </div>
    <!-- contract added product list -->
    <div class="x_content">
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
        <div id="contractProductUpdate">
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
</div>
<?php
$this->Js->get('#product_category_id')->event('change', $this->Js->request(array(
            'controller' => 'products',
            'action' => 'getByCategory'
                ), array(
            'update' => '#product_id',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'before' => "$('#loading').fadeIn();$('#ContractProductSubmit').attr('disabled','disabled');",
            'complete' => "$('#loading').fadeOut();$('#ContractProductSubmit').removeAttr('disabled');",        
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);
?>
<?php
$data = $this->Js->get('#ContractProduct')->serializeForm(array('isForm' => true, 'inline' => true));
$this->Js->get('#ContractProduct')->event(
        'submit', $this->Js->request(
                array('controller' => 'contract_products', 'action' => 'add'), array(
            'update' => '#contractProductUpdate',
            'data' => $data,
            'async' => true,
            'dataExpression' => true,
            'method' => 'POST',
            'before' => "$('#ContractProductSubmit').attr('disabled','disabled');",
            'complete' => "$('#ContractProductSubmit').removeAttr('disabled');$('#quantity').val('');$('#unit_price').val('');",
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
