````text
# Fake Tweet Generator - PHP Version
## Installation & Deployment Guide

### 📋 Requirements
- PHP 7.4 or higher
- Web server (Apache/Nginx)
- Write permissions for uploads directory

### 🚀 Quick Setup

#### Option 1: Local Development (XAMPP/WAMP)
1. Download and install XAMPP/WAMP
2. Create a new folder in `htdocs` (e.g., `fake-tweet-generator`)
3. Copy `index.php` and `.htaccess` to the folder
4. Create an `uploads` folder with write permissions
5. Visit `http://localhost/fake-tweet-generator`

#### Option 2: Shared Hosting
1. Upload `index.php` and `.htaccess` to your web root
2. Create an `uploads` folder with 755 permissions
3. Ensure PHP file uploads are enabled
4. Visit your domain

#### Option 3: VPS/Dedicated Server
1. Upload files to your document root
2. Set proper permissions:
   ```bash
   chmod 644 index.php
   chmod 644 .htaccess
   chmod 755 uploads
````

3. Configure your web server
4. Enable PHP extensions if needed

### 🔧 Server Configuration

#### Apache Configuration

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/html/fake-tweet-generator
    
    <Directory /var/www/html/fake-tweet-generator>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Enable PHP
    <FilesMatch \.php$>
        SetHandler application/x-httpd-php
    </FilesMatch>
</VirtualHost>
```

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/html/fake-tweet-generator;
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location /uploads {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### 📁 File Structure

```
fake-tweet-generator/
├── index.php          # Main application file
├── .htaccess          # Apache configuration
├── uploads/           # Image uploads directory
│   └── .gitkeep      # Keep directory in version control
└── README.md         # This file
```

### 🔐 Security Considerations

1. **File Upload Security:**

   * Only allow image files
   * Validate file types and sizes
   * Store uploads outside web root if possible

2. **Input Validation:**

   * All user inputs are sanitized with htmlspecialchars()
   * File uploads are validated

3. **Server Security:**

   * Disable directory listing
   * Set appropriate file permissions
   * Use HTTPS in production

### 🎨 Customization

#### Styling

* Edit the `<style>` section in `index.php`
* Add custom CSS files
* Modify Bootstrap classes

#### Features

* Add more social media platforms
* Implement user accounts
* Add tweet templates
* Create API endpoints

### 📱 Mobile Responsiveness

* Uses Bootstrap 5 responsive grid
* Optimized for mobile devices
* Touch-friendly interface

### 🐛 Troubleshooting

#### Common Issues:

1. **"Permission denied" errors:**

   ```bash
   chmod 755 uploads/
   chown www-data:www-data uploads/
   ```

2. **File upload not working:**

   * Check PHP upload settings in php.ini
   * Verify upload\_max\_filesize and post\_max\_size
   * Ensure uploads directory exists

3. **Screenshots not working:**

   * html2canvas requires modern browser
   * Check for JavaScript errors in console
   * Fallback to manual screenshot methods

### 🌐 Deployment Options

#### Free Hosting:

* 000webhost
* InfinityFree
* Freehostia

#### Paid Hosting:

* HostGator
* Bluehost
* SiteGround
* DigitalOcean

#### Cloud Platforms:

* Heroku (with PHP buildpack)
* AWS EC2
* Google Cloud Platform

### 📝 Environment Variables (Optional)

Create a `.env` file for sensitive settings:

```
UPLOAD_MAX_SIZE=10M
ALLOWED_EXTENSIONS=jpg,jpeg,png,gif,webp
MAX_FILE_SIZE=5242880
```

### 🔄 Updates & Maintenance

* Regularly update PHP version
* Monitor upload directory size
* Clean old uploaded files
* Update dependencies

### 📞 Support

For issues or questions:

* Check server error logs
* Verify PHP configuration
* Test file permissions
* Review browser console for JavaScript errors

***

**⚠️ Legal Disclaimer:** This tool is for entertainment purposes only. Creating fake social media posts to deceive others may be harmful and is not recommended. Use responsibly and in accordance with applicable laws and platform terms of service.

```
```

