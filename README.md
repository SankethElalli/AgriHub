# AgriHub - LLM Assisted Portal for Sustainable Agriculture

AgriHub is a comprehensive web-based agriculture management system that connects farmers and customers while providing intelligent crop and fertilizer recommendations using DeepSeek and AI.

## Features

### For Farmers
- Crop recommendation based on soil parameters and environmental conditions
- Fertilizer recommendation system using soil analysis
- Yield prediction for various crops
- Ability to list crops for sale
- Profile management
- Transaction history tracking

### For Customers
- Browse and purchase available crops
- View crop availability in real-time
- Secure payment integration with PayPal
- Order history tracking
- Profile management

### For Administrators
- Complete system oversight
- Manage farmer and customer accounts
- View sales reports
- Monitor transactions

## Technical Stack

- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 4
- **Backend:** PHP 7.4
- **Database:** MySQL
- **AI/ML Integration:** 
  - Deepseek AI API for crop and fertilizer recommendations
  - Deepseek AI API for crop, yield and rainfall predictions
- **Payment Integration:** PayPal API

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- XAMPP/Apache web server
- Modern web browser
- Internet connection for AI features

## Installation

1. Clone the repository to your XAMPP htdocs folder:
```bash
git clone https://github.com/SankethElalli/AgriHub.git
```

2. Import the database:
- Navigate to phpMyAdmin
- Create a new database named `agriculture_portal`
- Import the SQL file from `db/agriculture_portal.sql`

3. Configure database connection:
- Update database credentials in `sql.php`

4. Configure API keys:
- Update API keys in the respective API classes under `classes/` directory

5. Start XAMPP services (Apache & MySQL)

6. Access the application:
