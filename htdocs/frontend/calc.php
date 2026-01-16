<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ההזמנה התקבלה - TastyBites</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { padding-top: 80px; background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        /* Changed margin-right to margin-left for RTL logo spacing */
        .navbar-brand img { height: 35px; margin-left: 10px; }
        .footer { background-color: #212529; color: white; padding: 40px 0; margin-top: 50px; position: fixed; bottom: 0; width: 100%; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" alt="TastyBites Logo">
                TastyBites
            </a>
        </div>
    </nav>

    <div class="container text-center mt-5">
        <div class="card shadow border-success mx-auto" style="max-width: 600px;">
            <div class="card-header bg-success text-white">
                <h1 class="h3 mb-0">ההזמנה התקבלה!</h1>
            </div>
            <div class="card-body p-5">
                <?php
                    $qty = $_POST["qty"];
                    $meal = $_POST["meal"];
                    $price = 50;
                    $total = $qty * $price;

                    echo "<h4 class='mb-4'>הזמנת: <strong>" . $qty . " x " . $meal . "</strong></h4>";
                    echo "<div class='alert alert-warning fs-3 fw-bold'>סה\"כ לתשלום: " . $total . " ₪</div>";
                    echo "<p class='text-muted mt-3'>ההזמנה שלך נשלחה למטבח.</p>";
                ?>
                
                <div class="mt-4">
                    <a href="index.html" class="btn btn-outline-dark me-2">חזרה לדף הבית</a>
                    <a href="order.html" class="btn btn-success">הזמנה חדשה</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer text-center">
        <div class="container">
            <p class="mb-0">&copy; 2025 TastyBites. כל הזכויות שמורות.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>