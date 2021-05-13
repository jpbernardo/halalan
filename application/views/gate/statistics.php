<div class="content_center">
<h2><?php echo 'HALALAN ' . e('gate_statistics_label'); ?></h2>
</div>

<?php if (empty($all_elections)): ?>
	<div class="reminder"><?php echo e('gate_results_no_elections'); ?></div>
<?php else: ?>

	<?php if (empty($elections)): ?>
	<div class="reminder"><?php echo e('gate_results_reminder'); ?></div>
	<?php endif; ?>

	<div class="paging">
	<a href="#" class="toggleOptions">[<?php echo (empty($elections)) ? 'show' : 'hide'; ?> options]</a>
	<?php echo form_open('gate/statistics'); ?>
	<?php for ($s = 0; $s < 2; $s++): ?>
	<?php
		$limit = count($all_elections);
		if ($s == 0)
		{
			$side = 'left';
			$i = 0;
			$limit /= 2;
		}
		else
		{
			$side = 'right';
		}
	?>
	<div class="content_<?php echo $side; ?>">
		<table cellpadding="0" cellspacing="0" border="0" class="form_table">
		<?php for (; $i < $limit; $i++): ?>
			<?php $checked = in_array($all_elections[$i]['id'], $selected); ?>
			<tr>
				<td class="w5">
					<?php
						echo form_checkbox(
						'elections[]',
						$all_elections[$i]['id'],
						$checked,
						'id="cb_' . $all_elections[$i]['id'] . '"'
						);
					?>
				</td>
				<td>
					<?php echo form_label($all_elections[$i]['election'], 'cb_' . $all_elections[$i]['id']); ?>
				</td>
			</tr>
		<?php endfor; ?>
		</table>
	</div>
	<?php endfor; ?>
	<div class="clear"></div>
	<div class="notes">
		<?php echo form_submit('submit', e('gate_results_submit_button')); ?>
		<br /><br />
		<a href="#" class="toggleAllElections">select all</a> | <a href="#" class="toggleAllElections">deselect all</a>
	</div>
	</form>
	</div>

	<?php foreach ($elections as $election): ?>
		<div class="election"><?php echo $election['election']; ?></div>

		<div class="content_left notes">
			<table cellpadding="0" cellspacing="0" border="0" class="form_table delegateEvents">
				<tr>
					<td>Registered Voters</td>
					<td><?php echo $election['voter_count']; ?></td>
					<td><?php echo img(array('src' => 'public/images/info.png', 'alt' => 'info', 'class' => 'pointer', 'title' => 'More info')); ?></td>
				</tr>
				<tr>
					<td colspan="3">
						<div style="display:none;" class="details">
							<ul>
								<?php foreach ($election['voter_breakdown'] as $b): ?>
								<li><?php echo $b['block'] . ' - ' . $b['count']; ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</td>
				</tr>
				<tr>
					<td>Voters who voted</td>
					<td><?php echo $election['voted_count']; ?></td>
					<td><?php echo img(array('src' => 'public/images/info.png', 'alt' => 'info', 'class' => 'pointer', 'title' => 'More info')); ?></td>
				</tr>
				<tr>
					<td colspan="3">
						<div style="display:none;" class="details">
							<ul>
								<?php foreach ($election['voted_breakdown'] as $b): ?>
								<li><?php echo $b['block'] . ' - ' . $b['count']; ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</td>
				</tr>
				<tr>
					<td>Voter turnout</td>
					<td>
						<?php
							if ($election['voter_count'] > 0)
							{
								printf("%.2f", 100 * $election['voted_count'] / $election['voter_count']);
							}
							else
							{
								echo 0;
							}
						?>%
					</td>
					<td><?php echo img(array('src' => 'public/images/info.png', 'alt' => 'info', 'class' => 'pointer', 'title' => 'More info')); ?></td>
				</tr>
				<tr>
					<td colspan="3">
						<div style="display:none;" class="details">
							<ul>
								<?php for ($i = 0; $i < count($election['voter_breakdown']); $i++): ?>
								<li>
								<?php
									echo $election['voter_breakdown'][$i]['block'] . ' - ';
									if ($election['voter_breakdown'][$i]['count'] > 0)
									{
										printf("%.2f", 100 * $election['voted_breakdown'][$i]['count'] / $election['voter_breakdown'][$i]['count']);
									}
									else
									{
										echo 0;
									}
								?>%
								</li>
								<?php endfor; ?>
							</ul>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<?php  ?>
		<div class="content_right notes">
			<table cellpadding="0" cellspacing="0" border="0" class="form_table delegateEvents">
				<tr>
					<td>Voters who voted...</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="3"></td>
				</tr>
				<tr>
					<td>...in &lt; 1 minute</td>
					<td><?php echo $election['lt_one']; ?></td>
					<td><?php echo img(array('src' => 'public/images/info.png', 'alt' => 'info', 'class' => 'pointer', 'title' => 'More info')); ?></td>
				</tr>
				<tr>
					<td colspan="3">
						<div style="display:none;" class="details">
							<ul>
								<?php foreach ($election['lt_one_breakdown'] as $b): ?>
								<li><?php echo $b['block'] . ' - ' . $b['count']; ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</td>
				</tr>
				<tr>
					<td>...in &lt; 2 minutes but &gt;= 1 minute</td>
					<td><?php echo $election['lt_two_gte_one']; ?></td>
					<td><?php echo img(array('src' => 'public/images/info.png', 'alt' => 'info', 'class' => 'pointer', 'title' => 'More info')); ?></td>
				</tr>
				<tr>
					<td colspan="3">
						<div style="display:none;" class="details">
							<ul>
								<?php foreach ($election['lt_two_gte_one_breakdown'] as $b): ?>
								<li><?php echo $b['block'] . ' - ' . $b['count']; ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</td>
				</tr>
				<tr>
					<td>...in &lt; 3 minutes but &gt;= 2 minutes</td>
					<td><?php echo $election['lt_three_gte_two']; ?></td>
					<td><?php echo img(array('src' => 'public/images/info.png', 'alt' => 'info', 'class' => 'pointer', 'title' => 'More info')); ?></td>
				</tr>
				<tr>
					<td colspan="3">
						<div style="display:none;" class="details">
							<ul>
								<?php foreach ($election['lt_three_gte_two_breakdown'] as $b): ?>
								<li><?php echo $b['block'] . ' - ' . $b['count']; ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</td>
				</tr>
				<tr>
					<td>...in &gt; 3 minutes</td>
					<td><?php echo $election['gt_three']; ?></td>
					<td><?php echo img(array('src' => 'public/images/info.png', 'alt' => 'info', 'class' => 'pointer', 'title' => 'More info')); ?></td>
				</tr>
				<tr>
					<td colspan="3">
						<div style="display:none;" class="details">
							<ul>
								<?php foreach ($election['gt_three_breakdown'] as $b): ?>
								<li><?php echo $b['block'] . ' - ' . $b['count']; ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<?php  ?>
		<div class="clear"></div>
	<?php endforeach; ?>

<?php endif; ?>
