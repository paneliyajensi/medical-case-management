# MedCase &mdash; Medical Case Management System

> **Practical Assessment for Software Developer Intern &bull; @Jajal Medical**  
> Scaled-down implant lifecycle tracking web application built with **Laravel 10**, **MySQL (MariaDB)**, **Blade**, and **Laravel Sanctum**.

---

## 1. Project Overview & Tech Stack

A medical device company tracks implant cases from draft to completion. Authorized users log in, manage surgical cases, observe live status pipelines on a clinical dashboard, and interact with the case registry both via a responsive web portal and a REST API authenticated with Sanctum tokens.

### Tech Stack
- **Backend Framework:** Laravel 10.x (PHP 8.2+)
- **Database:** MySQL / MariaDB (InnoDB, relational migrations, foreign keys, unique & status indexes)
- **Frontend / UI:** Laravel Blade templates, custom responsive CSS (styled with medical device aesthetic, optimized for desktop and mobile)
- **Authentication:** Session-based auth for Web + Laravel Sanctum Bearer tokens for REST API
- **Authorization:** Laravel Policies (`MedicalCasePolicy`) with role-based access control (`Admin` vs `Viewer`)
- **Testing:** PHPUnit automated feature test suite (in-memory SQLite test database for rapid CI execution)
- **API Tooling:** Exported Postman / Thunder Client collection (`MedCase_API_Collection.json`)

---

## 2. Test Login Credentials (Dummy Accounts)

The database seeder automatically initializes two test users so reviewers can immediately log in and evaluate both access levels:

| Role | Email Address | Password | Permissions |
|---|---|---|---|
| **Admin** | `admin@medcase.local` | `password123` | View cases, Create cases, Edit cases, Change status, Delete cases |
| **Viewer** | `viewer@medcase.local` | `password123` | View cases & details only (Cannot create, edit, or delete) |

*(Note: On the login page `/login`, convenient 1-click credential buttons are also provided to fill these credentials automatically).*

---

## 3. Setup & Installation Instructions

Follow these steps to run the project locally:

### Prerequisites
- PHP 8.2 or higher (with `pdo_mysql`, `pdo_sqlite`, `mbstring`, `openssl`, `curl` extensions enabled)
- Composer 2.x
- MySQL or MariaDB server

### Step-by-Step Setup

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd MedCase
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Configure environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Verify your MySQL database settings in `.env` (default is database `medcase` on `127.0.0.1:3306` with user `root`).*

4. **Run migrations and seed dummy data:**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *This creates all tables, indexes, 2 role accounts (Admin & Viewer), and seeds 24 realistic dummy implant cases across various statuses.*

5. **Start local development server:**
   ```bash
   php artisan serve
   ```
   The application is now live at: **`http://127.0.0.1:8000`**

---

## 4. How to Run Automated Tests

The automated test suite verifies all 6 mandatory feature requirements specified in the assignment PDF:

```bash
php artisan test
```

### Verified Test Cases:
1. `test_valid_login_succeeds`: Valid credentials authenticate and redirect to dashboard.
2. `test_invalid_login_is_rejected`: Invalid password returns clear error feedback.
3. `test_guest_cannot_open_case_pages_or_apis`: Unauthenticated web requests redirect to `/login`; API requests return `401 Unauthorized`.
4. `test_admin_can_create_a_case`: Admin successfully creates cases via Web form and Sanctum API.
5. `test_creating_case_with_duplicate_case_number_fails`: Duplicate case number is rejected by Form Request with `422 Unprocessable Entity`.
6. `test_viewer_cannot_delete_a_case`: Server blocks Viewer deletion attempts with `403 Forbidden` (in both Web and API).
7. `test_api_login_and_logout_flow`: Issues and revokes Sanctum tokens.
8. `test_admin_can_update_case_and_self_unique_is_ignored`: Allows case editing while ignoring self `case_number` collision.
9. `test_api_returns_404_when_case_not_found`: Consistent 404 JSON response for missing resource IDs.
10. `test_admin_can_delete_a_case`: Admin successfully deletes a case.

---

## 5. REST API Documentation

All API endpoints return JSON in a consistent schema:

```json
{
  "success": true,
  "data": { ... },
  "message": "Human-readable status message."
}
```

Error responses (401, 403, 404, 422) follow the matching schema:
```json
{
  "success": false,
  "data": null,
  "message": "Validation failed.",
  "errors": {
    "case_number": ["This Case Number has already been registered."]
  }
}
```

### Endpoints Table

| Method | Endpoint | Description | Who Can Call | Auth Required |
|---|---|---|---|---|
| `POST` | `/api/login` | Authenticate and obtain Sanctum Bearer token | Anyone | No |
| `POST` | `/api/logout` | Revoke current token | Logged-in | Yes (Bearer) |
| `GET` | `/api/cases` | Paginated case listing (`?page=1&status=...&search=...`) | Admin, Viewer | Yes (Bearer) |
| `POST` | `/api/cases` | Create a new medical implant case | Admin | Yes (Bearer) |
| `GET` | `/api/cases/{id}` | Retrieve details of a single case | Admin, Viewer | Yes (Bearer) |
| `PUT` | `/api/cases/{id}` | Update an existing case | Admin | Yes (Bearer) |
| `DELETE` | `/api/cases/{id}` | Delete a case | Admin | Yes (Bearer) |

> **Postman / Thunder Client Export:** A ready-to-import API collection is included in the project root: `MedCase_API_Collection.json`.

---

## 6. Application Screens (Blade Views)

1. **Login / Logout Screen (`/login`):** Built-in session auth, role redirection, error display, and quick-fill demo buttons.
2. **Dashboard (`/dashboard`):** Real-time status metric counters for **Total Cases**, **Planning**, **In Review**, **Approved**, and **Completed**, along with a recent cases audit feed.
3. **Case Registry (`/cases`):** Paginated table (10 per page) with search by Case Number or Surgeon Name, filter by Status, and role-guarded View/Edit/Delete action buttons.
4. **Create / Edit Case Form (`/cases/create`, `/cases/{id}/edit`):** Single reusable Blade form partial (`cases/_form.blade.php`) with field-level validation error highlights next to each input.
5. **Case Details Page (`/cases/{id}`):** Comprehensive specification view displaying all case fields, plus full provenance metadata showing who created the case and when.

---

## 7. Assumptions & Known Limitations

- **Dummy Data Only:** In accordance with medical device compliance requirements, all patient identifiers are strictly dummy alphanumeric codes (e.g., `PAT-8812`) and no real patient identifiable information (PII) is stored.
- **Single Implant per Case:** Each surgical case is linked to a single implant device type. Multi-component revision surgeries can be tracked by generating sequential case identifiers.
- **Session Lifetimes:** Sanctum tokens do not expire by default unless explicitly revoked via `/api/logout`. Web session cookies expire according to `SESSION_LIFETIME` in `.env` (default 120 minutes).
- **Environment Exclusions:** Production secrets, encryption keys, and environment passwords are excluded from Git; `.env.example` provides the canonical template.

---

## 8. Requirements Traceability

See [docs/traceability.md](file:///c:/Users/himan/Downloads/MedCase/docs/traceability.md) for the bidirectional requirements traceability matrix linking specification IDs (`REQ-001`, `REQ-002`, `REQ-003`) to controller code and automated feature tests.

See [docs/database.md](file:///c:/Users/himan/Downloads/MedCase/docs/database.md) for database schema definitions, relations, and indexing rationale.
