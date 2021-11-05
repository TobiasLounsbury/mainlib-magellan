# MAIN Library: Magellan
#### (mainlib-magellan)

Custom WordPress Plugin for the MAIN-Library Magellan project.

This Projects aims to provide a flexible interface for the collection, management and display of metadata pertaining to individual libraries within the MAIN network.

## Project Dependencies
1. [WP-Store-Locator](https://wordpress.org/plugins/wp-store-locator/ "WP Store Locator - Wordpress Plugin") - 
The initial mapping plugin the project is written on top of
1. [Co-Authors Plus](https://wordpress.org/plugins/co-authors-plus/ "Co-Authors Plus - Wordpress Plugin") - 
Wordpress plugin that allows multiple authors to be associated with a post. Used to allow individual library employees to have limited access to author a single location's metadata.
1. [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/ "Advanced Custom Fields - Wordpress Plugin") - 
Used To add custom fields to the Library Services Taxonomy for Service Icon (service_icon) and Default Service toggle (default_service) 
1. [Font-Awesome](https://wordpress.org/plugins/font-awesome/ "Font Awesome - Wordpress Plugin") - 
Used to make sure the version of FontAwesome being included is up to date. FontAwesome icons are used in the Library Services UI
1. [Category Order and Taxonomy Terms Order](https://wordpress.org/plugins/taxonomy-terms-order/  "Category Order and Taxonomy Terms Order - Wordpress Plugin") - 
Used to reorganize the Display Order (term_order) of the Library Services taxonomy
1. [WPSL-Coauthors](https://github.com/TobiasLounsbury/wpsl-coauthors "wpsl-coauthors GitHub repository") - 
Wordpress Plugin that creates a new user role called `Store Locator: Co-Author` with extremely limited permissions tailored to editing a single Store Location location on which they are co-author. Used to Allow individual library employees to have a unique account and permission to author the metadata for their location while restricting access to the rest of the site. 
1. [WPSL-Expanded-Hours](https://github.com/TobiasLounsbury/wpsl-expanded-hours) - 
Custom wordpress plugin that adds the custom open/close times to the individual library locations inside store locator.

## Installation
* Install and activate all dependencies
* Unpack or clone the Magellan plugin (mainlib-magellan) into the wordpress plugins folder.
* Activate the Magellan plugin
* Update some Store Locator Settings
  * Permalink: Store slug → "libraries"
  * Permalink: Category slug → "services"

#### Automated Tasks
These actions are automatically taken when the Magellan plugin is activated.
* A new Page is created for listing library services
```
Title:      'All Library Services'
Url:        /library-services/
Content:    '[all-library-services]'
```
* A Custom Field Group is created for the Library Services Taxonomy with the following fields
   * `service_icon` - Used to select which icon represents each Library Service
   * `default_service` - Used for administrators to select which services are shown even when not available.
* Four default Library Services are created
   * Printing
   * Faxing
   * Scanning
   * Internet
* Some Store Locator Settings are updated:
```
User Experience: 
   Store Locator Template => Magellan Template
   Directions open in new Window => true

Search: 
   Enable Category Filter => true
   Search Radius => '5,[10],20,30'

Labels: 
   Category filter => "Library Services"

Permalink:
   Enable Permalink => true
```


## Settings
__Path to Locations Page__ - The url path to the page rendering Store Locator. This is used on the *All Library Services* page to know where to send links.

__Default Library Services Icon__ - Library Employees have permission to create new Library Services but do not have permission to customizze the metadata for that service. In this event the Default Icon is used until a MAIN employee alters the Library Service to have a custom icon. 


## Shortcodes
__[all-library-services]__ - Renders a list of all the library services with their descriptions, icons, and links to the locations page with the service filter set.

__[library-services]__ - Renders a list of the library services available at a single location. 

Optional params: __id__

__Example:__ `[library-service id="257"]`


## Usage
#### Adding a Library Employee
- As a site admin create an account for the employee with the role `Store Locator: Co-Author`
- Edit the Library to which the employee is being given access and add them as a Co-Author

