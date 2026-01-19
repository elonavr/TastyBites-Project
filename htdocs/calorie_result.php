<?php
include 'includes/db_connect.php';

// Input validation
if (!isset($_POST['meal_name']) || !isset($_POST['calories']) || !isset($_POST['weight'])) {
    header("Location: /calorie_calculator.html?error=missing");
    exit;
}

$meal_name = htmlspecialchars(trim($_POST['meal_name']));
$calories_per_100g = (int)$_POST['calories'];
$weight = (int)$_POST['weight'];

// Validate inputs
if ($calories_per_100g < 1 || $calories_per_100g > 1000 || $weight < 1 || $weight > 2000) {
    header("Location: /calorie_calculator.html?error=invalid");
    exit;
}

$total_calories = round(($calories_per_100g / 100) * $weight, 2);

// Save to database
try {
    $stmt = $conn->prepare("INSERT INTO calorie_calculations (meal_name, calories_per_100g, weight_grams, total_calories) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siid", $meal_name, $calories_per_100g, $weight, $total_calories);
    $stmt->execute();
    $calculation_id = $stmt->insert_id;
    $stmt->close();
} catch (Exception $e) {
    // Log error but continue to show results
    error_log("Failed to save calorie calculation: " . $e->getMessage());
}

$conn->close();

$tips = ["מומלץ לשתות מים עם המנה", "כדאי להוסיף ירקות ירוקים", "בתאבון!"];
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>תוצאת חישוב קלוריות - TastyBites</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 50px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .result-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .result-header {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .result-body {
            padding: 2rem;
        }
        .calorie-display {
            font-size: 4rem;
            font-weight: bold;
            color: #ff5722;
            text-align: center;
            margin: 2rem 0;
        }
        .meal-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 1rem;
        }
        .tips-list {
            list-style: none;
            padding: 0;
        }
        .tips-list li {
            padding: 0.8rem;
            margin: 0.5rem 0;
            background: #f8f9fa;
            border-right: 4px solid #ffc107;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="result-card">
                    <div class="result-header">
                        <h1><i class="fas fa-calculator"></i> תוצאת חישוב קלוריות</h1>
                    </div>

                    <div class="result-body">
                        <div class="meal-name">
                            <i class="fas fa-hamburger text-warning"></i> <?php echo $meal_name; ?>
                        </div>

                        <div class="calorie-display">
                            <i class="fas fa-fire"></i> <?php echo number_format($total_calories); ?> קלוריות
                        </div>

                        <div class="alert alert-info text-center">
                            <strong>במנה של <?php echo $weight; ?> גרם</strong><br>
                            (<?php echo $calories_per_100g; ?> קלוריות ל-100 גרם)
                        </div>

                        <h4 class="mt-4 mb-3"><i class="fas fa-lightbulb text-warning"></i> טיפים תזונתיים:</h4>
                        <ul class="tips-list">
                            <?php foreach ($tips as $tip): ?>
                                <li><i class="fas fa-check-circle text-success"></i> <?php echo $tip; ?></li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="d-grid gap-2 mt-4">
                            <a href="/calorie_calculator.html" class="btn btn-warning btn-lg">
                                <i class="fas fa-redo"></i> חשב מנה נוספת
                            </a>
                            <a href="/index.html" class="btn btn-outline-dark btn-lg">
                                <i class="fas fa-home"></i> חזרה לעמוד הבית
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
