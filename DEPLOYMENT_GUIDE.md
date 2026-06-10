# Deployment Guide: industry.co.zw

This guide provides instructions for deploying your PHP/MySQL application to free hosting providers in 2026.

## Recommended Options

### Option 1: InfinityFree (Traditional Shared Hosting)
**Best for**: Easy all-in-one setup, FTP deployment, and cPanel-like management.

1.  **Sign Up**: Create an account at [InfinityFree.com](https://infinityfree.com/).
2.  **Create Account**: Add a new hosting account (you can use a free `.rf.gd` subdomain).
3.  **Database Setup**:
    *   Go to "MySQL Databases" in the control panel.
    *   Create a new database named `industry_co_zw`.
    *   Note the **Hostname**, **Username**, and **Password**.
    *   Use "phpMyAdmin" to import your `database/schema.sql`.
4.  **Upload Files**:
    *   Use an FTP client (like FileZilla) or the "Online File Manager".
    *   Upload all files from the root of your project into the `htdocs` directory.
5.  **Configuration**:
    *   Update `api/config/database.php` with the MySQL credentials provided by InfinityFree.
    *   *Note: Free shared hosts often don't support system environment variables. You may need to hardcode credentials in this specific file on the server.*

---

### Option 2: Render + Aiven (Modern Cloud Hosting)
**Best for**: Deployment directly from GitHub/GitLab and a more professional environment.

#### Part A: Database (Aiven)
1.  **Sign Up**: Create a free account at [Aiven.io](https://aiven.io/).
2.  **Create Service**: Start a new "MySQL" service on the **Free Tier**.
3.  **Connection Details**: Once active, copy the Service URI or the individual Host, Port, User, and Password.
4.  **Import Schema**: Use a tool like MySQL Workbench or a CLI to connect and run `database/schema.sql`.

#### Part B: Web Service (Render)
1.  **Prepare Repo**: Ensure your code is pushed to a GitHub or GitLab repository.
2.  **Sign Up**: Create an account at [Render.com](https://render.com/).
3.  **New Web Service**:
    *   Connect your repository.
    *   **Environment**: `PHP`.
    *   **Build Command**: (Leave empty).
    *   **Start Command**: `php -S 0.0.0.0:10000 -t .`
4.  **Environment Variables**: In the "Environment" tab, add the following:
    *   `DB_HOST`: Your Aiven host.
    *   `DB_NAME`: Your database name.
    *   `DB_USER`: Your Aiven username.
    *   `DB_PASSWORD`: Your Aiven password.
5.  **Deploy**: Render will automatically deploy your site.

---

## Post-Deployment Checklist

### 1. File Upload Permissions
Ensure the `uploads/` directory is writable by the web server. On shared hosts, this usually means setting permissions to `755` or `775`.

### 2. Update Site URLs
If your application uses absolute URLs (e.g., in `api/config/`), ensure they point to your new live domain instead of `localhost`.

### 3. Security Cleanup
**IMPORTANT**: Delete `generate-hash.php` and `database/seed-data.sql` from your live server after the initial setup to prevent sensitive data exposure.

### 4. Admin Access
Your admin credentials will be the same as they were on your local machine (imported from your SQL dump).
- URL: `https://your-domain.com/admin/login.php`
