<?php echo display_messages('', $this->session->flashdata('messages')); ?>
<?php echo form_open('gate/voter_login', 'class="hashPassword"'); ?>
<div class="content_center">
	<h2><?php echo 'HALALAN ' . e('gate_voter_login_label'); ?></h2>
	<table cellpadding="0" cellspacing="0" border="0" class="form_table">        
    <?php 
        if($settings['auth_type'] == 'oauth2')
        {
            if(isset($login_button))
            {
                echo '<div align=center>'.$login_button.'</div>'; 
            }
        }
        else
        {
            ?>
            <tr>
            <td align="right"><?php echo form_label(e('gate_voter_username'), 'username'); ?>:</td>
                <td><?php echo form_input('username', '', 'id="username" maxlength="63" class="text"'); ?></td>
            </tr>
            <tr>
                <td align="right"><?php echo form_label(e('gate_voter_password'), 'password'); ?>:</td>
                <td><?php echo form_password('password', '', 'id="password" maxlength="' . $settings['password_length'] . '" class="text"' . '" onkeypress="capLock(event)"'); ?></td>
                <div id="divCaps" style="visibility:hidden"><center><h3>Caps Lock is on!</h3></center></div>
            </tr>
            <tr>
                <td colspan="2" align="center"><?php echo form_submit('submit', e('gate_voter_login_button')); ?></td>
            </tr>
            <?php
        } 
    ?>
<!--
		<tr>
			<td colspan="2" align="center">
				view:
				<?php echo anchor('gate/results', 'results'); ?> |
				<?php echo anchor('gate/statistics', 'statistics'); ?> |
				<?php echo anchor('gate/ballots', 'ballots'); ?>
			</td>
		</tr>
-->
	</table>
</div>
</form>
