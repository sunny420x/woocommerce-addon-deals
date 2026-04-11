<?php
/**
 * Plugin Name: Addon Deals For Woocommerce
 * Description: Addon Deals สำหรับ Woocommerce Cart สำหรับสินค้าที่มี slug เป็น 'addon-deals'
 * Version: 1.0
 * Author: Jirakit Pawnsakunrungrot
 * Author URI: https://www.linkedin.com/in/sunny-jirakit
 * Plugin URI: https://github.com/sunny420x/woocommerce-addon-deals
 */

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

    $html = '<div class="addon-deal-holder">';
    foreach ($results as $res) {
        $id = $res->ID;
        $product = wc_get_product($id);
        if (!$product) continue;

        // --- (โค้ดสร้างคูปองเดิมของพี่ ยกมาไว้ตรงนี้ทั้งหมด) ---
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

        $special_price = (float) $product->get_price() * 0.9;
        $in_cart = false;
        foreach (WC()->cart->get_cart() as $item) { if ($item['product_id'] == $id) { $in_cart = true; break; } }

        // สร้าง HTML สำหรับแต่ละชิ้น
        $html .= '<div class="addon-deal-item">';
        $html .= '<a href="'.$product->get_permalink().'">'.$product->get_image('thumbnail', array('style' => 'border-radius:8px; margin-bottom:15px; width:100%; height:150px; object-fit:cover;')).'</a>';
        $html .= '<a href="'.$product->get_permalink().'"><h5 class="addon-deal-name">'.$product->get_name().'</h5></a>';
        $html .= '<span class="addon-deal-price"><span style="font-size: 1.2rem;">฿</span> '.$special_price.' <del style="color:#bbb; font-weight:normal; font-size:0.7em; margin-left:5px;">฿ '.$product->get_price().'</del></span>';
        $html .= '<div style="margin-top: auto; padding: 15px;">';
        if ($in_cart) {
            $html .= '<div style="background: #e7f3e8; color: #28a745; padding: 10px; border-radius: 6px; font-weight: bold; font-size: 0.85em;">สินค้าอยู่ในตะกร้าแล้ว</div>';
        } elseif ($product->is_type('variable')) {
            $html .= '<a href="'.get_permalink($id).'" style="background: #6c757d; color: white; padding: 10px; text-decoration: none; border-radius: 6px; display: block; font-size: 0.9em; text-align: center;">เลือกขนาด/ตัวเลือก</a>';
        } else {
            $html .= '<a href="?add-to-cart='.$id.'&apply_deal_coupon='.$coupon_code.'" style="background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 6px; display: block; text-align: center; font-size: 0.9em;">เพิ่มลงในตะกร้า</a>';
        }
        $html .= '</div></div>';
    }
    $html .= '</div>';
    return $html;
}

// 2. ลงทะเบียน AJAX Endpoint
add_action('wp_ajax_refresh_addon_deals', 'ajax_refresh_addon_deals');
add_action('wp_ajax_nopriv_refresh_addon_deals', 'ajax_refresh_addon_deals');
function ajax_refresh_addon_deals() {
    echo get_worldchem_addon_deals_html();
    wp_die();
}

// 3. ฟังก์ชันหลักที่หน้า Cart
add_action('woocommerce_after_cart_table', 'wp_cart_coupon_lucky_deal');
function wp_cart_coupon_lucky_deal() {
    if (WC()->cart->is_empty()) return;
    ?>
    <style>
        span.addon-deal-price {
            margin: 20px 10px 10px 10px; 
            color: #dd140d; 
            font-weight: bold; 
            font-size: 1.3rem; 
            padding: 20px;
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
            padding: 20px; 
            margin-top: 30px; 
            border: 1px solid #e9e9e9; 
            border-radius: 10px; 
            background: #fafafa;
        }
        .addon-deal-heading {
            color: #856404; 
            margin-top: 0; 
            text-align: center; 
            margin-bottom: 30px;
        }
        .addon-deal-holder {
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); 
            gap: 20px;
        }

        .addon-deal-container #countdown {
            color: #dd140d;
            background: #FBE5F0;
            padding: 2px 5px;
            font-size: 1rem;
        }

        .addon-deal-discount {
            color: #dd140d;
            background: #FBE5F0;
            padding: 2px 5px;
            font-size: 1rem;
        }
    </style>
    
    <div class="addon-deal-container">
        <h3 class="addon-deal-heading">🎁 ดีลพิเศษสำหรับคุณ <span id="countdown">3:00</span></h3>
        
        <?php echo get_worldchem_addon_deals_html(); ?>

        <script>
            (function() {
                let countdown = 180;
                let countdownEl = document.getElementById("countdown");
                let wrapper = document.getElementById("addon-deals-wrapper");

                const timer = setInterval(() => {
                    countdown -= 1;
                    
                    if(countdown <= 0) {
                        // แทนที่จะ Reload หน้า ให้ยิง AJAX แทน
                        wrapper.style.opacity = '0.5'; // ทำจางๆ ระหว่างโหลด
                        
                        fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=refresh_addon_deals')
                            .then(response => response.text())
                            .then(data => {
                                wrapper.innerHTML = data;
                                wrapper.style.opacity = '1';
                                countdown = 180; // Reset เวลาใหม่
                            });
                        return;
                    }
                    
                    let min = Math.floor(countdown / 60);
                    let sec = (countdown % 60).toString().padStart(2, '0');
                    countdownEl.innerHTML = min + ":" + sec;
                }, 1000);
            })();
        </script>
    </div>
<?php
}

add_action('woocommerce_cart_totals_after_order_total', 'display_combined_addon_and_tiered_discount_v5');
add_action('woocommerce_review_order_after_order_total', 'display_combined_addon_and_tiered_discount_v5');

function display_combined_addon_and_tiered_discount_v5() {
    $total_discount = 0;

    // --- 1. ดึงส่วนลดจากคูปอง Add-on (D20-) ---
    $applied_coupons = WC()->cart->get_applied_coupons();
    if (!empty($applied_coupons)) {
        foreach ($applied_coupons as $code) {
            if (stripos($code, 'D20-') === 0) {
                $total_discount += WC()->cart->get_coupon_discount_amount($code);
            }
        }
    }

    // --- 2. ดึงส่วนลดจาก Tiered Brand Discount (ที่ส่งมาเป็น Fee) ---
    // ปกติ Fee ที่ลดราคามันจะเป็นค่าติดลบ เราเลยต้องเอามาบวกแบบค่าสัมบูรณ์ (abs)
    foreach (WC()->cart->get_fees() as $fee) {
        if ($fee->amount < 0) {
            $total_discount += abs($fee->amount);
        }
    }

    // --- 3. แสดงผลรวมทั้งหมดที่ประหยัดไปได้ ---
    if ($total_discount > 0) {
        ?>
        <div class="totals-discounts">
            <div class="title">
                <span>ประหยัดไปได้ทั้งหมด:</span> 
                <span class="totals" style="color: red;"><?php echo wc_price($total_discount); ?></span>
            </div>
        </div>
        <?php
    }
}

add_action('woocommerce_before_cart', 'smart_auto_addon_deal_handler');
add_action('woocommerce_before_checkout_form', 'smart_auto_addon_deal_handler');

function smart_auto_addon_deal_handler() {
    global $wpdb;
    if (WC()->cart->is_empty()) return;

    $category_slug = 'addon-deals';
    
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