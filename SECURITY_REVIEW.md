# Security Review: industry.co.zw

## 1. Identified Risks

### **Hardcoded Credentials & Sensitive Information**
*   **`api/config/database.php`**: Contains hardcoded database credentials (`localhost`, `root`, and an empty password).
*   **`generate-hash.php`**: Contains a hardcoded plaintext password (`Admin@2026!`). This file should be removed from production environments.
*   **`database/schema.sql`**: Contains a placeholder for the admin password hash.

### **Insecure Direct Object References (IDOR)**
*   Many API endpoints (e.g., `admin/api/members.php?id=...`) rely on simple integer IDs. While protected by an admin check, ensuring strict ownership checks is recommended if multi-tenant functionality is ever added.

### **Configuration Management**
*   Environment-specific settings (database host, credentials, debug levels) are currently baked into the PHP files or `render.yaml`.

### **Data Exposure in Backups/Dumps**
*   SQL dumps of the production database (like the one provided) contain sensitive information such as admin password hashes and private contact details. These should be stored in encrypted locations and never shared in public or insecure channels.

## 2. Recommendations

### **Implement Environment Variables**
*   Move all sensitive configuration to a `.env` file (not committed to version control).
*   Use a library like `phpdotenv` or PHP's built-in `getenv()` to access these variables.
*   Update `api/config/database.php` to use these variables:
    ```php
    $this->host = getenv('DB_HOST');
    $this->db_name = getenv('DB_NAME');
    $this->username = getenv('DB_USER');
    $this->password = getenv('DB_PASSWORD');
    ```

### **Cleanup Development Artifacts**
*   Delete `generate-hash.php` once the initial admin account is created.
*   Ensure `database/seed-data.sql` is not accessible or executed in production.

### **Enhance Input Validation & CSRF Protection**
*   Ensure all POST/PUT/DELETE requests in the admin panel include a CSRF token to prevent cross-site request forgery.
*   Implement stricter validation in the specialized CSV import tool (`admin/import-csv.php`) to prevent malicious file uploads or path traversal.

### **Secure Headers**
*   Implement security-related HTTP headers (e.g., `Content-Security-Policy`, `X-Frame-Options`, `X-Content-Type-Options`) in `includes/head.php` or via `.htaccess`.
