# Changelog

Все существенные изменения в этом проекте будут задокументированы в этом файле.

Данный формат основан на системе Keep a Changelog.

## [Unreleased]

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
