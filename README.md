# Entry GPS Coordinates plugin for Craft CMS

Pick a GPS location for an entry. Supports multiple instances on a single page, including inside Neo and Matrix fields.

![PluginImpression](https://user-images.githubusercontent.com/3450011/64065265-e3cbc680-cc0b-11e9-89fc-ed682123b109.gif)

## Installation

1. Open your terminal and go to your Craft project and tell Composer to install the plugin:
```
composer require nthmedia/entry-gps-coordinates
```

2. Activate the plugin through the command line or via Settings → Plugins in the Control Panel:
```
./craft install/plugin entry-gps-coordinates
```

3. Add a Coordinates field under Settings → Fields and add it to a section under Settings → Sections.

## Configuration

The field has four settings:

| Setting | Description |
|---|---|
| **Google API Key** | Required. Supports environment variables. |
| **Default Zoom Level** | Number between 1 and 20. Default: 13. |
| **Default Center Coordinates** | Coordinates in `lat,lng` format. Default: Amsterdam. |
| **Geocoding language** | Language code for stored address data (e.g. `en`, `de`, `nl`, `fr`). Determines the language of saved fields like `address`, `country`, and `locality`. Default: `en`. The map interface language is set automatically based on the logged-in user's language. |

## Usage in Twig

```twig
{{ entry.fieldName | searchQuery }}    {# Van Gogh Museum #}
{{ entry.fieldName | coordinates }}    {# 52.3584159,4.8810756 #}
{{ entry.fieldName | latitude }}       {# 52.3584159 #}
{{ entry.fieldName | longitude }}      {# 4.8810756 #}
{{ entry.fieldName | zoomLevel }}      {# 13 #}
{{ entry.fieldName | address }}        {# Paulus Potterstraat 7, 1071 CX Amsterdam, Netherlands #}
{{ entry.fieldName | streetNumber }}   {# 7 #}
{{ entry.fieldName | route }}          {# Paulus Potterstraat #}
{{ entry.fieldName | locality }}       {# Amsterdam #}
{{ entry.fieldName | postalCode }}     {# 1071 CX #}
{{ entry.fieldName | country }}        {# Netherlands #}
```

## GraphQL

All field values are available via GraphQL:

```graphql
{
  entries {
    ... on YourSection_Entry {
      yourFieldName {
        searchQuery
        coordinates
        address
        streetNumber
        route
        locality
        postalCode
        country
        zoomLevel
      }
    }
  }
}
```

## Google Cloud API

The following APIs need to be enabled in Google Cloud Console:

- Maps JavaScript API
- Places API
- Geocoding API
