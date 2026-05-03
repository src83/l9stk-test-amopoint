# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog.

## [Unreleased]

## [v0.3.0] - 2026-04-03

**Overview:** Introduces modular monolith architecture with a reference module and project documentation.

### Features
- Introduce modular architecture (`app/Modules`)
- Add reference module (`Example`) as a base for new features
- Add module-level routing, views, migrations, and assets support

### Added
- Add Example module with Cabinet and Ajax endpoints
- Add module ServiceProvider integration (routes, views, migrations)
- Add frontend asset structure for modules

### Docs
- Add comprehensive README with architecture description and usage guide
- Document module structure and integration points


## [v0.2.0] - 2026-03-16

**Overview:** Introduces user settings management, improves session handling, and includes code/style maintenance and security updates.

### Features 
- Add unit user settings for authentication

### Fixes
- Improve handling of expired sessions (419 Page Expired)

### Chores
- Clean up Composer scripts and dependencies
- Restrict API access to allowed domains

### Style
- Apply Laravel Pint formatting


## [v0.1.0] - 2026-03-10

**Overview:** Initial addition of the account UI and settings page.

### Added
- Added cabinet layout and basic cabinet pages.
- Added Settings page view.

### Changed
- Improved layout structure for cabinet pages.
- Adjusted Bootstrap layout for form fields (username, email, profile data).
- Improved responsive spacing for cabinet layout.
- Minor UI cleanup for Settings form.

[v0.1.0]: https://github.com/src83/l9stk/commit/f937c2c900c6407207a29533cf8b458de71905f0
[v0.2.0]: https://github.com/src83/l9stk/commit/3404dca08fda751c4e4a31eb1a0eb32af0fd0d16
[v0.3.0]: https://github.com/src83/l9stk/commit/a520c19dc02fb4a60d5a514e3035843a4df8daaf
