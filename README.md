# Diary Service

A RESTful diary API built with Laravel 13, Laravel Sanctum, and SQLite. It supports account registration and token authentication, plus private diary entries that are searchable, sortable, and filterable.

## Features

- User registration, login, logout, and password reset
- Bearer-token authentication with Laravel Sanctum
- Create, list, view, update, and delete diary entries
- One diary entry per user per date
- Search by title, content, mood, weather, or emoji text
- Filter by favourite status and date range
- Sort by entry date, title, mood, or weather
- Pagination with eight entries per page
- Email verification and signed verification routes
- User-owned data enforcement through policies

## Requirements

- PHP 8.3 or newer
- Composer
- Node.js and npm
- SQLite
- XAMPP or another local PHP server

## Installation

From the project directory:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

On Windows PowerShell, create `.env` with:

```powershell
Copy-Item .env.example .env
```

The project uses SQLite by default. Make sure `.env` contains:

```env
DB_CONNECTION=sqlite
```

Also ensure `database/database.sqlite` exists:

```powershell
New-Item -ItemType File database/database.sqlite -Force
php artisan migrate
```

## Running the API

Start the Laravel development server:

```bash
php artisan serve
```

The API base URL is:

```text
http://127.0.0.1:8000/api/v1
```

You can instead run the application under XAMPP from `C:\xampp\htdocs\Small Api\play-ground\public`.

## Authentication

Protected endpoints require a Sanctum bearer token:

```http
Authorization: Bearer <token>
Accept: application/json
```

### Register

```http
POST /api/v1/register
Content-Type: application/json
```

```json
{
  "name": "Ada Lovelace",
  "email": "ada@example.com",
  "password": "Strong-password1!"
}
```

Registration returns HTTP `204 No Content`.

### Login

```http
POST /api/v1/login
Content-Type: application/json
```

```json
{
  "email": "ada@example.com",
  "password": "Strong-password1!"
}
```

Response:

```json
{
  "user": {
    "name": "Ada Lovelace",
    "email": "ada@example.com"
  },
  "token": "1|generated-sanctum-token"
}
```

### Logout

```http
POST /api/v1/logout
Authorization: Bearer <token>
```

Logout deletes the current access token and returns HTTP `204 No Content`.

### Get the authenticated user

```http
GET /api/v1/user
Authorization: Bearer <token>
```

## Diary Endpoints

All diary endpoints require authentication.

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/v1/diaries` | List diary entries |
| `POST` | `/api/v1/diaries` | Create a diary entry |
| `GET` | `/api/v1/diaries/{diary}` | View a diary entry |
| `PUT` or `PATCH` | `/api/v1/diaries/{diary}` | Update a diary entry |
| `DELETE` | `/api/v1/diaries/{diary}` | Delete a diary entry |

### List diaries

```http
GET /api/v1/diaries
Authorization: Bearer <token>
Accept: application/json
```

Optional query parameters:

| Parameter | Type | Description |
|---|---|---|
| `search` | string | Search title, content, mood, and weather |
| `favourite` | boolean | Filter favourite entries |
| `start_date` | `Y-m-d` | Include entries from this date |
| `end_date` | `Y-m-d` | Include entries through this date |
| `sort_by` | string | `entry_date`, `title`, `mood`, or `weather` |
| `sort_dir` | string | `asc` or `desc` |
| `page` | integer | Laravel paginator page number |

Example:

```text
GET /api/v1/diaries?search=happy&favourite=true&start_date=2026-01-01&sort_by=entry_date&sort_dir=desc&page=1
```

### Create a diary

```http
POST /api/v1/diaries
Authorization: Bearer <token>
Content-Type: application/json
```

```json
{
  "title": "A good day",
  "content": "I completed the first milestone and learned something new.",
  "entry_date": "2026-05-03",
  "mood": "happy",
  "weather": "sunny",
  "is_favourite": true
}
```

Validation rules:

- `title`: optional, string, maximum 255 characters
- `content`: required when creating, string, 5-5000 characters
- `entry_date`: required, `Y-m-d`, cannot be in the future, unique per user
- `mood`: optional, string, maximum 50 characters
- `weather`: optional, string, maximum 50 characters
- `is_favourite`: optional boolean

A successful create returns HTTP `201 Created`.

### Update a diary

```http
PATCH /api/v1/diaries/1
Authorization: Bearer <token>
Content-Type: application/json
```

```json
{
  "title": "An excellent day",
  "mood": "excited",
  "is_favourite": false
}
```

All update fields are optional. A successful update returns HTTP `201 Created`.

### Delete a diary

```http
DELETE /api/v1/diaries/1
Authorization: Bearer <token>
```

Returns HTTP `204 No Content`.

## Example Response

```json
{
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "title": "A good day",
      "content": "I completed the first milestone and learned something new.",
      "entry_date": "2026-05-03",
      "mood": "happy",
      "weather": "sunny",
      "boolean": true
    }
  ],
  "links": {},
  "meta": {}
}
```

## Password and Email Routes

The API also exposes these authentication routes:

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/v1/forgot-password` | Send a password reset link |
| `POST` | `/api/v1/reset-password` | Reset a password |
| `GET` | `/api/v1/verify-email/{id}/{hash}` | Verify an email address |
| `POST` | `/api/v1/email/verification-notification` | Resend a verification notification |

The email verification and verification notification routes require authentication.

## Testing

Run the Pest test suite:

```bash
php artisan test
```

Reset the local database and run migrations:

```bash
php artisan migrate:fresh
```

## Project Structure

```text
app/
  Http/
    Controllers/
      API/V1/DiaryController.php
      Auth/
    Requests/
    Resources/
  Models/
    Diary.php
    User.php
  Policies/
database/
  migrations/
  seeders/
routes/
  api.php
  auth.php
tests/
  Feature/
```

## License

This project is available under the MIT license, following the Laravel application template.
