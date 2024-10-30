<?php
/**
 * Plugin Name:         Jigoshop Grouped Products Pro
 * Plugin URI:          http://www.chriscct7.com
 * Description:         Enhanced Jigoshop Grouped Products
 * Author:              Chris Christoff
 * Author URI:          http://www.chriscct7.com
 *
 * Contributors:        chriscct7
 *
 * Version:             4.0
 * Requires at least:   3.5.0
 * Tested up to:        3.6 Beta 3
 *
 * Text Domain:         jgpp
 * Domain Path:         /languages/
 *
 * @category            Plugin
 * @copyright           Copyright © 2013 Chris Christoff
 * @author              Chris Christoff
 * @package             JGPP
 */

if ( !class_exists( 'Jigoshop_Grouped_Products_Pro' ) ) {
    class Jigoshop_Grouped_Products_Pro {
        function __construct( ) {
            add_action( 'jigoshop_product_write_panel_tabs', array(
                 $this,
                'jigoshop_product_write_panel_tabs' 
            ) );
            add_action( 'product_write_panels', array(
                 $this,
                'product_write_panel' 
            ) );
            add_filter( 'jigoshop_process_product_meta', array(
                 $this,
                'me_product_save_data' 
            ) );
            add_action( 'admin_enqueue_scripts', array(
                 $this,
                'admin_enqueue' 
            ) );
            add_action( 'product_write_panels', array(
                 $this,
                'input' 
            ) );
            remove_action( 'jigoshop_add_to_cart_action', 'jigoshop_add_to_cart_action', 20 );
            add_action( 'init', 'jigoshop_add_to_cart_action' );
            add_action( 'admin_head', array(
                 $this,
                'badsetting' 
            ) );
        }
        function jspgactivation( ) {
            // checks if the jigoshop plugin is running and disables this plugin if it's not (and displays a message)
            if ( !is_plugin_active( 'jigoshop/jigoshop.php' ) ) {
                deactivate_plugins( plugin_basename( __FILE__ ) );
                wp_die( sprintf( _x( 'The Jigoshop Grouped Products Pro plugin requires %s to be activated in order to work. Please activate %s first.', 'A link to Jigoshop is provided in the placeholders', 'Jigoshop_Grouped_Products_Pro' ), '<a href="http://jigoshop.com" target="_blank">JigoShop</a>', '<a href="http://jigoshop.com" target="_blank">JigoShop</a>' ) . '<a href="' . admin_url( 'plugins.php' ) . '"> <br> &laquo; ' . _x( 'Go Back', 'Activation failed, so go back to the plugins page', 'Jigoshop_Grouped_Products_Pro' ) . '</a>' );
            }
            
        }
        /**
         * adds a new tab to the product interface
         *
         * @since 1.0
         * @return void
         */
        function jigoshop_product_write_panel_tabs( ) {
            $terms        = get_the_terms( $thepostid, 'product_type' );
            $product_type = ( $terms ) ? current( $terms )->slug : 'simple';
            
            if ( $product_type == 'grouped' || $product_type == "simple" ) { ?>
				<li><a href="#group_pro"><?php _e( 'Group Pro', 'Jigoshop_Grouped_Products_Pro' ); ?></a></li>
				<?php
            } else {
                // display nothing
            }
        }
        /**
         * adds the panel to the product interface
         *
         * @since 1.0
         * @return void
         */
        function product_write_panel( ) {
            global $post;
            $thepostid = $post->ID; ?>
			<div id="group_pro" class="panel jigoshop_options_panel">
			<?php
            echo '<p>If your min value is not equal to or less than your max value, this plugin <b>will not work </b></p>';
            $args = array(
                 'id' => 'maximum',
                'label' => __( 'maximum', 'Jigoshop_Grouped_Products_Pro' ),
                'min' => '0',
                'max' => '1000000',
                'type' => 'number',
                'step' => '1',
                'placeholder' => __( '0', 'Jigoshop_Grouped_Products_Pro' ) 
            );
            echo $this->input( $args );
            $args = array(
                 'id' => 'minimum',
                'label' => __( 'minimum', 'Jigoshop_Grouped_Products_Pro' ),
                'min' => '0',
                'max' => '1000000',
                'type' => 'number',
                'step' => '1',
                'placeholder' => __( '0', 'Jigoshop_Grouped_Products_Pro' ) 
            );
            echo $this->input( $args );
            $terms        = get_the_terms( $thepostid, 'product_type' );
            $product_type = ( $terms ) ? current( $terms )->slug : 'simple';
            if ( $product_type == 'grouped' ) {
                $args = array(
                     'id' => 'regular_price',
                    'label' => __( 'regular_price', 'Jigoshop_Grouped_Products_Pro' ),
                    'min' => '0',
                    'max' => '1000000',
                    'type' => 'number',
                    'step' => '0.01',
                    'placeholder' => __( '0', 'Jigoshop_Grouped_Products_Pro' ) 
                );
                echo $this->input( $args );
                $args = array(
                     'id' => 'quantity',
                    'label' => __( 'quantity', 'Jigoshop_Grouped_Products_Pro' ),
                    'min' => '0',
                    'max' => '1000000',
                    'type' => 'number',
                    'step' => '0',
                    'placeholder' => __( '100', 'Jigoshop_Grouped_Products_Pro' ) 
                );
                echo $this->input( $args );
            } ?>
			</div>
		<?php
        }
        
        /**
         * Sanitizes the numbers
         * Currently does nothing
         */
        function sanitize_num( $min, $max, $price, $quantity ) {
            $min      = (int) $min;
            $max      = (int) $min;
            $price    = (int) $price;
            $quantity = (int) $quantity;
            if ( $min > $max ) {
                if ( $max == 0 ) {
                    // okay
                } else {
                    bad_setting( 1 );
                }
            }
            
            if ( $price < 0 || $quantity < 0 || $min < 0 || $max < 0 ) {
                bad_setting( 2 );
            }
        }
        function badsetting( $error ) {
            if ( (int) $error == 1 ) {
                echo "<script type=\"text/javascript\">alert(\"Warning: Product Minimum > Product Maximum\");</script>";
            } else if ( (int) $error == 2 ) {
                echo "<script type=\"text/javascript\">alert(\"Warning: Invalid Setting on Grouped Products Tab\");</script>";
            }
        }
        /** 
         * Save the Data
         */
        function me_product_save_data( $post_id, $post ) {
            global $post;
            $min      = $_POST['minimum'];
            $max      = $_POST['maximum'];
            $price    = $_POST['regular_price'];
            $quantity = $_POST['quantity'];
            //sanitize_num($min,$max,$price,$quantity);
            update_post_meta( $post_id, 'maximum', $_POST['maximum'] );
            update_post_meta( $post_id, 'minimum', $_POST['minimum'] );
            update_post_meta( $post_id, 'regular_price', $_POST['regular_price'] );
            update_post_meta( $post_id, 'quantity', $_POST['quantity'] );
        }
        
        /**
         * adds css to the back-end
         *
         * @since 2.1
         * @return void
         */
        function admin_enqueue( $hook ) {
            global $post;
            
            // Don't enqueue script if not on product edit screen
            if ( $hook != 'post.php' || $post->post_type != 'product' )
                return false;
        }
        
        
        
        function input( $field ) {
            global $post;
            $args = array(
                 'id' => null,
                'type' => 'text',
                'label' => null,
                'after_label' => null,
                'class' => 'short',
                'desc' => false,
                'tip' => false,
                'value' => null,
                'min' => null,
                'max' => null,
                'step' => null,
                'placeholder' => null 
            );
            extract( wp_parse_args( $field, $args ) );
            $value = isset( $value ) ? esc_attr( $value ) : get_post_meta( $post->ID, $id, true );
            $html  = '';
            $html .= "<p class='form-field {$id}_field'>";
            $html .= "<label for='{$id}'>$label{$after_label}</label>";
            $html .= "<input type='{$type}' id='{$id}' name='{$id}' class='{$class}'";
            $html .= " value='{$value}'";
            if ( $type == 'number' ) {
                if ( !empty( $min ) )
                    $html .= " min='{$min}'";
                if ( !empty( $max ) )
                    $html .= " max='{$max}'";
                if ( !empty( $step ) )
                    $html .= " step='{$step}'";
            }
            $html .= " placeholder='{$placeholder}' />";
            if ( $desc ) {
                $html .= '<span class="description">' . $desc . '</span>';
            }
            $html .= "</p>";
            return $html;
        }
    } // end class
    
    /**
     * init the class
     */
    add_action( 'plugins_loaded', 'Jigoshop_Grouped_Products_Pro_init', 1 );
    function Jigoshop_Grouped_Products_Pro_init( ) {
        
        global $jigoshopsoftware;
        $jigoshopsoftware = new Jigoshop_Grouped_Products_Pro();
    }
} // end class exists
register_activation_hook( __FILE__, array(
     'Jigoshop_Grouped_Products_Pro',
    'jspgactivation' 
) );

