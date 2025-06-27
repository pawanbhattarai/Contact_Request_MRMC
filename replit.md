# Mahendraratna Scholarship Form Plugin

## Overview

This is a WordPress plugin designed to create and manage a scholarship application form for Mahendraratna Scholarship. The plugin provides a front-end form accessible via shortcode and an admin panel for managing submissions. The form is specifically designed for Nepali language support with responsive layout and comprehensive data collection.

## System Architecture

### Frontend Architecture
- **WordPress Plugin Structure**: Single-file plugin architecture with modular asset organization
- **Shortcode Implementation**: Uses WordPress shortcode system `[mahendraratna_scholarship_form]` for form display
- **Responsive Design**: CSS-based responsive layout with specific mobile and print optimizations
- **JavaScript Enhancement**: Progressive enhancement with vanilla JavaScript for form interactions

### Backend Architecture
- **WordPress Plugin Framework**: Built on WordPress plugin API with proper hooks and filters
- **Database Layer**: Custom database table for storing scholarship submissions
- **Admin Panel Integration**: WordPress admin interface for managing submissions
- **File Upload Handling**: WordPress media handling for document attachments

## Key Components

### 1. Main Plugin File (`mr-scholarship-form.php`)
- Plugin initialization and configuration
- Database table creation and management
- Shortcode registration and form rendering
- Form submission processing and validation
- Admin panel integration

### 2. Frontend Assets
- **CSS Styling** (`assets/css/mr-scholarship-style.css`): Responsive form styling with Nepali font support
- **Print Styles** (`assets/css/mr-scholarship-print.css`): Optimized styles for printing submissions
- **JavaScript** (`assets/js/mr-scholarship-form.js`): Form interactions, validation, and responsive behavior

### 3. Form Features
- **Bilingual Support**: Nepali and English name fields with proper character encoding
- **Address System**: Structured address collection with district, municipality type (न.पा./गा.पा.), and ward
- **Academic Details**: Faculty, level, and year selection dropdowns
- **File Uploads**: Document attachment capabilities
- **Responsive Layout**: Mobile-optimized form layout

## Data Flow

1. **Form Display**: Shortcode renders form with proper styling and JavaScript
2. **User Input**: Form collects scholarship application data with client-side validation
3. **Submission Processing**: Server-side validation and sanitization of form data
4. **Database Storage**: Submission data stored in custom WordPress database table
5. **Admin Review**: Submissions accessible through WordPress admin panel
6. **Print Generation**: Formatted printable versions of submissions

## External Dependencies

### WordPress Core Dependencies
- WordPress 5.2+ (minimum requirement)
- PHP 7.2+ (minimum requirement)
- WordPress database API (`$wpdb`)
- WordPress plugin API (hooks, filters, shortcodes)

### Frontend Dependencies
- **Noto Sans Devanagari Font**: For proper Nepali text rendering
- **Modern Browser Support**: CSS Flexbox, ES6 JavaScript features

### Character Encoding
- UTF-8MB4 database charset for proper Nepali character support
- Unicode normalization for consistent text handling

## Deployment Strategy

### Development Environment
- **Replit PHP Module**: Configured for PHP development with WordPress
- **Local Server**: PHP built-in server on port 5000
- **File Structure**: Standard WordPress plugin directory structure

### Production Deployment
- Standard WordPress plugin installation process
- Database table auto-creation on plugin activation
- Asset file serving through WordPress URL structure
- Plugin can be packaged as ZIP for WordPress admin upload

### Database Management
- Custom table creation with proper charset collation
- WordPress database API for cross-platform compatibility
- Activation hook ensures proper setup on plugin activation

## Changelog

- June 27, 2025. Initial setup

## User Preferences

Preferred communication style: Simple, everyday language.