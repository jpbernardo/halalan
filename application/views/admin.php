<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Halalan - Administration - <?php echo $title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<?php if (isset($meta) && ! empty($meta)): ?>
	<?php echo $meta; ?>
	<?php endif; ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/stylesheets/admin.css'); ?>" />
	<script type="text/javascript" src="<?php echo base_url('public/javascripts/jquery.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('public/javascripts/jquery.color.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('public/javascripts/jquery.cookie.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('public/javascripts/common.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('public/javascripts/admin.js'); ?>"></script>
</head>
<body>
<div id="wrap">
	<div id="header">
		<div id="header_bg">
			<div id="header_left">
				<?php
					$CI =& get_instance();
					$special = $CI->config->item('halalan');
				?>
				<?php if (isset($special['v']) && $special['v']): ?>
				<?php echo img(array('src' => 'public/images/logo_admin_v.png', 'alt' => 'Halalan Logo')); ?>
				<?php else: ?>
				<?php echo img(array('src' => 'public/images/logo_small.png', 'alt' => 'Halalan Logo')); ?>
				<span>administration</span>
				<?php endif; ?>
<!--
				<h1>
					<?php echo anchor(site_url(), 'Halalan'); ?>
					<span>administration</span>
				</h1>
-->
			</div>
			<div id="header_right">
				<p>LOGGED IN AS <?php echo strtoupper($username); ?> | <?php echo anchor('gate/logout', 'LOGOUT'); ?></p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div id="menu">
		<ul>
			<li><?php echo anchor('admin/home', 'HOME', 'title="Home"'); ?></li>
			<li><?php echo anchor('admin/elections', 'ELECTIONS', 'title="Manage Elections"'); ?></li>
			<li><?php echo anchor('admin/positions', 'POSITIONS', 'title="Manage Positions"'); ?></li>
			<li><?php echo anchor('admin/parties', 'PARTIES', 'title="Manage Parties"'); ?></li>
			<li><?php echo anchor('admin/candidates', 'CANDIDATES', 'title="Manage Candidates"'); ?></li>
			<li><?php echo anchor('admin/blocks', 'BLOCKS', 'title="Manage Blocks"'); ?></li>
			<li><?php echo anchor('admin/voters', 'VOTERS', 'title="Manage Voters"'); ?></li>
            <?php $uadmin = $this->session->userdata('admin');
				$u = $uadmin['electionid'];
			?>
			<?php if($u == 1): ?>
				<li><?php echo anchor('admin/regenerate', 'ADMINS', 'title="Regenerate Election Passwords"'); ?></li>
                <li><?php echo anchor('admin/logs', 'LOGS', 'title="View Password Logs"'); ?></li>
			<?php endif; ?>
		</ul>
	</div>
	<div id="content">
		<?php echo $body; ?>
	</div>
	<div id="footer">
		<div id="footer_bg">
			<div id="footer_left">
				<p>&copy; University of the Philippines Linux Users' Group (UnPLUG)</p>
			</div>
			<div id="footer_right">
				<p>Powered by Halalan <?php echo HALALAN_VERSION; ?></p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
</body>
</html>
