<div class="x_panel">      
    <div class="x_title">
        <h2><strong>PO. PRODUCT EDIT:</strong></h2>         
        <ul class="nav navbar-right panel_toolbox">                      
            <li><a href="javascript:window.history.go(-1);" class="btn btn-primary"><i class="fa fa-arrow-right"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <?php echo $this->Form->create('ContractProduct', array('action' => 'edit', 'class' => 'form-horizontal form-label-left', 'id' => 'ContractProduct')); ?>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product/Category Name <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->hidden("id");?>                
                <?php echo $this->Form->input("product_category_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control", 'required' => 'required', 'tabindex' => -1, 'empty' => '', 'id' => 'product_category_id')) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product Name<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->data['Product']['name'];?>
                <?php echo $this->Form->input("product_id", array("type" => "hidden", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12 form-control", 'required' => 'required','id' => 'product_id','readOnly'=>true)) ?>
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
                <?php echo $this->Form->input("uom", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control", 'required' => 'required','id'=>'uomID')) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Unit Price<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("unit_price", array("type" => "number", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12", 'required' => 'required','id'=>'unit_price','step'=>'any')) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Currency <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("currency", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12", 'required' => 'required', 'tabindex' => -1,'readOnly'=>true)) ?>
            </div>
        </div>
        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Unit Weight(KG)
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("unit_weight", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12",'id'=>'unit_weightID','required'=>true)) ?>
            </div>
        </div>         
        <div class="ln_solid"></div>
        <div class="form-group">
            <div class="col-md-6 col-md-offset-3"> 
                <?php echo $this->Form->hidden("contract_id", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12 numeric_number", 'required' => 'required')) ?>
                
                <?php echo $this->Form->submit('Edit Product', array('class' => 'btn btn-success disabled_btn', 'id' => 'ContractProductSubmit')); ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>       
    </div>
</div>

<script>
    document.getElementById("product_category_id").disabled=true;
    //document.getElementById("product_id").disabled=true;
</script>