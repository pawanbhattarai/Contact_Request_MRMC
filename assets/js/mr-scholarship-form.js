/**
 * JavaScript for Mahendraratna Scholarship Form
 * Handles form interactions and validations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize form functionality
    initializeForm();
});

function initializeForm() {
    // Handle municipality type radio button changes
    handleMunicipalityTypeChange();
    
    // Handle form submission
    handleFormSubmission();
    
    // Handle file upload validations
    handleFileUploads();
    
    // Initialize responsive behavior
    initializeResponsiveBehavior();
}

/**
 * Handle municipality type radio button changes
 */
function handleMunicipalityTypeChange() {
    const municipalityRadios = document.querySelectorAll('input[name="municipality_type"]');
    const municipalityNameInput = document.getElementById('address_municipality');
    
    municipalityRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Update placeholder text based on selection
            if (this.value === 'न.पा.') {
                municipalityNameInput.placeholder = 'नगरपालिकाको नाम लेख्नुहोस्';
            } else if (this.value === 'गा.पा.') {
                municipalityNameInput.placeholder = 'गाउँपालिकाको नाम लेख्नुहोस्';
            }
            
            // Clear any existing validation errors
            clearFieldError(municipalityNameInput);
            clearFieldError(this);
        });
    });
}

/**
 * Handle form submission with validation
 */
function handleFormSubmission() {
    const form = document.getElementById('scholarshipForm');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        // Disable submit button immediately to prevent double submission
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.value = 'पेश गर्दै...';
        }
        
        // Add loading state
        this.classList.add('form-loading');
        
        // Validate required fields
        if (!validateForm()) {
            e.preventDefault();
            this.classList.remove('form-loading');
            
            // Re-enable submit button if validation fails
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.value = 'Submit Application';
            }
            return false;
        }
        
        // Process municipality data before submission
        processMunicipalityData();
        
        // Show submission feedback
        showSubmissionFeedback();
    });
}

/**
 * Validate form fields
 */
function validateForm() {
    let isValid = true;
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    
    // Clear previous error states
    clearAllErrors();
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFieldError(field, 'यो क्षेत्र अनिवार्य छ।');
            isValid = false;
        }
    });
    
    // Validate municipality selection
    const municipalityType = document.querySelector('input[name="municipality_type"]:checked');
    const municipalityName = document.getElementById('address_municipality');
    
    if (municipalityName.value.trim() && !municipalityType) {
        showFieldError(municipalityName, 'कृपया न.पा. वा गा.पा. छान्नुहोस्।');
        isValid = false;
    }
    
    // Validate file uploads
    if (!validateFileUploads()) {
        isValid = false;
    }
    
    return isValid;
}

/**
 * Process municipality data for submission
 */
function processMunicipalityData() {
    const municipalityType = document.querySelector('input[name="municipality_type"]:checked');
    const municipalityName = document.querySelector('input[name="municipality_name"]');
    
    if (municipalityType && municipalityName && municipalityName.value.trim()) {
        // Create hidden input with combined value for database storage
        const combinedValue = `${municipalityName.value.trim()} (${municipalityType.value})`;
        
        // Create a hidden input to store the combined value
        let hiddenInput = document.querySelector('input[name="address_municipality"]');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'address_municipality';
            document.getElementById('scholarshipForm').appendChild(hiddenInput);
        }
        hiddenInput.value = combinedValue;
    }
}

/**
 * Handle file upload validations
 */
function handleFileUploads() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            validateFileUpload(this);
        });
    });
}

/**
 * Validate individual file upload
 */
function validateFileUpload(input) {
    const file = input.files[0];
    if (!file) return true;
    
    const maxSize = 2 * 1024 * 1024; // 2MB
    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    
    // Check file size
    if (file.size > maxSize) {
        showFieldError(input, 'फाइलको साइज २ MB भन्दा कम हुनुपर्छ।');
        input.value = '';
        return false;
    }
    
    // Check file type
    if (!allowedTypes.includes(file.type)) {
        showFieldError(input, 'केवल PDF, JPG, JPEG, वा PNG फाइलहरू मात्र अपलोड गर्न सकिन्छ।');
        input.value = '';
        return false;
    }
    
    // Clear error if validation passes
    clearFieldError(input);
    return true;
}

/**
 * Validate all file uploads
 */
function validateFileUploads() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    let allValid = true;
    
    fileInputs.forEach(input => {
        if (!validateFileUpload(input)) {
            allValid = false;
        }
    });
    
    return allValid;
}

/**
 * Show field error message
 */
function showFieldError(field, message) {
    // Remove existing error
    clearFieldError(field);
    
    // Add error class
    field.classList.add('error');
    
    // Create error message element
    const errorElement = document.createElement('div');
    errorElement.className = 'error-message';
    errorElement.textContent = message;
    errorElement.style.color = '#e74c3c';
    errorElement.style.fontSize = '12px';
    errorElement.style.marginTop = '4px';
    
    // Insert error message after field
    field.parentNode.insertBefore(errorElement, field.nextSibling);
    
    // Focus on field
    field.focus();
}

/**
 * Clear field error
 */
function clearFieldError(field) {
    field.classList.remove('error');
    const errorMessage = field.parentNode.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
}

/**
 * Clear all form errors
 */
function clearAllErrors() {
    const errorMessages = document.querySelectorAll('.error-message');
    const errorFields = document.querySelectorAll('.error');
    
    errorMessages.forEach(message => message.remove());
    errorFields.forEach(field => field.classList.remove('error'));
}

/**
 * Show submission feedback
 */
function showSubmissionFeedback() {
    // This could be enhanced to show a loading spinner
    const submitBtn = document.querySelector('.submit-btn');
    if (submitBtn) {
        submitBtn.textContent = 'पेश गर्दै...';
        submitBtn.disabled = true;
    }
}

/**
 * Initialize responsive behavior
 */
function initializeResponsiveBehavior() {
    // Handle window resize for responsive adjustments
    window.addEventListener('resize', function() {
        adjustFormLayout();
    });
    
    // Initial layout adjustment
    adjustFormLayout();
}

/**
 * Adjust form layout based on screen size
 */
function adjustFormLayout() {
    const addressLayout = document.querySelector('.address-layout');
    if (!addressLayout) return;
    
    const isMobile = window.innerWidth <= 768;
    
    if (isMobile) {
        addressLayout.classList.add('mobile-layout');
    } else {
        addressLayout.classList.remove('mobile-layout');
    }
}

/**
 * Utility function to format Nepali text
 */
function formatNepaliText(text) {
    // Basic text formatting for Nepali content
    return text.trim().replace(/\s+/g, ' ');
}

/**
 * Auto-save functionality (optional enhancement)
 */
function initializeAutoSave() {
    const form = document.getElementById('mr-scholarship-form');
    if (!form) return;
    
    const formData = new FormData();
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // Save to localStorage
            localStorage.setItem(`mr_scholarship_${this.name}`, this.value);
        });
    });
}

/**
 * Load saved form data (optional enhancement)
 */
function loadSavedData() {
    const form = document.getElementById('mr-scholarship-form');
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        const savedValue = localStorage.getItem(`mr_scholarship_${input.name}`);
        if (savedValue && !input.value) {
            input.value = savedValue;
        }
    });
}

/**
 * Clear saved form data
 */
function clearSavedData() {
    const keys = Object.keys(localStorage);
    keys.forEach(key => {
        if (key.startsWith('mr_scholarship_')) {
            localStorage.removeItem(key);
        }
    });
}
