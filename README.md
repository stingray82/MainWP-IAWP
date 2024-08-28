
# MainWP to Independent Analytics Bridge

## main-wp-to-independent-analytics-child-plugin
This is installed on child themes to give us an Public REST API Endpoint to reference you maybe able to do this direct from IA, I have asked if this is avaliable in the plugin.
This works by checking that IA Free or Pro is installed and if so will run the api points;
The Endpoint is `{$site_url}/wp-json/iawp/v1/analytics/?from={$from_date}&to={$to_date}` where from date is the date you want them extract and to date is the date up to these are now set within pro-reports and hooked in directly so there are no settings on the child site to setup install and the end point is avaliable not just for Main WP but for any software you need with access.

## main-wp-to-independent-analytics-bridge-extension
This is installed on the MainWP Dashboard, this is now fully hooked in to report generation and requires no modifications to the site or templates just install and its done, there are no custom settings.

## custom-email-body.php
This is the custom template I am using based on this video https://www.youtube.com/watch?v=ytUNgJMu0vg & this Article https://kb.mainwp.com/docs/how-to-create-an-html-only-report-using-the-pro-reports-extension/

The only modification(s) are the custom `[ipwa-visitors]` added and it uses `[ithemes.scan.count]` rather than wordfence to better work with my setup.

This also works with Custom PDF reports too; I have not made a custom PDF report template yet.

## Usage Instructions
1) Add the child site to your child site(s)
2) Add the main extension to your dashboard
3) Add the new outputs to your templates they are avaliable to you immediately and are custom tokens to use as the rest of the tokens within MainWP Dashboard!

### What does it output?
It adds three new custom tokens you can access which are displayed below;
[ipwa-views]
[ipwa-visitors]
[ipwa-sessions]

![Example Output in a report](https://i.ibb.co/x1Gr5Xk/IWAP-bridge.png%29)

**Please Note: I have tested this and written this and it works for my custom dashbard and requirements you will need to test in your setup**


### Future Intergration
1) I would like to enquire with IA to see if the rest point already exsits within the plugin I can call then you wont need the child plugin
2) Additional output will be added as I get time and its avaliable within the developer API

