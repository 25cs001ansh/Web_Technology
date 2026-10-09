# Practical 7: PHP Form Processing with Server-Side Validation and CSV/JSON File Storage

## 📌 Aim & Problem Definition
Process submitted registration/contact form data using PHP. Validate and sanitize inputs on the server side and store records in **CSV** (`submissions.csv`) or **JSON** (`submissions.json`) file format. Display appropriate success/error flash messages and CSRF protection.

---

## 📁 Project Structure
```text
Practical 7 /
├── form.php             # HTML5 Registration Form with CSRF Token & Validation UI
├── process.php          # Backend PHP Processor (POST check, Sanitization, Validation, File Storage)
├── display.php          # Page displaying stored JSON & CSV records in HTML table
├── submissions.json     # JSON storage file
├── submissions.csv      # CSV storage file
├── Practical_7_Lab_Manual.docx  # CHARUSAT Laboratory Manual report
└── README.md            # Comprehensive documentation
```

---

## 🔑 Key Features & Technical Implementation

1. **PHP Form (`form.php`)**:
   - Collects Full Name, Email Address, Mobile Number, and Course.
   - Includes hidden CSRF token (`$_SESSION['csrf_token']`) to prevent Cross-Site Request Forgery attacks.

2. **POST Method (`process.php`)**:
   - Verifies `$_SERVER['REQUEST_METHOD'] === 'POST'`. Direct access is blocked.

3. **Input Sanitization**:
   - Uses `htmlspecialchars(trim(...))` and `filter_var(..., FILTER_SANITIZE_EMAIL)` to sanitize inputs against XSS.

4. **Server-Side Validation**:
   - Checks empty fields.
   - Validates email format using `filter_var(..., FILTER_VALIDATE_EMAIL)`.

5. **File Storage**:
   - **JSON**: Appends record into `submissions.json` using `json_encode()` with `JSON_PRETTY_PRINT`.
   - **CSV**: Appends row into `submissions.csv` using `fputcsv()`.

6. **Display Page (`display.php`)**:
   - Reads stored `submissions.json` and renders all records dynamically in an HTML table.

---

## 🚀 How to Run in XAMPP

1. Copy the `Practical 7` folder to `D:\xampp\htdocs\practical7`.
2. Open XAMPP Control Panel and start **Apache**.
3. Open your browser and navigate to:
   - **Registration Form**: `http://localhost:8000/practical7/form.php`
   - **View Stored Records**: `http://localhost:8000/practical7/display.php`

---

## 💡 Viva Voce Q&A Reference

| Viva Question | Technical Answer / Key Concept |
| :--- | :--- |
| **Why use POST instead of GET?** | POST transmits data in the HTTP request body rather than URL parameters, preventing sensitive data exposure in browser history and server logs. |
| **Sanitization vs Validation?** | **Sanitization** cleans/escapes input to prevent security exploits (e.g., `htmlspecialchars` for XSS). **Validation** checks if data conforms to rules (e.g., valid email pattern). |
| **What is CSRF and how to prevent it?** | Cross-Site Request Forgery tricks users into executing unintended actions. Prevented using secret per-session tokens checked on form submission. |
| **Why use `fputcsv()` for CSV writing?** | `fputcsv()` handles formatting, delimiter escaping, and quotes automatically according to CSV standards. |
