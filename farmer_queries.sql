-- Farmer Advisory System Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS farmer_advisory;
USE farmer_advisory;

-- Table for storing predefined responses
CREATE TABLE IF NOT EXISTS responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    query_type VARCHAR(50) NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_query_type (query_type),
    INDEX idx_question (question(100))
);

-- Table for logging farmer queries
CREATE TABLE IF NOT EXISTS query_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    query_type VARCHAR(50) NOT NULL,
    question TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    INDEX idx_query_type (query_type),
    INDEX idx_timestamp (timestamp)
);

-- Table for storing farmer queries that need expert review
CREATE TABLE IF NOT EXISTS farmer_queries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    query_type VARCHAR(50) NOT NULL,
    question TEXT NOT NULL,
    farmer_name VARCHAR(100),
    farmer_phone VARCHAR(20),
    status ENUM('pending', 'reviewed', 'answered') DEFAULT 'pending',
    expert_answer TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_query_type (query_type)
);

-- Table for contact form messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- Insert sample data for responses table

-- Pest Control Responses
INSERT INTO responses (query_type, question, answer) VALUES
('Pest', 'How to control whiteflies on cotton?', 'Use neem oil spray (3-5ml per liter) or Imidacloprid 17.8% solution (0.5ml per liter) in early morning or evening. Spray every 7-10 days. Also use yellow sticky traps and maintain field hygiene by removing weeds.'),
('Pest', 'What to do about aphids on tomatoes?', 'Spray neem oil solution (5ml per liter) or use Dimethoate 30% EC (2ml per liter). Install reflective mulch around plants. Encourage beneficial insects like ladybugs. Remove affected leaves and maintain proper plant spacing.'),
('Pest', 'How to prevent bollworm in cotton?', 'Use pheromone traps, spray Bt (Bacillus thuringiensis) based insecticides, practice crop rotation, and plant trap crops like marigold around the field. Monitor regularly and spray during early larval stage.'),
('Pest', 'Red spider mites on chili plants', 'Increase humidity around plants, spray with miticides like Propargite or use neem oil. Remove affected leaves, maintain proper irrigation, and avoid over-fertilization with nitrogen.'),
('Pest', 'Fruit fly control in mango', 'Use methyl eugenol traps, cover fruits with paper bags, collect and destroy fallen fruits, spray Malathion 50% EC (2ml per liter) during fruit development stage.'),

-- Crop Care Responses
('Crop', 'When is the best time to plant rice?', 'Kharif season: June-July (monsoon season). Rabi season: November-December (winter season). Ensure soil temperature is 20-35°C and adequate water availability for transplanting.'),
('Crop', 'How much water does wheat need?', 'Wheat requires 450-650mm water throughout growing season. Critical stages: Crown root initiation (20-25 days), tillering (40-45 days), flowering (75-80 days), and grain filling (90-110 days). Irrigate when soil moisture drops to 50%.'),
('Crop', 'Best fertilizer for tomato plants?', 'NPK ratio 19:19:19 during vegetative stage, then 13:0:45 during flowering/fruiting. Apply 200kg DAP + 100kg Urea + 100kg MOP per hectare. Also add organic compost 10-15 tons per hectare.'),
('Crop', 'Sugarcane planting distance?', 'Row to row: 90-120cm, Plant to plant: 60cm for autumn planting, 45cm for spring planting. Use 2-3 budded setts per pit. Maintain proper drainage and irrigation channels.'),
('Crop', 'Cotton seed treatment before planting?', 'Treat seeds with Carbendazim 2g per kg seed for fungal diseases, Imidacloprid 5g per kg for sucking pests. Soak in water for 12 hours, then dry in shade before planting.'),

-- Weather Information Responses
('Weather', 'Will it rain tomorrow?', 'Please check IMD (India Meteorological Department) updates at www.imd.gov.in or use weather apps. Current forecast shows moderate rainfall expected in northern regions. Plan indoor activities for crops accordingly.'),
('Weather', 'Best weather for wheat sowing?', 'Temperature: 15-25°C, Humidity: 50-60%, Clear sunny days with cool nights. Avoid sowing during heavy rains or extreme cold. Soil temperature should be 12-25°C for optimal germination.'),
('Weather', 'How does rain affect cotton crop?', 'Moderate rain (25-50mm) is beneficial during vegetative growth. Excessive rain causes waterlogging, increases disease risk, and affects fiber quality. Ensure proper drainage and avoid harvesting during wet conditions.'),
('Weather', 'Impact of heatwave on crops?', 'Heatwave (>40°C) causes water stress, reduces photosynthesis, and affects fruit setting. Provide shade nets, increase irrigation frequency, apply mulching, and spray water on leaves during evening hours.'),
('Weather', 'Frost protection for crops?', 'Use smoke screens, sprinkler irrigation, cover crops with plastic sheets, plant windbreaks, and avoid low-lying areas. Apply anti-transpirants and ensure adequate soil moisture before frost nights.'),

-- Market Price Responses
('Market', 'What is the current price of wheat?', 'Current wheat prices: ₹2,200-2,400 per quintal (varies by region and quality). MSP: ₹2,125 per quintal. Check local mandis for exact rates. Prices typically rise during off-season (March-May).'),
('Market', 'Rice market rates today?', 'Rice prices: Basmati ₹4,500-6,000/quintal, Non-basmati ₹2,500-3,200/quintal. MSP for common paddy: ₹2,040/quintal. Quality and variety significantly affect pricing.'),
('Market', 'Cotton price forecast?', 'Current cotton prices: ₹6,800-7,200 per quintal for medium staple. International demand and monsoon conditions affect prices. Consider forward selling during peak harvest season.'),
('Market', 'Best time to sell sugarcane?', 'Peak crushing season: November-April. Prices highest in December-January (₹350-380/quintal). Coordinate with nearest sugar mill for delivery schedules and payment terms.'),
('Market', 'Vegetable market trends?', 'Tomato: ₹15-25/kg, Onion: ₹20-30/kg, Potato: ₹12-18/kg. Prices fluctuate based on season, weather, and supply. Direct marketing and farmer producer organizations can improve margins.');

-- Additional helpful responses
INSERT INTO responses (query_type, question, answer) VALUES
('Crop', 'Organic farming benefits?', 'Organic farming improves soil health, reduces chemical dependency, commands premium prices (20-30% higher), and promotes sustainable agriculture. Certification process takes 3 years. Focus on compost, bio-fertilizers, and natural pest control.'),
('Pest', 'Natural pest control methods?', 'Use neem oil, garlic-chili spray, companion planting (marigold, basil), encourage beneficial insects, crop rotation, proper sanitation, and pheromone traps. These methods are eco-friendly and cost-effective.'),
('Weather', 'Monsoon preparation for farmers?', 'Clean drainage channels, repair farm equipment, stock seeds and fertilizers, check irrigation systems, prepare for pest outbreaks, and ensure proper storage facilities for harvested crops.'),
('Market', 'How to get better crop prices?', 'Form farmer groups, direct marketing, value addition (processing), proper grading and packaging, use of technology for market information, and explore export opportunities through FPOs.');

-- Create indexes for better performance
CREATE INDEX idx_responses_search ON responses(query_type, question(50));
CREATE INDEX idx_query_logs_search ON query_logs(query_type, timestamp);

-- Show table structure
DESCRIBE responses;
DESCRIBE query_logs;
DESCRIBE farmer_queries;
DESCRIBE contact_messages;
