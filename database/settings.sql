-- Create hotel categories table
CREATE TABLE IF NOT EXISTS hotel_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create price ranges table
CREATE TABLE IF NOT EXISTS price_ranges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    min_price DECIMAL(10,2) NOT NULL,
    max_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create site settings table
CREATE TABLE IF NOT EXISTS site_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'boolean', 'image', 'color', 'json') NOT NULL DEFAULT 'text',
    category VARCHAR(50) NOT NULL DEFAULT 'general',
    label VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default site settings
INSERT INTO site_settings (setting_key, setting_value, setting_type, category, label, description) VALUES
('site_name', 'Hotel Booking System', 'text', 'general', 'Site Name', 'The name of your website'),
('site_logo', 'assets/images/logo.png', 'image', 'general', 'Site Logo', 'Your website logo'),
('site_favicon', 'assets/images/favicon.ico', 'image', 'general', 'Site Favicon', 'Your website favicon'),
('primary_color', '#007bff', 'color', 'appearance', 'Primary Color', 'Main color theme of the website'),
('secondary_color', '#6c757d', 'color', 'appearance', 'Secondary Color', 'Secondary color theme'),
('contact_email', 'contact@example.com', 'text', 'contact', 'Contact Email', 'Main contact email address'),
('contact_phone', '+1234567890', 'text', 'contact', 'Contact Phone', 'Main contact phone number'),
('address', '123 Hotel Street, City, Country', 'text', 'contact', 'Address', 'Physical address'),
('currency_symbol', '$', 'text', 'booking', 'Currency Symbol', 'Symbol for prices'),
('currency_code', 'USD', 'text', 'booking', 'Currency Code', 'Three letter currency code'),
('tax_rate', '10', 'number', 'booking', 'Tax Rate (%)', 'Default tax rate for bookings'),
('booking_advance_days', '1', 'number', 'booking', 'Minimum Advance Booking Days', 'Minimum days in advance for booking'),
('max_booking_days', '30', 'number', 'booking', 'Maximum Booking Days', 'Maximum days allowed for a single booking'),
('social_facebook', '', 'text', 'social', 'Facebook URL', 'Facebook page URL'),
('social_twitter', '', 'text', 'social', 'Twitter URL', 'Twitter profile URL'),
('social_instagram', '', 'text', 'social', 'Instagram URL', 'Instagram profile URL'),
('maintenance_mode', '0', 'boolean', 'system', 'Maintenance Mode', 'Enable maintenance mode'),
('maintenance_message', 'We are currently performing maintenance. Please check back later.', 'text', 'system', 'Maintenance Message', 'Message to display during maintenance');

-- Add category column to hotels table if not exists
ALTER TABLE hotels
ADD COLUMN IF NOT EXISTS category_id INT,
ADD CONSTRAINT fk_hotel_category
FOREIGN KEY (category_id) REFERENCES hotel_categories(id)
ON DELETE SET NULL;

-- Insert default hotel categories
INSERT INTO hotel_categories (name, description, icon) VALUES
('Luxury', 'High-end hotels with premium amenities', 'fa-star'),
('Business', 'Hotels catering to business travelers', 'fa-briefcase'),
('Resort', 'Vacation resorts and spa hotels', 'fa-umbrella-beach'),
('Boutique', 'Unique, stylish, and intimate hotels', 'fa-gem'),
('Budget', 'Affordable accommodations', 'fa-money-bill');

-- Insert default price ranges
INSERT INTO price_ranges (name, min_price, max_price) VALUES
('Budget', 0, 100),
('Economy', 101, 200),
('Mid-Range', 201, 500),
('Luxury', 501, 1000),
('Ultra-Luxury', 1001, 99999); 
CREATE TABLE IF NOT EXISTS hotel_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create price ranges table
CREATE TABLE IF NOT EXISTS price_ranges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    min_price DECIMAL(10,2) NOT NULL,
    max_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create site settings table
CREATE TABLE IF NOT EXISTS site_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'boolean', 'image', 'color', 'json') NOT NULL DEFAULT 'text',
    category VARCHAR(50) NOT NULL DEFAULT 'general',
    label VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default site settings
INSERT INTO site_settings (setting_key, setting_value, setting_type, category, label, description) VALUES
('site_name', 'Hotel Booking System', 'text', 'general', 'Site Name', 'The name of your website'),
('site_logo', 'assets/images/logo.png', 'image', 'general', 'Site Logo', 'Your website logo'),
('site_favicon', 'assets/images/favicon.ico', 'image', 'general', 'Site Favicon', 'Your website favicon'),
('primary_color', '#007bff', 'color', 'appearance', 'Primary Color', 'Main color theme of the website'),
('secondary_color', '#6c757d', 'color', 'appearance', 'Secondary Color', 'Secondary color theme'),
('contact_email', 'contact@example.com', 'text', 'contact', 'Contact Email', 'Main contact email address'),
('contact_phone', '+1234567890', 'text', 'contact', 'Contact Phone', 'Main contact phone number'),
('address', '123 Hotel Street, City, Country', 'text', 'contact', 'Address', 'Physical address'),
('currency_symbol', '$', 'text', 'booking', 'Currency Symbol', 'Symbol for prices'),
('currency_code', 'USD', 'text', 'booking', 'Currency Code', 'Three letter currency code'),
('tax_rate', '10', 'number', 'booking', 'Tax Rate (%)', 'Default tax rate for bookings'),
('booking_advance_days', '1', 'number', 'booking', 'Minimum Advance Booking Days', 'Minimum days in advance for booking'),
('max_booking_days', '30', 'number', 'booking', 'Maximum Booking Days', 'Maximum days allowed for a single booking'),
('social_facebook', '', 'text', 'social', 'Facebook URL', 'Facebook page URL'),
('social_twitter', '', 'text', 'social', 'Twitter URL', 'Twitter profile URL'),
('social_instagram', '', 'text', 'social', 'Instagram URL', 'Instagram profile URL'),
('maintenance_mode', '0', 'boolean', 'system', 'Maintenance Mode', 'Enable maintenance mode'),
('maintenance_message', 'We are currently performing maintenance. Please check back later.', 'text', 'system', 'Maintenance Message', 'Message to display during maintenance');

-- Add category column to hotels table if not exists
ALTER TABLE hotels
ADD COLUMN IF NOT EXISTS category_id INT,
ADD CONSTRAINT fk_hotel_category
FOREIGN KEY (category_id) REFERENCES hotel_categories(id)
ON DELETE SET NULL;

-- Insert default hotel categories
INSERT INTO hotel_categories (name, description, icon) VALUES
('Luxury', 'High-end hotels with premium amenities', 'fa-star'),
('Business', 'Hotels catering to business travelers', 'fa-briefcase'),
('Resort', 'Vacation resorts and spa hotels', 'fa-umbrella-beach'),
('Boutique', 'Unique, stylish, and intimate hotels', 'fa-gem'),
('Budget', 'Affordable accommodations', 'fa-money-bill');

-- Insert default price ranges
INSERT INTO price_ranges (name, min_price, max_price) VALUES
('Budget', 0, 100),
('Economy', 101, 200),
('Mid-Range', 201, 500),
('Luxury', 501, 1000),
('Ultra-Luxury', 1001, 99999); 