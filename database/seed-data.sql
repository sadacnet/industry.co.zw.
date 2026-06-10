-- Sample Data for Testing
-- Run this after importing schema.sql

USE industry_co_zw;

-- ============================================
-- SAMPLE COMPANIES
-- ============================================

-- Mining Companies
INSERT INTO companies (name, industry_id, province_id, stakeholder, phone, email, website, description, is_active) VALUES
('Zimplats Mining', 11, 8, NULL, '+263 242 123456', 'info@zimplats.co.zw', 'www.zimplats.co.zw', 'Leading platinum mining company in Zimbabwe', 1),
('Murowa Diamonds', 11, 9, NULL, '+263 242 789012', 'info@murowadiamonds.co.zw', 'www.murowadiamonds.co.zw', 'Diamond mining and exploration company', 1),
('Hwange Colliery', 11, 8, NULL, '+263 242 345678', 'info@hwangecolliery.co.zw', 'www.hwangecolliery.co.zw', 'Coal mining and processing company', 1);

-- Agriculture Companies (CZI Members)
INSERT INTO companies (name, industry_id, province_id, stakeholder, phone, email, website, description, is_active) VALUES
('National Foods Zimbabwe', 3, 1, 'CZI', '+263 242 901234', 'info@natfoods.co.zw', 'www.natfoods.co.zw', 'Food processing and agricultural products', 1),
('Seed Co Zimbabwe', 3, 1, 'CZI', '+263 242 567890', 'info@seedco.co.zw', 'www.seedco.co.zw', 'Seed production and agricultural research', 1),
('Tanganda Tea Company', 3, 3, 'CZI', '+263 242 112233', 'info@tanganda.co.zw', 'www.tanganda.co.zw', 'Tea production and export company', 1);

-- Banking & Finance (Harare)
INSERT INTO companies (name, industry_id, province_id, stakeholder, phone, email, website, description, is_active) VALUES
('ZB Financial Holdings', 4, 1, 'CZI', '+263 242 445566', 'info@zb.co.zw', 'www.zb.co.zw', 'Financial services and banking group', 1),
('CBZ Holdings', 4, 1, 'CZI', '+263 242 778899', 'info@cbz.co.zw', 'www.cbz.co.zw', 'Banking and financial services', 1),
('Econet Wireless', 12, 1, 'CZI', '+263 242 112244', 'info@econet.co.zw', 'www.econet.co.zw', 'Telecommunications and mobile services', 1);

-- Construction Companies (CIFOZ Members)
INSERT INTO companies (name, industry_id, province_id, stakeholder, phone, email, website, description, is_active) VALUES
('Murray & Roberts Zimbabwe', 6, 1, 'CIFOZ', '+263 242 334455', 'info@murrob.co.zw', 'www.murrob.co.zw', 'Construction and engineering services', 1),
('Costain Zimbabwe', 6, 2, 'CIFOZ', '+263 292 667788', 'info@costain.co.zw', 'www.costain.co.zw', 'Civil engineering and building construction', 1);

-- Manufacturing (Bulawayo)
INSERT INTO companies (name, industry_id, province_id, stakeholder, phone, email, website, description, is_active) VALUES
('Treger Group of Companies', 10, 2, 'CZI', '+263 292 990011', 'info@treger.co.zw', 'www.treger.co.zw', 'Diversified manufacturing group', 1),
('Zimbabwe Steel Company', 10, 10, null, '+263 242 556677', 'info@ziscosteel.co.zw', 'www.ziscosteel.co.zw', 'Steel manufacturing and processing', 1);

-- Tourism & Hospitality (Various Provinces)
INSERT INTO companies (name, industry_id, province_id, stakeholder, phone, email, website, description, is_active) VALUES
('Victoria Falls Hotel', 13, 8, null, '+263 242 778800', 'reservations@vfhotel.co.zw', 'www.victoriafallshotel.co.zw', 'Luxury hotel at Victoria Falls', 1),
('Meikles Hotel', 13, 1, 'CZI', '+263 242 707721', 'info@meikleshotel.co.zw', 'www.meikleshotel.co.zw', 'Five-star hotel in Harare CBD', 1);

