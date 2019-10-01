CONTENTS OF THIS FILE
---------------------
   
 * Introduction
 * Requirements
 * Installation
 * Configuration
 * Maintainers


INTRODUCTION
------------

Module extends Drupal role and allows configure user settings per role.

For instance user form or view mode.
 
Role configurations appends settings to default config file by `ThirdPartySettingsInterface`.

Base module features:
 * Control user edit form mode per Role
 * Control user full view per Role

Sub modules:
 * Role Appearance: Controls site theme per user Role
 * Role Registration: Adds a new route `user/register/{role_id}` for specific role registration

Adds a new plugin type `RoleConfigElement` which allows add extra fields to Role.

 * For a full description of the module, visit the project page:
   https://www.drupal.org/project/role

 * To submit bug reports and feature suggestions, or track changes:
   https://www.drupal.org/project/issues/role


REQUIREMENTS
------------

This module requires no modules outside of Drupal core.


INSTALLATION
------------
 
 * Install as you would normally install a contributed Drupal module. Visit
   https://www.drupal.org/documentation/install/modules-themes/modules-8 for
   further information.


CONFIGURATION
-------------
 
 * Configure the role in Administration » People » Roles:

   - Edit necessary role to set Form/View modes


MAINTAINERS
-----------

Current maintainers:
 * Roman Salo (rolki) - https://www.drupal.org/user/3595404
 * Mykhailo Gurei (ozin) - https://www.drupal.org/user/2752909
 * Mariia Denysiuk (mariadenysyuk) - https://www.drupal.org/user/3427927

This project has been supported by:
 * Lemberg Solutions Limited
