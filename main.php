<?php
/**
 * Plugin Name: Addon Deals For Woocommerce
 * Description: Addon Deals สำหรับ Woocommerce Cart สำหรับสินค้าที่มี slug เป็น 'addon-deals'
 * Version: 1.0
 * Author: Jirakit Pawnsakunrungrot
 * Author URI: https://www.linkedin.com/in/sunny-jirakit
 * Plugin URI: https://github.com/sunny420x/woocommerce-addon-deals
 */

add_action('admin_menu', 'worldchem_addon_deals_menu');

function worldchem_addon_deals_menu() {
    add_menu_page(
        'Addon Deals Settings', // Title ของหน้า
        'ระบบ Addon Deals', // ชื่อเมนูที่โชว์ในแถบข้าง
        'manage_options', //สิทธิ์การเข้าถึง (Admin)
        'woocommerce-addon-deals-settings', // Slug ของหน้า
        'woocommerce_addon_deals_setting_page', // ฟังก์ชันที่ใช้พ่น HTML หน้า Setting
        'dashicons-cart', // ไอคอน
        '80' // ตำแหน่งเมนู
    );
}

function woocommerce_addon_deals_setting_page() {
    ?>
        <style>
        .leftside {
            width: 350px;
            background: #f8f8f8;
            height: max-content;
        }
        .leftside h1 {
            background: #009FE3;
            color: #fff;
            font-size: 16px;
            padding: 10px 20px;
            margin: 0;
        }
        .leftside a {
            padding: 10px 20px;
            font-size: 14px;
            background: #f8f8f8;
            color: #000;
            transition: .2s ease-in-out;
            display: block;
            width: 100%;
            text-decoration: none;
        }
        .leftside a:hover {
            background: #fff;
            cursor: pointer;
        }
        .container {
            width: 1200px;
            background: #fff; 
        }
        .container h1 {
            background: #555;
            color: #fff;
            font-size: 16px;
            padding: 10px 20px;
            margin: 0;
        }
        .container p {
            padding: 0;
        }
        .white-label-zone {
            width: calc(100% + 20px);
            height: auto;
            background: #fff;
            display: flex;
            margin: 0 0 0 -20px;
        }
        .white-label-zone h1,p {
            padding: 0 20px;
        }
    </style>
    <div class="white-label-zone no-print">
        <span style="padding: 60px 10px 60px 40px;float: left;font-size: 60px;">🛒</span>
        <div style="padding: 20px 0;">
            <h1>WooCommerce Addon Deals</h1>
            <p>ระบบสิทธิพิเศษ Addon Deals สำหรับ WooCommerce บน WordPress ประกอบไปด้วย คะแนนและระดับของสมาชิก แลกคะแนนเป็นส่วนลด ส่วนลดสำหรับสินค้าพิเศษ ส่วนลดสำหรับ Brand พิเศษ เป็นต้น
                <br>
                <strong>Github Repository:</strong> <a href="https://github.com/sunny420x/woocommerce-addon-deals" target="_blank">https://github.com/sunny420x/woocommerce-addon-deals</a>
            </p>
        </div>
    </div>
    <div class="wrap">
        <div style="display: flex;">
            <div class="leftside">
                <h1>WooCommerce Addon Deals</h1>
                <a href="/wp-admin/admin.php?page=woocommerce-addon-deals-settings&option=addon_by_slug">📦 Deals แบบกลุ่ม</a>
                <a href="/wp-admin/admin.php?page=woocommerce-addon-deals-settings&option=addon_by_id">🎁 Deals แบบรายชิ้น</a>
                <a href="/wp-admin/admin.php?page=woocommerce-addon-deals-settings">📜 คู่มือการใช้งาน</a>
            </div>
            <div class="container">
                <?php
                if(isset($_GET['option']) && $_GET['option'] === 'addon_by_slug') {
                ?>
                <h1>Addon Deals แบบกลุ่ม</h1>
                <div style="padding: 0 25px 25px 25px;">
                    <p><strong>Addon Deals</strong> เงื่อนไขการทำงาน คือ จะโชว์ดีลพิเศษจนกว่าจะหมดอายุ หากหมดอายุแล้วจะไม่แสดงดีลให้อีก จนกว่าจะดำเนินการสั่งซื้อออเดอร์ปัจจุบันเสร็จ จะพบว่าดีลพิเศษแสดงอีกครั้งเมื่อเลือกซื้อสินค้ารอบหน้า</p>
                    <p>การเพิ่มสินค้าลงไปใน Addon Deals จะทำได้โดยการกำหนด Product Category เพิ่มจากเดิมโดยกำหนด slug เป็น 'addon-deals'</p>
                    <form action="options.php" method="post">
                        <?php
                        settings_fields('addon_settings_group');
                        ?>
                        <h2>Category Slug ID:</h2>
                        <input type="number" name="addon_deal_category_slug_id" value="<?php echo esc_attr(get_option('addon_deal_category_slug_id', 514)); ?>" />
                        <h2>ลดราคาร้อยละ (%):</h2>
                        <input type="number" name="addon_deal_percent_discount" value="<?php echo esc_attr(get_option('addon_deal_percent_discount', 10)); ?>" />
                        <h2>จำนวนวินาทีนับถอยหลังก่อนหมดเวลา (Timeout):</h2>
                        <input type="number" name="addon_deal_timeout" value="<?php echo esc_attr(get_option('addon_deal_timeout', 180)); ?>" />
                        <h2>ภาพ Banner ดีลพิเศษ:</h2>
                        <div class="banner-upload-wrapper">
                            <input type="text" name="addon_deal_banner_url" id="addon_deal_banner_url" 
                                value="<?php echo esc_attr(get_option('addon_deal_banner_url', '')); ?>" 
                                style="width: 70%;" />
                            
                            <button type="button" class="button" id="upload_banner_button">เลือกรูปภาพ...</button>
                            
                            <div id="banner_preview" style="margin-top: 10px;">
                                <?php $banner_url = get_option('addon_deal_banner_url'); ?>
                                <?php if ($banner_url) : ?>
                                    <img src="<?php echo esc_url($banner_url); ?>" style="max-width: 300px; border: 1px solid #ccc;" />
                                <?php endif; ?>
                            </div>
                        </div>
                        <br>
                        <?php submit_button('บันทึกการเปลี่ยนแปลง'); ?>
                        <hr>
                        <p>Github Repository: <a href="https://github.com/sunny420x/woocommerce-addon-deals" target="_blank">github.com/sunny420x/woocommerce-addon-deals</a></p>
                    </form>
                </div>
                <script type="text/javascript">
                jQuery(document).ready(function($){
                    $('#upload_banner_button').click(function(e) {
                        e.preventDefault();
                        
                        // สร้าง Media Frame
                        var image_frame = wp.media({
                            title: 'เลือกรูปภาพ Banner',
                            multiple: false,
                            library: { type: 'image' }
                        });

                        // เมื่อเลือกรูปภาพเสร็จแล้ว
                        image_frame.on('select', function() {
                            var selection = image_frame.state().get('selection').first().toJSON();
                            var image_url = selection.url;

                            // 1. เอา URL ไปใส่ใน Input
                            $('#addon_deal_banner_url').val(image_url);
                            
                            // 2. แสดงตัวอย่างรูปภาพ (Preview)
                            $('#banner_preview').html('<img src="'+image_url+'" style="max-width: 300px; border: 1px solid #ccc;" />');
                        });

                        image_frame.open();
                    });
                });
                </script>
                <?php
                } else if(isset($_GET['option']) && $_GET['option'] === 'addon_by_id') {
                ?>
                <h1>Addon Deals แบบรายชิ้น</h1>
                <div style="padding: 0 25px 25px 25px;">
                    <form action="options.php" method="post">
                        <?php
                        settings_fields('addon_by_id_settings_group');
                        ?>
                        <h2>ลดราคาสินค้าแรกที่เลือกซื้อ (%):</h2>
                        <input type="number" name="addon_deal_first_product_discount_percent" value="<?php echo esc_attr(get_option('addon_deal_first_product_discount_percent', 40)); ?>" />
                        <h2>ลดราคาสินค้าต่อไปที่เลือกซื้อ (%):</h2>
                        <input type="number" name="addon_deal_next_product_discount_percent" value="<?php echo esc_attr(get_option('addon_deal_next_product_discount_percent', 50)); ?>" />
                        <h2> IDs ของสินค้าที่ต้องการให้ลดราคา (คั่นด้วย ,):</h2>
                        <input type="text" name="addon_deal_product_ids" value="<?php echo esc_attr(get_option('addon_deal_product_ids', '')); ?>" style="width: 100%;"/>
                        <br>
                        <?php submit_button('บันทึกการเปลี่ยนแปลง'); ?>
                    </form>
                </div>
                <?php
                } else {
                ?>
                <h1>Addon Deals</h1>
                <div style="padding: 0 25px 25px 25px;">

                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}

add_action('admin_enqueue_scripts', 'addon_deal_setting_load_media_picker');
function addon_deal_setting_load_media_picker($hook) {
    // โหลดเฉพาะในหน้า Setting ของเรา (กันไปตีกับหน้าอื่น)
    if ($hook !== 'woocommerce-addon-deals-settings') { // เปลี่ยนชื่อ slug ให้ตรง
        return;
    }
    wp_enqueue_media();
}

add_action('admin_init', 'woocommerce_addon_deals_setting_init');

function woocommerce_addon_deals_setting_init() {
    register_setting('addon_settings_group', 'addon_deal_percent_discount');
    register_setting('addon_settings_group', 'addon_deal_timeout');
    register_setting('addon_settings_group', 'addon_deal_banner_url');
    register_setting('addon_settings_group', 'addon_deal_category_slug_id');

    register_setting('addon_by_id_settings_group', 'addon_deal_first_product_discount_percent');
    register_setting('addon_by_id_settings_group', 'addon_deal_next_product_discount_percent');
    register_setting('addon_by_id_settings_group', 'addon_deal_product_ids');
}

add_shortcode('addon_deal_product_ids_page', 'render_addon_deal_product_ids_page');

function render_addon_deal_product_ids_page() {
    $product_ids = get_addon_deal_target_product_ids();
    if (empty($product_ids)) {
        return '<p>ยังไม่มีสินค้าในรายการ addon_deal_product_ids</p>';
    }

    $products = array();
    foreach ($product_ids as $product_id) {
        $product = wc_get_product($product_id);
        if (!$product || !is_object($product)) {
            continue;
        }

        $products[] = $product;

        if ($product->is_type('variable')) {
            $children = $product->get_children();
            foreach ($children as $child_id) {
                $child_product = wc_get_product($child_id);
                if ($child_product && is_object($child_product)) {
                    $products[] = $child_product;
                }
            }
        }
    }

    if (empty($products)) {
        return '<p>ไม่พบสินค้าในรายการ addon_deal_product_ids</p>';
    }

    $products = array_values(array_unique(array_map(function ($product) {
        return $product->get_id();
    }, $products)));

    ob_start();
    echo '<div class="addon-deal-category-products" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:20px;">';

    foreach ($products as $product_id) {
        $product = wc_get_product($product_id);
        if (!$product || !is_object($product)) {
            continue;
        }

        $product_url = $product->get_permalink();
        $image_html = $product->get_image('woocommerce_thumbnail');
        $price_html = $product->is_on_sale() ? wc_format_sale_price($product->get_regular_price(), $product->get_sale_price()) : wc_price($product->get_price());
    ?>
        <div class="addon-deal-category-product" style="border:1px solid #e5e5e5; border-radius:10px; padding:15px; background:#fff;">
            <a href="<?=esc_url($product_url)?>" style="display:block; text-decoration:none; color:inherit;">
            <div style="margin-bottom:12px;"><?=$image_html ?></div>
            <h3 style="margin:0 0 8px; font-size:16px;"><?=esc_html($product->get_name())?></h3>
            <div style="font-weight:600; color:#d00;"><?=$price_html ?></div>
            </a>
        </div>
    <?php
    }

    echo '</div>';
    return ob_get_clean();
}

function get_addon_deal_target_product_ids() {
    $raw_ids = get_option('addon_deal_product_ids', '');
    if (empty($raw_ids)) {
        return array();
    }

    $ids = array();
    foreach (explode(',', $raw_ids) as $raw_id) {
        $clean_id = trim($raw_id);
        if ($clean_id === '') {
            continue;
        }

        $id = absint($clean_id);
        if ($id) {
            $ids[] = $id;
        }
    }

    return array_values(array_unique($ids));
}

function apply_addon_deal_product_id_discounts($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    if (empty($cart) || !method_exists($cart, 'get_cart')) {
        return;
    }

    $target_ids = get_addon_deal_target_product_ids();
    if (empty($target_ids)) {
        return;
    }

    $first_discount = (float) get_option('addon_deal_first_product_discount_percent', 40);
    $next_discount = (float) get_option('addon_deal_next_product_discount_percent', 50);
    $matched_count = 0;
    $discount_total = 0;
    $fee_label = 'Flash Sale';

    if (property_exists($cart, 'fees') && is_array($cart->fees)) {
        foreach ($cart->fees as $fee_key => $fee) {
            if (is_object($fee) && isset($fee->name) && $fee->name === $fee_label) {
                unset($cart->fees[$fee_key]);
            }
        }
    }

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        $product_id = !empty($cart_item['product_id']) ? (int) $cart_item['product_id'] : 0;
        $variation_id = !empty($cart_item['variation_id']) ? (int) $cart_item['variation_id'] : 0;

        $is_target_item = in_array($product_id, $target_ids, true) || ($variation_id && in_array($variation_id, $target_ids, true));
        if (!$is_target_item) {
            continue;
        }

        $discount_percent = $matched_count === 0 ? $first_discount : $next_discount;
        $matched_count++;

        $product = isset($cart_item['data']) ? $cart_item['data'] : null;
        if (!$product || !is_object($product)) {
            continue;
        }

        $base_price = (float) $product->get_price();
        if ($base_price <= 0) {
            continue;
        }

        $discounted_price = round($base_price * (1 - ($discount_percent / 100)), 2);
        $discount_amount = round(($base_price - $discounted_price) * $cart_item['quantity'], 2);
        $discount_total += $discount_amount;

        if (isset($cart->cart_contents[$cart_item_key])) {
            $cart->cart_contents[$cart_item_key]['data'] = $product;
            $cart->cart_contents[$cart_item_key]['addon_deal_discount_percent'] = (float) $discount_percent;
            $cart->cart_contents[$cart_item_key]['addon_deal_original_price'] = (float) $base_price;
            $cart->cart_contents[$cart_item_key]['addon_deal_effective_price'] = (float) $discounted_price;
        }
    }

    if ($discount_total > 0) {
        $cart->add_fee($fee_label, -$discount_total, false);
    }
}

