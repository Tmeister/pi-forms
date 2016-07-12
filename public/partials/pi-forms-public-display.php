<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://enriquechavez.co
 * @since      1.0.0
 *
 * @package    Pi_Forms
 * @subpackage Pi_Forms/public/partials
 */
$options = get_option( 'pi_forms_settings' );
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="regal-form">
	<form action="<?php echo $options['click_again_lead_url'] ?>" method="post">
		<p><?php echo $options['active_campaign_form_html_top'] ?></p>
		<p>
			<input type="text" name="fields_fname" placeholder="Name *" required/>
		</p>
		<p>
			<input type="text" name="fields_email" placeholder="Email *" required/>
		</p>
		<p>
			<input type="text" name="fields_phone" placeholder="Phone *" id="fields_phone2" required/>
		</p>
		<p>
			<input type="submit" class="button-text" value="<?php echo $options['active_campaign_form_html_button_label'] ?>"/>
		</p>
		<p><?php echo $options['active_campaign_form_html_bottom'] ?></p>
	</form>
</div>