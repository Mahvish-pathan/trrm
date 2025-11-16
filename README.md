# Farmer Query Support and Advisory System

A comprehensive web-based farmer advisory platform that provides instant guidance on agricultural problems including pest control, weather conditions, market prices, and crop care.

## 🌾 Features

- **Query System**: Interactive chatbot-style interface for farmer questions
- **Category-based Responses**: Organized by Pest Control, Crop Care, Weather, and Market
- **AJAX Integration**: Real-time responses without page reload
- **Responsive Design**: Works on desktop and mobile devices
- **Contact System**: Direct communication with agricultural experts
- **Modern UI**: Clean, farmer-friendly design with green and earthy themes

## 🚀 Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Server**: Apache (WAMP/XAMPP)
- **Styling**: Custom CSS with Poppins font
- **Icons**: Font Awesome 6.0

## 📁 Project Structure

```
farmer_advisory_system/
├── index.html          # Homepage
├── query.html          # Query interface page
├── about.html          # About system page
├── contact.html        # Contact form page
├── css/
│   └── style.css       # Main stylesheet
├── js/
│   └── script.js       # JavaScript functionality
├── php/
│   ├── db_connect.php  # Database connection
│   ├── get_response.php # Query response handler
│   ├── add_query.php   # Save farmer queries
│   └── contact.php     # Contact form handler
├── database/
│   └── farmer_queries.sql # Database schema and sample data
└── README.md           # This file
```

## 🛠️ Installation & Setup

### Prerequisites
- WAMP/XAMPP server installed
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Modern web browser

### Step 1: Setup Web Server
1. Install WAMP/XAMPP on your system
2. Start Apache and MySQL services
3. Place the project folder in `www` (WAMP) or `htdocs` (XAMPP) directory

### Step 2: Database Setup
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Import the database schema:
   - Click "Import" tab
   - Choose `database/farmer_queries.sql` file
   - Click "Go" to execute

Alternatively, run the SQL commands manually:
```sql
-- Create database
CREATE DATABASE farmer_advisory;

-- Import the complete schema from farmer_queries.sql
```

### Step 3: Configure Database Connection
Edit `php/db_connect.php` if needed:
```php
$servername = "localhost";
$username = "root";        // Your MySQL username
$password = "";            // Your MySQL password
$dbname = "farmer_advisory";
```

### Step 4: Access the Application
Open your web browser and navigate to:
```
http://localhost/farmer_advisory_system/
```

## 📊 Database Schema

### Tables Overview

1. **responses** - Stores predefined Q&A pairs
   - `id`, `query_type`, `question`, `answer`, `created_at`, `updated_at`

2. **query_logs** - Logs all user queries
   - `id`, `query_type`, `question`, `timestamp`, `ip_address`

3. **farmer_queries** - Stores queries needing expert review
   - `id`, `query_type`, `question`, `farmer_name`, `farmer_phone`, `status`, `expert_answer`

4. **contact_messages** - Stores contact form submissions
   - `id`, `name`, `phone`, `email`, `subject`, `message`, `status`, `created_at`

## 🎨 Design Theme

- **Colors**: Green (#2d5016), Light Green (#4a7c59), Earth Brown (#8b4513), Cream (#f5f5dc)
- **Typography**: Poppins font family
- **Style**: Modern, clean with rounded corners and soft shadows
- **Icons**: Font Awesome for consistent iconography
- **Layout**: Responsive grid system for all screen sizes

## 🔧 Usage Guide

### For Farmers
1. **Homepage**: Overview of services and quick access to features
2. **Ask Query**: Select category and type your farming question
3. **Quick Questions**: Click on pre-defined common questions
4. **Contact**: Send messages to agricultural experts
5. **About**: Learn more about the system

### For Administrators
1. **Database Management**: Add/edit responses via phpMyAdmin
2. **Query Monitoring**: Review farmer queries in `farmer_queries` table
3. **Contact Management**: Handle contact messages in `contact_messages` table

## 📱 Responsive Features

- Mobile-friendly navigation with hamburger menu
- Flexible grid layouts that adapt to screen size
- Touch-friendly buttons and form elements
- Optimized font sizes for different devices

## 🔒 Security Features

- Input sanitization and validation
- SQL injection prevention using prepared statements
- XSS protection with htmlspecialchars()
- CSRF protection ready (can be implemented)

## 🚀 Future Enhancements

1. **Weather API Integration**: Live weather data using OpenWeatherMap API
2. **Admin Panel**: Web-based interface for managing responses
3. **User Authentication**: Farmer registration and login system
4. **Multi-language Support**: Hindi and regional language support
5. **SMS Integration**: Send responses via SMS
6. **Mobile App**: React Native or Flutter mobile application
7. **AI Integration**: Machine learning for better query matching

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check MySQL service is running
   - Verify database credentials in `db_connect.php`
   - Ensure database exists

2. **AJAX Not Working**
   - Check browser console for JavaScript errors
   - Verify PHP files are accessible
   - Ensure proper file permissions

3. **Styling Issues**
   - Clear browser cache
   - Check CSS file path
   - Verify Font Awesome CDN is loading

4. **Form Submission Problems**
   - Check PHP error logs
   - Verify form field names match PHP variables
   - Ensure proper form validation

## 📞 Support

For technical support or questions:
- Email: info@krishiassistant.com
- Phone: +91 12345 67890

## 📄 License

This project is developed for educational and agricultural support purposes. Feel free to modify and distribute according to your needs.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📈 Sample Data Included

The system comes with pre-loaded sample data including:
- 20+ pest control solutions
- 15+ crop care guidelines
- 10+ weather-related advice
- 10+ market price information

## 🔄 Version History

- **v1.0** (Current): Basic query system with AJAX integration
- **v1.1** (Planned): Weather API integration
- **v2.0** (Planned): Admin panel and user authentication

---

**Made with ❤️ for Indian Farmers**
