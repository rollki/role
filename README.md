# Role
Module extends Drupal role and allow configure user settings per role.
For instance user form or view mode.

Our role configurations appends settings to default config file by `ThirdPartySettingsInterface`.

## Opportunities:

### Single Role
Allows selecting only one role for the user. 
This module can work with [Role Delegation](https://www.drupal.org/project/role_delegation) module.
You can select the type of field for the role list (radio or select list field) you can also write some description for 
the roles that will be displayed at the bottom of the role list.
