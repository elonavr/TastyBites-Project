# TastyBites - Restaurant Website

A full-stack restaurant website with ordering system, customer reviews, and text analyzer built with HTML, CSS, JavaScript, PHP, and MySQL.

**Live Demo:** http://elon-project.byethost3.com

---

## Features

- **Home & Pages** - Home, About, Gallery, Menu, Contact, Team
- **Custom Burger Builder** - Interactive ingredient selection with real-time pricing
- **Order System** - Table reservations and food delivery/pickup
- **Customer Reviews** - Add and view customer feedback (Database form)
- **Text Analyzer** - Advanced text analysis with statistics (PHP logic form)
- **Calorie Calculator** - Calculate nutritional values
- **Responsive Design** - Mobile, tablet, and desktop support
- **RTL Hebrew Support** - Complete right-to-left layout

---

## Technologies

- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript
- **Backend:** PHP 7.4+
- **Database:** MySQL 8.0
- **Hosting:** Byethost
- **Version Control:** Git/GitHub

---

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/elonavr/TastyBites-Project.git
cd TastyBites-WebProject
```

### 2. Configure Database

Edit `htdocs/includes/db_connect.php` with your credentials:

```php
$servername = "your_host";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";
```

### 3. Create Database

Run the SQL file in phpMyAdmin or MySQL:

```bash
mysql -u username -p < htdocs/database/schema.sql
```

Or manually run:

```sql
-- Create customer_reviews table
CREATE TABLE IF NOT EXISTS customer_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT NOT NULL,
    visit_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 4. Start Server

```bash
cd htdocs
php -S localhost:8000
```

Access: `http://localhost:8000/index.html`

---

## PHP Implementation

### ✅ Form 1: Customer Reviews (customer_reviews.php)

**Database form with add + view functionality**

- **Table:** `customer_reviews` (7 fields)
- **Features:**
  - Add new customer reviews via form
  - View all reviews in table format
  - Star rating system (1-5 stars)
  - Input validation
- **Database Operations:** INSERT, SELECT

### ✅ Form 2: Text Analyzer (text_analyzer.php)

**PHP logic form (no database)**

- **Required PHP Features:**
  - ✅ **Functions:** 4 custom functions (countHebrewVowels, calculateReadingTime, getMostCommonWords, calculateComplexityScore)
  - ✅ **Arrays:** 10+ array operations (vowels array, word frequencies, color array, etc.)
  - ✅ **Loops:** 4+ loops (for loop, multiple foreach loops)
  - ✅ **String Functions:** 20+ functions (mb_strlen, mb_substr, mb_strtolower, str_word_count, preg_replace, explode, trim, etc.)
  - ✅ **Calculations:** 6+ calculations (reading time, complexity score, averages, percentages, etc.)

- **Features:**
  - Analyze text for character/word/sentence count
  - Calculate reading time
  - Find most common words
  - Keyword frequency analysis
  - Text complexity scoring

---

## Project Structure

```
htdocs/
├── index.html                    # Home page
├── about.html                    # About page
├── order.html                    # Order system
├── customer_reviews.php          # Reviews (Database form)
├── text_analyzer.php             # Text analyzer (PHP logic)
├── backend/
│   ├── order_process.php         # Order processing
│   └── contact_process.php       # Contact form handler
├── includes/
│   └── db_connect.php            # Database connection
├── database/
│   └── schema.sql                # Database schema
└── assets/                       # Images & videos
```

---

## Database Tables

1. **reservations** - Table bookings (6 fields)
2. **food_orders** - Food orders (13 fields)
3. **order_items** - Order line items (7 fields)
4. **contact_submissions** - Contact messages (7 fields)
5. **calorie_calculations** - Calorie data (5 fields)
6. **customer_reviews** - Customer feedback (7 fields)
7. **menu_items** - Menu items (10 fields)

---

## Requirements Met

### ✅ PHP & Database Requirements

- [x] Form with MySQL table (4+ fields) → **customer_reviews (7 fields)**
- [x] Add records to database → **INSERT queries**
- [x] View table on site → **Displays all reviews**
- [x] All via same form → **customer_reviews.php**

### ✅ Third Form - PHP Logic (No Database)

- [x] PHP form without database → **text_analyzer.php**
- [x] Function (at least once) → **4 functions**
- [x] Array (at least once) → **10+ array operations**
- [x] Loop (at least once) → **4+ loops**
- [x] String function (at least once) → **20+ string functions**
- [x] Calculation (at least once) → **6+ calculations**

---

## Team Members

| Name               | Role               | Email                 |
| ------------------ | ------------------ | --------------------- |
| **יונתן בודובסקי** | Team Leader        | yoni1997gym@gmail.com |
| **אילון אברהם**    | Backend Developer  | elonav@ac.sce.ac.il   |
| **עומרי חמו**      | Frontend Developer | omrih@example.com     |
| **אלמוג מלכה**     | Designer           | almogm@example.com    |

---

## Quick Links

- **Repository:** https://github.com/elonavr/TastyBites-Project
- **Live Site:** http://elon-project.byethost3.com
- **PHP Documentation:** [PHP_IMPLEMENTATION.md](PHP_IMPLEMENTATION.md)

---

## License

Educational project - © 2025 TastyBites Team

---

**Last Updated:** January 2026 | **Status:** ✅ Complete
