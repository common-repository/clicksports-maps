=== CLICKSPORTS Maps ===
Contributors: clicksports
Author link: https://www.clicksports.de
Tags: openmaptiles, map, maps, alternative, google maps alternative, clicksports
Requires at least: 4.8
Tested up to: 6.5
Stable tag: 1.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

CLICKSPORTS Maps offers a privacy-friendly alternative to Google Maps. Any number of locations can be added to the customizable map. Additional individual information, such as addresses or links, can be added to each location via a pop-up window.

You can choose between 3 different map appearances. We also offer a variety of options to make the map fit the style of your website.

== Usage ==

The map service is based on OpenMapTiles and is operated by CLICKSPORTS GmbH on German servers. We may load external resources from our server in order to display the map correctly. Please refer to the FAQ for more information about privacy issues.

The use of CLICKSPORTS Maps is free of charge for private and non-profit use. Commercial use is subject to a license per domain.

== Prices, Demo & Details ==

For more information, please visit our [website](https://www.clicksports.de/wordpress-plugins/clicksports-maps-google-maps-alternative).
Please refer to our ticket system for support and questions: [Support](http://service.clicksports.de).

== Installation ==

1. Download this plugin and rename the extracted directory to 'clicksports-maps'.
2. Upload the entire directory to the plugin directory and activate the plugin.
3. Settings and shortcode instructions can be found under "Settings, CLICKSPORTS Maps".

== Frequently Asked Questions ==

= I have a personal or charitable website, do I need to register with you? =
Yes, you need to register your domain with us in order to use our map service. It will be free of charge.
Get in touch with us via our [website](https://www.clicksports.de).

= I have a business website, do I need to register with you? =
Yes, a small monthly fee will be charged. Get in touch with us via our [website](https://www.clicksports.de/wordpress-plugins/clicksports-maps-google-maps-alternative).

= Why not use Google Maps? =
There are no free services. If the service is free of charge, most of the time the user data is the product. This is also the case with Google Maps and for this reason the service, just like Google Analytics, is under scrutiny by German and European data protection authorities.
Due to a lack of alternatives to Google's map service, many website operators have decided to get rid of Google Maps entirely. With CLICKSPORTS Maps, we offer an alternative in accordance with European data protection law.

= What external resources are loaded while using this plugin? =

This plugins loads a few external resources form our German map server in order to display the map. The map itself is based on OpenMapTiles and Mapbox.
We off-load resources in order to provide the most up-to-date map experience. External resources include:

* Mapbox Styles
* Mapbox Scripts
* OpenMapTiles Scripts
* Mapbox and OpenMapTiles configuration data

= Do I need a consent manager or cookie banner? =

Regardless of the use of CLICKSPORTS Maps, we recommend using a consent manager to meet the requirements of the GDPR for your website. Since CLICKSPORTS Maps establishes a connection to our maps server in order to load the map data and to display the map, we believe that CLICKSPORTS Maps should be listed in the consent manager.

= Does CLICKSPORTS Maps have to be listed in the privacy policy? =

Yes, we recommend listing CLICKSPORTS Maps in the privacy policy.

= Is CLICKSPORTS Maps compliant with the GDPR? =

The map is operated on servers in Germany. This solution is data protection-friendly, as the data technically required for the map to function is stored anonymously after transmission.
Log data is used exclusively for technical monitoring of our service. The anonymized logs are automatically deleted after 14 days.
Furthermore, we as the CLICKSPORTS GmbH do not collect any personal data while fetching map data or operating the map.
The map does not use cookies. With the prerequisite of a functioning consent manager and a clarification in the privacy policy, we consider CLICKSPORTS Maps to be compliant with the GDPR.

= Are there any map themes to choose from? =
Yes, we currently provide 3 different map themes and customizable color for the map marker.

= I can't choose any map themes, the select field is empty. =
Please make sure the setting 'allow_url_fopen' is enabled by your hosting configuration. This is neccessary because all map styles are loaded from our server. Ask your hosting provider for details on how to change this setting.

= Can I display multiple markers on a single map? =
Yes, you can create multiple map markers and display all of them on your map.

= Can I display multiple maps on a single page? =
No, unfortunately this is not supported at the moment. You can, however, display individual maps beyond the global settings on another page.
See the settings page for shortcodes and details.

= I tried the full width map feature, but it breaks my layout. =
Support for the full width map option heavily depends on your current theme and is out of our hands, unfortunately.

= Where can I get the coordinates for my desired location? =
You can use a service like [Nominatim](https://nominatim.openstreetmap.org/) or [LatLong.net](https://www.latlong.net/) to get
your coordinates for the map settings.

== Translations ==
* English - Default
* German

== Screenshots ==

1. Plugin settings page.
2. Plugin settings page.
3. Map displayed on the website.

== Changelog ==

= 1.4.0 =
* Changed: Internal enhancements.

= 1.3.9 =
* Tested up to WordPress 6.5

= 1.3.8 =
* Fixed: Added fallback for unrecognized map theme with shortcode usage.
* Fixed: Miscellaneous minor fixes.

= 1.3.7 =
* Changed: Minor additions to descriptions and translations.

= 1.3.6 =
* Tested up to WordPress 6.4.

= 1.3.5 =
* Tested up to WordPress 6.1.
* Fixed: Conditional loading of map styles was not working correctly under specific circumstances.
* Changed: Improvements for plugin settings page.
* Added: Conditional loading of map scripts.

= 1.3.4 =
* Added: Possibility to add links in popup markers.
* Added: German formal translation.

= 1.3.3 =
* Added: Option to disable conditional CSS loading.

= 1.3.2 =
* Fixed: Minor bugfixes.

= 1.3.1 =
* Fixed: Various fixes and internal improvements.

= 1.3.0 =
* Changed: Performance optimizations by loading map styles and scripts only when necessary, affecting both frontend and backend.

= 1.2.1 =
* Added: Accessibility measures for maps.
* Fixed: Various small fixes and translation updates.

= 1.2 =
* Changed: New design and layout for plugin settings.

= 1.1.4 =
* Added: Option to load map script in footer.

= 1.1.3 =
* Fixed: Empty settings could cause an error under certain circumstances.

= 1.1.2 =
* Fixed: Some backend validation functionality was partially called from the frontend which threw a critical error.

= 1.1.1 =
* Fixed: Validation errors.
* Changed: Map theme setting is now handled locally instead of being dependant on server response.

= 1.1.0 =
* Feature: Multiple map markers.
* Fixed: Various fixes and improvements regarding the plugin's settings and security.

= 1.0.7 =
* Fixed: Settings link on Plugin overview page was missing.

= 1.0.6 =
* Fixed: Setting a map width within the shortcode could break the coordinates notation.

= 1.0.5 =
* Fixed: Several translation strings.

= 1.0.4 =
* Fixed: Shortcode settings weren't overwriting global settings under certain circumstances.
* Fixed: Small adjustment for the shortcode example.

= 1.0.3 =
* Added: Route planning link in marker popup using Google Maps.

= 1.0.2 =
* Changed: Loading Mapbox scripts and styles from our CDN instead of embedding them locally (still fully GDPR compliant since we don't log any requests).
* Fixed: CSS enhancements for plugin settings page.
* Fixed: CSS for marker popup that could lead to display errors with certain themes.

= 1.0.1 =
* Fixed: Script error on pages without any map.

= 1.0.0 =
* Initial release.