add_action('woocommerce_cart_calculate_fees', 'apply_addon_deal_product_id_discounts', 10, 1);
add_filter('woocommerce_cart_item_price', 'addon_deal_cart_item_price', 10, 3);
add_filter('woocommerce_cart_item_subtotal', 'addon_deal_cart_item_subtotal', 10, 3);

function addon_deal_cart_item_price($price, $cart_item, $cart_item_key) {
    if (empty($cart_item['data']) || !is_object($cart_item['data'])) {
        return $price;
    }

    $target_ids = get_addon_deal_target_product_ids();
    if (empty($target_ids)) {
        return $price;
    }

    $product_id = !empty($cart_item['product_id']) ? (int) $cart_item['product_id'] : 0;
    $variation_id = !empty($cart_item['variation_id']) ? (int) $cart_item['variation_id'] : 0;
    $is_target_item = in_array($product_id, $target_ids, true) || ($variation_id && in_array($variation_id, $target_ids, true));

    if (!$is_target_item) {
        return $price;
    }

    $original_price = !empty($cart_item['addon_deal_original_price']) ? (float) $cart_item['addon_deal_original_price'] : (float) $cart_item['data']->get_regular_price();
    if ($original_price <= 0) {
        $original_price = (float) $cart_item['data']->get_price();
    }

    $effective_price = !empty($cart_item['addon_deal_effective_price']) ? (float) $cart_item['addon_deal_effective_price'] : (float) $cart_item['data']->get_price();
    if ($effective_price > 0 && $original_price > 0) {
        $discount_percent = !empty($cart_item['addon_deal_discount_percent']) ? (float) $cart_item['addon_deal_discount_percent'] : round((1 - ($effective_price / $original_price)) * 100, 0);
        return '<span style="text-decoration: line-through; color: #999; margin-right: 6px;">' . wc_price($original_price) . '</span><span style="font-weight: 600; color: #d00;">' . wc_price($effective_price) . '</span><span style="display:block; font-size: 0.8em; color: #d00;">ลด ' . (int) $discount_percent . '%</span>';
    }

    if ($effective_price > 0) {
        return wc_price($effective_price);
    }

    return $price;
}

