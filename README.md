# Entry GPS Coordinates plugin for Craft CMS 3.x

Pick a GPS location for an entry

![PluginImpression](https://user-images.githubusercontent.com/3450011/64065265-e3cbc680-cc0b-11e9-89fc-ed682123b109.gif)

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project and tell composer to install the plugin
```composer require nthmedia```

2. Activate the plugin in Craft through the command line or through the Control Panel under Settings → Plugins 
```./craft install/plugin entry-gps-coordinates```

3. Add a Coordinates field under Settings → Fields and add this field to a Section under Settings → Sections

## Usage in Twig

```
{{ entry.fieldName | coordinates }} => 47.11736635136284,12.82762888348384
{{ entry.fieldName | latitude }} => 47.11736635136284
{{ entry.fieldName | longitude }} => 12.82762888348384
```