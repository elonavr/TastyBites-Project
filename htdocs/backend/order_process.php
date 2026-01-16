<?php
include '../includes/db_connect.php';

$meal = $_POST['meal'];
$qty = (int)$_POST['qty'];
$customer = "אורח"; 
$total = $qty * 50;

$insert_sql = "INSERT INTO orders (customer_name, meal_type, quantity, total_price) 
               VALUES ('$customer', '$meal', '$qty', '$total')";
$conn->query($insert_sql);

$sql = "SELECT id, customer_name, meal_type, quantity, total_price FROM orders ORDER BY id DESC";
$result = $conn->query($sql);

echo "<h2>הזמנות TastyBites</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1' style='width:100%; text-align:right;'>
            <tr>
                <th>ID</th>
                <th>לקוח</th>
                <th>ארוחה</th>
                <th>כמות</th>
                <th>סה\"כ</th>
            </tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"]. "</td>
                <td>" . $row["customer_name"]. "</td>
                <td>" . $row["meal_type"]. "</td>
                <td>" . $row["quantity"]. "</td>
                <td>" . $row["total_price"]. " ₪</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "אין תוצאות";
}

$conn->close();
?>