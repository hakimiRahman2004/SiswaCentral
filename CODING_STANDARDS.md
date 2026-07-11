# 🧑‍💻 SISWACentral Coding Standards

## 📌 Overview

This document defines the coding standards for the **SISWACentral** project.

Purpose:

* Ensure consistent code structure
* Improve maintainability and scalability
* Avoid messy and unstructured code (especially with PHP OOP + Tailwind)

Tech Stack:

* HTML5
* Tailwind CSS (CLI)
* PHP (OOP + PDO)
* MySQL
* JavaScript (for interactivity)

---

# 🧱 1. PROJECT STRUCTURE RULES

```id="c0y4ra"
/app
 ├── controllers/
 ├── models/
 ├── views/
 ├── core/
 ├── helpers/

/public
 ├── assets/
 ├── uploads/

/config
 ├── database.php

/routes
 ├── web.php
```

## 📌 Rules:

* Controllers handle logic
* Models handle database operations
* Views handle UI only
* Do NOT place SQL queries inside views ❌

---

# 🧑‍💻 2. PHP CODING STYLE

## 📌 Naming Convention

| Item     | Style      | Example             |
| -------- | ---------- | ------------------- |
| Class    | PascalCase | `StudentController` |
| Function | camelCase  | `getStudentData()`  |
| Variable | camelCase  | `$userData`         |
| Constant | UPPER_CASE | `BASE_URL`          |

---

## 📌 Class Example

```php id="n8w7vt"
class StudentController {

    public function dashboard() {
        // logic here
    }

}
```

---

## 📌 Function Rules

* One function = one responsibility
* Avoid long functions (>50 lines)
* Use return values instead of echo inside models

---

# 🗄️ 3. DATABASE (PDO STANDARD)

## 📌 Rules:

* MUST use PDO (no mysqli ❌)
* Use prepared statements (for security)

## 📌 Example:

```php id="3p5hfb"
$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();
```

---

# 🔐 4. SECURITY RULES

## 📌 MUST:

* Use `password_hash()` for registration
* Use `password_verify()` for login
* Validate all inputs (`$_POST`, `$_GET`)
* Escape output using `htmlspecialchars()`

## 📌 Example:

```php id="v8c6a9"
echo htmlspecialchars($user['name']);
```

---

# 🎨 5. TAILWIND CSS STANDARD

## 📌 Rules:

* Use utility classes only
* Do NOT use inline CSS ❌
* Maintain consistent spacing:

  * `p-4`, `p-6`
  * `mt-4`, `mb-6`

## 📌 Layout Pattern:

* Sidebar + Navbar + Content
* Card-based UI

---

## 📌 Example Card

```html id="kw47y1"
<div class="bg-white rounded-xl shadow p-6">
  <h2 class="text-lg font-semibold">Title</h2>
</div>
```

---

# ⚡ 6. JAVASCRIPT RULES

## 📌 Rules:

* Use JavaScript only for interactivity
* Avoid heavy logic on frontend
* Use event listeners

## 📌 Example:

```javascript id="z5u9kp"
document.getElementById("menuBtn").addEventListener("click", () => {
    document.getElementById("sidebar").classList.toggle("hidden");
});
```

---

# 📂 7. FILE NAMING RULES

| Type       | Format                  |
| ---------- | ----------------------- |
| Controller | `StudentController.php` |
| Model      | `Student.php`           |
| View       | `dashboard.php`         |

---

# 🔁 8. MVC RULES

## 📌 Controller:

* Handles requests
* Calls models
* Passes data to views

## 📌 Model:

* Handles database queries
* Returns data

## 📌 View:

* Displays UI only
* No database queries ❌

---

# 📊 9. COMMENTS & DOCUMENTATION

## 📌 Rules:

* Comment only important logic
* Avoid excessive comments

## 📌 Example:

```php id="p9h2sd"
// Check if user exists before login
```

---

# 🚫 10. BAD PRACTICES (AVOID)

* SQL inside views ❌
* Inline CSS ❌
* Hardcoded URLs ❌
* Duplicate code ❌
* Overly long functions ❌

---

# ✅ 11. BEST PRACTICES

* Use reusable components (header, sidebar)
* Use helper functions
* Keep logic separated properly
* Write clean and readable code

---

# 🚀 CONCLUSION

By following these coding standards:

* The project becomes scalable
* Easier to maintain
* Easier to debug
* Looks professional (important for SaaS systems)

---

🔥 Follow these standards strictly to maintain high-quality code in SISWACentral
