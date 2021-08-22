<?php

if (! defined('ABSPATH')) {
    exit;
}

/**
 * @var \Premmerce\Filter\Admin\Tabs\Base\TabInterface[] $tabs
 */
?>
<div class="wrap">
    <h1><?php _e('Premmerce Product Filter for WooCommerce', 'premmerce-filter') ?></h1>
    <h2 class="nav-tab-wrapper">
		<?php foreach ($tabs as $tab): ?>
			<?php if ($tab->valid()): ?>
				<?php $class = ($tab == $current) ? ' nav-tab-active' : ''; ?>
                <a class='nav-tab<?php echo $class ?>'
                   href='?page=premmerce-filter-admin&tab=<?php echo $tab->getName() ?>'><?php echo $tab->getLabel() ?></a>
			<?php endif; ?>
		<?php endforeach; ?>


        <?php if (!premmerce_pwpf_fs()->can_use_premium_code()) : //if it is not Premium plan.?>
            <a class="nav-tab premmerce-upgrate-to-premium-button"
                href="<?php echo admin_url('admin.php?page=premmerce-filter-admin-pricing'); ?>">
                    <?php _e('Upgrate to Premium', 'premmerce-filter') ?>
            </a>
        <?php endif; ?>
    </h2>

	<?php echo $current->render() ?>

</div>

