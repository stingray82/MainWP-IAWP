
# MainWP to Independent Analytics Bridge

**Warning: This is work in Progress and currently requires a custom template per client site!
These notes are currently more for me than anyone else as the project is a working proof of concept; 
use at your own risk, just because it didn't crash mine doesn't mean it wont yours!**

## main-wp-to-independent-analytics-child-plugin
This is installed on child themes to give us an Public API to reference you maybe able to do this direct from IA one day but this was the easiest way for me to implement
This works by checking that IA Free or Pro is installed and if so will run the api points;
The Endpoint is `{$site_url}/wp-content/plugins/main-wp-to-independent-analytics-child-plugin/api.php?from={$from_date}&to={$to_date};` where from date is the date you want them extract and to date is the date up to these will ideally be set by pro reports if I can get that working, there is no settings on the child site just install and forget.

## main-wp-to-independent-analytics-bridge-extension
This is installed on the MainWP Dashboard, This is where you set your date options **Please See Usage Instructions**
This is a proof  of concept and has a lot of modifiers to get it to function but its not slick


## custom-email-body.php
This is the custom template I am using based on this video https://www.youtube.com/watch?v=ytUNgJMu0vg & this Article https://kb.mainwp.com/docs/how-to-create-an-html-only-report-using-the-pro-reports-extension/

The only modification(s) are the custom `[ipwa-visitors]` added and it uses `[ithemes.scan.count]` rather than wordfence to better work with my setup to access the custom [ipwa-visitor] you need the following code in your report template currently;

`<?php add_filter('mainwp_pro_reports_custom_tokens', function($tokensValues, $report, $website) {
    $site_url = "https://yoursite.com; // Replace with the dynamic site URL
    return iwap_generate_Custom_analytics_tokens($tokensValues, $report, $website, $site_url);
}, 10, 3); ?>`

**Please replace site URL with your actual URL this template is for **

*The following is depreciated for now; but is here for reference in case I want to use it later.*
`<?php add_filter('mainwp_pro_reports_custom_tokens', 'iwap_generate_Custom_analytics_tokens', 10, 3); ?>`

## Usage Instructions
1) Add the child site to your child site(s)
2) Add the main extension to your dashboard
3) Add your custom-email-body.php to wp-content/uploads/mainwp/report-email-templates/
Edit logo and other details (You can scrip this step if you have your own and can add it in) you will need to add the function above though - **Remember to set the site URL**
4) Select your dates range from the extentions settings:
![Date Picker Screen](https://i.ibb.co/rGbF9VS/Date-Picker.png)
5) test your template
6) Currently assuming you send out monthly you will need to change the dates manually every month (some additional modification could be added here to manipulate the based on month i.e start to end of the previous month but I want to wait to see if there is a way to hook in that doesn't require all these modifications first.

**Tested by me, a little please do test and report any issues**

### What does it output?
It adds three new custom tokens you can access which are displayed below;
[ipwa-views]
[ipwa-visitors]
[ipwa-sessions]

![Example Output in a report](https://i.ibb.co/x1Gr5Xk/IWAP-bridge.png%29)



