<option value=""></option>
<?php foreach ($contracts as $key => $value): 
     $balance=$value[0]['delivery_qty']-$value[0]['pli_qty'];
    if($balance>0):
    ?>
<option value="<?php echo $value[0]['id']; ?>"><?php echo $value[0]['contract_no']; ?>(<?php echo $balance;?>)</option>
<?php else:?>
<option value="<?php echo $value[0]['id']; ?>"><?php echo $value[0]['contract_no']; ?>(<?php echo $balance;?>)</option>
<?php endif;?>
<?php endforeach; ?>