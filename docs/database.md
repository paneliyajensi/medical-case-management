# Database Documentation - MedCase

This document details the database schema, data dictionary, relationships, and indexing decisions for the MedCase application.

## Entity Relationship Overview

```
 +-------------------------+               +--------------------------------------+
 |          users          |               |                cases                 |
 +-------------------------+               +--------------------------------------+
 | id (PK, unsigned bigint)| 1           * | id (PK, unsigned bigint)             |
 | name (varchar 255)      |---------------+ created_by (FK -> users.id)          |
 | email (varchar 255, UQ) |               | case_number (varchar 255, UNIQUE)    |
 | password (varchar 255)  |               | patient_reference (varchar 255)      |
 | role (varchar 255)      |               | surgeon_name (varchar 255)           |
 | remember_token          |               | implant_type (varchar 255)           |
 | created_at, updated_at  |               | status (varchar 255, INDEXED)        |
 +-------------------------+               | priority (varchar 255)               |
                                           | surgery_date (date, NULLABLE)        |
                                           | created_at, updated_at (timestamps)  |
                                           +--------------------------------------+
```

---

## Tables and Columns

### 1. `users`
Stores user credentials and role authorizations.

| Column | Type | Nullable | Key / Index | Description |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary Key, Auto Increment | Unique user identifier |
| `name` | VARCHAR(255) | No | - | Full name of the user |
| `email` | VARCHAR(255) | No | UNIQUE | User login email |
| `password` | VARCHAR(255) | No | - | Bcrypt-hashed password |
| `role` | VARCHAR(255) | No | - | User role (`Admin` or `Viewer`) |
| `remember_token`| VARCHAR(100) | Yes | - | Persistent session token |
| `created_at` | TIMESTAMP | Yes | - | Record creation timestamp |
| `updated_at` | TIMESTAMP | Yes | - | Record update timestamp |

### 2. `cases`
Stores medical implant case lifecycle data.

| Column | Type | Nullable | Key / Index | Description |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary Key, Auto Increment | Unique record identifier |
| `case_number` | VARCHAR(255) | No | UNIQUE INDEX | Business case tracking identifier (e.g., `CASE-1001`) |
| `patient_reference` | VARCHAR(255) | No | - | Anonymized patient code (e.g., `PAT-8812`) |
| `surgeon_name` | VARCHAR(255) | No | - | Operating lead surgeon |
| `implant_type` | VARCHAR(255) | No | - | Medical implant device type |
| `status` | VARCHAR(255) | No | INDEX | Current status: `Draft`, `Planning`, `In Review`, `Approved`, `Completed`, `Cancelled` |
| `priority` | VARCHAR(255) | No | - | Case priority: `Low`, `Medium`, `High` |
| `surgery_date` | DATE | Yes | - | Scheduled surgery date |
| `created_by` | BIGINT UNSIGNED | No | FOREIGN KEY -> `users(id)` | User who created the case (Cascades on user deletion) |
| `created_at` | TIMESTAMP | Yes | - | Record creation timestamp |
| `updated_at` | TIMESTAMP | Yes | - | Record update timestamp |

---

## Indexing & Performance Rationale

1. **`cases.case_number` (UNIQUE Index)**:
   - Ensures strict uniqueness of medical case numbers across the system.
   - Provides \(O(1)\) lookup when searching or retrieving individual cases by case number.

2. **`cases.status` (Single-column Index)**:
   - Optimized for dashboard status aggregation counters (`COUNT(*) WHERE status = ?`) and filter queries on the case listing page.

3. **`cases.created_by` (Foreign Key Index)**:
   - Automatically indexed for foreign key referential integrity checks and join performance when retrieving user relationship data.
