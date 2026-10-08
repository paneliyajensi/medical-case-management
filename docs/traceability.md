# Medical Software Requirements Traceability Matrix (RTM)

This document establishes bidirectional traceability between system requirements, application code implementation, and automated test verification, following medical device software quality management principles (IEC 62304 / ISO 13485 concepts).

| ID | Requirement (input) | Implementation (output) | Verification |
|---|---|---|---|
| **REQ-001** | Admin shall be able to create a case | `app/Http/Controllers/CaseController.php@store`<br>`app/Http/Controllers/Api/CaseApiController.php@store`<br>`resources/views/cases/create.blade.php`<br>`resources/views/cases/_form.blade.php` | `tests/Feature/CaseManagementTest.php::test_admin_can_create_a_case`<br>*(Web POST + API POST with Sanctum token)* |
| **REQ-002** | Case Number shall be unique | Unique DB Index (`2026_10_08_000001_create_cases_table.php`)<br>Form Request rule (`app/Http/Requests/StoreCaseRequest.php`)<br>Update ignore rule (`app/Http/Requests/UpdateCaseRequest.php`) | `tests/Feature/CaseManagementTest.php::test_creating_case_with_duplicate_case_number_fails`<br>*(Web session errors + API 422 HTTP response)* |
| **REQ-003** | Only Admin shall delete cases | Authorization Policy (`app/Policies/MedicalCasePolicy.php@delete`)<br>Web Controller check (`CaseController.php@destroy`)<br>API Controller check (`CaseApiController.php@destroy`)<br>Blade `@can('delete', $case)` conditional rendering | `tests/Feature/CaseManagementTest.php::test_viewer_cannot_delete_a_case`<br>*(Blocks Viewer with HTTP 403 in Web and API; asserts case is retained in DB)* |

---

## Traceability Rationale & Compliance Notes

1. **REQ-001 (Implant Case Registration)**:
   - Ensures authorized surgical coordinators and administrators can create new implant tracking cases with full audit provenance (`created_by` captured automatically from the authenticated session).
   - Validated both in the user-facing web interface and via headless REST API.

2. **REQ-002 (Unique Case Identifier)**:
   - Eliminates duplicate clinical tracking numbers. Enforced at both the database schema layer (`UNIQUE KEY (case_number)`) and application layer (`unique:cases,case_number` in Form Request).
   - Edit requests safely ignore the current case ID to permit updating other metadata without triggering false uniqueness conflicts.

3. **REQ-003 (Access Control & Regulatory Deletion Protection)**:
   - Hiding the UI delete button from Viewers is insufficient for clinical data integrity; server-side enforcement blocks unauthorized DELETE HTTP verbs with standard `403 Forbidden` responses.
