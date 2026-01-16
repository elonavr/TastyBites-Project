<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>הזמן עכשיו - TastyBites</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { padding-top: 80px; background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        /* Changed margin-right to margin-left for RTL logo spacing */
        .navbar-brand img { height: 35px; margin-left: 10px; }
        .footer { background-color: #212529; color: white; padding: 40px 0; margin-top: 50px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" alt="TastyBites Logo">
                TastyBites
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">בית</a></li>
                    <li class="nav-item"><a class="nav-link" href="menu.html">תפריט</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.html">גלריה</a></li>
                    <li class="nav-item"><a class="nav-link active text-warning" href="order.html">הזמן עכשיו</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.html">צור קשר</a></li>
                    <li class="nav-item"><a class="nav-link" href="team.html">הצוות שלנו</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-3 mt-4">
                    <div class="card-header bg-warning text-dark text-center p-4">
                        <h2 class="fw-bold mb-0">בצעו הזמנה</h2>
                        <p class="mb-0">המבורגרים חמים וטריים (50 ₪ לארוחה)</p>
                    </div>
                    <div class="card-body p-5">
                        <form action="calc.php" method="POST">
                            
                            <div class="mb-4">
                                <label for="meal" class="form-label fw-bold">בחרו את הארוחה שלכם</label>
                                <select class="form-select form-select-lg" name="meal" id="meal">
                                    <option value="ארוחת המבורגר קלאסי">ארוחת המבורגר קלאסי</option>
                                    <option value="ארוחת צ'יזבורגר">ארוחת צ'יזבורגר</option>
                                    <option value="ארוחת דאבל סמאש">ארוחת דאבל סמאש</option>
                                    <option value="ארוחת המבורגר טבעוני">ארוחת המבורגר פטריות טבעוני</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="qty" class="form-label fw-bold">כמות</label>
                                <input type="number" class="form-control form-control-lg" name="qty" id="qty" value="1" min="1" max="50" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-dark btn-lg">חישוב מחיר ושליחה</button>
                            </div>
                        </form>
                    </div>
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