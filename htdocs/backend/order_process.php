<?php
include '../includes/db_connect.php';

// Check if order_category is set
if (!isset($_POST['order_category'])) {
    die("<div class='alert alert-danger'>שגיאה: נתונים חסרים - לא צוין סוג הזמנה</div>");
}

$order_category = filter_input(INPUT_POST, 'order_category', FILTER_SANITIZE_STRING);

// Handle Table Reservation
if ($order_category === 'table') {
    // Validate required fields for table reservation
    if (!isset($_POST['contact_name']) || !isset($_POST['contact_phone'])) {
        die("<div class='alert alert-danger'>שגיאה: נתונים חסרים - נא למלא את כל השדות החובה</div>");
    }

    $contact_name = filter_input(INPUT_POST, 'contact_name', FILTER_SANITIZE_STRING);
    $contact_phone = filter_input(INPUT_POST, 'contact_phone', FILTER_SANITIZE_STRING);
    $table_datetime = filter_input(INPUT_POST, 'table_datetime', FILTER_SANITIZE_STRING);
    $guests = filter_input(INPUT_POST, 'guests', FILTER_VALIDATE_INT) ?: 2;

    if (empty($contact_name) || empty($contact_phone)) {
        die("<div class='alert alert-danger'>שגיאה: נתונים לא תקינים</div>");
    }

    $reservation_date = '2026-01-20'; // Default
    $reservation_time = '19:00:00'; // Default

    if (!empty($table_datetime)) {
        $dt = new DateTime($table_datetime);
        $reservation_date = $dt->format('Y-m-d');
        $reservation_time = $dt->format('H:i:s');
    }

    $stmt = $conn->prepare("INSERT INTO reservations (customer_name, phone, reservation_date, reservation_time, guest_count, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("ssssi", $contact_name, $contact_phone, $reservation_date, $reservation_time, $guests);

    if (!$stmt->execute()) {
        die("<div class='alert alert-danger'>שגיאה בשמירת ההזמנה: " . htmlspecialchars($stmt->error) . "</div>");
    }

    $order_id = $stmt->insert_id;
    $stmt->close();

    echo "<!DOCTYPE html>
    <html lang='he' dir='rtl'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>אישור הזמנה - TastyBites</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
        <div class='container mt-5'>
            <div class='card shadow'>
                <div class='card-body text-center p-5'>
                    <h1 class='text-success mb-4'>✓ ההזמנה התקבלה בהצלחה!</h1>
                    <p class='fs-4'>מספר הזמנה: <strong>" . $order_id . "</strong></p>
                    <p class='fs-5'>שולחן עבור: <strong>" . htmlspecialchars($contact_name) . "</strong></p>
                    <p>מספר סועדים: " . ($guests ?: '2') . "</p>
                    " . ($table_datetime ? "<p>תאריך ושעה: " . htmlspecialchars($table_datetime) . "</p>" : "") . "
                    <p class='text-muted mt-4'>ניצור איתך קשר בקרוב לאישור ההזמנה</p>
                    <a href='/index.html' class='btn btn-dark btn-lg mt-3'>חזרה לדף הבית</a>
                </div>
            </div>
        </div>
    </body>
    </html>";

    $conn->close();
    exit;
}

// Handle Food Order
if ($order_category === 'food') {
    // Validate required fields for food order
    if (!isset($_POST['customer_name']) || !isset($_POST['customer_phone'])) {
        die("<div class='alert alert-danger'>שגיאה: נתונים חסרים - נא למלא שם וטלפון</div>");
    }

    $customer_name = filter_input(INPUT_POST, 'customer_name', FILTER_SANITIZE_STRING);
    $customer_phone = filter_input(INPUT_POST, 'customer_phone', FILTER_SANITIZE_STRING);
    $delivery_method = filter_input(INPUT_POST, 'delivery_method', FILTER_SANITIZE_STRING) ?: 'delivery';
    $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING) ?: 'cash';

    // Get address if delivery
    $city = '';
    $street = '';
    $floor = '';
    $apt = '';
    $delivery_address = '';

    if ($delivery_method === 'delivery') {
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
        $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING);
        $floor = filter_input(INPUT_POST, 'floor', FILTER_SANITIZE_STRING);
        $apt = filter_input(INPUT_POST, 'apt', FILTER_SANITIZE_STRING);
        $delivery_address = trim($street . ', ' . $city . ($floor ? ', קומה ' . $floor : '') . ($apt ? ', דירה ' . $apt : ''));
    }

    // Get order details from JSON
    $full_order_details = $_POST['full_order_details'] ?? '';
    $order_items_array = [];
    $subtotal = 0;

    if (!empty($full_order_details)) {
        $order_items_array = json_decode($full_order_details, true);
        if ($order_items_array && is_array($order_items_array)) {
            // Calculate subtotal from all items
            foreach ($order_items_array as $item) {
                $item_price = floatval($item['price'] ?? 0);
                $item_qty = intval($item['qty'] ?? 1);
                $subtotal += ($item_price * $item_qty);
            }
        }
    }

    // Fallback: try custom burger data if full_order_details is empty
    $custom_burger_data = $_POST['custom_burger_data'] ?? '';
    if ($subtotal <= 0 && !empty($custom_burger_data)) {
        $custom_data = json_decode($custom_burger_data, true);
        if ($custom_data && isset($custom_data['totalPrice'])) {
            $subtotal = floatval($custom_data['totalPrice']);
        }
    }

    // Final validation
    if ($subtotal <= 0) {
        die("<div class='alert alert-danger'>שגיאה: לא נמצאו פריטים בהזמנה. אנא בחר לפחות פריט אחד.</div>");
    }

    // Calculate delivery cost
    $delivery_fee = 0;
    if ($delivery_method === 'delivery') {
        $delivery_fee = 15.00;
    }

    $total = $subtotal + $delivery_fee;

    // Insert food order into food_orders table
    $stmt = $conn->prepare("INSERT INTO food_orders (customer_name, phone, delivery_method, payment_method, delivery_address, delivery_city, delivery_street, delivery_floor, delivery_apartment, delivery_fee, subtotal, total, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sssssssssddd", $customer_name, $customer_phone, $delivery_method, $payment_method, $delivery_address, $city, $street, $floor, $apt, $delivery_fee, $subtotal, $total);

    if (!$stmt->execute()) {
        die("<div class='alert alert-danger'>שגיאה בשמירת ההזמנה: " . htmlspecialchars($stmt->error) . "</div>");
    }

    $order_id = $stmt->insert_id;

    // Insert all order items from the JSON array
    if (!empty($order_items_array) && is_array($order_items_array)) {
        foreach ($order_items_array as $item) {
            $item_type = $item['type'] ?? 'other';
            $item_name = $item['name'] ?? 'פריט';
            $item_qty = intval($item['qty'] ?? 1);
            $item_price = floatval($item['price'] ?? 0);
            $item_total = $item_price * $item_qty;

            // Store additional details as JSON
            $item_customizations = json_encode($item);

            $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, item_type, item_name, customizations, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_item->bind_param("isssidd", $order_id, $item_type, $item_name, $item_customizations, $item_qty, $item_price, $item_total);
            $stmt_item->execute();
            $stmt_item->close();
        }
    }
    // Fallback: Insert custom burger if using old format
    elseif (!empty($custom_burger_data)) {
        $custom_data = json_decode($custom_burger_data, true);
        $item_name = $custom_data['name'] ?? 'המבורגר מותאם אישית';
        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, item_type, item_name, customizations, quantity, unit_price, total_price) VALUES (?, 'custom_burger', ?, ?, 1, ?, ?)");
        $stmt_item->bind_param("issdd", $order_id, $item_name, $custom_burger_data, $subtotal, $subtotal);
        $stmt_item->execute();
        $stmt_item->close();
    }

    $stmt->close();

    echo "<!DOCTYPE html>
    <html lang='he' dir='rtl'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>אישור הזמנה - TastyBites</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
        <div class='container mt-5'>
            <div class='card shadow'>
                <div class='card-body text-center p-5'>
                    <h1 class='text-success mb-4'>✓ ההזמנה התקבלה בהצלחה!</h1>
                    <p class='fs-4'>מספר הזמנה: <strong>" . $order_id . "</strong></p>
                    <p class='fs-5'>שם: <strong>" . htmlspecialchars($customer_name) . "</strong></p>
                    <p>" . htmlspecialchars($item_name) . "</p>
                    <p class='fs-4 text-warning'>סה\"כ לתשלום: <strong>" . number_format($total, 2) . " ₪</strong></p>
                    <p>סוג משלוח: " . ($delivery_method === 'delivery' ? 'משלוח' : 'איסוף עצמי') . "</p>
                    " . ($delivery_address ? "<p class='text-muted'>כתובת: " . htmlspecialchars($delivery_address) . "</p>" : "") . "
                    <p class='text-muted mt-4'>ההזמנה בדרך אליך!</p>
                    <a href='/index.html' class='btn btn-dark btn-lg mt-3'>חזרה לדף הבית</a>
                </div>
            </div>
        </div>
    </body>
    </html>";

    $conn->close();
    exit;
}

// If order_category is not recognized
die("<div class='alert alert-danger'>שגיאה: סוג הזמנה לא מזוהה</div>");

$conn->close();
?>