function addon_deal_cart_item_subtotal($subtotal, $cart_item, $cart_item_key) {
    if (empty($cart_item['data']) || !is_object($cart_item['data'])) {
        return $subtotal;
    }

    $target_ids = get_addon_deal_target_product_ids();
    if (empty($target_ids)) {
        return $subtotal;
    }

    $product_id = !empty($cart_item['product_id']) ? (int) $cart_item['product_id'] : 0;
    $variation_id = !empty($cart_item['variation_id']) ? (int) $cart_item['variation_id'] : 0;
    $is_target_item = in_array($product_id, $target_ids, true) || ($variation_id && in_array($variation_id, $target_ids, true));

    if (!$is_target_item) {
        return $subtotal;
    }

    $effective_price = !empty($cart_item['addon_deal_effective_price']) ? (float) $cart_item['addon_deal_effective_price'] : (float) $cart_item['data']->get_price();
    if ($effective_price > 0) {
        return wc_price($effective_price * (int) $cart_item['quantity']);
    }

    return $subtotal;
}

function get_worldchem_addon_deals_html() {
    global $wpdb;
    $category_slug = 'addon-deals';
    
    $results = $wpdb->get_results($wpdb->prepare("
        SELECT p.ID FROM {$wpdb->prefix}posts p
        INNER JOIN {$wpdb->prefix}term_relationships tr ON (p.ID = tr.object_id)
        INNER JOIN {$wpdb->prefix}term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
        INNER JOIN {$wpdb->prefix}terms t ON (tt.term_id = t.term_id)
        INNER JOIN {$wpdb->prefix}postmeta pm ON (p.ID = pm.post_id)
        WHERE p.post_type = 'product' AND p.post_status = 'publish'
        AND t.slug = %s AND pm.meta_key = '_stock_status' AND pm.meta_value = 'instock'
        GROUP BY p.ID ORDER BY RAND() LIMIT 8
    ", $category_slug));

    if (empty($results)) return '<p style="text-align:center;">ขออภัย ดีลพิเศษหมดชั่วคราว</p>';

    $product_ids = array_column($results, 'ID');
    
    $html = '';
    $html .= render_addon_item_cards($product_ids); // เรียกใช้ตัว Render

    return $html;
}

// 2. ลงทะเบียน AJAX Endpoint
add_action('wp_ajax_refresh_addon_deals', 'ajax_refresh_addon_deals');
add_action('wp_ajax_nopriv_refresh_addon_deals', 'ajax_refresh_addon_deals');
function ajax_refresh_addon_deals() {
    echo get_worldchem_addon_deals_html();
    wp_die();
}

function render_addon_item_cards($product_ids) {
    global $wpdb;
    $html = '';

    foreach ($product_ids as $id) {
        $product = wc_get_product($id);
        if (!$product) continue;

        // --- 1. Logic คูปอง ---
        $prefix = 'D20-' . $id . '-';
        $existing_coupon_code = $wpdb->get_var($wpdb->prepare("SELECT post_title FROM {$wpdb->prefix}posts p INNER JOIN {$wpdb->prefix}postmeta pm ON p.ID = pm.post_id WHERE p.post_type = 'shop_coupon' AND p.post_status = 'publish' AND p.post_title LIKE %s AND pm.meta_key = 'usage_count' AND CAST(pm.meta_value AS UNSIGNED) < 1 ORDER BY p.ID DESC LIMIT 1", $prefix . '%'));
        
        if (!$existing_coupon_code) {
            $new_code = $prefix . strtoupper(wp_generate_password(4, false));
            $coupon = new WC_Coupon();
            $coupon->set_code($new_code);
            $coupon->set_discount_type('fixed_product');
            $coupon->set_amount((float) $product->get_price() * 0.1);
            $coupon->set_usage_limit(1);
            $coupon->set_product_ids(array($id));
            $coupon->save();
            $coupon_code = $new_code;
        } else {
            $coupon_code = $existing_coupon_code;
        }

        // --- 2. Logic เช็คราคาและสินค้าในตะกร้า ---
        $special_price = (float) $product->get_price() * (1 - get_option('addon_deal_percent_discount', 0.1) / 100);
        $in_cart = false;
        foreach (WC()->cart->get_cart() as $item) { 
            if ($item['product_id'] == $id) { $in_cart = true; break; } 
        }

        $html .= '<div class="addon-deal-item" data-id="'.$id.'">';
        $html .= '<a href="'.$product->get_permalink().'">'."<div class='addon-product-img-wrapper'>".$product->get_image('thumbnail', array('class' => 'addon-product-img')).'</div></a>';
        $html .= '<a href="'.$product->get_permalink().'"><h5 class="addon-deal-name">'.$product->get_name().'</h5></a>';
        $html .= '<span class="addon-deal-price"><span style="font-size: 1.2rem;">฿</span> '.number_format($special_price, 0).' <del style="color:#bbb; font-weight:normal; font-size:0.7em; margin-left:5px;">฿ '.number_format($product->get_price(), 0).'</del> <span class="addon-deal-discount">-10%</span></span>';
        
        $html .= '<div style="margin-top: auto; padding: 15px;">';
        if ($in_cart) {
            $html .= '<div style="background: #6c757d; color: #fff; padding: 10px; border-radius: 6px; font-size: 0.85em;">สินค้าอยู่ในตะกร้าแล้ว</div>';
        } elseif ($product->is_type('variable')) {
            $html .= '<a href="'.get_permalink($id).'" style="background: #e7f3e8; color: #28a745; padding: 10px; text-decoration: none; border-radius: 6px; display: block; font-size: 0.9em; text-align: center;">เลือกขนาด/ตัวเลือก</a>';
        } else {
            $html .= '<a href="?add-to-cart='.$id.'&apply_deal_coupon='.$coupon_code.'" style="background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 6px; display: block; text-align: center; font-size: 0.9em;">เพิ่มลงในตะกร้า</a>';
        }
        $html .= '</div></div>';
    }
    return $html;
}

function ajax_load_more_addon_deals() {
    global $wpdb;
    $category_slug = 'addon-deals';
    
    // รับ ID ที่โชว์อยู่แล้วมากันซ้ำ
    $exclude = isset($_GET['exclude']) ? array_map('intval', explode(',', $_GET['exclude'])) : array();
    $exclude_sql = !empty($exclude) ? " AND p.ID NOT IN (" . implode(',', $exclude) . ") " : "";

    $results = $wpdb->get_results($wpdb->prepare("
        SELECT p.ID FROM {$wpdb->prefix}posts p
        INNER JOIN {$wpdb->prefix}term_relationships tr ON (p.ID = tr.object_id)
        WHERE p.post_type = 'product' AND p.post_status = 'publish'
        AND p.ID IN (SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id IN (SELECT term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy WHERE term_id IN (SELECT term_id FROM {$wpdb->prefix}terms WHERE slug = %s)))
        $exclude_sql
        GROUP BY p.ID ORDER BY RAND() LIMIT 4
    ", $category_slug));

    if (empty($results)) {
        echo 'DONE';
        wp_die();
    }

    $product_ids = array_column($results, 'ID');
    echo render_addon_item_cards($product_ids); // พ่นแค่ตัว Card ออกไปเสียบต่อท้าย
    wp_die();
}

// อย่าลืมลงทะเบียน AJAX ตัวใหม่
add_action('wp_ajax_load_more_addon_deals', 'ajax_load_more_addon_deals');
add_action('wp_ajax_nopriv_load_more_addon_deals', 'ajax_load_more_addon_deals');

// 3. ฟังก์ชันหลักที่หน้า Cart
add_action('woocommerce_after_cart_table', 'wp_cart_coupon_lucky_deal');
function wp_cart_coupon_lucky_deal() {
    if (WC()->cart->is_empty()) return;

    // 1. เช็คก่อนว่า User คนนี้ทำดีลหมดอายุไปหรือยัง
    if (!session_id()) session_start();
    if (isset($_SESSION['worldchem_addon_deal_is_expired']) && $_SESSION['worldchem_addon_deal_is_expired'] === true) {
        return; // ⛔️ ถ้าหมดเวลาแล้ว ไม่ต้องโชว์อะไรเลย จบงาน!
    }
    ?>
    <style>
        span.addon-deal-price {
            color: #dd140d;
            font-weight: bold;
            font-size: 1.3rem;
            padding: 10px;
            width: 100%;
            display: block;
        }

        .addon-deal-name {
            margin: 0 10px; 
            font-size: 1.3rem; 
            color: #333; 
            font-weight: 600; 
            line-height: 1.4
        }

        .addon-deal-item {
            background: #fff; 
            border-radius: 10px; 
            text-align: center; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #eee; 
            height: max-content;
        }

        .addon-deal-container {
            margin-top: 30px; 
            border: 1px solid #e9e9e9; 
            border-radius: 10px; 
            background: #fafafa;
            overflow: hidden;
        }
        .addon-deal-heading {
            color: #856404; 
            margin-top: 0; 
            text-align: center;
        }
        .addon-deal-heading img {
            width: 100%;
        }
        .addon-deal-holder {
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); 
            gap: 20px;
            padding: 20px; 
        }

        .addon-deal-container #countdown {
            color: #dd140d;
            background: #FBE5F0;
            padding: 2px 5px;
            font-size: 1rem;
            width: 100%;
        }

        .addon-deal-discount {
            color: #dd140d;
            background: #FBE5F0;
            padding: 2px 5px;
            font-size: 1rem;
            font-weight: normal;
            margin: 0 0 0 5px;
            border-radius: 5px;
        }

        .addon-product-img-wrapper {
            width: 100%;
            height: auto;
            overflow: hidden;
        }

        .addon-product-img {
            border-radius:8px; 
            margin-bottom:15px;
            width:100%;
            height:150px;
            transition: .2s ease-in-out;
        }
        .addon-product-img:hover {
            transform: scale(1.2);
        }
    </style>

    <script>
        // ล้าง URL parameter หลังจากกดเพิ่มสินค้าเสร็จ เพื่อกันการกด F5 แล้วแอดซ้ำ
        if (window.history.replaceState) {
            const url = new URL(window.location.href);
            if (url.searchParams.has('add-to-cart') || url.searchParams.has('apply_deal_coupon')) {
                url.searchParams.delete('add-to-cart');
                url.searchParams.delete('apply_deal_coupon');
                window.history.replaceState({}, document.title, url.pathname + url.search);
            }
        }
    </script>
    
    <div class="addon-deal-container">
        <div class="addon-deal-heading">
            <img src="<?php echo get_option('addon_deal_banner_url', "https://www.worldpools.co.th/wp-content/uploads/2026/04/addon-deal-new.jpg");?>">    
            <div id="countdown"></div>
        </div>
        
        <div class="addon-deal-holder" id="addon-deals-wrapper">
            <?php echo get_worldchem_addon_deals_html(); ?>
        </div>

        <div style="text-align: center; margin: 20px 0;">
            <span id="load-more-btn" style="color: #333; border: none; padding: 10px 20px; cursor: pointer;">
                ดูดีลเพิ่มเติม
            </span>
        </div>

        <script>
            let countdown = <?php echo get_option('addon_deal_timeout', 180); ?>;
            let wrapper = document.getElementById("addon-deals-wrapper");

            document.getElementById("countdown").innerHTML = "หมดอายุในอีก " + Math.floor(countdown / 60) + ":" + (countdown % 60).toString().padStart(2, '0');

            const timer = setInterval(() => {
                let countdownEl = document.getElementById("countdown"); //ต้องอยู่ตำแหน่งนี้ เพราะ ถ้ากด plus, minus จะถูกแทนที่จนหาไม่เจอ
                
                countdown -= 1;
                
                if(countdown <= 0) {
                    wrapper.style.opacity = '0.5';

                    fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=set_addon_deal_expired')
                        .then(() => {
                            // 2. ลบกล่องดีลออกจากหน้าจอแบบเนียนๆ
                            let container = document.querySelector('.addon-deal-container');
                            if(container) {
                                container.style.transition = 'opacity 0.5s';
                                container.style.opacity = '0';
                                setTimeout(() => { container.remove(); }, 500);
                            }
                            clearInterval(timer); // หยุดเวลานับ
                        });
                    return;
                }
                
                let min = Math.floor(countdown / 60);
                let sec = (countdown % 60).toString().padStart(2, '0');
                countdownEl.innerHTML = "หมดอายุในอีก " + min + ":" + sec;
            }, 1000);

            document.getElementById('load-more-btn').addEventListener('click', function() {
                let btn = this;
                let holder = document.querySelector('.addon-deal-holder');
                
                // 1. เก็บ ID สินค้าที่มีอยู่บนหน้าจอตอนนี้ทั้งหมด
                let existingIds = [];
                document.querySelectorAll('.addon-deal-item').forEach(item => {
                    let id = item.getAttribute('data-id'); 
                    if(id) existingIds.push(id);
                });

                btn.innerHTML = "กำลังค้นหา...";
                btn.disabled = true;

                // 2. ยิง AJAX ไปดึงของใหม่โดยส่ง ID เก่าไปกันซ้ำ
                fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=load_more_addon_deals&exclude=' + existingIds.join(','))
                .then(res => res.text())
                .then(data => {
                    if (data === 'DONE') {
                        btn.innerHTML = "ไม่มีดีลเพิ่มเติมแล้ว";
                        btn.style.display = 'none'; // หรือซ่อนปุ่มไปเลย
                    } else {
                        // 3. เอา HTML ใหม่ที่ได้มา "เสียบต่อท้าย"
                        holder.insertAdjacentHTML('beforeend', data);
                        btn.innerHTML = "ดูดีลเพิ่มเติม";
                        btn.disabled = false;
                    }
                });
            });
        </script>
    </div>
<?php
}

add_action('wp_ajax_set_addon_deal_expired', 'ajax_set_addon_deal_expired');
add_action('wp_ajax_nopriv_set_addon_deal_expired', 'ajax_set_addon_deal_expired');

function ajax_set_addon_deal_expired() {
    if (!session_id()) session_start();
    $_SESSION['worldchem_addon_deal_is_expired'] = true; // จดบันทึกว่า "หมดอายุแล้ว"
    wp_die();
}

add_action('woocommerce_before_cart', 'smart_auto_addon_deal_handler');
add_action('woocommerce_before_checkout_form', 'smart_auto_addon_deal_handler');

function smart_auto_addon_deal_handler() {
    global $wpdb;
    if (WC()->cart->is_empty()) return;

    if (!session_id()) session_start();
    $category_slug = 'addon-deals';

    // 1. เช็คว่าดีลหมดอายุไปแล้วหรือยัง (จาก Session ที่เราตั้งไว้)
    $is_expired = (isset($_SESSION['worldchem_addon_deal_is_expired']) && $_SESSION['worldchem_addon_deal_is_expired'] === true);

    // 2. ดึงคูปองดีลทั้งหมดที่ใช้ (หรือค้าง) อยู่ตอนนี้
    $applied_coupons = WC()->cart->get_applied_coupons();

    // --- 🚨 กรณีที่ "ดีลหมดอายุ" หรือ "ไม่มีสินค้าหลัก" ---
    // เราจะกวาดล้างคูปอง D20- ทิ้งทั้งหมด
    $has_main_product = false;
    foreach (WC()->cart->get_cart() as $cart_item) {
        if (!has_term($category_slug, 'product_cat', $cart_item['product_id'])) {
            $has_main_product = true;
            break;
        }
    }

    if ($is_expired || !$has_main_product) {
        foreach ($applied_coupons as $code) {
            if (stripos($code, 'D20-') === 0) {
                WC()->cart->remove_coupon($code);
            }
        }
        return; // จบการทำงาน ไม่ต้องไปเช็คยัดคูปองเพิ่มแล้ว
    }
    
    // 1. ตรวจสอบว่าในตะกร้ามี "สินค้าหลัก" หรือไม่ (สินค้าที่ไม่ได้อยู่ในหมวด addon-deals)
    $has_main_product = false;
    foreach (WC()->cart->get_cart() as $cart_item) {
        if (!has_term($category_slug, 'product_cat', $cart_item['product_id'])) {
            $has_main_product = true;
            break;
        }
    }

    // 2. ดึงคูปองดีลทั้งหมดที่ใช้อยู่ตอนนี้
    $applied_coupons = WC()->cart->get_applied_coupons();

    // --- กรณีที่ 1: ไม่มีสินค้าหลัก (ล้างบางคูปอง) ---
    if (!$has_main_product) {
        foreach ($applied_coupons as $code) {
            if (stripos($code, 'D20-') === 0) {
                WC()->cart->remove_coupon($code);
            }
        }
        return; // จบการทำงาน ไม่ต้องไปเช็คยัดคูปองเพิ่ม
    }

    // --- กรณีที่ 2: มีสินค้าหลักครบถ้วน (ใส่คูปองให้ตามปกติ) ---
    $addon_product_ids = $wpdb->get_col($wpdb->prepare("
        SELECT p.ID FROM {$wpdb->prefix}posts p
        INNER JOIN {$wpdb->prefix}term_relationships tr ON (p.ID = tr.object_id)
        INNER JOIN {$wpdb->prefix}term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
        INNER JOIN {$wpdb->prefix}terms t ON (tt.term_id = t.term_id)
        WHERE p.post_type = 'product' AND p.post_status = 'publish' AND t.slug = %s
    ", $category_slug));

    foreach (WC()->cart->get_cart() as $cart_item) {
        $product_id = $cart_item['product_id'];
        if (in_array($product_id, $addon_product_ids)) {
            $prefix = 'D20-' . $product_id . '-';
            $coupon_code = $wpdb->get_var($wpdb->prepare("
                SELECT post_title FROM {$wpdb->prefix}posts p
                INNER JOIN {$wpdb->prefix}postmeta pm ON p.ID = pm.post_id
                WHERE p.post_type = 'shop_coupon' AND p.post_status = 'publish' 
                AND p.post_title LIKE %s
                AND pm.meta_key = 'usage_count' AND CAST(pm.meta_value AS UNSIGNED) < 1
                ORDER BY p.ID DESC LIMIT 1
            ", $prefix . '%'));

            if ($coupon_code && !WC()->cart->has_discount($coupon_code)) {
                WC()->cart->apply_coupon($coupon_code);
            }
        }
    }
}

/**
 * ซ่อนหมวดหมู่สินค้า 'addon-deals' ออกจาก Widget Product Categories
 */
add_filter( 'woocommerce_product_categories_widget_args', 'exclude_widget_category_addon_deal' );

function exclude_widget_category_addon_deal( $args ) {
    // ใส่ ID ของหมวดหมู่ 'addon-deals'
    $args['exclude'] = array( get_option('addon_deal_category_slug_id', 514) ); //ซ่อน addon-deal
    
    return $args;
}

/**
 * ล้างสถานะดีลหมดอายุ เมื่อสั่งซื้อสำเร็จ
 * เพื่อให้ออเดอร์ถัดไปลูกค้าสามารถเห็นดีลได้อีกครั้ง
 */
add_action('woocommerce_thankyou', 'reset_worldchem_addon_deal_after_purchase', 10, 1);
function reset_worldchem_addon_deal_after_purchase( $order_id ) {
    if ( ! $order_id ) return;

    // 1. เริ่ม Session (ถ้ายังไม่เริ่ม)
    if ( ! session_id() ) {
        session_start();
    }

    // 2. ล้าง Session ที่บอกว่าดีลหมดอายุ
    if ( isset($_SESSION['worldchem_addon_deal_is_expired']) ) {
        unset($_SESSION['worldchem_addon_deal_is_expired']);
    }
}