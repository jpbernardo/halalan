<div class="election"><?php echo $election['election']; ?></div>
<?php foreach ($usc_positions as $usc_position): ?>
	<div class="divider"> <!-- used only in JS to determine separation of positions -->
	<div class="position"><?php echo $usc_position['position'] . ' (' . $usc_position['maximum'] . ')'; ?></div>
	<?php
		// compute the column width first
		$count = count($usc_position['parties']);
		$space = 3;
		$borders = 10;
		$width = 770;
		$column = ($width - ($space * ($count - 1)) - ($borders * $count)) / $count;
	?>
	<?php foreach ($usc_position['parties'] as $key => $usc_party): ?>
		<div class="notes" style="float: left; width: <?php echo $column; ?>px;">
		<h2><?php echo $usc_party['party']; ?></h2>
		<!-- start -->
		<table cellpadding="0" cellspacing="0" border="0" class="form_table delegateEvents2">
			<?php if (empty($usc_party['candidates'])): ?>
				<tr>
					<td><em><?php echo e('voter_vote_no_candidates'); ?></em></td>
				</tr>
			<?php else: ?>
				<?php foreach ($usc_party['candidates'] as $usc_candidate): ?>
					<?php
						// used to populate the form
						$name = $usc_candidate['first_name'];
						if ( ! empty($usc_candidate['alias']))
						{
							$name .= ' "' . $usc_candidate['alias'] . '"';
						}
						$name .= ' ' . $usc_candidate['last_name'];
						$name = quotes_to_entities($name);
					?>
					<?php if ($usc_candidate['voted']): ?>
    				        <tr class="selected">
				        <?php else: ?>
					<tr>
					<?php endif; ?>
						<td class="w5" align="center">
							<?php if ($usc_candidate['voted']): ?>
								<?php echo img(array('src' => 'public/images/ok.png', 'alt' => 'Check')); ?>
							<?php else: ?>
								<?php echo img(array('src' => 'public/images/x.png', 'alt' => 'X')); ?>
							<?php endif; ?>
						</td>
						<td>
							<?php echo $name; ?>
						</td>
						<?php if ($settings['show_candidate_details']): ?>
							<td class="w5">
								<?php echo img(array('src' => 'public/images/info.png', 'alt' => 'info', 'class' => 'pointer', 'title' => 'More info')); ?>
							</td>
						<?php endif; ?>
					</tr>
					<tr class="details">
						<td colspan="3">
						<?php if ($settings['show_candidate_details']): ?>
							<div style="display:none;" class="details">
							<?php if ( ! empty($usc_candidate['picture'])): ?>
								<div style="float:left;padding-right:5px;">
									<?php echo img(array('src' => 'public/uploads/pictures/' . $usc_candidate['picture'], 'alt' => 'picture')); ?>
								</div>
							<?php endif; ?>
							<div style="float:left;">
								Name: <?php echo $name; ?><br />
								Party: <?php echo (isset($usc_party['party']) && ! empty($usc_party['party'])) ? $usc_party['party'] . ( ! empty($usc_party['alias']) ? ' (' . $usc_party['alias'] . ')' : '') : 'none'; ?>
							</div>
							<div class="clear"></div>
							<?php if ( ! empty($usc_candidate['description'])): ?>
								<div><br />
									<?php echo nl2br($usc_candidate['description']); ?>
								</div>
							<?php endif; ?>
							</div>
						<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</table>
		<!-- end -->
		</div>
		<?php if ($key < $count - 1): ?>
		<div style="float: left; width: <?php echo $space; ?>px;">&nbsp;</div>
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="clear"></div>
	<?php if ($usc_position['abstain'] == TRUE): ?>
	<div class="paging">
		<table cellpadding="0" cellspacing="0" border="0" class="delegateEvents2" width="100%">
			<?php if ($usc_position['abstains']): ?>
			<tr class="selected">
			<?php else: ?>
			<tr>
			<?php endif; ?>
				<td>
					<?php if ($usc_position['abstains']): ?>
						<?php echo img(array('src' => 'public/images/ok.png', 'alt' => 'Check')); ?>
					<?php else: ?>
						<?php echo img(array('src' => 'public/images/x.png', 'alt' => 'X')); ?>
					<?php endif; ?>
					ABSTAIN
				</td>
			</tr>
		</table>
	</div>
	<?php endif; ?>
	</div> <!-- end for div class divider -->
<?php endforeach; ?>

<div class="clear"></div>
<div class="paging">
	<input type="hidden" name="election_id" value="<?php echo $election['id']; ?>" id="election_id" />
	<input type="button" class="printVotes" value="<?php echo e('voter_votes_print_button'); ?>" />
	<?php if ($settings['generate_image_trail']): ?>
	<input type="button" class="downloadVotes" value="<?php echo e('voter_votes_download_button'); ?>" />
	<?php endif; ?>
</div>
