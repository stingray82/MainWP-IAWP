<?php
defined( 'ABSPATH' ) || exit;
?>
<?php add_filter('mainwp_pro_reports_custom_tokens', 'mycustom_generate_analytics_tokens', 10, 3); ?>
<!DOCTYPE html>
<div style="font-size: 0px;">
<table style="width: 600px; margin: 0 auto; padding: 0; border-spacing: 0; border-collapse: collapse; color: #41413c; font-size: 16px;">
<tbody>
<tr>
<td>
	<?php 
	if ( $email_message ) {
		$email_message = stripslashes( $email_message );
		echo wp_kses_post( wpautop( wptexturize( $email_message ) ) );
	}
?>
</td>
</tr>
<tr>
	<td style="background: #1c1d1b; margin-top: 0; padding: 0; height: 180px; text-align: center; border-bottom: 5px solid #7fb100;"><img class="aligncenter" style="display: block; border: 0; line-height: 1; margin: 0 auto; font-size: 16px;" src="https://thanet.digital/wp-content/uploads/Thanet-Digital-Header_drk.png" width="300" alt="Logo" /></td>
</tr>
<tr>
<td style="padding: 2em; background-color: #E7EEF6;">
<p style="text-align: center; font-size: 2em; color: #2D3B44; font-weight: 200; padding: 0px; margin: 0px;"><strong>[client.site.name]</strong></p>
<p style="text-align: center; font-size: 1.5em; font-weight: 200; padding: 0px; margin: 0px;">[report.daterange]</p>
</td>
</tr>
<tr>
<td style="padding: 2em; background-color: #E7EEF6; font-size: 0;">
<table style="width: 600px; margin: 0 auto; padding: 0; border-spacing: 0; border-collapse: collapse; color: #3a4c58; font-size: 16px;">
<tbody>
<tr>
<td style="text-align: center; margin: 0; padding: 0 0 0;" colspan="6"><span style="font-size: 28px; font-weight: bold;">Performance</span><br/><br/></td>
</tr>
<tr>
<td style="width: 50%;" colspan="3">
<p style="text-align: center; margin: 0;"><span style="display: block; width: 100px; height: 100px; border-radius: 50%; background: #3a4c58; margin: 0 auto; color: #F1F6FA; font-size: 24px; font-weight: bold; line-height: 4;"><strong>[aum.uptime30]</strong></span>
<br/><span style="font-size: 24px; line-height: .5;">Uptime</span></p>
</td>
<td style="width: 50%;" colspan="3">
<p style="text-align: center; margin: 0;"><span style="display: block; width: 100px; height: 100px; border-radius: 50%; background: #3a4c58; margin: 0 auto; color: #F1F6FA; font-size: 24px; font-weight: bold; line-height: 4;"><strong>[ga.pageviews]</strong></span>
<br/><span style="font-size: 24px; line-height: .5;">Visits <small>(page views)</small></span></p>
</td>
</tr>
<tr>
<td style="text-align: center; margin: 0; padding: 2em 0 0;" colspan="6"><span style="font-size: 28px; font-weight: bold;">Updates</span><br/><br/></td>
</tr>
<tr>
<td style="width: 33%;" colspan="2">
<p style="text-align: center; margin: 0;"><span style="display: block; width: 100px; height: 100px; border-radius: 50%; background: #3a4c58; margin: 0 auto; color: #F1F6FA; font-size: 48px; font-weight: bold; line-height: 2;"><strong>[wordpress.updated.count]</strong></span>
<br/><span style="font-size: 24px; line-height: .5;">WordPress</span></p>
</td>
<td style="width: 33%;" colspan="2">
<p style="text-align: center; margin: 0;"><span style="display: block; width: 100px; height: 100px; border-radius: 50%; background: #3a4c58; margin: 0 auto; color: #F1F6FA; font-size: 48px; font-weight: bold; line-height: 2;"><strong>[theme.updated.count]</strong></span>
<br/><span style="font-size: 24px; line-height: .5;">Themes</span></p>
</td>
<td style="width: 33%; margin: 0; padding: 0;" colspan="2">
<p style="text-align: center; margin: 0;"><span style="display: block; width: 100px; height: 100px; border-radius: 50%; background: #3a4c58; margin: 0 auto; color: #F1F6FA; font-size: 48px; font-weight: bold; line-height: 2;"><strong>[plugin.updated.count]</strong></span>
<br/><span style="font-size: 24px; line-height: .5;">Plugins</span></p>
</td>
</tr>
<tr>
<td style="text-align: center; margin: 0; padding: 2em 0 0;" colspan="6"><span style="font-size: 28px; font-weight: bold;">Security</span><br/><br/></td>
</tr>
<tr>
<td style="width: 50%; margin: 0; padding: 0;" colspan="3">
<p style="text-align: center; margin: 0;"><span style="display: block; width: 100px; height: 100px; border-radius: 50%; background: #3a4c58; margin: 0 auto; color: #F1F6FA; font-size: 48px; font-weight: bold; line-height: 2;"><strong>[ithemes.scan.count]</strong></span>
<br/><span style="font-size: 24px; line-height: .5;">Scans</span></p>
</td>
<td style="width: 50%;" colspan="3">
<p style="text-align: center; margin: 0;"><span style="display: block; width: 100px; height: 100px; border-radius: 50%; background: #3a4c58; margin: 0 auto; color: #F1F6FA; font-size: 48px; font-weight: bold; line-height: 2;"><strong>[backup.created.count]</strong></span>
<br/><span style="font-size: 24px; line-height: .5;">Backups</span></p>
</td>
</tr>
<tr>
<td style="text-align: center; margin: 0; padding: 2em 0 0;" colspan="6"></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="padding: 1.5em; text-align: center; font-size: 80%; background: #3a4c58; color: #FAAC2E;"><a style="color: #FAAC2E;" href="https://thanet.digital">Thanet Digital Limited</a> • <a style="color: #FAAC2E;" href="mailto:hello@thanet.digital">hello@thanet.digital</a></td>
</tr>
</tbody>
</table>
</div>
<?php
