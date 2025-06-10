# HireSmart - Job Application Management System

A comprehensive web application built with Laravel for managing job applications, candidate assessments, and hiring workflows.

## Features

- **Job Posting Management**
  - Create and manage job postings
  - Define job requirements and descriptions
  - Track application deadlines

- **Application Processing**
  - Online application submission
  - Resume/CV upload and parsing
  - Automated candidate ranking
  - Personality assessment integration

- **Multi-Role System**
  - HR Portal
  - HOD (Head of Department) Dashboard
  - Dean's Approval Workflow
  - Applicant Interface

- **Smart Features**
  - AI-powered CV ranking
  - Automated skills matching
  - Interview scheduling
  - Email notifications

## Requirements

- PHP >= 8.1
- MySQL
- Composer
- Node.js & NPM
- Laravel 10.x

## Installation

1. Clone the repository
```bash
git clone <repository-url>
cd hiresmart
```

2. Install dependencies
```bash
composer install
npm install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Set up database
```bash
php artisan migrate
php artisan db:seed
```

5. Set up storage
```bash
php artisan storage:link
```

6. Start the development server
```bash
php artisan serve
npm run dev
```

## Environment Variables

Make sure to set the following environment variables in your .env file:

```env
APP_NAME="HireSmart"
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
```

## Project Structure

- `app/` - Contains the core code of the application
- `database/` - Contains database migrations and seeders
- `resources/` - Contains views, raw assets, and localization files
- `routes/` - Contains all route definitions
- `public/` - Contains publicly accessible files

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests.

## Authors

- **Nimra** - *Initial work and development*

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

- SZABIST University
- Faculty and advisors
- All contributors and testers
