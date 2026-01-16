<?php
$meal_name = $_POST['meal_name'];
$calories_per_100g = (int)$_POST['calories'];
$weight = (int)$_POST['weight'];

$total_calories = ($calories_per_100g / 100) * $weight;

$formatted_meal = strtoupper(trim($meal_name));

echo "<h2>תוצאת חישוב עבור: " . $formatted_meal . "</h2>";
echo "<p>במנה של " . $weight . " גרם יש כ-" . $total_calories . " קלוריות.</p>";

$tips = ["מומלץ לשתות מים עם המנה", "כדאי להוסיף ירקות ירוקים", "בתאבון!"];
echo "טיפ: " . $tips[array_rand($tips)];
?>