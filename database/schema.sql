-- This creates our database and tells MySQL to use it
CREATE DATABASE IF NOT EXISTS industry_co_zw
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE industry_co_zw;

-- This table stores all 14 industry sectors
CREATE TABLE industries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(10),
    description TEXT,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE provinces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    opportunities TEXT,
    key_industries TEXT,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    industry_id INT NOT NULL,
    province_id INT NOT NULL,
    city VARCHAR(100),
    address TEXT,
    stakeholder VARCHAR(20) DEFAULT NULL,
    listing_type ENUM('industry', 'partner') DEFAULT 'industry',
    phone VARCHAR(20),
    email VARCHAR(255),
    website VARCHAR(255),
    logo VARCHAR(255),
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    is_featured TINYINT(1) DEFAULT 0,

    -- Showcase Fields
    showcase_active TINYINT(1) DEFAULT 0,
    showcase_tier ENUM('platinum', 'gold', 'silver') DEFAULT NULL,
    showcase_order INT DEFAULT 0,
    showcase_tagline VARCHAR(255),
    showcase_full_description TEXT,

    -- CIFOZ Specific Fields
    cifoz_member_type VARCHAR(100),
    cifoz_category_raw TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (industry_id) REFERENCES industries(id) ON DELETE CASCADE,
    FOREIGN KEY (province_id) REFERENCES provinces(id) ON DELETE CASCADE,
    INDEX idx_stakeholder (stakeholder),
    INDEX idx_industry (industry_id),
    INDEX idx_province (province_id),
    INDEX idx_listing_type (listing_type)
);

CREATE TABLE advertisements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stakeholder VARCHAR(20) NOT NULL,
    type ENUM('logo', 'banner', 'flyer', 'poster') NOT NULL,
    title VARCHAR(200),
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(10),
    link_url VARCHAR(255),
    display_order INT DEFAULT 0,
    views INT DEFAULT 0,
    clicks INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_stakeholder_type (stakeholder, type)
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    organizer VARCHAR(20) NOT NULL,
    event_date DATE NOT NULL,
    end_date DATE DEFAULT NULL,
    location VARCHAR(200),
    description TEXT,
    poster VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_organizer_date (organizer, event_date)
);

CREATE TABLE tenders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tender_number VARCHAR(50),
    title VARCHAR(200) NOT NULL,
    issuing_organization VARCHAR(200),
    category VARCHAR(100),
    description TEXT,
    budget DECIMAL(15,2),
    location VARCHAR(200),
    closing_date DATE NOT NULL,
    bid_opening_date DATE,
    contact_email VARCHAR(255),
    contact_phone VARCHAR(50),
    submission_requirements TEXT,
    eligibility_criteria TEXT,
    document_url VARCHAR(255),
    document_url2 VARCHAR(255),
    document_url3 VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_closing_date (closing_date),
    INDEX idx_category (category)
);

CREATE TABLE exports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(200) NOT NULL,
    category VARCHAR(100),
    company VARCHAR(200),
    description TEXT,
    specs TEXT,
    price DECIMAL(15,2),
    moq INT DEFAULT 1,
    image VARCHAR(255),
    rating DECIMAL(3,2) DEFAULT 0,
    reviews INT DEFAULT 0,
    exports_to TEXT,
    certifications TEXT,
    verified TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category)
);

CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200),
    category VARCHAR(100),
    file_path VARCHAR(255) NOT NULL,
    caption TEXT,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    category VARCHAR(100),
    embed_url VARCHAR(500) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contact_enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    recaptcha_score DECIMAL(3,2),
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- CIFOZ Specific Categories Table
CREATE TABLE cifoz_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT DEFAULT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(200) NOT NULL,
    level INT DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES cifoz_categories(id) ON DELETE CASCADE
);

-- Company to CIFOZ Category Mapping
CREATE TABLE company_cifoz_categories (
    company_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (company_id, category_id),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES cifoz_categories(id) ON DELETE CASCADE
);

