# Practical 11: Student Management System (CRUD with Search & Filter)

## 📌 Aim & Overview
A complete implementation of a PHP and MySQL Student Management System using standard procedural **mysqli** functions (without PDO or prepared statements) providing:
- Full CRUD operations (Create, Read, Update, Soft Delete)
- Search functionality by name or email
- Course-wise dynamic filtering
- Delete confirmation dialog
- Soft delete mechanism (`status = 'Inactive'`)
- User feedback alerts (success and error messages)
- Dynamic CSV file export for Microsoft Excel viewing

---

## 📋 Submission Checklist & Deliverables

| Requirement / Checklist Item | Implemented File | Description & Technical Highlights | Status |
| :--- | :--- | :--- | :---: |
| **Database Setup** | [`schema.sql`](file:///d:/WDF/Practical%2011/schema.sql) | `student_db` database & `students` table schema with seed records. | ✅ Verified |
| **Database Connection** | [`db.php`](file:///d:/WDF/Practical%2011/db.php) | Connects using standard `mysqli_connect()` with error handling. | ✅ Verified |
| **Main Management Page** | [`index.php`](file:///d:/WDF/Practical%2011/index.php) | Handles Create, Read, Search, Filter, Soft Delete, and CSV Export in one UI. | ✅ Verified |
| **Edit Student Page** | [`edit.php`](file:///d:/WDF/Practical%2011/edit.php) | Handles Update operation for existing records. | ✅ Verified |
| **CSV Export Script** | [`export_csv.php`](file:///d:/WDF/Practical%2011/export_csv.php) | Generates and streams `students_export.csv` using `fputcsv()`. | ✅ Verified |
| **CSS Stylesheet** | [`style.css`](file:///d:/WDF/Practical%2011/style.css) | Clean, responsive card-based layout with status badges. | ✅ Verified |
| **Word Lab Manual Report** | [`Practical_11_Lab_Manual.docx`](file:///d:/WDF/Practical%2011/Practical_11_Lab_Manual.docx) | CHARUSAT Lab Manual report with all 9 screenshots embedded. | ✅ Generated |

---

## 🛠️ SQL Statements Reference

### 1. Create (Insert):
```sql
INSERT INTO students (name, email, course, gender, status)
VALUES ('John Doe', 'john@example.com', 'Computer Science', 'Male', 'Active');
```

### 2. Read (Fetch All):
```sql
SELECT * FROM students ORDER BY id DESC;
```

### 3. Read (Search & Filter):
```sql
SELECT * FROM students
WHERE (name LIKE '%John%' OR email LIKE '%John%')
AND course = 'Computer Science'
ORDER BY id DESC;
```

### 4. Update:
```sql
UPDATE students
SET name = 'John Smith', email = 'johnsmith@example.com', course = 'Web Development',
gender = 'Male', status = 'Active'
WHERE id = 1;
```

### 5. Soft Delete:
```sql
UPDATE students SET status = 'Inactive' WHERE id = 1;
```

---

## 📸 Test Report Screenshots (All 9 Required Outputs)

1. **Student records displayed in a table:** [`p11_1_records_table.png`](file:///d:/WDF/Practical%2011/p11_1_records_table.png)
2. **Successful addition of a new student:** [`p11_2_add_student_success.png`](file:///d:/WDF/Practical%2011/p11_2_add_student_success.png)
3. **Successful update of student details:** [`p11_3_update_student_success.png`](file:///d:/WDF/Practical%2011/p11_3_update_student_success.png)
4. **Search functionality:** [`p11_4_search_functionality.png`](file:///d:/WDF/Practical%2011/p11_4_search_functionality.png)
5. **Course-wise filtering:** [`p11_5_course_filtering.png`](file:///d:/WDF/Practical%2011/p11_5_course_filtering.png)
6. **Delete confirmation message:** [`p11_6_delete_confirmation.png`](file:///d:/WDF/Practical%2011/p11_6_delete_confirmation.png)
7. **Successful soft delete (status changed to Inactive):** [`p11_7_soft_delete_success.png`](file:///d:/WDF/Practical%2011/p11_7_soft_delete_success.png)
8. **Success/error messages:** [`p11_8_success_error_messages.png`](file:///d:/WDF/Practical%2011/p11_8_success_error_messages.png)
9. **Exported CSV file opened in Excel:** [`p11_9_exported_csv_excel.png`](file:///d:/WDF/Practical%2011/p11_9_exported_csv_excel.png)

---

## 🌐 Localhost URL Endpoints
- **Student Management Dashboard:** [http://localhost:8000/practical11/index.php](http://localhost:8000/practical11/index.php)
- **CSV Direct Export:** [http://localhost:8000/practical11/export_csv.php](http://localhost:8000/practical11/export_csv.php)
