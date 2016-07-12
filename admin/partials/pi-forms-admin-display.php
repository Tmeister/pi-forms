<?php
/**
 * Providers Setting Builders
 */
?>

<div class="wrap">
	<h2 class="nav-tab-wrapper">
		<?php foreach ($this->providers as $tab => $name): ?>
			<?php $class = ( $tab == $current_tab ) ? ' nav-tab-active' : ''; ?>
			<a class='nav-tab<?php echo $class ?>' href='?page=forms-builder&amp;tab=<?php echo $tab ?>'><?php echo $name ?></a>
		<?php endforeach ?>
	</h2>
	<div id="tab_container">
		<form method="post" action="options.php">
			<table class="form-table">
				<?php
				settings_fields( 'pi_forms_settings' );
				do_settings_fields( 'pi_forms_settings_' . $current_tab, 'pi_forms_settings_' . $current_tab );
				?>
			</table>
			<?php submit_button(); ?>
		</form>
	</div><!-- #tab_container-->
	<?php if( $this->ac_credentials_error ): ?>
		<div class="error">Error: Invalid Credentials, please try again.</div>
	<?php endif; ?>
</div><!-- .wrap -->