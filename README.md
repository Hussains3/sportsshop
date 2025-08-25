# 🏪 Sports Shop Management System

A comprehensive Laravel-based Point of Sale (POS) and inventory management system designed for sports equipment stores. Built with Laravel 11, Tailwind CSS, and modern web technologies.

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-red.svg" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.1+-blue.svg" alt="PHP Version">
  <img src="https://img.shields.io/badge/Tailwind-CSS-38B2AC.svg" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Dark-Mode-Enabled-1F2937.svg" alt="Dark Mode">
</p>

## ✨ Features

### 🛍️ Point of Sale (POS) System
- **Real-time Product Search** - Instant product lookup by name or SKU
- **Batch Selection** - Choose from available stock batches with different pricing
- **Dynamic Cart Management** - Add/remove items with quantity updates
- **Multiple Payment Methods** - Cash, Card, Mobile Money support
- **Change Calculation** - Automatic change calculation for cash payments
- **Receipt Generation** - Professional receipts with transaction details
- **Sales History** - Complete transaction tracking and history

### 📦 Inventory Management
- **Product Management** - Add, edit, and organize products with categories
- **Batch Tracking** - Track individual batches with purchase and selling prices
- **Stock Monitoring** - Real-time stock levels with low stock alerts
- **Category System** - Hierarchical categories and subcategories
- **Image Management** - Product image upload and storage
- **SKU Management** - Unique product identification system

### 📊 Comprehensive Reporting
- **Sales Reports** - Daily, monthly, and custom date range reports
- **Inventory Reports** - Stock status, category breakdown, and value analysis
- **Top Products** - Best-selling products with ranking and insights
- **Financial Reports** - Revenue, profit, and trend analysis
- **CSV Export** - Export sales data for external analysis
- **Profit Analytics** - Detailed profit margin calculations

### 🎯 Dashboard & Analytics
- **Real-time Metrics** - Today's sales, monthly revenue, and stock alerts
- **Inventory Alerts** - Low stock and out-of-stock notifications
- **Quick Actions** - Fast access to common operations
- **Recent Sales** - Latest transactions with quick view options
- **Stock Value** - Total inventory value calculations

### 🎨 User Experience
- **Dark Mode Support** - Complete dark theme throughout the application
- **Responsive Design** - Works perfectly on desktop, tablet, and mobile
- **Intuitive Navigation** - Clean and organized interface
- **Search & Filtering** - Advanced search and filter capabilities
- **Professional UI** - Modern design with consistent styling

## 🚀 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL/PostgreSQL database
- Node.js and NPM (for asset compilation)

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd sportsshop
```

### Step 2: Install Dependencies
```bash
composer install
npm install
```

### Step 3: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Database Configuration
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sportsshop
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 5: Run Migrations and Seeders
```bash
php artisan migrate
php artisan db:seed
```

### Step 6: Storage Setup
```bash
php artisan storage:link
```

### Step 7: Compile Assets
```bash
npm run build
```

### Step 8: Start the Application
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 📋 Database Structure

### Core Tables
- **users** - User authentication and profiles
- **categories** - Product categories
- **sub_categories** - Product subcategories
- **products** - Product information and metadata
- **batches** - Stock batches with pricing
- **sales** - Sales transactions
- **sale_items** - Individual items in sales

### Key Relationships
- Products belong to subcategories
- Subcategories belong to categories
- Products have many batches
- Sales have many sale items
- Sale items reference batches

## 🎯 Usage Guide

### Setting Up Products
1. **Create Categories** - Navigate to Categories and add main product categories
2. **Add Subcategories** - Create subcategories under main categories
3. **Add Products** - Create products with images, descriptions, and SKUs
4. **Add Batches** - Create stock batches with purchase and selling prices

### Using the POS System
1. **Start a Sale** - Go to POS and begin a new transaction
2. **Search Products** - Use the search bar to find products
3. **Select Batch** - Choose from available stock batches
4. **Add to Cart** - Add items with desired quantities
5. **Process Payment** - Select payment method and complete transaction
6. **Generate Receipt** - Print or view transaction receipt

### Generating Reports
1. **Sales Reports** - View sales by date range and payment method
2. **Inventory Reports** - Analyze stock levels and values
3. **Top Products** - Identify best-selling items
4. **Financial Reports** - Track revenue and profit trends
5. **Export Data** - Download CSV files for external analysis

## 🔧 Configuration

### Currency Settings
The application uses the Taka symbol (৳) as the default currency. To change this:
1. Update currency symbols in views
2. Modify model accessors for formatted amounts
3. Update JavaScript functions for calculations

### File Storage
Product images are stored in the `storage/app/public/products` directory. Ensure proper permissions and storage link setup.

### Email Configuration
Configure email settings in `.env` for password reset and notifications:
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

## 🛠️ Development

### Code Structure
```
app/
├── Http/Controllers/     # Application controllers
├── Models/              # Eloquent models
├── Http/Requests/       # Form request validation
└── Providers/          # Service providers

resources/
├── views/              # Blade templates
├── css/               # Tailwind CSS
└── js/                # JavaScript files

database/
├── migrations/         # Database migrations
├── seeders/           # Database seeders
└── factories/         # Model factories
```

### Key Features Implementation
- **Authentication**: Laravel Breeze with session management
- **File Uploads**: Secure file handling with validation
- **Database Transactions**: Ensures data integrity
- **Eager Loading**: Optimized database queries
- **Form Validation**: Comprehensive input validation
- **Error Handling**: User-friendly error messages

## 📱 Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## 🤝 Contributing
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support
For support and questions:
- Create an issue in the repository
- Check the documentation
- Review the code comments

---

**Built with ❤️ using Laravel 11 and Tailwind CSS**
