<div class="orderItems form">
<?php echo $this->Form->create('OrderItem'); ?>
	<fieldset>
		<legend><?php echo __('Edit Order Item'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('order_id');
		echo $this->Form->input('invoice_no');
		echo $this->Form->input('product_name');
		echo $this->Form->input('product_id');
		echo $this->Form->input('unit_price');
		echo $this->Form->input('discount_percent');
		echo $this->Form->input('discount_amount');
		echo $this->Form->input('net_amount');
		echo $this->Form->input('currency');
		echo $this->Form->input('client_id');
		echo $this->Form->input('client_name');
		echo $this->Form->input('added_by');
		echo $this->Form->input('added_date');
		echo $this->Form->input('status');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('OrderItem.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('OrderItem.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Order Items'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clients'), array('controller' => 'clients', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Client'), array('controller' => 'clients', 'action' => 'add')); ?> </li>
	</ul>
</div>
