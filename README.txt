MobileBanking - mb-secure-4321
-----------------------------

What is included:
- PHP files for user registration, login, dashboard, transfers (pending), admin panel and settings.
- SQL file 'install.sql' to create required tables and a seeded admin row.
- Config file: includes/config.php already set to your DB credentials.
- Upload directories: public/uploads for profile pictures and messages attachments.
- Admin panel URL: /mb-secure-4321-admin (rename folder or path in config if desired).

Install steps (InfinityFree):
1. Upload all files into your htdocs folder.
2. Import install.sql via phpMyAdmin into database 'if0_39682543_walletdb'.
3. Ensure includes/config.php has DB credentials (already set).
4. Visit /public/register.php to create a new user or /admin/login.php to login as admin.
   Default admin email: nobleearnltd@gmail.com (change password ASAP using password reset).
5. Configure SMTP in Admin > Settings to enable email notifications.

Security note:
- This is a development prototype. Do not use with real funds or production without a security audit.
- Change default passwords and secure your SMTP credentials.

