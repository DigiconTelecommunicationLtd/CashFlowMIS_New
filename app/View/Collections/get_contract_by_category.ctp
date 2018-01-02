<option value=""></option>
<?php foreach ($contracts as $key => $value): ?> 
<option value="<?php echo $value['c']['id']; ?>"><?php echo $value['c']['contract_no']; ?></option>
 <?php endforeach; ?>