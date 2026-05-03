# Changelog

Все существенные изменения в этом проекте будут задокументированы в этом файле.

Данный формат основан на системе Keep a Changelog.

## [Unreleased]

## [0.3.0] - 2026-05-30

### Added
- JS: conditional field visibility by selected type (`type_val`)
- `app/Modules/Example/resources/cabinet/js/app.js` — Vanilla JS, IIFE pattern
- Fields and buttons are shown/hidden based on whether their `name` contains the selected value
- Visibility applied on page load and on every `change` event

## [0.2.0] - 2026-05-28

### Added
- REST API: `GET /api/events` — paginated list of earthquake events
- REST API: `GET /api/events/{id}` — single event by ID
- `EarthquakeRepository::getListPaginated()` and `findById()`
- `EventResource` — API resource with extensible field set
- Unified API response layer: `ApiResponse`, `ApiSuccessResponse`, `ApiErrorResponse`
- `ApiPaginator` — pagination metadata DTO
- Full exception handling in `Handler::handleApiException()`: 400, 401, 403, 404, 405, 409, 413, 422, 423, 4XX fallback, 5XX default
- Feature tests: `ExceptionHandlerTest`, `EventsControllerTest`
- Unit tests: `ApiResponseTest`

### Fixed
- `Handler`: 405 Method Not Allowed returned HTML instead of JSON when `Accept` header was absent (middleware hadn't run yet at routing stage)

### Changed
- Project version bumped to v0.2.0

## [0.1.0] - 2026-05-07

### Added
- AFAD earthquake ingestion pipeline
- Console command `earthquake:update`
- External API integration layer (AfadEarthquakeProvider)
- DTO for earthquake events (`EarthquakeEventDTO`)
- Repository with bulk upsert persistence (idempotent by `event_id`)
- Sliding time window sync strategy (UTC-based)

### Changed
- Project version bumped to v0.1.0

### Fixed
- Code style issues (phpdoc, formatting, spacing)
