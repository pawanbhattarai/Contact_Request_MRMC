<!-- Complete Form Template -->
<div class="input-group">
    <label>२ जन्म मिति *:</label>
    <div class="input-container">
        <span>
            वि.सं. <input type="text" id="dob_year_bs" name="dob_year_bs" required size="4" placeholder="साल"
                class="input-inline"
                value="<?php echo isset($_POST['dob_year_bs']) ? esc_attr($_POST['dob_year_bs']) : ''; ?>">
            <input type="text" id="dob_month_bs" name="dob_month_bs" required size="10" placeholder="महिना"
                class="input-inline"
                value="<?php echo isset($_POST['dob_month_bs']) ? esc_attr($_POST['dob_month_bs']) : ''; ?>">
            <input type="text" id="dob_day_bs" name="dob_day_bs" required size="2" placeholder="गते"
                class="input-inline"
                value="<?php echo isset($_POST['dob_day_bs']) ? esc_attr($_POST['dob_day_bs']) : ''; ?>">
        </span>
    </div>
</div>

<!-- Address fields - all on one line -->
<div class="input-group address-group">
    <label>ठेगाना *:</label>
    <div class="input-container">
        <div class="address-fields">
            <div class="address-field">
                <label>जिल्लाः</label>
                <input type="text" name="address_district" required
                    value="<?php echo isset($_POST['address_district']) ? esc_attr($_POST['address_district']) : ''; ?>">
            </div>
            <div class="address-field">    
                <label>
                    <input type="radio" name="municipality_type" value="न.पा." 
                        <?php echo (isset($_POST['municipality_type']) && $_POST['municipality_type'] == 'न.पा.') ? 'checked' : ''; ?> required>
                    न.पा.
                    <input type="radio" name="municipality_type" value="गा.पा." 
                        <?php echo (isset($_POST['municipality_type']) && $_POST['municipality_type'] == 'गा.पा.') ? 'checked' : ''; ?>>
                    गा.पा.
                </label>
                <input type="text" name="municipality_name" required
                    placeholder="नगरपालिका/गाउँपालिका नाम"
                    value="<?php echo isset($_POST['municipality_name']) ? esc_attr($_POST['municipality_name']) : ''; ?>">
            </div>
            <div class="address-field">
                <label>वडा नं.:</label>
                <input type="text" name="address_ward" required
                    value="<?php echo isset($_POST['address_ward']) ? esc_attr($_POST['address_ward']) : ''; ?>">
            </div>
        </div>
    </div>
</div>

<div class="input-group">
    <label for="father_name">४ बुबाको नाम *:</label>
    <input type="text" id="father_name" name="father_name" required size="20" class="input-inline"
        value="<?php echo isset($_POST['father_name']) ? esc_attr($_POST['father_name']) : ''; ?>">
    पेशा: <input type="text" id="father_occupation" name="father_occupation" size="15" class="input-inline"
        value="<?php echo isset($_POST['father_occupation']) ? esc_attr($_POST['father_occupation']) : ''; ?>">
</div>

<div class="input-group">
    <label for="grandfather_name">५ बाजेको नाम *:</label>
    <input type="text" id="grandfather_name" name="grandfather_name" required size="20" class="input-inline"
        value="<?php echo isset($_POST['grandfather_name']) ? esc_attr($_POST['grandfather_name']) : ''; ?>">
    पेशा: <input type="text" id="grandfather_occupation" name="grandfather_occupation" size="15"
        class="input-inline"
        value="<?php echo isset($_POST['grandfather_occupation']) ? esc_attr($_POST['grandfather_occupation']) : ''; ?>">
</div>

<div class="input-group">
    <label>६ शैक्षिक भर्ना विवरण *:</label>
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
    <label></label>
    <span class="enrollment-fields">
        <span class="field-pair">
            <span class="field-label">भर्ना वर्ष *:</span>
            <input type="text" id="admission_year" name="admission_year" required size="4"
                class="input-inline"
                value="<?php echo isset($_POST['admission_year']) ? esc_attr($_POST['admission_year']) : ''; ?>">
        </span>
        <span class="field-pair">
            <span class="field-label">रोल नं.:</span>
            <input type="text" id="roll_no" name="roll_no" required size="10" class="input-inline"
                value="<?php echo isset($_POST['roll_no']) ? esc_attr($_POST['roll_no']) : ''; ?>">
        </span>
        <span class="field-pair">
            <span class="field-label">फोन नं.:</span>
            <input type="text" id="phone_no" name="phone_no" required size="15" class="input-inline"
                value="<?php echo isset($_POST['phone_no']) ? esc_attr($_POST['phone_no']) : ''; ?>">
        </span>
    </span>
</div>

<div class="input-group">
    <label>७ बैंकको नाम र बैंक खाता:</label>
    <span class="bank-name-section">
        <span>(बैंक खाता भएको भए)</span>
        <?php $selected_bank = isset($_POST['bank_name']) ? $_POST['bank_name'] : ''; ?>
        <span class="bank-radio-option">क. माछापुच्छ्रे बैंक <input type="radio" name="bank_name"
                value="Machhapuchchhre Bank" <?php checked($selected_bank, 'Machhapuchchhre Bank'); ?>></span>
        <span class="bank-radio-option">ख. प्रभु बैंक <input type="radio" name="bank_name"
                value="Prabhu Bank" <?php checked($selected_bank, 'Prabhu Bank'); ?>></span>
        <span class="bank-radio-option">ग. नेपाल बैंक <input type="radio" name="bank_name"
                value="Nepal Bank" <?php checked($selected_bank, 'Nepal Bank'); ?>></span>
    </span>
</div>

<div class="bank-account-container">
    <div class="bank-account-field">
        <label for="account_no">घ) खाता नं.:</label>
        <input type="text" id="account_no" name="account_no"
            value="<?php echo isset($_POST['account_no']) ? esc_attr($_POST['account_no']) : ''; ?>">
    </div>
    <div class="bank-account-field">
        <label for="bank_branch">ङ) शाखा कार्यालय:</label>
        <input type="text" id="bank_branch" name="bank_branch"
            value="<?php echo isset($_POST['bank_branch']) ? esc_attr($_POST['bank_branch']) : ''; ?>">
    </div>
</div>