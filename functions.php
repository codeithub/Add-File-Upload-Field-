/**
 * @snippet       File Upload at WooCommerce My Account Registration
 * @author        codeithub
 */
 
// --------------
// 1. Add file input to register form
 
add_action( 'woocommerce_register_form', 'codeithub_add_woo_account_registration_fields' );
  
function codeithub_add_woo_account_registration_fields() {
    
   ?>
    
   <p class="form-row validate-required" id="image" data-priority=""><label for="image" class="">Image (JPG, PNG, PDF)<abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper"><input type='file' name='image' accept='image/*,.pdf' required></span></p>
    
   <?php
       
}
 
// --------------
// 2. Validate new field
 
add_filter( 'woocommerce_registration_errors', 'codeithub_validate_woo_account_registration_fields', 10, 3 );
  
function codeithub_validate_woo_account_registration_fields( $errors, $username, $email ) {
    if ( isset( $_POST['image'] ) && empty( $_POST['image'] ) ) {
        $errors->add( 'image_error', __( 'Please provide a valid image', 'woocommerce' ) );
    }
    return $errors;
}
 
// --------------
// 3. Save new field
 
add_action( 'user_register', 'codeithub_save_woo_account_registration_fields', 1 );
  
function codeithub_save_woo_account_registration_fields( $customer_id ) {
   if ( isset( $_FILES['image'] ) ) {
      require_once( ABSPATH . 'wp-admin/includes/image.php' );
      require_once( ABSPATH . 'wp-admin/includes/file.php' );
      require_once( ABSPATH . 'wp-admin/includes/media.php' );
      $attachment_id = media_handle_upload( 'image', 0 );
      if ( is_wp_error( $attachment_id ) ) {
         update_user_meta( $customer_id, 'image', $_FILES['image'] . ": " . $attachment_id->get_error_message() );
      } else {
         update_user_meta( $customer_id, 'image', $attachment_id );
      }
   }
}
 
// --------------
// 4. Add enctype to form to allow image upload
 
add_action( 'woocommerce_register_form_tag', 'codeithub_enctype_custom_registration_forms' );
 
function codeithub_enctype_custom_registration_forms() {
   echo 'enctype="multipart/form-data"';
}