if ( !function_exists( 'jigoshop_add_to_cart_action' ) ) { //make function pluggable
    function jigoshop_add_to_cart_action( $url = false ) {
        if ( empty( $_REQUEST['add-to-cart'] ) || !jigoshop::verify_nonce( 'add_to_cart' ) )
            return false;
        
        $jigoshop_options = Jigoshop_Base::get_options();
        
        $product_added = false;
        
        switch ( $_REQUEST['add-to-cart'] ) {
            
            case 'variation':
                
                if ( empty( $_REQUEST['variation_id'] ) || !is_numeric( $_REQUEST['variation_id'] ) ) {
                    jigoshop::add_error( __( 'Please choose product options&hellip;', 'jigoshop' ) );
                    wp_safe_redirect( apply_filters( 'jigoshop_product_id_add_to_cart_filter', get_permalink( $_REQUEST['product_id'] ) ) );
                    exit;
                }
                
                $product_id   = apply_filters( 'jigoshop_product_id_add_to_cart_filter', (int) $_REQUEST['product_id'] );
                $variation_id = apply_filters( 'jigoshop_variation_id_add_to_cart_filter', (int) $_REQUEST['variation_id'] );
                $quantity     = ( isset( $_REQUEST['quantity'] ) ) ? (int) $_REQUEST['quantity'] : 1;
                $attributes   = (array) maybe_unserialize( get_post_meta( $product_id, 'product_attributes', true ) );
                $variations   = array( );
                
                $all_variations_set = true;
                
                if ( get_post_meta( $product_id, 'customizable', true ) == 'yes' ) {
                    // session personalization initially set to parent product until variation selected
                    $custom_products                = (array) jigoshop_session::instance()->customized_products;
                    // transfer it to the variation
                    $custom_products[$variation_id] = $custom_products[$product_id];
                    unset( $custom_products[$product_id] );
                    jigoshop_session::instance()->customized_products = $custom_products;
                }
                
                foreach ( $attributes as $attribute ) {
                    
                    if ( !$attribute['variation'] )
                        continue;
                    
                    $attr_name = 'tax_' . sanitize_title( $attribute['name'] );
                    if ( !empty( $_REQUEST[$attr_name] ) ) {
                        $variations[$attr_name] = esc_attr( $_REQUEST[$attr_name] );
                    } else {
                        $all_variations_set = false;
                    }
                }
                
                // Add to cart validation
                $is_valid = apply_filters( 'jigoshop_add_to_cart_validation', true, $product_id, $quantity );
                
                if ( $all_variations_set && $is_valid ) {
                    jigoshop_cart::add_to_cart( $product_id, $quantity );
                    $product_added = true;
                }
                
                break;
            
            case 'group':
                //get id of parent
                if ( empty( $_REQUEST['quantity'] ) || !is_array( $_REQUEST['quantity'] ) ) {
                    break; // do nothing
                }
                
                $errors       = 0;
                //make sure childs are not greater than allowed
                $nameid       = (int) $_GET['product'];
                $nameofparent = get_the_title( $nameid );
                if ( isset( $_REQUEST['quantity'] ) && is_array( $_REQUEST['quantity'] ) ) {
                    foreach ( $_REQUEST['quantity'] as $item => $quantity ) {
                        $product_id   = (int) $item;
                        $nameofobject = get_the_title( $product_id );
                        $maxperchild  = (int) get_post_meta( $product_id, 'maximum', true );
                        $minperchild  = (int) get_post_meta( $product_id, 'minimum', true );
                        $quantity     = (int) $quantity;
                        if ( $maxperchild == '' ) {
                            $maxperchild = 0;
                        }
                        if ( $quantity > $maxperchild ) {
                            if ( $maxperchild == 0 ) {
                                // unlimited max
                            } else {
                                jigoshop::add_error( __( ( "You must have at least " . $maxperchild . " " . $nameofobject . "'s per " . $nameofparent ), 'jigoshop' ) );
                                wp_safe_redirect( apply_filters( 'jigoshop_product_id_add_to_cart_filter', get_permalink( $_REQUEST['product'] ) ) );
                                exit;
                            }
                        }
                        if ( $minperchild == '' ) {
                            $minperchild = 0;
                        }
                        if ( $quantity < $minperchild ) {
                            jigoshop::add_error( __( ( "You can not more than " . $minperchild . " " . $nameofobject . "'s per " . $nameofparent ), 'jigoshop' ) );
                            wp_safe_redirect( apply_filters( 'jigoshop_product_id_add_to_cart_filter', get_permalink( $_REQUEST['product'] ) ) );
                            exit;
                        } else {
                            //good		
                        }
                        
                    }
                }
                //not greater than min/max per product
                $total_quantity = 0;
                if ( isset( $_REQUEST['quantity'] ) && is_array( $_REQUEST['quantity'] ) ) {
                    foreach ( $_REQUEST['quantity'] as $item => $quantity ) {
                        $total_quantity = $total_quantity + $quantity;
                    }
                }
                $pid         = (int) $_GET['product'];
                $subtract    = (int) $total_quantity;
                $min         = (int) get_post_meta( $pid, 'minimum', true );
                $max         = (int) get_post_meta( $pid, 'maximum', true );
                $parentprice = (int) get_post_meta( $pid, 'regular_price', true );
                if ( ( $subtract < $min ) || ( $subtract > $max ) ) {
                    if ( $max == 0 ) {
                        //good
                    } else if ( $max == $min ) {
                        jigoshop::add_error( __( ( "You must have exactly " . $min . " products" ), 'jigoshop' ) );
                        wp_safe_redirect( apply_filters( 'jigoshop_product_id_add_to_cart_filter', get_permalink( $_REQUEST['product'] ) ) );
                        exit;
                    } else {
                        jigoshop::add_error( __( ( "You must have between " . $min . " and " . $max . " products" ), 'jigoshop' ) );
                        wp_safe_redirect( apply_filters( 'jigoshop_product_id_add_to_cart_filter', get_permalink( $_REQUEST['product'] ) ) );
                        exit;
                    }
                }
                
                if ( $errors == 0 ) {
                    if ( $parentprice > 0 ) {
                        jigoshop_cart::add_to_cart( $pid, 1 );
                    }
                    if ( isset( $_REQUEST['quantity'] ) && is_array( $_REQUEST['quantity'] ) ) {
                        foreach ( $_REQUEST['quantity'] as $item => $quantity ) {
                            
                            // Skip if no quantity
                            if ( !$quantity )
                                continue;
                            
                            $quantity = (int) $quantity;
                            // Add to cart validation
                            $is_valid = apply_filters( 'jigoshop_add_to_cart_validation', true, $product_id, $quantity );
                            // Add to the cart if passsed validation
                            if ( $is_valid ) {
                                $product_id = $item;
                                
                                jigoshop_cart::add_to_cart( $product_id, $quantity );
                                $product_added = true;
                            }
                        }
                    }
                } else {
                    $product_added = false;
                }
                
                break;
            
            default:
                
                if ( !is_numeric( $_REQUEST['add-to-cart'] ) )
                // Handle silently for now
                    break;
                
                // Get product ID & quantity
                $product_id = apply_filters( 'jigoshop_product_id_add_to_cart_filter', (int) $_GET['add-to-cart'] );
                $quantity   = ( isset( $_REQUEST['quantity'] ) ) ? (int) $_REQUEST['quantity'] : 1;
                
                // Add to cart validation
                $is_valid = apply_filters( 'jigoshop_add_to_cart_validation', true, $product_id, $quantity );
                
                // Add to the cart if passsed validation
                if ( $is_valid ) {
                    jigoshop_cart::add_to_cart( $product_id, $quantity );
                    $product_added = true;
                }
                
                break;
        }
        
        if ( !$product_added ) {
            jigoshop::add_error( __( 'Product could not be added to the cart', 'jigoshop' ) );
            return false;
        }
        
        
        switch ( $jigoshop_options->get_option( 'jigoshop_redirect_add_to_cart', 'same_page' ) ) {
            case 'same_page':
                $message = __( 'Product successfully added to your cart.', 'jigoshop' );
                $button  = __( 'View Cart &rarr;', 'jigoshop' );
                $message = '<a href="%s" class="button">' . $button . '</a> ' . $message;
                jigoshop::add_message( sprintf( $message, jigoshop_cart::get_cart_url() ) );
                break;
            
            case 'to_checkout':
                // Do nothing
                break;
            
            default:
                jigoshop::add_message( __( 'Product successfully added to your cart.', 'jigoshop' ) );
                break;
        }
        
        if ( apply_filters( 'add_to_cart_redirect', $url ) ) {
            wp_safe_redirect( $url );
            exit;
        } else if ( $jigoshop_options->get_option( 'jigoshop_redirect_add_to_cart', 'same_page' ) == 'to_checkout' && !jigoshop::has_errors() ) {
            wp_safe_redirect( jigoshop_cart::get_checkout_url() );
            exit;
        } else if ( $jigoshop_options->get_option( 'jigoshop_redirect_add_to_cart', 'to_cart' ) == 'to_cart' && !jigoshop::has_errors() ) {
            wp_safe_redirect( jigoshop_cart::get_cart_url() );
            exit;
        } else if ( wp_get_referer() ) {
            wp_safe_redirect( remove_query_arg( array(
                 'add-to-cart',
                'quantity',
                'product_id' 
            ), wp_get_referer() ) );
            exit;
        } else {
            wp_safe_redirect( home_url() );
            exit;
        }
    }
}