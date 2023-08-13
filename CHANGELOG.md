# Entry GPS Coordinates Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 2.2.0 - 2023-08-13
### Added
- Save zoom level (Issue #21)
- Ability to set a default zoom level (Issue #21)
- Ability to set a default map center

### Changed
- Bugfix: an empty value would still pass required field validation (Issue #22)

## 2.1.2 - 2022-05-03
### Changed
- Move ECS to dev dependencies


## 2.1.1 - 2022-05-03
### Changed
- Bugfix Twig filters


## 2.1.0 - 2022-05-03
### Changed
- Refactor Javascript to support multiple instances (Issue #16)
- Update lay-out to also show the actual address in a separate field (Issue #13, thanks @GMConsultant)


## 2.0.0 - 2022-05-02
### Changed
- Craft v4 compatibility


## 1.3.1 - 2021-06-03
### Changed
- Bugfix normalizeValue() of EntryCoordinates for PHP8+ (Issue #14)


## 1.3.0 - 2020-01-14
### Added
- GraphQL support


## 1.2.1 - 2020-01-02
### Changed
- Fix parsing Google API key from environment vars (thanks @jrrdnx)
- Fix parsing negative coordinates through latitude and longitude helpers (Issue #5, thanks @jrrdnx)


## 1.2.0 - 2019-12-23
### Added
- Enable environment vars for the Google API key (@jrrdnx)

### Changed
- Fixed a bug that required the field have a fixed name (Issue #2, @nicbou)


## 1.1.0 - 2019-08-31
### Added
- Added installation instructions to readme
- Added Twig filter to output coordinates in front-end
- Added Twig documentation to readme


## 1.0.0 - 2019-08-31
### Added
- First version of the Craft plugin.
- Include plugin icon
- Include Craft license
- Add preview to readme (#1)
- Draggable marker on a Google Map
- Includes searching through Google Places API
- Google key configurable in field setting

