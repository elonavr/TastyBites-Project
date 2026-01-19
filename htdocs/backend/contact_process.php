<?php
include '../includes/db_connect.php';

if (!isset($_POST['fullName']) || !isset($_POST['email']) || !isset($_POST['topic']) || !isset($_POST['message'])) {
    die("<div class='alert alert-danger'>שגיאה: כל השדות חובה</div>");
}

$fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$topic = filter_input(INPUT_POST, 'topic', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("<div class='alert alert-danger'>שגיאה: כתובת אימייל לא תקינה</div>");
}

$allowedTopics = ['Support', 'Sales', 'Other'];
if (!in_array($topic, $allowedTopics)) {
    die("<div class='alert alert-danger'>שגיאה: נושא לא תקין</div>");
}

if (strlen($fullName) < 2 || strlen($fullName) > 100) {
    die("<div class='alert alert-danger'>שגיאה: שם חייב להיות בין 2-100 תווים</div>");
}

if (strlen($message) < 10 || strlen($message) > 1000) {
    die("<div class='alert alert-danger'>שגיאה: הודעה חייבת להיות בין 10-1000 תווים</div>");
}

try {
    $stmt = $conn->prepare("INSERT INTO contact_submissions (full_name, email, topic, message, status) VALUES (?, ?, ?, ?, 'new')");
    $stmt->bind_param("ssss", $fullName, $email, $topic, $message);

    if (!$stmt->execute()) {
        throw new Exception("שגיאה בשמירת הפנייה: " . $stmt->error);
    }

    $submissionId = $stmt->insert_id;
    $stmt->close();

    ?>
    <!DOCTYPE html>
    <html lang="he" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>פנייה התקבלה - TastyBites</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
        <style>
            body {
                padding-top: 80px;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .success-card {
                background-color: white;
                border-radius: 15px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                max-width: 600px;
                padding: 3rem;
                text-align: center;
            }

            .success-icon {
                font-size: 5rem;
                color: #28a745;
                animation: bounceIn 1s;
            }

            @keyframes bounceIn {
                0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
                40% { transform: translateY(-30px); }
                60% { transform: translateY(-15px); }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="success-card">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="mt-4 mb-3">תודה <?php echo htmlspecialchars($fullName); ?>!</h1>
                <p class="lead">פנייתך התקבלה בהצלחה</p>
                <p class="text-muted">מספר פנייה: <strong>#<?php echo $submissionId; ?></strong></p>

                <div class="alert alert-info mt-4" role="alert">
                    <strong>מה הלאה?</strong><br>
                    הצוות שלנו יעיין בפנייתך ויחזור אלייך בהקדם ל: <strong><?php echo htmlspecialchars($email); ?></strong>
                </div>

                <div class="mt-4">
                    <h5 class="mb-3">פרטי הפנייה:</h5>
                    <table class="table table-bordered text-end">
                        <tr>
                            <th>נושא:</th>
                            <td><?php echo htmlspecialchars($topic); ?></td>
                        </tr>
                        <tr>
                            <th>הודעה:</th>
                            <td><?php echo nl2br(htmlspecialchars($message)); ?></td>
                        </tr>
                    </table>
                </div>

                <div class="mt-4">
                    <a href="/index.html" class="btn btn-primary btn-lg me-2">חזרה לעמוד הבית</a>
                    <a href="/contact.html" class="btn btn-outline-secondary btn-lg">שלח פנייה נוספת</a>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            console.log("פנייה נשמרה בהצלחה!");
            console.log("מספר פנייה: <?php echo $submissionId; ?>");
            console.log("לקוח: <?php echo htmlspecialchars($fullName); ?>");
            console.log("נושא: <?php echo htmlspecialchars($topic); ?>");

            setTimeout(function() {
                console.log("מפנה לעמוד הבית...");
                window.location.href = '/index.html';
            }, 10000);
        </script>
    </body>
    </html>
    <?php

} catch (Exception $e) {
    ?>
    <!DOCTYPE html>
    <html lang="he" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>שגיאה - TastyBites</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    </head>
    <body class="p-5">
        <div class="container">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">אופס! משהו השתבש</h4>
                <p><?php echo htmlspecialchars($e->getMessage()); ?></p>
                <hr>
                <p class="mb-0">
                    <a href="/contact.html" class="btn btn-danger">חזרה לטופס יצירת קשר</a>
                </p>
            </div>
        </div>
    </body>
    </html>
    <?php
}

$conn->close();
?>