-- Healthcare (Harare & Bulawayo)
INSERT INTO companies (name, industry_id, province_id, stakeholder, phone, email, website, description, is_active) VALUES
('Corporate 24 Medical', 9, 1, null, '+263 242 334422', 'info@corp24.co.zw', 'www.corp24.co.zw', '24-hour medical and emergency services', 1),
('Mpilo Central Hospital', 9, 2, null, '+263 292 112255', 'admin@mpilo.co.zw', 'www.mpilo.co.zw', 'Major referral hospital in Bulawayo', 1);

-- ============================================
-- SAMPLE EVENTS
-- ============================================

INSERT INTO events (title, organizer, event_date, end_date, location, description, is_active) VALUES
('CZI Annual Business Conference 2026', 'CZI', '2026-06-15', '2026-06-17', 'Harare International Conference Centre', 'Annual gathering of Zimbabwe''s business leaders discussing industrial growth and economic development', 1),
('CIFOZ Construction Expo 2026', 'CIFOZ', '2026-06-20', '2026-06-22', 'ZITF Bulawayo', 'Exhibition showcasing the latest in construction technology, materials, and services', 1),
('CZI Manufacturing Workshop', 'CZI', '2026-07-01', null, 'Meikles Hotel, Harare', 'One-day workshop on modern manufacturing techniques and industry 4.0', 1),
('Mining Indaba Zimbabwe', 'CZI', '2026-07-15', '2026-07-16', 'Victoria Falls', 'Mining industry conference focusing on investment opportunities and sustainable practices', 1),
('CIFOZ Safety Training', 'CIFOZ', '2026-07-20', null, 'Harare Construction College', 'Construction safety standards and compliance training for CIFOZ members', 1),
('CZI Awards Gala 2026', 'CZI', '2026-08-10', null, 'Harare International Conference Centre', 'Annual CZI awards recognizing excellence in Zimbabwean industry', 1);

-- ============================================
-- SAMPLE TENDERS
-- ============================================

INSERT INTO tenders (title, description, closing_date, is_active) VALUES
('Supply of Mining Equipment to Zimplats', 'Supply and delivery of underground mining equipment including drilling rigs and conveyor systems', '2026-06-30', 1),
('Construction of New Warehouses - Harare', 'Construction of three industrial warehouses with office blocks in Msasa Industrial Area', '2026-06-15', 1),
('IT Infrastructure Upgrade for Government Ministries', 'Supply and installation of network infrastructure, servers, and cybersecurity solutions', '2026-07-10', 1),
('Road Rehabilitation Project - Bulawayo-Victoria Falls Highway', 'Rehabilitation and widening of 200km highway including bridge repairs', '2026-06-25', 1),
('Supply of Agricultural Equipment - Tractors', 'Supply of 50 tractors with accessories for the Ministry of Agriculture', '2026-06-20', 1),
('School Building Materials Tender', 'Supply of cement, steel, and roofing materials for 20 schools in Manicaland', '2026-05-30', 1),
('Expired Tender - Old Project', 'This tender has already expired for testing purposes', '2026-01-01', 0);

-- ============================================
-- SAMPLE ADVERTISEMENTS
-- ============================================

-- CZI Advertisements
INSERT INTO advertisements (stakeholder, type, title, file_path, file_type, link_url, display_order, is_active) VALUES
('CZI', 'banner', 'CZI Annual Conference 2026 Banner', 'uploads/banners/czi-conference-banner.jpg', 'jpg', 'www.czi.co.zw/conference', 1, 1),
('CZI', 'banner', 'Join CZI Today Banner', 'uploads/banners/czi-join-banner.jpg', 'jpg', 'www.czi.co.zw/join', 2, 1),
('CZI', 'logo', 'Sable Chemicals Logo', 'uploads/logos/sable-chemicals.png', 'png', 'www.sablechemicals.co.zw', 1, 1),
('CZI', 'logo', 'Willowvale Motors Logo', 'uploads/logos/willowvale-motors.png', 'png', 'www.willowvale.co.zw', 2, 1),
('CZI', 'flyer', 'CZI Member Benefits Brochure', 'uploads/flyers/czi-benefits-2026.pdf', 'pdf', null, 1, 1),
('CZI', 'poster', 'CZI Awards 2026 Poster', 'uploads/posters/czi-awards-2026.jpg', 'jpg', null, 1, 1);

