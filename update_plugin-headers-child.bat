@echo off
setlocal enabledelayedexpansion

:: Define variables
set "pluginFile=main-wp-to-independent-analytics-child-plugin/main-wp-to-independent-analytics-child-plugin.php"   :: Path to main plugin file

:: Run PHP script to update plugin headers
php -f update_plugin_headers.php "%pluginFile%"

echo Plugin headers updated successfully!
pause
