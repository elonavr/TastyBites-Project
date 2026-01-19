# Git Update Instructions

## Quick Commands

Open terminal/command prompt in your project folder and run:

```bash
# 1. Check what files changed
git status

# 2. Add all changes
git add .

# 3. Commit with message
git commit -m "Add PHP forms: customer reviews and text analyzer"

# 4. Push to GitHub
git push origin main
```

---

## Detailed Steps

### Step 1: Check Status
```bash
git status
```
This shows what files were added/modified/deleted.

**You should see:**
- `htdocs/customer_reviews.php` (new)
- `htdocs/text_analyzer.php` (new)
- `htdocs/index.html` (modified)
- `htdocs/order.html` (modified)
- `htdocs/about.html` (modified)
- `htdocs/menu.html` (modified)
- `htdocs/contact.html` (modified)
- `htdocs/team.html` (modified)
- `htdocs/gallery.html` (modified)
- `htdocs/burger_calculator.html` (modified)
- `htdocs/calorie_calculator.html` (modified)
- `htdocs/database/schema.sql` (modified)
- `README.md` (modified)
- `PHP_IMPLEMENTATION.md` (new)
- `create_reviews_table.sql` (new)

---

### Step 2: Add All Changes
```bash
git add .
```
This stages all changes for commit.

**Or add specific files:**
```bash
git add htdocs/customer_reviews.php
git add htdocs/text_analyzer.php
git add README.md
# etc...
```

---

### Step 3: Commit Changes
```bash
git commit -m "Add PHP forms: customer reviews and text analyzer"
```

**Or with a detailed message:**
```bash
git commit -m "Add PHP implementation for project requirements

- Add customer_reviews.php (database form with add/view)
- Add text_analyzer.php (PHP logic without database)
- Update navigation on all pages to include customer reviews
- Update schema.sql with customer_reviews table
- Update README with PHP implementation details
- Add PHP_IMPLEMENTATION.md documentation"
```

---

### Step 4: Push to GitHub
```bash
git push origin main
```

**If your branch is named 'master' instead:**
```bash
git push origin master
```

**First time pushing? Use:**
```bash
git push -u origin main
```

---

## Troubleshooting

### Error: "fatal: not a git repository"
**Solution:** Initialize git first
```bash
git init
git remote add origin https://github.com/elonavr/TastyBites-Project.git
```

### Error: "Updates were rejected"
**Solution:** Pull first, then push
```bash
git pull origin main
git push origin main
```

### Error: "Authentication failed"
**Solution:** Use personal access token instead of password
1. Go to GitHub Settings → Developer settings → Personal access tokens
2. Generate new token with "repo" scope
3. Use token as password when prompted

---

## Verify Upload

After pushing, check GitHub:
1. Go to https://github.com/elonavr/TastyBites-Project
2. Verify new files appear:
   - `htdocs/customer_reviews.php`
   - `htdocs/text_analyzer.php`
   - `PHP_IMPLEMENTATION.md`
3. Check the latest commit message

---

## Quick Reference

```bash
# Check status
git status

# Add all
git add .

# Commit
git commit -m "Your message here"

# Push
git push origin main

# View history
git log --oneline

# View remote URL
git remote -v
```

---

**Done!** Your changes are now on GitHub! 🚀
