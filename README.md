# ATW Back-end Task

This is a Laravel application that serves as a task for the Back-end department at ATW Ltd. The application includes an authentication system with Sanctum, tags and posts API resources, and scheduled jobs.

## Requirements

- PHP 8.0 or later
- MySQL

## Installation

1. Clone the repository: git clone https://github.com/sowidan1/ATW-Ltd.git

2. Install dependencies:
   
cd atw-backend-task
composer install

3. Create a new `.env` file by duplicating `.env.example` and update the database credentials.

4. Generate an application key: php artisan key:generate

5. Migrate the database and seed with initial data: php artisan migrate --seed

6. Start the development server: php artisan serve

## API Endpoints

### Authentication

- `POST /api/register`: Registers a new user with name, phone number, and password.
- `POST /api/login`: Authenticates a user and returns an access token.

### Tags

- `GET /api/tags`: Lists all tags (requires authentication).
- `POST /api/tags`: Creates a new tag (requires authentication).
- `PUT /api/tags/{id}`: Updates an existing tag (requires authentication).
- `DELETE /api/tags/{id}`: Deletes a tag (requires authentication).

### Posts

- `GET /api/posts`: Lists all posts created by the authenticated user.
- `POST /api/posts`: Creates a new post (requires authentication).
- `GET /api/posts/{id}`: Retrieves a specific post created by the authenticated user.
- `PUT /api/posts/{id}`: Updates a post created by the authenticated user.
- `DELETE /api/posts/{id}`: Soft-deletes a post created by the authenticated user.
- `GET /api/posts/deleted`: Lists all soft-deleted posts created by the authenticated user.
- `PUT /api/posts/{id}/restore`: Restores a soft-deleted post created by the authenticated user.

### Statistics

- `GET /api/stats`: Returns statistics about users and posts.

## Scheduled Jobs

- A daily job that force-deletes all soft-deleted posts older than 30 days.
- A job that runs every six hours, makes an HTTP request to `https://randomuser.me/api/`, and logs the results object in the response.

