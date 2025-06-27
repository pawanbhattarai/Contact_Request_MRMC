<?php
/**
 * Plugin Name:       Mahendraratna Scholarship Form Enhanced
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Enhanced scholarship form with responsive address layout, radio buttons for municipality types, and dropdown selections via shortcode [mahendraratna_scholarship_form] and manages submissions in the admin panel under "Scholarship".
 * Version:           1.1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Your Name
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mr-scholarship
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('MR_SCHOLARSHIP_VERSION', '1.1.0');
define('MR_SCHOLARSHIP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MR_SCHOLARSHIP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MR_SCHOLARSHIP_INCLUDES_PATH', MR_SCHOLARSHIP_PLUGIN_PATH . 'includes/');
define('MR_SCHOLARSHIP_ASSETS_PATH', MR_SCHOLARSHIP_PLUGIN_PATH . 'assets/');
define('MR_SCHOLARSHIP_ASSETS_URL', MR_SCHOLARSHIP_PLUGIN_URL . 'assets/');

global $wpdb;
define('MR_SCHOLARSHIP_TABLE_NAME', $wpdb->prefix . 'scholarship_submissions');

// --- Activation Hook ---
register_activation_hook(__FILE__, 'mr_scholarship_activate');
function mr_scholarship_activate()
{
    mr_scholarship_create_db_table();
}

// --- Database Table Creation ---
function mr_scholarship_create_db_table()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = MR_SCHOLARSHIP_TABLE_NAME;

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        submission_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        name_nepali tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        name_english tinytext DEFAULT NULL,
        dob_year_bs varchar(4) DEFAULT NULL,
        dob_month_bs varchar(20) DEFAULT NULL,
        dob_day_bs varchar(2) DEFAULT NULL,
        address_district VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        address_municipality VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        address_ward VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        father_name tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        father_occupation tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        grandfather_name tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        grandfather_occupation tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        faculty tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        ac_level tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        ac_year tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        admission_year varchar(4) DEFAULT NULL,
        roll_no varchar(20) DEFAULT NULL,
        phone_no varchar(20) DEFAULT NULL,
        bank_name varchar(50) DEFAULT NULL,
        account_no varchar(50) DEFAULT NULL,
        bank_branch tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        academic_plus2_institution text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        academic_plus2_gpa varchar(10) DEFAULT NULL,
        academic_plus2_remarks text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        academic_bachelor1_institution text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        academic_bachelor1_gpa varchar(10) DEFAULT NULL,
        academic_bachelor1_remarks text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        academic_bachelor2_institution text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        academic_bachelor2_gpa varchar(10) DEFAULT NULL,
        academic_bachelor2_remarks text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        academic_bachelor3_institution text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        academic_bachelor3_gpa varchar(10) DEFAULT NULL,
        academic_bachelor3_remarks text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        document_1 text DEFAULT NULL,
        document_2 text DEFAULT NULL,
        document_3 text DEFAULT NULL,
        submit_year_bs varchar(4) DEFAULT NULL,
        submit_month_bs varchar(20) DEFAULT NULL,
        submit_day_bs varchar(2) DEFAULT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// --- Enqueue Frontend Styles and Scripts ---
add_action('wp_enqueue_scripts', 'mr_scholarship_enqueue_assets');
function mr_scholarship_enqueue_assets()
{
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'mahendraratna_scholarship_form')) {
        wp_enqueue_style('mr-scholarship-style', MR_SCHOLARSHIP_ASSETS_URL . 'css/mr-scholarship-style.css', array(), MR_SCHOLARSHIP_VERSION);
        wp_enqueue_style('mr-scholarship-print', MR_SCHOLARSHIP_ASSETS_URL . 'css/mr-scholarship-print.css', array(), MR_SCHOLARSHIP_VERSION, 'print');
        wp_enqueue_script('mr-scholarship-script', MR_SCHOLARSHIP_ASSETS_URL . 'js/mr-scholarship-form.js', array(), MR_SCHOLARSHIP_VERSION, true);
        
        // Localize script for AJAX and translations
        wp_localize_script('mr-scholarship-script', 'mrScholarship', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mr_scholarship_nonce'),
            'strings' => array(
                'required' => __('यो क्षेत्र अनिवार्य छ।', 'mr-scholarship'),
                'fileSize' => __('फाइलको साइज २ MB भन्दा कम हुनुपर्छ।', 'mr-scholarship'),
                'fileType' => __('केवल PDF, JPG, JPEG, वा PNG फाइलहरू मात्र अपलोड गर्न सकिन्छ।', 'mr-scholarship'),
                'municipalityRequired' => __('कृपया न.पा. वा गा.पा. छान्नुहोस्।', 'mr-scholarship'),
                'submitting' => __('पेश गर्दै...', 'mr-scholarship')
            )
        ));
    }
}

// --- Shortcode Registration & Handler ---
add_shortcode('mahendraratna_scholarship_form', 'mr_scholarship_form_shortcode_handler');
function mr_scholarship_form_shortcode_handler()
{
    global $wpdb;
    $table_name = MR_SCHOLARSHIP_TABLE_NAME;
    $feedback_message = '';
    $message_class = '';

    // --- Handle Form Submission ---
    if (isset($_POST['mr_scholarship_submit']) && isset($_POST['mr_scholarship_nonce'])) {
        if (WP_DEBUG === true) {
            error_log('Scholarship form submitted');
            error_log('POST data: ' . print_r($_POST, true));
        }

        if (!wp_verify_nonce(sanitize_key($_POST['mr_scholarship_nonce']), 'mr_scholarship_form_action')) {
            $feedback_message = esc_html__('Security check failed. Please try submitting again.', 'mr-scholarship');
            $message_class = 'error';
        } else {
            // Process form data
            $data = array();
            $data['submission_time'] = current_time('mysql');

            // Enhanced field sanitization
            $fields_to_sanitize = [
                'name_nepali',
                'name_english',
                'dob_year_bs',
                'dob_month_bs',
                'dob_day_bs',
                'address_district',
                'address_municipality', // This will now contain the combined value
                'address_ward',
                'father_name',
                'father_occupation',
                'grandfather_name',
                'grandfather_occupation',
                'faculty',
                'ac_level',
                'ac_year',
                'admission_year',
                'roll_no',
                'phone_no',
                'account_no',
                'bank_branch',
                'academic_plus2_institution',
                'academic_plus2_gpa',
                'academic_plus2_remarks',
                'academic_bachelor1_institution',
                'academic_bachelor1_gpa',
                'academic_bachelor1_remarks',
                'academic_bachelor2_institution',
                'academic_bachelor2_gpa',
                'academic_bachelor2_remarks',
                'academic_bachelor3_institution',
                'academic_bachelor3_gpa',
                'academic_bachelor3_remarks',
                'submit_year_bs',
                'submit_month_bs',
                'submit_day_bs'
            ];

            foreach ($fields_to_sanitize as $field) {
                if (isset($_POST[$field])) {
                    if (strpos($field, 'remarks') !== false || strpos($field, 'institution') !== false || strpos($field, 'address_') !== false) {
                        $data[$field] = sanitize_textarea_field($_POST[$field]);
                    } elseif ($field === 'phone_no' || $field === 'roll_no' || $field === 'account_no' || strpos($field, '_gpa') !== false) {
                        $data[$field] = sanitize_text_field(preg_replace('/[^-0-9.%]/', '', $_POST[$field]));
                    } elseif (strpos($field, '_year') !== false && $field !== 'ac_year') {
                        $data[$field] = sanitize_text_field(preg_replace('/[^0-9]/', '', $_POST[$field]));
                    } elseif ($field === 'address_ward' || strpos($field, '_day') !== false) {
                        $data[$field] = sanitize_text_field(preg_replace('/[^0-9]/', '', $_POST[$field]));
                    } else {
                        $data[$field] = sanitize_text_field($_POST[$field]);
                    }
                } else {
                    $data[$field] = null;
                }
            }

            // Handle radio button for bank_name
            $data['bank_name'] = isset($_POST['bank_name']) ? sanitize_text_field($_POST['bank_name']) : null;

            // Handle file uploads
            $upload_dir = wp_upload_dir();
            $upload_basedir = $upload_dir['basedir'] . '/mr-scholarship-uploads';

            if (!file_exists($upload_basedir)) {
                wp_mkdir_p($upload_basedir);
                
                // Security files
                file_put_contents($upload_basedir . '/index.php', '<?php // Silence is golden');
                file_put_contents($upload_basedir . '/.htaccess', 
                    "Options -Indexes\n" .
                    "<FilesMatch \".(php|php5|phtml)$\">\n" .
                    "Order Allow,Deny\n" .
                    "Deny from all\n" .
                    "</FilesMatch>\n"
                );
            }

            $file_fields = ['document_1', 'document_2', 'document_3'];
            $upload_errors = array();

            foreach ($file_fields as $file_field) {
                if (!empty($_FILES[$file_field]['name'])) {
                    $allowed_file_types = ['pdf', 'jpg', 'jpeg', 'png'];
                    $max_file_size = 2 * 1024 * 1024; // 2MB

                    $file_tmp = $_FILES[$file_field]['tmp_name'];
                    $file_name = sanitize_file_name($_FILES[$file_field]['name']);
                    $file_size = $_FILES[$file_field]['size'];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    // Validate file
                    if ($file_size > $max_file_size) {
                        $upload_errors[] = sprintf(__('File %s is too large. Maximum size is 2MB.', 'mr-scholarship'), $file_name);
                        continue;
                    }

                    if (!in_array($file_ext, $allowed_file_types)) {
                        $upload_errors[] = sprintf(__('File %s has invalid type. Only PDF, JPG, JPEG, PNG are allowed.', 'mr-scholarship'), $file_name);
                        continue;
                    }

                    // Generate unique filename
                    $unique_filename = uniqid() . '_' . time() . '.' . $file_ext;
                    $file_path = $upload_basedir . '/' . $unique_filename;

                    if (move_uploaded_file($file_tmp, $file_path)) {
                        $data[$file_field] = $unique_filename;
                        
                        if (WP_DEBUG) {
                            error_log("File uploaded successfully: $file_field -> $unique_filename");
                        }
                    } else {
                        $upload_errors[] = sprintf(__('Failed to upload file %s.', 'mr-scholarship'), $file_name);
                    }
                } else {
                    $data[$file_field] = null;
                }
            }

            // Check for upload errors
            if (!empty($upload_errors)) {
                $feedback_message = implode('<br>', $upload_errors);
                $message_class = 'error';
            } else {
                // Insert data into database
                $result = $wpdb->insert($table_name, $data);

                if ($result !== false) {
                    $feedback_message = esc_html__('Your scholarship application has been submitted successfully!', 'mr-scholarship');
                    $message_class = 'success';
                    
                    if (WP_DEBUG) {
                        error_log('Scholarship application submitted successfully. ID: ' . $wpdb->insert_id);
                    }
                    
                    // Clear POST data after successful submission
                    $_POST = array();
                } else {
                    $feedback_message = esc_html__('There was an error submitting your application. Please try again.', 'mr-scholarship');
                    $message_class = 'error';
                    
                    if (WP_DEBUG) {
                        error_log('Database insertion failed: ' . $wpdb->last_error);
                    }
                }
            }
        }
    }

    // Start output buffering
    ob_start();
    ?>

    <div class="mr-scholarship-form">
        <?php if (!empty($feedback_message)) : ?>
            <div class="feedback-message <?php echo esc_attr($message_class); ?>">
                <?php echo wp_kses_post($feedback_message); ?>
            </div>
        <?php endif; ?>

        <form id="mr-scholarship-form" method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('mr_scholarship_form_action', 'mr_scholarship_nonce'); ?>

            <!-- Personal Information -->
            <div class="input-group">
                <label for="name_nepali">१ नाम (देवनागरीमा) <span class="required">*</span>:</label>
                <input type="text" id="name_nepali" name="name_nepali" required 
                       value="<?php echo isset($_POST['name_nepali']) ? esc_attr($_POST['name_nepali']) : ''; ?>">
            </div>

            <div class="input-group">
                <label for="name_english">२ नाम (अंग्रेजीमा) <span class="required">*</span>:</label>
                <input type="text" id="name_english" name="name_english" required 
                       value="<?php echo isset($_POST['name_english']) ? esc_attr($_POST['name_english']) : ''; ?>">
            </div>

            <!-- Date of Birth -->
            <div class="input-group">
                <label>३ जन्म मिति (वि.स.) <span class="required">*</span>:</label>
                <div class="academic-details">
                    <span class="field-group">
                        <label for="dob_year_bs">साल:</label>
                        <input type="text" id="dob_year_bs" name="dob_year_bs" required 
                               placeholder="२०८०" maxlength="4"
                               value="<?php echo isset($_POST['dob_year_bs']) ? esc_attr($_POST['dob_year_bs']) : ''; ?>">
                    </span>
                    <span class="field-group">
                        <label for="dob_month_bs">महिना:</label>
                        <select id="dob_month_bs" name="dob_month_bs" required>
                            <option value="">-- छान्नुहोस् --</option>
                            <option value="बैशाख" <?php echo (isset($_POST['dob_month_bs']) && $_POST['dob_month_bs'] == 'बैशाख') ? 'selected' : ''; ?>>बैशाख</option>
                            <option value="जेठ" <?php echo (isset($_POST['dob_month_bs']) && $_POST['dob_month_bs'] == 'जेठ') ? 'selected' : ''; ?>>जेठ</option>
                            <option value="आषाढ" <?php echo (isset($_POST['dob_month_bs']) && $_POST['dob_month_bs'] == 'आषाढ') ? 'selected' : ''; ?>>आषाढ</option>
                            <option value="श्रावण" <?php echo (isset($_POST['dob_month_bs']) && $_POST['dob_month_bs'] == 'श्रावण') ? 'selected' : ''; ?>>श्रावण</option>
                            <option value="भाद्र" <?php echo (isset($_POST['dob_month_bs']) && $_POST['dob_month_bs'] == 'भाद्र') ? 'selected' : ''; ?>>भाद्र</option>
                            <option value="आश्विन" <?php echo (isset($_POST['dob_month_bs']) && $_POST['dob_month_bs'] == 'आश्विन') ? 'selected' : ''; ?>>आश्विन</option>
                            <option value="कार्तिक" <?php echo (isset($_POST['dob_month_bs']) && $_POST['dob_month_bs'] == 'कार्तिक') ? 'selected' : ''; ?>>कार्तिक</option>
                            <option value="मंसिर" <?php echo (isset($_POST['dob_month_bs']) && $_POST['dob_month_bs'] == 'मंसिर') ? 'selected' : ''; ?>>मंसिर</option>
                            <option value="पुष" <?php echo (isset($_POST['dob_month_bs']) && $_POST['dob_month_bs'] == 'पुष') ? 'selected' : ''; ?>>पुष</option>
                            <option value="माघ" <?php echo (isset($_POST['dob_month_bs']) && $_POST['dob_month_bs'] == 'माघ') ? 'selected' : ''; ?>>माघ</option>
                            <option value="फाल्गुन" <?php echo (isset($_POST['dob_month_bs']) && $_POST['dob_month_bs'] == 'फाल्गुन') ? 'selected' : ''; ?>>फाल्गुन</option>
                            <option value="चैत्र" <?php echo (isset($_POST['dob_month_bs']) && $_POST['dob_month_bs'] == 'चैत्र') ? 'selected' : ''; ?>>चैत्र</option>
                        </select>
                    </span>
                    <span class="field-group">
                        <label for="dob_day_bs">गते:</label>
                        <input type="text" id="dob_day_bs" name="dob_day_bs" required 
                               placeholder="१५" maxlength="2"
                               value="<?php echo isset($_POST['dob_day_bs']) ? esc_attr($_POST['dob_day_bs']) : ''; ?>">
                    </span>
                </div>
            </div>

            <!-- Enhanced Address Section -->
            <div class="input-group">
                <label>४ ठेगाना <span class="required">*</span>:</label>
                <div class="address-layout">
                    <div class="address-field district">
                        <label for="address_district">जिल्लाः</label>
                        <input type="text" id="address_district" name="address_district" required
                               value="<?php echo isset($_POST['address_district']) ? esc_attr($_POST['address_district']) : ''; ?>">
                    </div>
                    
                    <div class="address-field municipality-section">
                        <div class="municipality-radio-group">
                            <div class="radio-option">
                                <input type="radio" id="municipality_napa" name="municipality_type" value="न.पा."
                                       <?php echo (isset($_POST['municipality_type']) && $_POST['municipality_type'] == 'न.पा.') ? 'checked' : ''; ?>>
                                <label for="municipality_napa">न.पा.</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="municipality_gapa" name="municipality_type" value="गा.पा."
                                       <?php echo (isset($_POST['municipality_type']) && $_POST['municipality_type'] == 'गा.पा.') ? 'checked' : ''; ?>>
                                <label for="municipality_gapa">गा.पा.</label>
                            </div>
                        </div>
                        <div class="municipality-input">
                            <input type="text" id="address_municipality" name="address_municipality" required
                                   placeholder="नाम लेख्नुहोस्"
                                   value="<?php echo isset($_POST['address_municipality']) ? esc_attr($_POST['address_municipality']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="address-field ward">
                        <label for="address_ward">वडा नं.:</label>
                        <input type="text" id="address_ward" name="address_ward" required
                               placeholder="१"
                               value="<?php echo isset($_POST['address_ward']) ? esc_attr($_POST['address_ward']) : ''; ?>">
                    </div>
                </div>
            </div>

            <!-- Family Information -->
            <div class="input-group">
                <label for="father_name">५ बुबाको नाम थर <span class="required">*</span>:</label>
                <input type="text" id="father_name" name="father_name" required
                       value="<?php echo isset($_POST['father_name']) ? esc_attr($_POST['father_name']) : ''; ?>">
            </div>

            <div class="input-group">
                <label for="father_occupation">बुबाको पेशा:</label>
                <input type="text" id="father_occupation" name="father_occupation"
                       value="<?php echo isset($_POST['father_occupation']) ? esc_attr($_POST['father_occupation']) : ''; ?>">
            </div>

            <div class="input-group">
                <label for="grandfather_name">हजुरबुबाको नाम थर:</label>
                <input type="text" id="grandfather_name" name="grandfather_name"
                       value="<?php echo isset($_POST['grandfather_name']) ? esc_attr($_POST['grandfather_name']) : ''; ?>">
            </div>

            <div class="input-group">
                <label for="grandfather_occupation">हजुरबुबाको पेशा:</label>
                <input type="text" id="grandfather_occupation" name="grandfather_occupation"
                       value="<?php echo isset($_POST['grandfather_occupation']) ? esc_attr($_POST['grandfather_occupation']) : ''; ?>">
            </div>

            <!-- Academic Information -->
            <div class="input-group">
                <label>६ शैक्षिक भर्ना विवरण <span class="required">*</span>:</label>
                <div class="academic-details">
                    <span class="field-group">
                        <label for="faculty">संकायः</label>
                        <select id="faculty" name="faculty" required class="input-inline">
                            <option value="">-- छान्नुहोस् --</option>
                            <option value="व्यवस्थापन" <?php echo (isset($_POST['faculty']) && $_POST['faculty'] == 'व्यवस्थापन') ? 'selected' : ''; ?>>व्यवस्थापन</option>
                            <option value="मानविकी" <?php echo (isset($_POST['faculty']) && $_POST['faculty'] == 'मानविकी') ? 'selected' : ''; ?>>मानविकी</option>
                            <option value="शिक्षाशास्त्र" <?php echo (isset($_POST['faculty']) && $_POST['faculty'] == 'शिक्षाशास्त्र') ? 'selected' : ''; ?>>शिक्षाशास्त्र</option>
                        </select>
                    </span>
                    <span class="field-group">
                        <label for="ac_level">तहः</label>
                        <select id="ac_level" name="ac_level" required class="input-inline">
                            <option value="">-- छान्नुहोस् --</option>
                            <option value="स्नातक" <?php echo (isset($_POST['ac_level']) && $_POST['ac_level'] == 'स्नातक') ? 'selected' : ''; ?>>स्नातक</option>
                        </select>
                    </span>
                    <span class="field-group">
                        <label for="ac_year">वर्षः</label>
                        <select id="ac_year" name="ac_year" class="input-inline">
                            <option value="">-- छान्नुहोस् --</option>
                            <option value="पहिलो वर्ष" <?php echo (isset($_POST['ac_year']) && $_POST['ac_year'] == 'पहिलो वर्ष') ? 'selected' : ''; ?>>पहिलो वर्ष</option>
                            <option value="दोस्रो वर्ष" <?php echo (isset($_POST['ac_year']) && $_POST['ac_year'] == 'दोस्रो वर्ष') ? 'selected' : ''; ?>>दोस्रो वर्ष</option>
                            <option value="तेस्रो वर्ष" <?php echo (isset($_POST['ac_year']) && $_POST['ac_year'] == 'तेस्रो वर्ष') ? 'selected' : ''; ?>>तेस्रो वर्ष</option>
                            <option value="चौथो वर्ष" <?php echo (isset($_POST['ac_year']) && $_POST['ac_year'] == 'चौथो वर्ष') ? 'selected' : ''; ?>>चौथो वर्ष</option>
                        </select>
                    </span>
                </div>
            </div>

            <div class="input-group">
                <label for="admission_year">७ भर्ना भएको साल (ई.स.) <span class="required">*</span>:</label>
                <input type="text" id="admission_year" name="admission_year" required
                       placeholder="२०२३" maxlength="4"
                       value="<?php echo isset($_POST['admission_year']) ? esc_attr($_POST['admission_year']) : ''; ?>">
            </div>

            <div class="input-group">
                <label for="roll_no">८ रोल नम्बर <span class="required">*</span>:</label>
                <input type="text" id="roll_no" name="roll_no" required
                       value="<?php echo isset($_POST['roll_no']) ? esc_attr($_POST['roll_no']) : ''; ?>">
            </div>

            <div class="input-group">
                <label for="phone_no">९ मोबाइल नम्बर <span class="required">*</span>:</label>
                <input type="tel" id="phone_no" name="phone_no" required
                       placeholder="९८१२३४५६७८"
                       value="<?php echo isset($_POST['phone_no']) ? esc_attr($_POST['phone_no']) : ''; ?>">
            </div>

            <!-- Bank Information -->
            <div class="input-group">
                <label>१० बैंक विवरण <span class="required">*</span>:</label>
                <div class="bank-selection">
                    <div class="bank-options">
                        <div class="bank-option">
                            <input type="radio" id="bank_nabil" name="bank_name" value="नबिल बैंक"
                                   <?php echo (isset($_POST['bank_name']) && $_POST['bank_name'] == 'नबिल बैंक') ? 'checked' : ''; ?>>
                            <label for="bank_nabil">नबिल बैंक</label>
                        </div>
                        <div class="bank-option">
                            <input type="radio" id="bank_nic" name="bank_name" value="एन आई सी एशिया बैंक"
                                   <?php echo (isset($_POST['bank_name']) && $_POST['bank_name'] == 'एन आई सी एशिया बैंक') ? 'checked' : ''; ?>>
                            <label for="bank_nic">एन आई सी एशिया बैंक</label>
                        </div>
                        <div class="bank-option">
                            <input type="radio" id="bank_other" name="bank_name" value="अन्य"
                                   <?php echo (isset($_POST['bank_name']) && $_POST['bank_name'] == 'अन्य') ? 'checked' : ''; ?>>
                            <label for="bank_other">अन्य</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="input-group">
                <label for="account_no">खाता नम्बर <span class="required">*</span>:</label>
                <input type="text" id="account_no" name="account_no" required
                       value="<?php echo isset($_POST['account_no']) ? esc_attr($_POST['account_no']) : ''; ?>">
            </div>

            <div class="input-group">
                <label for="bank_branch">शाखा <span class="required">*</span>:</label>
                <input type="text" id="bank_branch" name="bank_branch" required
                       value="<?php echo isset($_POST['bank_branch']) ? esc_attr($_POST['bank_branch']) : ''; ?>">
            </div>

            <!-- Academic Records Table -->
            <div class="input-group">
                <label>११ शैक्षिक विवरण:</label>
                <table class="academic-table">
                    <thead>
                        <tr>
                            <th>तह</th>
                            <th>शिक्षण संस्थाको नाम</th>
                            <th>जी.पी.ए./प्रतिशत</th>
                            <th>उत्तीर्ण साल/कैफियत</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>+२</td>
                            <td><input type="text" name="academic_plus2_institution" 
                                       value="<?php echo isset($_POST['academic_plus2_institution']) ? esc_attr($_POST['academic_plus2_institution']) : ''; ?>"></td>
                            <td><input type="text" name="academic_plus2_gpa" 
                                       value="<?php echo isset($_POST['academic_plus2_gpa']) ? esc_attr($_POST['academic_plus2_gpa']) : ''; ?>"></td>
                            <td><input type="text" name="academic_plus2_remarks" 
                                       value="<?php echo isset($_POST['academic_plus2_remarks']) ? esc_attr($_POST['academic_plus2_remarks']) : ''; ?>"></td>
                        </tr>
                        <tr>
                            <td>स्नातक प्रथम वर्ष</td>
                            <td><input type="text" name="academic_bachelor1_institution" 
                                       value="<?php echo isset($_POST['academic_bachelor1_institution']) ? esc_attr($_POST['academic_bachelor1_institution']) : ''; ?>"></td>
                            <td><input type="text" name="academic_bachelor1_gpa" 
                                       value="<?php echo isset($_POST['academic_bachelor1_gpa']) ? esc_attr($_POST['academic_bachelor1_gpa']) : ''; ?>"></td>
                            <td><input type="text" name="academic_bachelor1_remarks" 
                                       value="<?php echo isset($_POST['academic_bachelor1_remarks']) ? esc_attr($_POST['academic_bachelor1_remarks']) : ''; ?>"></td>
                        </tr>
                        <tr>
                            <td>स्नातक द्वितीय वर्ष</td>
                            <td><input type="text" name="academic_bachelor2_institution" 
                                       value="<?php echo isset($_POST['academic_bachelor2_institution']) ? esc_attr($_POST['academic_bachelor2_institution']) : ''; ?>"></td>
                            <td><input type="text" name="academic_bachelor2_gpa" 
                                       value="<?php echo isset($_POST['academic_bachelor2_gpa']) ? esc_attr($_POST['academic_bachelor2_gpa']) : ''; ?>"></td>
                            <td><input type="text" name="academic_bachelor2_remarks" 
                                       value="<?php echo isset($_POST['academic_bachelor2_remarks']) ? esc_attr($_POST['academic_bachelor2_remarks']) : ''; ?>"></td>
                        </tr>
                        <tr>
                            <td>स्नातक तृतीय वर्ष</td>
                            <td><input type="text" name="academic_bachelor3_institution" 
                                       value="<?php echo isset($_POST['academic_bachelor3_institution']) ? esc_attr($_POST['academic_bachelor3_institution']) : ''; ?>"></td>
                            <td><input type="text" name="academic_bachelor3_gpa" 
                                       value="<?php echo isset($_POST['academic_bachelor3_gpa']) ? esc_attr($_POST['academic_bachelor3_gpa']) : ''; ?>"></td>
                            <td><input type="text" name="academic_bachelor3_remarks" 
                                       value="<?php echo isset($_POST['academic_bachelor3_remarks']) ? esc_attr($_POST['academic_bachelor3_remarks']) : ''; ?>"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Document Uploads -->
            <div class="input-group">
                <label>१२ आवश्यक कागजातहरू:</label>
                <div class="file-upload">
                    <label for="document_1">नागरिकताको प्रतिलिपि (PDF/Image):</label>
                    <input type="file" id="document_1" name="document_1" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <div class="file-upload">
                    <label for="document_2">शैक्षिक प्रमाणपत्र (PDF/Image):</label>
                    <input type="file" id="document_2" name="document_2" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <div class="file-upload">
                    <label for="document_3">अन्य कागजात (PDF/Image):</label>
                    <input type="file" id="document_3" name="document_3" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>

            <!-- Submission Date -->
            <div class="input-group">
                <label>१३ निवेदन दिएको मिति (वि.स.) <span class="required">*</span>:</label>
                <div class="academic-details">
                    <span class="field-group">
                        <label for="submit_year_bs">साल:</label>
                        <input type="text" id="submit_year_bs" name="submit_year_bs" required 
                               placeholder="२०८१" maxlength="4"
                               value="<?php echo isset($_POST['submit_year_bs']) ? esc_attr($_POST['submit_year_bs']) : ''; ?>">
                    </span>
                    <span class="field-group">
                        <label for="submit_month_bs">महिना:</label>
                        <select id="submit_month_bs" name="submit_month_bs" required>
                            <option value="">-- छान्नुहोस् --</option>
                            <option value="बैशाख" <?php echo (isset($_POST['submit_month_bs']) && $_POST['submit_month_bs'] == 'बैशाख') ? 'selected' : ''; ?>>बैशाख</option>
                            <option value="जेठ" <?php echo (isset($_POST['submit_month_bs']) && $_POST['submit_month_bs'] == 'जेठ') ? 'selected' : ''; ?>>जेठ</option>
                            <option value="आषाढ" <?php echo (isset($_POST['submit_month_bs']) && $_POST['submit_month_bs'] == 'आषाढ') ? 'selected' : ''; ?>>आषाढ</option>
                            <option value="श्रावण" <?php echo (isset($_POST['submit_month_bs']) && $_POST['submit_month_bs'] == 'श्रावण') ? 'selected' : ''; ?>>श्रावण</option>
                            <option value="भाद्र" <?php echo (isset($_POST['submit_month_bs']) && $_POST['submit_month_bs'] == 'भाद्र') ? 'selected' : ''; ?>>भाद्र</option>
                            <option value="आश्विन" <?php echo (isset($_POST['submit_month_bs']) && $_POST['submit_month_bs'] == 'आश्विन') ? 'selected' : ''; ?>>आश्विन</option>
                            <option value="कार्तिक" <?php echo (isset($_POST['submit_month_bs']) && $_POST['submit_month_bs'] == 'कार्तिक') ? 'selected' : ''; ?>>कार्तिक</option>
                            <option value="मंसिर" <?php echo (isset($_POST['submit_month_bs']) && $_POST['submit_month_bs'] == 'मंसिर') ? 'selected' : ''; ?>>मंसिर</option>
                            <option value="पुष" <?php echo (isset($_POST['submit_month_bs']) && $_POST['submit_month_bs'] == 'पुष') ? 'selected' : ''; ?>>पुष</option>
                            <option value="माघ" <?php echo (isset($_POST['submit_month_bs']) && $_POST['submit_month_bs'] == 'माघ') ? 'selected' : ''; ?>>माघ</option>
                            <option value="फाल्गुन" <?php echo (isset($_POST['submit_month_bs']) && $_POST['submit_month_bs'] == 'फाल्गुन') ? 'selected' : ''; ?>>फाल्गुन</option>
                            <option value="चैत्र" <?php echo (isset($_POST['submit_month_bs']) && $_POST['submit_month_bs'] == 'चैत्र') ? 'selected' : ''; ?>>चैत्र</option>
                        </select>
                    </span>
                    <span class="field-group">
                        <label for="submit_day_bs">गते:</label>
                        <input type="text" id="submit_day_bs" name="submit_day_bs" required 
                               placeholder="१५" maxlength="2"
                               value="<?php echo isset($_POST['submit_day_bs']) ? esc_attr($_POST['submit_day_bs']) : ''; ?>">
                    </span>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="submit-section">
                <button type="submit" name="mr_scholarship_submit" class="submit-btn">
                    निवेदन पेश गर्नुहोस्
                </button>
            </div>
        </form>
    </div>

    <?php
    return ob_get_clean();
}

// --- Admin Menu for Managing Submissions ---
add_action('admin_menu', 'mr_scholarship_admin_menu');
function mr_scholarship_admin_menu()
{
    add_menu_page(
        'Scholarship Submissions',
        'Scholarship',
        'manage_options',
        'mr-scholarship-submissions',
        'mr_scholarship_admin_page',
        'dashicons-graduation-cap',
        30
    );
}

function mr_scholarship_admin_page()
{
    global $wpdb;
    $table_name = MR_SCHOLARSHIP_TABLE_NAME;
    
    // Handle deletion
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if (wp_verify_nonce($_GET['_wpnonce'], 'delete_submission_' . $id)) {
            $wpdb->delete($table_name, array('id' => $id));
            echo '<div class="notice notice-success"><p>Submission deleted successfully.</p></div>';
        }
    }
    
    // Get all submissions
    $submissions = $wpdb->get_results("SELECT * FROM $table_name ORDER BY submission_time DESC");
    
    ?>
    <div class="wrap">
        <h1>Scholarship Submissions</h1>
        
        <?php if (empty($submissions)) : ?>
            <p>No submissions found.</p>
        <?php else : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name (Nepali)</th>
                        <th>Name (English)</th>
                        <th>Phone</th>
                        <th>Faculty</th>
                        <th>Submission Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $submission) : ?>
                        <tr>
                            <td><?php echo esc_html($submission->id); ?></td>
                            <td><?php echo esc_html($submission->name_nepali); ?></td>
                            <td><?php echo esc_html($submission->name_english); ?></td>
                            <td><?php echo esc_html($submission->phone_no); ?></td>
                            <td><?php echo esc_html($submission->faculty); ?></td>
                            <td><?php echo esc_html($submission->submission_time); ?></td>
                            <td>
                                <a href="?page=mr-scholarship-submissions&action=view&id=<?php echo $submission->id; ?>" class="button">View</a>
                                <a href="?page=mr-scholarship-submissions&action=delete&id=<?php echo $submission->id; ?>&_wpnonce=<?php echo wp_create_nonce('delete_submission_' . $submission->id); ?>" 
                                   class="button button-small" 
                                   onclick="return confirm('Are you sure you want to delete this submission?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}
?>
