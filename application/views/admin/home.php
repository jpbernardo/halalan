<?php echo display_messages('', $this->session->flashdata('messages')); ?>
<div class="content_left">
	<h2><?php echo e('admin_home_left_label'); ?></h2>
	<div class="notes">
		<h2><?php echo e('admin_home_manage_question'); ?></h2>
		<ul>
			<li><?php echo anchor('admin/elections', e('admin_home_manage_elections')); ?></li>
			<li><?php echo anchor('admin/positions', e('admin_home_manage_positions')); ?></li>
			<li><?php echo anchor('admin/parties', e('admin_home_manage_parties')); ?></li>
			<li><?php echo anchor('admin/candidates', e('admin_home_manage_candidates')); ?></li>
			<li><?php echo anchor('admin/blocks', e('admin_home_manage_blocks')); ?></li>
			<li><?php echo anchor('admin/voters', e('admin_home_manage_voters')); ?></li>
            <?php $uadmin = $this->session->userdata('admin');
				$u = $uadmin['electionid'];
			?>
			<?php if($u == 1): ?>
				<li><?php echo anchor('admin/regenerate', 'Manage College Admins'); ?></li>
                <li><?php echo anchor('admin/logs', 'View Password Logs'); ?></li>
			<?php endif; ?>
		</ul>
	</div>
</div>
<div class="content_right">
	<h2><?php echo e('admin_home_right_label'); ?></h2>
	<?php echo form_open('admin/home/do_regenerate'); ?>
	<table cellpadding="0" cellspacing="0" border="0" class="form_table">
		<tr>
			<td align="right">
				<?php echo form_label($settings['password_pin_generation'] == 'email' ? e('admin_home_email') : e('admin_home_username'), 'username'); ?>
			</td>
			<td>
				<?php echo form_input('username', '', 'id="username" maxlength="63" class="text"'); ?>
			</td>
		</tr>
		<?php if ($settings['pin']): ?>
		<tr>
			<td align="right">
			</td>
			<td>
				<?php echo form_checkbox('pin', TRUE, FALSE, 'id="pin"'); ?>
				<?php echo form_label(e('admin_home_pin'), 'pin'); ?>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td align="right">
			</td>
			<td>
				<?php echo form_checkbox('login', TRUE, FALSE, 'id="login"'); ?>
				<?php echo form_label(e('admin_home_login'), 'login'); ?>
			</td>
        </tr>
        <?php $uadmin = $this->session->userdata('admin');
				$u = $uadmin['electionid'];
			?>
			<?php if($u != 1 && $u != 20): ?>
        <tr>
			<td align="right">
				<?php echo "PassKey"; ?>
			</td>
			<td>
				<?php echo form_password('passkey', '', 'id="passkey" maxlength="35" class="text"'); ?>
			</td>
		</tr>
			<?php endif; ?>
		<tr>
			<td colspan="2" align="center">
				<?php echo form_submit('submit', e('admin_home_submit')); ?>
			</td>
		</tr>
	</table>
	<?php echo form_close(); ?>
</div>

<div class="content_right">
	<h2>Reset Halalan Database</h2>
	<?php #echo form_open('admin/home'); #/reset_db ?>
    <form action="<?php echo site_url('admin/home/reset_db'); ?>" name="resetForm"  onclick="return(validate());"> 
	<table cellpadding="0" cellspacing="0" border="0" class="form_table">
		<tr>
			<td colspan="2" align="center">
				<?php $confirm = "Are you sure?"; $js = 'onclick="confirm("$confirm")"'; echo form_submit('submit', e('admin_home_reset'), $js); ?>
			</td>
		</tr>
	</table>
	<?php echo form_close(); ?>
</div>

<div style="clear:both;"></div>


<script type="text/javascript">
function validate()
{
     var r=confirm("Are you sure?")
    if (r==true)
      return true;
    else
      return false;
}
</script>