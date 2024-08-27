<?php 
function custom_from_date_filter() {
    return '2024-08-01'; // Replace with the dynamic from date
}
add_filter('custom_from_date', 'custom_from_date_filter');

function custom_to_date_filter() {
    return '2024-08-27'; // Replace with the dynamic to date
}
add_filter('custom_to_date', 'custom_to_date_filter');
