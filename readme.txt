=== Foodlist ===
Contributors: v-media
Tags: restaurant menu, café menu, restaurant, eatery, menus, bar, list
Requires at least: 3.8
Tested up to: 4.0
Stable tag: trunk

Allows you to build restaurant/bar/café menu.

== Description ==

Foodlist is a plugin for restaurants, cafés, bars, etc. that want to display their menus online. With this plugin you can easily manage your menus (the same way as you do it with your widgets).

Menus consist of sections and sections consist of items. You can reuse your items in multiple sections, as well as sections can be reused in multiple menus.

Menu tags are used to mark some of your menu items (healthy, spicy, or something else), you can set multiple tags for your menu item, if you want.

The plugin also includes template editor and css editor that will let you make the plugin look and feel as the rest of your site.

This plugin is already bundled with some demo data so that you could see how it works without setting up the test content.

Moreover, when you don't need the demo content you can easily remove it with one click.

See the [screenshots tab](http://wordpress.org/extend/plugins/foodlist/screenshots/) for more details.

== Installation ==

1. Go to your admin area and select Plugins -> Add new from the menu.
2. Search for "Foodlist".
3. Click install.
4. Click activate.

Alternatively you can install the plugin manually.

1. Get an archive with the most recent version of Foodlist.
1. Uncompress the `foodlist` directory from the archive to your `wp-content/plugins` directory.
1. Activate the plugin in the administration panel.

== Screenshots ==

1. Section manager.
2. Menu manager.
3. Menu Tags list.
4. Settings Screen.
5. Menu Item editor.

== ChangeLog ==

= Version 1.10 =
* Tested up to WordPress 4.1.1
* New field in the Menu Section post type: Note. You can now put your short note (description) to the section and it will be shown when you add a section to the menu. This will let you distinguish different sections with the same names.

= Version 1.9.1 =
* Tested up to WordPress 4.0 (no changes)

= Version 1.9 =
* Wrong action names in `MenuItemView::applyShortcodes()` and in `MenuSectionView::applyShortcodes()`.

= Version 1.8 =
* New shortcode to output url of the menu item thumbnail: `[menu_item_thumbnail_url size="thumbnail"]`, where `size` is any registered image size in WordPress (use `full` for the full size).

= Version 1.7 =
* Some code cleanup

= Version 1.6 =

* New shortcodes menu template shortcodes: menu_permalink, menu_section_permalink and menu_item_permalink (can be used in the corresponding templates)
* Polish language translation files (thanks to Paweł Krawczyk)

= Version 1.5 =

* Fixed inability to minimize menus and sections in menu and section managers
* Fixed inability to remove tags from menu items
* Made the look of menu and section managers closer to WP 3.9 widget manager

= Version 1.4 =

* New metaboxes when creating/editing Sections and Menus. It is now possible to build the list of sections within the menu and the list of items within the section editing screens.

= Version 1.3 =

* Fixed wrong function names in the initialization procedure (this problem is visible when outdated PHP or WordPress version were used)

= Version 1.2 =

* Fixed empty menu excerpt issue
* Added `flush_rewrite_rules()` call on plugin activation

= Version 1.1 =

* Removed screenshots from the plugin folder (moved in svn to /assets)
* Updated the compatibility version up to 3.7

= Version 1.0 =

* Initial release.
