<div class="x_panel">
    <div class="x_content">       
        <?php echo $this->Form->create('ContractProduct', array('action' => 'add_contract_product/', 'class' => 'form-horizontal form-label-left', 'id' => 'ContractProduct')); ?>

        <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product/Category Name <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php echo $this->Form->input("product_category_id", array("type" => "select", 'label' => false, 'div' => false, 'class' => "select2_single form-control", 'required' => 'required', 'tabindex' => -1, 'empty' => '', 'id' => 'product_category_id','data-placeholder'=>'Choose Product Category','options'=>$product_categories)) ?>
            </div>
        </div>  
        <div class="form-group">
            <div class="col-md-6 col-md-offset-3"> 
                <?php echo $this->Form->submit('Search Product', array('class' => 'btn btn-success', 'id' => 'SearchProduct')); ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>    
    <?php if(isset($products)): ?>
    <div class="x_content">
        <table width="100%">
            <tr>               
                <th>Product ID:</th>
                <th>Product Name:</th>
                <th>Quantity:</th>
                <th>UOM</th>
                <th>Unit Weight</th>
                <th>Unit Weight UOM</th>
                <th>Unit Weight Price</th>
                 <th>Action</th>
            </tr>
            <?php foreach($products as $value):?>
            <tr id="Row_<?php echo $value['Product']['id'];?>">                
                <td><?php echo $value['Product']['id'];?></td>
                <td><?php echo $value['Product']['name'];?></td>
                <td><?php echo $this->Form->input("quantity", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12 numeric_number", 'required' => 'required','id'=>'quantity','step'=>'any')) ?></td>
                <td><?php echo $this->Form->input("quantity", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12 numeric_number", 'required' => 'required','id'=>'quantity','step'=>'any')) ?></td>
                <td><?php echo $this->Form->input("quantity", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12 numeric_number", 'required' => 'required','id'=>'quantity','step'=>'any')) ?></td>
                <td><?php echo $this->Form->input("quantity", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12 numeric_number", 'required' => 'required','id'=>'quantity','step'=>'any')) ?></td>
                <td><?php echo $this->Form->input("quantity", array("type" => "text", 'label' => false, 'div' => false, 'class' => "form-control col-md-7 col-xs-12 numeric_number", 'required' => 'required','id'=>'quantity','step'=>'any')) ?></td>
                
                <td><button onclick="delete_row(<?php echo $value['Product']['id'];?>)">Remove(-)</button></td>
            </tr>
            <?php endforeach;?>
        </table>
    </div>
    
    <?php endif;?>
</div>
<style>
table, td, th {
    border: 1px solid black;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th {
    height: 50px;
}
</style>