<?php
include 'includes/db_connect.php';

// Create reviews table if it doesn't exist
$create_table_sql = "CREATE TABLE IF NOT EXISTS customer_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT NOT NULL,
    visit_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_rating (rating),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$conn->query($create_table_sql);

// Handle form submission
$submission_success = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $customer_name = trim($_POST['customer_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    $review_text = trim($_POST['review_text'] ?? '');
    $visit_date = $_POST['visit_date'] ?? '';

    // Validation
    if (empty($customer_name) || empty($email) || $rating < 1 || $rating > 5 || empty($review_text) || empty($visit_date)) {
        $error_message = 'נא למלא את כל השדות בצורה תקינה';
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO customer_reviews (customer_name, email, rating, review_text, visit_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $customer_name, $email, $rating, $review_text, $visit_date);

        if ($stmt->execute()) {
            $submission_success = true;
        } else {
            $error_message = 'שגיאה בשמירת הביקורת: ' . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch all reviews
$reviews_query = "SELECT * FROM customer_reviews ORDER BY created_at DESC";
$reviews_result = $conn->query($reviews_query);
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ביקורות לקוחות - TastyBites</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 80px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('/assets/Background.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .navbar-brand img {
            height: 35px;
            margin-left: 10px;
        }

        .glass-card {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            padding: 30px;
            margin-bottom: 30px;
        }

        .rating-stars {
            font-size: 1.5rem;
            color: #ffc107;
        }

        .review-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: transform 0.2s;
        }

        .review-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .star-rating {
            display: inline-block;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input[type="radio"]:checked ~ label {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/index.html">
                <img src="/assets/logo.png" alt="TastyBites Logo">
                TastyBites
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="/index.html">בית</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about.html">על המסעדה</a></li>
                    <li class="nav-item"><a class="nav-link" href="/menu.html">תפריט</a></li>
                    <li class="nav-item"><a class="nav-link" href="/order.html">הזמן עכשיו</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/customer_reviews.php">ביקורות</a></li>
                    <li class="nav-item"><a class="nav-link" href="/team.html">הצוות שלנו</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Add Review Form -->
        <div class="glass-card">
            <h2 class="text-center mb-4"><i class="fas fa-star text-warning"></i> שתפו את החוויה שלכם</h2>

            <?php if ($submission_success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> תודה רבה! הביקורת שלך נוספה בהצלחה.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">שם מלא: <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">אימייל: <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">תאריך הביקור: <span class="text-danger">*</span></label>
                        <input type="date" name="visit_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">דירוג: <span class="text-danger">*</span></label>
                        <div class="star-rating" style="direction: ltr;">
                            <input type="radio" name="rating" value="5" id="star5" required>
                            <label for="star5"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" value="4" id="star4">
                            <label for="star4"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" value="3" id="star3">
                            <label for="star3"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" value="2" id="star2">
                            <label for="star2"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" value="1" id="star1">
                            <label for="star1"><i class="fas fa-star"></i></label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">הביקורת שלך: <span class="text-danger">*</span></label>
                    <textarea name="review_text" class="form-control" rows="4" required placeholder="שתפו אותנו בחוויה שלכם במסעדה..."></textarea>
                </div>

                <button type="submit" name="submit_review" class="btn btn-warning btn-lg w-100 fw-bold">
                    <i class="fas fa-paper-plane"></i> שלח ביקורת
                </button>
            </form>
        </div>

        <!-- Display Reviews -->
        <div class="glass-card">
            <h2 class="text-center mb-4"><i class="fas fa-comments text-primary"></i> כל הביקורות</h2>

            <?php if ($reviews_result && $reviews_result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>מס'</th>
                                <th>שם</th>
                                <th>אימייל</th>
                                <th>דירוג</th>
                                <th>ביקורת</th>
                                <th>תאריך ביקור</th>
                                <th>תאריך פרסום</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($review = $reviews_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $review['id']; ?></td>
                                    <td><?php echo htmlspecialchars($review['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($review['email']); ?></td>
                                    <td>
                                        <span class="rating-stars">
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $review['rating']) {
                                                    echo '<i class="fas fa-star"></i>';
                                                } else {
                                                    echo '<i class="far fa-star"></i>';
                                                }
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($review['review_text']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($review['visit_date'])); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-3">
                    <strong><i class="fas fa-info-circle"></i> סה"כ ביקורות:</strong> <?php echo $reviews_result->num_rows; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle"></i> אין עדיין ביקורות. היו הראשונים לשתף!
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mb-5">
            <a href="/index.html" class="btn btn-dark btn-lg">
                <i class="fas fa-home"></i> חזרה לעמוד הבית
            </a>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2025 TastyBites. כל הזכויות שמורות.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
