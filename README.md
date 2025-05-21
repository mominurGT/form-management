## Local Setup Instructions for Form Management System Project

This guide outlines the steps to clone and set up Form Management System application GitHub repository.

### **Prerequisites**

Ensure your development environment includes the following:

* PHP >= 8.2
* Composer >= 2.5.8
* Node.js >= 20.17.0
* NPM >= 10.8.2
* MySQL >= 8
* Laravel Verion >= 12.14.1
* Git


### **Step-by-Step Setup Process**

#### 1. **Clone the Repository**

```bash
git clone https://github.com/mominurGT/form-management.git
cd your-repository
```

#### 2. **Install PHP Dependencies**

```bash
composer install
```

#### 3. **Set Up Environment Configuration**

Copy the example environment file and modify it according to your local configuration:

```bash
cp .env.example .env
```

Then update the following variables in your `.env` file:

```dotenv

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### 4. **Generate the Application Key**

```bash
php artisan key:generate
```

#### 5. **Install Frontend Dependencies**

```bash
npm install
```

#### 6. **Compile Frontend Assets**
```bash
npm run dev
```

#### 7. **Create the Database**

Manually create a new MySQL database with the name specified in your `.env` file.

#### 8. **Run Migrations and Seeders**

Execute the following command to create tables and seed initial data:

```bash
php artisan migrate
php artisan db:seed
```

#### 9. **Start the Laravel Development Server**

```bash
php artisan serve
```

Visit the application at: [http://127.0.0.1:8000](http://127.0.0.1:8000)



### Additional Notes

* The Project User Manual video is available in the project's public folder.
https://drive.google.com/file/d/1jj3qx3iLcHWMWz-eU18HilRF31TWKXma/view?usp=sharing


* **When running the database seeder**, two default admin users and two form configurations are created.

**Admin Users:**

* **Admin One**
  Email: `adminone@gmail.com`
  Password: `AdminOne123`

* **Admin Two**
  Email: `admintwo@gmail.com`
  Password: `AdminTwo123`

You can log in using these credentials or register a new user as needed.