<?php
// Show orders across all sites on a wordpress multisite. Use shortcode [network_user_orders] on a page

function get_network_orders_for_user($user_id) {
    $orders = [];

    $sites = get_sites();
    foreach ($sites as $site) {
        switch_to_blog($site->blog_id);

        $user_orders = wc_get_orders(['customer_id' => $user_id]);
        $orders = array_merge($orders, $user_orders);

        restore_current_blog();
         
    }

    return $orders;
}

add_shortcode('network_user_orders', 'display_network_user_orders');

function display_network_user_orders() {
    if (!is_user_logged_in()) {
        return 'Please log in to view your orders.';
    }

    $user_id = get_current_user_id();
    $orders = get_network_orders_for_user($user_id);

    // Display the orders
    foreach ($orders as $order) {
        echo 'Order ID: ' . $order->get_id() . '<br>';
        echo 'Order Date: ' . $order->get_date_created()->date('Y-m-d H:i:s') . '<br>';
        // Add more order details as needed
    }
}