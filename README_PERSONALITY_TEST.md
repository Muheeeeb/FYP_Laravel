# Personality Test Module

This module adds personality assessment functionality to the job application process. When candidates apply for a job, they will be asked to complete a personality test after submitting their basic information and resume.

## Features

- Personality test with Likert-scale and multiple-choice questions
- Automatic scoring and trait analysis
- Personality profile generation for each candidate
- Detailed results visible to HODs when reviewing candidates
- Integration with the existing job application workflow

## Installation

1. Run the migrations to create the necessary database tables:

```
php artisan migrate
```

2. Seed the database with default personality test questions:

```
php artisan db:seed --class=PersonalityQuestionSeeder
```

Alternatively, you can run the `setup_personality_test.bat` file to perform both steps at once.

## Workflow

1. Candidate applies for a job position
2. After submitting basic information and resume, they are redirected to the personality test
3. Candidate completes all questions in the personality test
4. Upon completion, a personality profile with traits analysis is generated
5. HODs can view the personality test results when reviewing candidates for their department

## Customizing Questions

To customize the personality test questions, you can:

1. Edit the `database/seeders/PersonalityQuestionSeeder.php` file
2. Add/modify questions in the `$questions` array
3. Run the seeder to update the questions:

```
php artisan db:seed --class=PersonalityQuestionSeeder
```

## Traits Analyzed

The personality test analyzes the following traits:

- Teamwork
- Problem Solving
- Risk Taking
- Adaptability
- Structure Preference
- Leadership
- Stress Management
- Innovation
- Attention to Detail
- Communication 