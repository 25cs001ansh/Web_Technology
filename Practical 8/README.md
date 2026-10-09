# Practical 8: Database Connection & Prepared Statements (PDO)

## 📌 Submission Checklist Compliance

| Checklist Item | Required Component | Implementation Details | Status |
| :--- | :--- | :--- | :---: |
| **db.php** | Secure PHP PDO connection with try-catch | Uses PDO with `mysql:host=localhost;dbname=studenthub`, `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`, and try-catch error handling. | ✅ Verified |
| **test_connection.php** | Prepared Statements Data Fetching | Executes `$pdo->prepare()` for `students`, `events`, and relational JOIN queries to fetch and render data dynamically. | ✅ Verified |
| **Screenshot** | Browser proof of successful DB connection & tables | Includes high-resolution screenshots of `db.php` connection output and `test_connection.php` table output. | ✅ Verified |

---

## 📁 Project File Structure
```text
Practical 8 /
├── db.php                       # Secure PHP PDO connection file with try-catch error handling
├── test_connection.php          # PHP code fetching data using Prepared Statements & JOIN queries
├── students.php                 # PHP script displaying student records
├── insert.php                   # Insert student record using Prepared Statements
├── p8_db_connection.png         # Screenshot: PDO Database Connection Success
├── p8_test_connection.png       # Screenshot: Prepared Statements & Relational Tables Browser Output
├── Practical_8_Lab_Manual.docx  # CHARUSAT Practical 8 Submission Document (.docx)
└── README.md                    # Practical 8 documentation and Submission Checklist
```

---

## 🌐 Localhost Testing Endpoints

- **PDO Connection Test:** `http://localhost:8000/studenthub/db.php`
- **Prepared Statements Test & Data Output:** `http://localhost:8000/studenthub/test_connection.php`
- **Student Data View:** `http://localhost:8000/studenthub/students.php`

---

## 💡 Key Technical Concepts
- **PDO (PHP Data Objects):** Database-agnostic layer ensuring secure connection and unified interface.
- **Prepared Statements:** Prevents **SQL Injection** vulnerabilities by separating SQL logic from parameters (`$stmt = $pdo->prepare(...)`).
- **Error Handling:** Implements `try-catch` blocks to capture `PDOException` without exposing raw database credentials.
