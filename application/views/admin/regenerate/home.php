<?php echo display_messages('', $this->session->flashdata('messages')); ?>
<div class="content_left">
	<h2><?php echo "Regenerate Elections Password"; ?></h2>
</div>
<div class="content_right">
	<p class="align_right"><?php #echo anchor('admin/elections/add', e('admin_elections_add')); ?></p>
</div>
<div class="clear"></div>
<table cellpadding="0" cellspacing="0" class="table">
	<tr>
		<th scope="col" class="w5">Election ID</th>
		<th scope="col">Election/College</th>
		<th scope="col" class="w10">Username</th>
		<th scope="col" class="w20">Admin Password</th>
        <th scope="col" class="w15">CSEB Key</th>
	</tr>
	<?php $i = 0; ?>
	<?php foreach($admins as $admin): ?>
		<tr class="<?php echo ($i % 2 == 0) ? 'odd' : 'even'  ?>">
		<td align="center">
			<?php echo $admin['electionid']; ?>
		</td>
		<td>
			<?php echo $admin['last_name'].' '.$admin['first_name']; ?>
		</td>
		<td align="center">
			<?php echo $admin['username']; ?>
		</td>
		<td align="center">
			<?php echo anchor('admin/regenerate/do_regenerate/'.$admin['username'].'/'.$admin['id'], 'Reset Password'); ?>
		</td>
        <td align="center">
			<?php echo anchor('admin/regenerate/update_key/'.$admin['username'].'/'.$admin['id'], 'UPDate Key'); ?>
		</td>
	</tr>
	<?php $i = $i + 1; ?>
	<?php endforeach; ?>
</table>
<?php #echo anchor('admin/regenerate/all', 'Regenerate All'); ?>
