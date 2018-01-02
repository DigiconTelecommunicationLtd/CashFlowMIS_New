<option value=""></option>
<?php foreach ($contracts as $key => $value): 
     $balance=$value['lot']['lot_qty']-$value['ins']['ins_qty'];
    if($balance>0):
    ?>
<option value="<?php echo $value['c']['id']; ?>"><?php echo $value['c']['contract_no']; ?>(<?php echo $balance;?>)</option>
<?php else:?>
<option value="<?php echo $value['c']['id']; ?>"><?php echo $value['c']['contract_no']; ?>(<?php echo $balance;?>)</option>
<?php endif;?>
<?php endforeach; ?>