-- CIFOZ Advertisements
INSERT INTO advertisements (stakeholder, type, title, file_path, file_type, link_url, display_order, is_active) VALUES
('CIFOZ', 'banner', 'CIFOZ Construction Expo Banner', 'uploads/banners/cifoz-expo-banner.jpg', 'jpg', 'www.cifoz.co.zw/expo', 1, 1),
('CIFOZ', 'logo', 'Nash Builders Logo', 'uploads/logos/nash-builders.png', 'png', 'www.nashbuilders.co.zw', 1, 1),
('CIFOZ', 'poster', 'Construction Safety Week Poster', 'uploads/posters/safety-week-2026.jpg', 'jpg', null, 1, 1);

-- ============================================
-- SAMPLE EXPORTS
-- ============================================

INSERT INTO exports (product_name, category, description, is_active) VALUES
('Tobacco - Virginia Gold Leaf', 'Agriculture', 'Premium quality Virginia tobacco for international markets', 1),
('Raw Diamonds', 'Minerals', 'High-quality raw diamonds from Marange diamond fields', 1),
('Platinum Group Metals', 'Minerals', 'Extracted platinum, palladium, and rhodium for export', 1),
('Processed Tea - Tanganda', 'Agriculture', 'Black tea packaged for export to European and Asian markets', 1),
('Horticultural Products - Roses', 'Agriculture', 'Fresh-cut roses and flowers for European markets', 1),
('Granite Stone Products', 'Minerals', 'Processed black granite slabs for construction industry', 1),
('Cotton Lint', 'Agriculture', 'High-grade cotton for textile industries', 1),
('Ferrochrome Alloys', 'Minerals', 'Processed ferrochrome for steel manufacturing', 1),
('Sugar - Refined', 'Agriculture', 'Refined sugar from Triangle Sugar Estates', 1),
('Leather Products', 'Manufacturing', 'Processed leather from Zimbabwean cattle for export', 1);

-- ============================================
-- SAMPLE GALLERY IMAGES
-- ============================================

INSERT INTO gallery (title, category, file_path, caption, display_order) VALUES
('CZI Conference 2025 Opening', 'events', 'uploads/gallery/czi2025-opening.jpg', 'Official opening of the CZI Annual Conference 2025', 1),
('Manufacturing Plant Tour', 'industry', 'uploads/gallery/manufacturing-plant.jpg', 'Tour of Treger Manufacturing facility in Bulawayo', 1),
('Mining Operations - Zimplats', 'industry', 'uploads/gallery/mining-ops.jpg', 'Underground mining operations at Zimplats platinum mine', 2),
('Victoria Falls Tourist View', 'tourism', 'uploads/gallery/vic-falls.jpg', 'Aerial view of Victoria Falls during peak season', 1),
('Construction Site Visit', 'industry', 'uploads/gallery/construction.jpg', 'CIFOZ members visiting new construction site in Harare', 3),
('Networking Event Cocktail', 'events', 'uploads/gallery/networking-cocktail.jpg', 'Evening networking session at CZI Awards Gala', 2);

-- ============================================
-- SAMPLE VIDEOS
-- ============================================

INSERT INTO videos (title, category, embed_url) VALUES
('Zimbabwe Mining Investment Opportunities', 'mining', 'https://www.youtube.com/embed/sample1'),
('CZI Annual Conference Highlights 2025', 'events', 'https://www.youtube.com/embed/sample2'),
('Construction Industry Growth in Zimbabwe', 'construction', 'https://www.youtube.com/embed/sample3'),
('Tourism Zimbabwe - Discover Your Adventure', 'tourism', 'https://www.youtube.com/embed/sample4'),
('Agriculture Sector Modernization', 'agriculture', 'https://www.youtube.com/embed/sample5');