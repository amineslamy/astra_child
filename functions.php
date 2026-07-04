<?php
function sahab_enqueue_styles() {
    // تغییر ورژن به 1.2 برای شکستن اجباری کش مرورگر
    wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_uri(), array(), '1.2' );
}
add_action( 'wp_enqueue_scripts', 'sahab_enqueue_styles', 99 );