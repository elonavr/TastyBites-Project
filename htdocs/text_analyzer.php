<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>מנתח טקסט - TastyBites</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 80px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .stat-box {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .stat-box h3 {
            font-size: 2.5rem;
            margin-bottom: 5px;
        }

        .stat-box p {
            margin: 0;
            font-size: 1.1rem;
        }

        .word-cloud {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #dee2e6;
        }

        .word-badge {
            display: inline-block;
            margin: 5px;
            padding: 8px 15px;
            background: #007bff;
            color: white;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .result-section {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
                    <li class="nav-item"><a class="nav-link active" href="/text_analyzer.php">מנתח טקסט</a></li>
                    <li class="nav-item"><a class="nav-link" href="/team.html">הצוות שלנו</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="glass-card">
            <h2 class="text-center mb-4"><i class="fas fa-text-width text-primary"></i> מנתח טקסט מתקדם</h2>
            <p class="text-center text-muted mb-4">הזן טקסט כדי לקבל ניתוח סטטיסטי מקיף</p>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-bold">הטקסט לניתוח:</label>
                    <textarea name="input_text" class="form-control" rows="6" required placeholder="הזן כאן את הטקסט שברצונך לנתח..."><?php echo isset($_POST['input_text']) ? htmlspecialchars($_POST['input_text']) : ''; ?></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">מקדם חשיבות (1-10):</label>
                        <input type="number" name="importance_factor" class="form-control" min="1" max="10" value="<?php echo isset($_POST['importance_factor']) ? intval($_POST['importance_factor']) : 5; ?>" required>
                        <small class="text-muted">מקדם להכפלת ציון החשיבות</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">מילות מפתח לחיפוש (מופרד בפסיקים):</label>
                        <input type="text" name="keywords" class="form-control" value="<?php echo isset($_POST['keywords']) ? htmlspecialchars($_POST['keywords']) : ''; ?>" placeholder="דוגמה: טעים, איכותי, שירות">
                    </div>
                </div>

                <button type="submit" name="analyze" class="btn btn-primary btn-lg w-100 fw-bold">
                    <i class="fas fa-chart-bar"></i> נתח טקסט
                </button>
            </form>
        </div>

        <?php
        // PHP Functions demonstrating all required features

        /**
         * Function to count vowels in Hebrew text
         * Demonstrates: FUNCTION usage, STRING function (mb_substr, mb_strlen)
         */
        function countHebrewVowels($text) {
            $vowels = ['א', 'ו', 'י', 'ה', 'ע'];
            $count = 0;
            $length = mb_strlen($text, 'UTF-8');

            // Using loop to iterate through text
            for ($i = 0; $i < $length; $i++) {
                $char = mb_substr($text, $i, 1, 'UTF-8');
                if (in_array($char, $vowels)) {
                    $count++;
                }
            }

            return $count;
        }

        /**
         * Function to calculate reading time
         * Demonstrates: FUNCTION usage, CALCULATION
         */
        function calculateReadingTime($wordCount, $wordsPerMinute = 200) {
            $minutes = $wordCount / $wordsPerMinute;
            $seconds = ($minutes - floor($minutes)) * 60;
            return [
                'minutes' => floor($minutes),
                'seconds' => round($seconds)
            ];
        }

        /**
         * Function to find most common words
         * Demonstrates: FUNCTION, ARRAY usage, LOOP
         */
        function getMostCommonWords($text, $limit = 5) {
            // Remove punctuation and convert to lowercase (STRING function)
            $cleaned = mb_strtolower($text, 'UTF-8');
            $cleaned = preg_replace('/[^\p{L}\s]/u', '', $cleaned);

            // Split into words array (ARRAY)
            $words = preg_split('/\s+/', $cleaned);
            $words = array_filter($words, function($word) {
                return mb_strlen($word, 'UTF-8') > 2; // Ignore short words
            });

            // Count word frequencies (ARRAY + LOOP)
            $wordFreq = [];
            foreach ($words as $word) {
                if (isset($wordFreq[$word])) {
                    $wordFreq[$word]++;
                } else {
                    $wordFreq[$word] = 1;
                }
            }

            // Sort by frequency
            arsort($wordFreq);

            // Return top words
            return array_slice($wordFreq, 0, $limit, true);
        }

        /**
         * Function to calculate text complexity score
         * Demonstrates: FUNCTION, CALCULATION, STRING functions
         */
        function calculateComplexityScore($text, $importanceFactor) {
            $charCount = mb_strlen($text, 'UTF-8');
            $wordCount = str_word_count($text, 0);

            // Calculate average word length (CALCULATION)
            $avgWordLength = $wordCount > 0 ? $charCount / $wordCount : 0;

            // Complexity formula: (avg word length * importance factor) + (word count / 10)
            $complexityScore = ($avgWordLength * $importanceFactor) + ($wordCount / 10);

            return round($complexityScore, 2);
        }

        // Process form submission
        if (isset($_POST['analyze']) && !empty($_POST['input_text'])) {
            $inputText = trim($_POST['input_text']);
            $importanceFactor = intval($_POST['importance_factor']);
            $keywordsInput = isset($_POST['keywords']) ? $_POST['keywords'] : '';

            // Basic statistics using STRING functions
            $charCount = mb_strlen($inputText, 'UTF-8');
            $charCountNoSpaces = mb_strlen(str_replace(' ', '', $inputText), 'UTF-8');
            $wordCount = str_word_count($inputText, 0);
            $sentenceCount = preg_match_all('/[.!?]+/', $inputText, $matches);

            // Use functions
            $vowelCount = countHebrewVowels($inputText); // FUNCTION + STRING function
            $readingTime = calculateReadingTime($wordCount); // FUNCTION + CALCULATION
            $commonWords = getMostCommonWords($inputText, 10); // FUNCTION + ARRAY + LOOP
            $complexityScore = calculateComplexityScore($inputText, $importanceFactor); // FUNCTION + CALCULATION

            // Process keywords (ARRAY + LOOP + STRING function)
            $keywords = [];
            $keywordMatches = [];
            if (!empty($keywordsInput)) {
                $keywords = array_map('trim', explode(',', $keywordsInput)); // ARRAY + STRING function

                // Count keyword occurrences (LOOP + STRING function)
                foreach ($keywords as $keyword) {
                    if (!empty($keyword)) {
                        $count = mb_substr_count(mb_strtolower($inputText, 'UTF-8'), mb_strtolower($keyword, 'UTF-8'));
                        $keywordMatches[$keyword] = $count;
                    }
                }
            }

            // Calculate averages (CALCULATION)
            $avgWordLength = $wordCount > 0 ? round($charCountNoSpaces / $wordCount, 2) : 0;
            $avgWordsPerSentence = $sentenceCount > 0 ? round($wordCount / $sentenceCount, 2) : 0;

            // Display results
            ?>
            <div class="glass-card result-section">
                <h3 class="text-center mb-4"><i class="fas fa-chart-pie text-success"></i> תוצאות הניתוח</h3>

                <!-- Basic Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-box" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h3><?php echo number_format($charCount); ?></h3>
                            <p>תווים (כולל רווחים)</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-box" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <h3><?php echo number_format($wordCount); ?></h3>
                            <p>מילים</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-box" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <h3><?php echo number_format($sentenceCount); ?></h3>
                            <p>משפטים</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-box" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <h3><?php echo number_format($vowelCount); ?></h3>
                            <p>תנועות עברית</p>
                        </div>
                    </div>
                </div>

                <!-- Advanced Statistics -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-ruler text-warning"></i> אורך ממוצע למילה</h5>
                                <p class="display-6 text-primary"><?php echo $avgWordLength; ?></p>
                                <p class="text-muted">תווים</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-clock text-info"></i> זמן קריאה משוער</h5>
                                <p class="display-6 text-success"><?php echo $readingTime['minutes']; ?>:<?php echo str_pad($readingTime['seconds'], 2, '0', STR_PAD_LEFT); ?></p>
                                <p class="text-muted">דקות:שניות</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-brain text-danger"></i> ציון מורכבות</h5>
                                <p class="display-6 text-warning"><?php echo $complexityScore; ?></p>
                                <p class="text-muted">מקדם: ×<?php echo $importanceFactor; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Most Common Words -->
                <div class="mb-4">
                    <h4 class="mb-3"><i class="fas fa-fire text-danger"></i> המילים הנפוצות ביותר</h4>
                    <div class="word-cloud">
                        <?php
                        // LOOP to display common words
                        $colorIndex = 0;
                        $colors = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6f42c1'];
                        foreach ($commonWords as $word => $frequency) {
                            $color = $colors[$colorIndex % count($colors)];
                            echo '<span class="word-badge" style="background: ' . $color . ';">';
                            echo htmlspecialchars($word) . ' (' . $frequency . ')';
                            echo '</span>';
                            $colorIndex++;
                        }

                        if (empty($commonWords)) {
                            echo '<p class="text-muted">לא נמצאו מילים נפוצות</p>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Keyword Analysis -->
                <?php if (!empty($keywordMatches)): ?>
                <div class="mb-4">
                    <h4 class="mb-3"><i class="fas fa-search text-primary"></i> ניתוח מילות מפתח</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>מילת מפתח</th>
                                    <th>מספר הופעות</th>
                                    <th>אחוז מהטקסט</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // LOOP through keyword matches (ARRAY + LOOP + CALCULATION)
                                foreach ($keywordMatches as $keyword => $count) {
                                    $percentage = $wordCount > 0 ? round(($count / $wordCount) * 100, 2) : 0;
                                    echo '<tr>';
                                    echo '<td><strong>' . htmlspecialchars($keyword) . '</strong></td>';
                                    echo '<td><span class="badge bg-primary">' . $count . '</span></td>';
                                    echo '<td>' . $percentage . '%</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Additional Statistics -->
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> סטטיסטיקה נוספת</h5>
                    <ul class="mb-0">
                        <li>ממוצע מילים למשפט: <strong><?php echo $avgWordsPerSentence; ?></strong></li>
                        <li>תווים ללא רווחים: <strong><?php echo number_format($charCountNoSpaces); ?></strong></li>
                        <li>יחס תנועות לתווים: <strong><?php echo $charCount > 0 ? round(($vowelCount / $charCount) * 100, 2) : 0; ?>%</strong></li>
                        <li>הטקסט המקורי באותיות גדולות: <strong><?php echo mb_strtoupper(mb_substr($inputText, 0, 50, 'UTF-8'), 'UTF-8') . '...'; ?></strong></li>
                    </ul>
                </div>

                <div class="text-center">
                    <a href="/text_analyzer.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-redo"></i> נתח טקסט חדש
                    </a>
                </div>
            </div>
            <?php
        }
        ?>

        <div class="text-center mt-4">
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
