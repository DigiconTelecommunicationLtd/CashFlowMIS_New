<option value=""></option>
<?php foreach ($contracts as $key => $value): 
     $balance=$value['ins']['ins_qty']-$value['d']['delivery_qty'];
    if($balance>0):
    ?>
<option value="<?php echo $value['c']['id']; ?>"><?php echo $value['c']['contract_no']; ?>(<?php echo $balance;?>)</option>
<?php else:?>
<option value="<?php echo $value['c']['id']; ?>"><?php echo $value['c']['contract_no']; ?>(<?php echo $balance;?>)</option>
<?php endif;?>
<?php endforeach; ?>