-- Industry Showcase Table (Used in industries.php)
CREATE TABLE industry_showcase (
    id INT AUTO_INCREMENT PRIMARY KEY,
    industry_slug VARCHAR(50) NOT NULL,
    company_id INT NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    INDEX idx_industry_slug (industry_slug)
);

INSERT INTO industries (slug, name, icon, description, display_order) VALUES
('auto', 'Auto', '🚗', 'Automotive industry including vehicle sales, repairs, and parts manufacturing', 1),
('accommodation', 'Accommodation', '🏨', 'Hotels, lodges, and accommodation services across Zimbabwe', 2),
('agriculture', 'Agriculture', '🌾', 'Farming, crop production, livestock, and agricultural services', 3),
('banking-finance', 'Banking & Finance', '🏦', 'Banks, microfinance, insurance, and financial services', 4),
('biotechnology', 'Biotechnology', '🧬', 'Biotech research, development, and applications', 5),
('construction', 'Construction', '🏗️', 'Building, civil engineering, and construction services', 6),
('education', 'Education', '📚', 'Schools, universities, colleges, and educational services', 7),
('energy-power', 'Energy & Power', '⚡', 'Electricity generation, distribution, and renewable energy', 8),
('healthcare', 'Healthcare', '🏥', 'Hospitals, clinics, pharmaceutical, and medical services', 9),
('manufacturing', 'Manufacturing', '🏭', 'Industrial manufacturing and production', 10),
('mining', 'Mining', '⛏️', 'Mineral extraction, mining operations, and quarrying', 11),
('technology-ict', 'Technology & ICT', '💻', 'Information technology, software, and telecommunications', 12),
('tourism-hospitality', 'Tourism & Hospitality', '🎯', 'Tourism operators, travel agencies, and hospitality', 13),
('transport-logistics', 'Transport & Logistics', '🚛', 'Transportation, logistics, and supply chain services', 14);

INSERT INTO provinces (slug, name, opportunities, key_industries, display_order) VALUES
('harare', 'Harare', 'Capital city with diverse business opportunities, financial services hub, and growing tech sector', 'Banking & Finance, Technology & ICT, Manufacturing, Construction', 1),
('bulawayo', 'Bulawayo', 'Industrial hub with strong manufacturing base, cultural tourism, and educational institutions', 'Manufacturing, Education, Tourism & Hospitality, Transport & Logistics', 2),
('manicaland', 'Manicaland', 'Agricultural heartland with timber, tea, coffee production, and tourism potential', 'Agriculture, Mining, Tourism & Hospitality, Education', 3),
('mashonaland-central', 'Mashonaland Central', 'Mining region with agricultural potential, tobacco farming, and mineral deposits', 'Mining, Agriculture, Manufacturing, Construction', 4),
('mashonaland-east', 'Mashonaland East', 'Agricultural production, horticulture, and proximity to Harare markets', 'Agriculture, Manufacturing, Transport & Logistics, Education', 5),
('mashonaland-west', 'Mashonaland West', 'Tourism attractions, Lake Kariba, mining operations, and commercial farming', 'Tourism & Hospitality, Mining, Agriculture, Energy & Power', 6),
('masvingo', 'Masvingo', 'Great Zimbabwe heritage site, agriculture, and growing industrial base', 'Tourism & Hospitality, Agriculture, Mining, Construction', 7),
('matabeleland-north', 'Matabeleland North', 'Victoria Falls tourism, wildlife conservation, coal mining, and timber', 'Tourism & Hospitality, Mining, Agriculture, Construction', 8),
('matabeleland-south', 'Matabeleland South', 'Ranching, mining, border trade with South Africa and Botswana', 'Mining, Agriculture, Transport & Logistics, Manufacturing', 9),
('midlands', 'Midlands', 'Central location advantage, mining, manufacturing, and educational institutions', 'Mining, Manufacturing, Education, Agriculture', 10);

INSERT INTO admin_users (username, password_hash, email) VALUES
('admin', '$2y$10$YourNewHashHere', 'admin@industry.co.zw');