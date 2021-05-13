<?php echo display_messages('', $this->session->flashdata('messages')); ?>
<div class="content_left">
	<h2><?php echo "Password Logs"; ?></h2>
</div>
<div class="content_right">
	<p class="align_right"><?php #echo anchor('admin/elections/add', e('admin_elections_add')); ?></p>
</div>
<div class="clear"></div>
<table cellpadding="0" cellspacing="0" class="table">
	<tr>
		<th scope="col" class="w5">Admin ID</th>
		<th scope="col" class="w5">Voter ID</th>
        <th scope="col" class="w5">Block ID</th>
        <th scope="col">Description</th>
		<th scope="col">IP Address</th>
		<th scope="col">Timestamp</th>
	</tr>
	<?php $i = 0; ?>
	<?php foreach($logs as $log): ?>
		<tr class="<?php echo ($i % 2 == 0) ? 'odd' : 'even'  ?>">
		<td align="center">
			<?php echo $log['adminid']; ?>
		</td>
		<td align="center">
			<?php echo $log['voterid']; ?>
		</td>
		<td align="center">
			<?php echo $log['blockid']; ?>
		</td>
        <td align="left">
			<?php echo $log['description']; ?>
		</td>
        <td align="center">
			<?php echo $log['ipaddress']; ?>
		</td>
        <td align="center">
			<?php echo $log['timestamp']; ?>
		</td>
	</tr>
	<?php $i = $i + 1; ?>
	<?php endforeach; ?>
</table>
<div class="paging"><?php echo form_open('admin/logs/download'); echo form_submit('submit', 'Download All'); echo form_close(); ?></div>

