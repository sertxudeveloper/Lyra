# Lyra Documentation
Lyra is an administration dashboard for Laravel applications. With Lyra you'll be able to administer your underlying database records using Eloquent, you can acomplish it defining a Lyra "resource" that corresponds to each Eloquent model in your application.

# Installation
## Requirements
Lyra has a few requirements you should be aware of before installing:
 - Composer
 - Laravel Framework 6.0+
 - Laravel Mix
 - Node.js & NPM

## Installing Lyra
You may download a Lyra release using Composer, this project is available under the namespace `sertxudeveloper/lyra` so you'll be able to require it.

```bash
composer require sertxudeveloper/lyra
```

This command will download the latest release available in our GitHub repository.

Finally, you should execute the Lyra installation manager, the following command will install Lyra's configuration file and public assets within your application.

```bash
php artisan lyra:install
```

After running this command, verify that the `config/lyra.php` and the public assets folder `public/vendor/sertxudeveloper/lyra` were added to your application. If it wasn't you should add it manually.

```bash
php artisan vendor:publish --tag lyra-assets

php artisan vendor:publish --tag lyra-config
```

By default Lyra will install in its simplest way, if you want to change its working modes please visit the [working modes](#working-modes) section.

To access to the Lyra dashboard open your prefered browser and navigate to the `/lyra` path in your application. Keep in mind that this route can be modified in the Lyra configuration file.

## Working Modes
Lyra provides two different working modes.

### Basic
The `basic` mode is the default one, it provides full access to all the users using the built-in Laravel authenticator. In order to limit the access to only a few users, you should edit the Lyra configuration file.

In this file you will find an option to select the mode called `authenticator`, make sure is setted to `basic`. Below that option is the `authorized_users` array, in this array you should add the users you want to let to access Lyra. Keep in mind that in this mode there's no roles, so all the users will have access to all the resources.

```php
 "authorized_users" => [
   "mail@example.com"
 ],
```

### Lyra
The `lyra` mode is the most complete one, it provide a role-based access control and a notificatior system built-in. Using this mode the `authorized_users` array will be ignored.

In addition, this mode adds a new authenticator system, so you should create all the users you want to access Lyra.

#### Create new users
To create new Lyra users you can use the following command:

```bash
php artisan lyra:user mail@example.com
```

This command will ask you for entering the name of the user, the password and the password confirmation.

The created user will only be able to access Lyra, but it won't be able to perform any action with the existing resources. We encorauge disabling the `use_permissions` parameter while configuring the Lyra users and permissions, once you've done activate it again to use permissions you just configured.

## Upgrade Guide
Lyra uses [Semantic Versioning](https://semver.org) to provide a version number.
The version number `MAJOR.MINOR.PATCH` will increment:
1. `MAJOR` version when Lyra has changed its core and make it incompatible with user created Resources or Vue components.
2. `MINOR` version when Lyra has new components, added new functionality in a backwards-compatible manner or changes in the current available themes.
3. `PATCH` version when Lyra has backwards-compatible bug fixes, typo errors.

Minor and patch releases should **never** contain breaking changes.

When a new version requires some user interactions or modifications in its code or configuration files an upgrade instructions guide will be added in the release notes.

To update your current installation, you should increment your Lyra version number in your `composer.json` and run `composer update` followed by `php artisan lyra:update` to update the public assets.

## Support Policy
Once a new `MINOR` release is published, the latest release will receive a bug and security fixes until date.

| Version | Release | Bug Fixes Until | Security Fixes Until |
| --- | --- | --- | --- |
| 1.0 | 13/03/2020 | TBA | TBA |

# Contribution Guide
## Bug Reports
All the bug reports are welcome and you can create an issue in the [Lyra GitHub repository](https://github.com/sertxudeveloper/Lyra/issues).
If you decide to create a new issue to report a bug, we strongly recomment you to add a description of the issue, add as much relevant information as posible and a code sample to be able to replicate the bug and solve the problem with in a new release.

All the bug fixes should be sent to the latest release branch. For example, if you want to send a bug fix to the latest release `v1.5.9`, you should send it to the `1.5` branch.

The `master` branch should only receive bug fixes if the bug is only in that branch.

## Compiled Assets
If you are submitting a change that will affect a compiled file, such as most of the files in `resources/assets/sass` or `resources/assets/js` do not commit the compiled files. All the compiled files will be generated and committed by Lyra maintainers.

## Security Vulnerabilities
If you find a security vulnerability, do **NOT** open an issue, please send an email to dev.sertxu@gmail.com instead. We will publish a new release as soon as the bug is fixed.

# The Basics

## Defining Resources
By default, Lyra resources are stored in the `app/Lyra` directory of your application. You may generate a new resource using the `lyra:resource` Artisan command:

```bash
php artisan lyra:resource Post
```

The most basic and fundamental property of a resource is its `model` property. This property tells Lyra which Eloquent model the resource corresponds to:

```php
/**
 * The model the resource corresponds to.
 *
 * @var string
 */
public static $model = Post::class;
```

Freshly created Lyra resources only contain an `Id` field definition. Don't worry, we'll add more fields to our resource soon.

## Registering Resources
Once you've created a new Lyra resource, you should add it to the menu cofiguration in your `config/lyra.php` configuration file in order to register it automatically.

Inside this file you need to find the `menu` array key, here is an example about how you should register the new Lyra resource:

```php
  "menu" => [
    ...
    [
      "name" => "Posts",
      "key" => "posts",
      "icon" => "far fa-file-alt",
      "resource" => App\Lyra\Post::class
    ]
  ]
```

The order of appearing in the `menu` array will determinate the order in the menu inside Lyra. To learn more about it please read the [menu configuration](#menu-configuration).

## Pagination
By default Lyra will show a "links" style pagination in all the resources. Currently this feature is not customizable, but if you're interested in customize it please open an issue in the [Lyra GitHub repository](https://github.com/sertxudeveloper/Lyra/issues) to let us know you want this feature.

## Customizing Pagination
If you'd like to customize the amounts shown on each Lyra resource's "per page" filter menu, you can do so by customizing its `perPageOptions` property inside the desired resource:

```php
/**
 * The pagination per-page options configured for this resource.
 *
 * @return array
 */
public static $perPageOptions = [50, 100, 150];
```

# Fields
## Defining fields
Each Lyra resource contains a `fields` method. This method returns an array of fields, which generally extend the `SertxuDeveloper\Lyra\Fields\Field` class. Lyra itself includes fields for text inputs, booleans, dates, file uploads, Markdown, and more.

To add a field to a resource, we can simply add it to the resource's `fields` method. The fields should be created using their static `make` method. This method accepts several arguments; however, you usually only need to pass the "human readable" name of the field. Lyra will automatically "snake case" this string to determine the underlying database column:

```php
use SertxuDeveloper\Lyra\Fields\Id;
use SertxuDeveloper\Lyra\Fields\Text;

/**
 * Get the fields displayed by the resource.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return array
 */
public function fields(Request $request)
{
    return [
        ID::make('Id')->sortable(),
        Text::make('Name')->sortable(),
    ];
}
```
### Field Column Conventions
As noted above, Lyra will "snake case" the displayable name of the field to determine the underlying database column. However, if necessary, you may pass the column name as the second argument to the field's `make` method:

```php
Text::make('Name', 'name_column');
```

## Showing / Hiding Fields
Some fields will automatically hide in some of the views, for example, the field `Id` will hide in the create and edit view.

You can hide each field using the following methods:
 - `hideOnIndex`
 - `hideOnShow`
 - `hideOnCreate`
 - `hideOnEdit`

You may chain any of these methods onto your field's definition in order to instruct Lyra where the field should be displayed:

```php
Text::make('Name')->hideOnIndex()
```

In addition if you want to invert the method and show a field hidded by default you should pass a false boolean to the method.

```php
Id::make('Id')->hideOnCreate(false)
```

## Sortable Fields
If you want to be able to sort by a field in the index view, you may use the `sortable` method in order to enable this functionality.

```php
Id::make('Id')->sortable()
```

## Field Types
Lyra has built-in the mostly used field types.

### Text Field
The text field is the simples editable field, it will show a text input.

#### Length limit
With the method ``size`` we can add a length limitator, the first parameter its required and it should be an int to specify the maximum length, the second parameter is optional and it will determine the type of the limitator.

There are available two types of limitator, the hard limit and the soft limit.

##### Soft limit
This mode shows a character count followed by the maximum length setted.
Once the limit is reached the user will be able to continue writing, it works only as an orientative length limit.

```php
Text::make('Name')->size(25)
```

##### Hard limit
This options shows a character count followed by the maximum length setted. Once the limit is reached the user won't be able to continue writing. Keep in mind this limit is client-side only.

```php
Text::make('Name')->size(25, true)
```

### URL Field
This field extends the Text field, it will show a clickable link in the index and the show views.

```php
Url::make('Link')
```

### Slug Field
This field also extends the Text field, it will show a text input as the Text field but with a slug pattern.

```php
Slug::make('Slug')
```

In addition to the functionality of the Text field, you can use the method `sluglify` to autogenerate the slug from another existing field. This method requires the column name of the origin field.

```php
Text::make('Title'),
Slug::make('Slug')->sluglify('title'),
```

The slug will be automatically created while you're typing in the origin field, in this case the "Text" field. This will only occour if there wasn't any previous value in the slug field at the moment of accessing the form.

### Boolean Field
This field is used to represent a boolean value, it shows a :heavy_check_mark: when the value is true, and a :x: if the value is false.

```php
Boolean:make('Active')
```

You can set the `true` and `false` values if, for example, you have an enum field in your database, in order to do that you should use the `values` method. This method requires two parameters, the first one will be the `true` value and the second one will be the `false` value.

```php
Boolean:make('Status')->values('ACTIVE', 'FALSE')
```

Also, you can set if you want to be checked by default or not. If you want it checked you should use the method `checked`.

```php
Boolean:make('Active')->checked()
```

### Select Field
It shows a selectable dropdown with multiple options, that options should be added to the field using the `options` method.

```php
Select::make('Status')->options([
  'Draft', 'Pending', 'Published'
])
```

You can also use an associative array, the keys will be saved in the database and the values will be displayed in the dropdown.

```php
Select::make('Status')->options([
  'draft' => 'Draft',
  'pending' => 'Publish pending',
  'published' => 'Published'
])
```

In order to set a default value you should use the `default` method.

```php
Select::make('Status')->options([
  'Draft', 'Pending', 'Published'
])->default('Draft')
```

```php
Select::make('Status')->options([
  'draft' => 'Draft',
  'pending' => 'Publish pending',
  'published' => 'Published'
])->default('draft')
```

### Radio Field
This field will display a group of radio inputs, you should specify the available options using the `options` method.

```php
Radio::make('Status')->options(['Draft', 'Pending', 'Published'])
```

You can also use an associative array, the keys will be saved in the database and the values will be displayed in the dropdown.

```php
Radio::make('Status')->options([
  'draft' => 'Draft',
  'pending' => 'Publish pending',
  'published' => 'Published'
])
```

In order to set a default value you should use the `default` method.

```php
Radio::make('Status')->options([
  'Draft', 'Pending', 'Published'
])->default('Draft')
```

```php
Radio::make('Status')->options([
  'draft' => 'Draft',
  'pending' => 'Publish pending',
  'published' => 'Published'
])->default('draft')
```


### Heading field
This field does not correspond to any column in your application's database. It shows a separator to group different fields inside the show and edit views.

```php
Heading::make('Meta')
```

You can also display HTML code in the field, it will render the content.

```php
Heading::make('Extra data - <span style="color:red">Important</span>')
```

# Advanced Configurations


## Menu configuration
You should create an element inside the `menu` array in the Lyra configuration file in order to be able to use the resource you've created.

All the elements in the menu should contain the keys `name`, `key`, `icon`, `resource`.

```php
  "menu" => [
    ...
    [
      "name" => "Posts",
      "key" => "posts",
      "icon" => "far fa-file-alt",
      "resource" => App\Lyra\Post::class
    ]
  ]
```

Also, Lyra has two elements preconfigured in the `menu` array, the Dashboard and the Media Manager, that's because these elements works directly with the frond-end, you can see these two doesn't have the `resource` key in the array.


```php
  "menu" => [
    ...
    [
      "name" => "Dashboard",
      "key" => "lyra",
      "icon" => "fas fa-home",
    ],
    [
      "name" => "Media Manager",
      "key" => "media",
      "icon" => "fas fa-photo-video"
    ],
  ]
```

### Nested elements
You can add nested elements inside the menu to group some resources, this can be accomplished adding a parent element, this element won't be attached to any resource but will contain an array with the menu element with resources.

For example, we want to create a parent element called "Blog" an inside of that we want to have the "Categories", "Posts" and "Tags" resources.

```php
  "menu" => [
    ...
    [
      "name" => "Blog",
      "key" => "blog",
      "icon" => "",
      "items" => [
          [
            "name" => "Categories",
            "key" => "categories",
            "icon" => "",
            "resource" => \App\Lyra\Category::class
          ],
          [
            "name" => "Posts",
            "key" => "posts",
            "icon" => "",
            "resource" => \App\Lyra\Post::class
          ],
          [
            "name" => "Tags",
            "key" => "tags",
            "icon" => "",
            "resource" => \App\Lyra\Tag::class
          ],
      ]
    ]
 ]
```

As you can see in the example, the parent element "Blog" contains all the resources inside the `items` key and it hasn't got the `resource` key.

You can have all the nested levels you want, but keep in mind the front-end is not prepared to manage too many nested levels.

# API Documentation
#### (need update before release)
In this section of the documentation we're going to see how works and how to create some of the structures of Lyra.

## Fields
All the fields, except the Heading, extends the `SertxuDeveloper\Lyra\Fields\Field` abstract class, this class contains all the common methods and properties that all the fields needs to work.

### Properties
These are the properties of the fields:

| Type | Name | Default value | Description |
| --- | --- | --- | --- |
| protected | component | void | Contains the name of the Vue.js container. |
| protected | callback | null | Will contain, if required, the callback function added in the `column` parameter. |
| protected | hideOnIndex | false | Specify if the field should be hidden in the index view or not. |
| protected | hideOnShow | false | Specify if the field should be hidden in the show view or not. |
| protected | hideOnCreate | false | Specify if the field should be hidden in the create view or not. |
| protected | hideOnEdit | false | Specify if the field should be hidden in the edit view or not. |
| protected | data | null | Will contain the collection with all the data required by the front-end. |

### Methods
These are the methods of the fields:

| Type | Name | Return | Description |
| --- | --- | --- | --- |
| public static | [make](#public-static-make) | $this | Method to initialize the field. |
| public | [description](#public-description) | $this | Adds a description text to the field. |
| public | [sortable](#public-sortable) | $this | Let the user sort by these field. |
| public | [translatable](#public-translatable) | $this | Makes the field translatable. |
| public | [hideOnIndex](#public-hideonindex) | $this | Enable or disable the visibility in the index view. |
| public | [hideOnShow](#public-hideonshow) | $this | Enable or disable the visibility in the show view. |
| public | [hideOnCreate](#public-hideoncreate) | $this | Enable or disable the visibility in the create view. |
| public | [hideOnEdit](#public-hideonedit) | $this | Enable or disable the visibility in the edit view. |
| public | [saveValue](#public-savevalue) | void | Saves the field value in the model column. |
| public | [getPermissions](#public-getpermissions) | array | Returns an array with the hide configuration values. |
| public | [getValue](#public-getvalue) | array | Sets the data value and return all the data array filled. |
| public | [getValueShow](#public-getvalueshow) | mixed | Returns the model value when the user has access the index and show views. |
| public | [getValueEdit](#public-getvalueedit) | mixed | Returns the model value when the user has access the create and edit views. |
| public | [getTranslatedValue](#public-gettranslatedvalue) | mixed | Returns the translated model value when the user has requested other language. |
| public | [retrieveValue](#public-retrievevalue) | mixed | Default method to obtain the data from the model column, or a null if it not exists. |

#### public static make
Method to initialize the field, all the field should start with a static call to these method.

```php
make(string $name, mixed $column = null)
```

**Return Value**
$this

#### public description
Adds a field description to give the user information about the field.

```php
description(string $text)
```

**Return Value**
$this

#### public sortable
Allow the field to be sortable in the index view.

```php
sortable()
```

**Return Value**
$this

#### public translatable
Sets the field as translatable to enable foreign locales.

```php
translatable()
```

**Return Value**
$this

#### public hideOnIndex
Enable or disable the visibility in the index view.

```php
hideOnIndex(bool $hide = true)
```

**Return Value**
$this

#### public hideOnShow
Enable or disable the visibility in the show view.

```php
hideOnShow(bool $hide = true)
```

**Return Value**
$this

#### public hideOnCreate
Enable or disable the visibility in the create view.

```php
hideOnCreate(bool $hide = true)
```

**Return Value**
$this

#### public hideOnEdit
Enable or disable the visibility in the edit view.

```php
hideOnEdit(bool $hide = true)
```

**Return Value**
$this

#### public saveValue
Saves the field value in the model column.

```php
saveValue(array $field, $model)
```

**Return Value**
$this

#### public getPermissions
Returns an array with the hide configuration values.

```php
getPermissions()
```

**Return Value**
array

#### public getValue
Sets the data value and return all the data array filled. The type indicates if it was acceces via index, show, create or edit.

```php
getValue(mixed $model, string $type)
```

**Return Value**
array

#### public getValueShow
Returns the model value when the user has access the index and show views.

```php
getValueShow(mixed $model)
```

**Return Value**
mixed

#### public getValueEdit
Returns the model value when the user has access the create and edit views.

```php
getValueEdit(mixed $model)
```

**Return Value**
mixed

#### public getTranslatedValue
Returns the translated model value when the user has requested other language.

```php
getValueEdit(mixed $model)
```

**Return Value**
mixed

#### public retrieveValue
Default method to obtain the data from the model column, or a null if it not exists.

```php
retrieveValue(mixed $model)
```

**Return Value**
mixed

## Resource
All the resources extends the `SertxuDeveloper\Lyra\Http\Resources\Resource` abstract class, this class contains all the common methods and properties that all the resources needs to work.

### Properties
These are the properties of the resouces:

| Type | Name | Default value | Description |
| --- | --- | --- | --- |
| private | type | void | Specify the type of request (index, show, create, edit). |
| public static | model | void | Contains the model of the resource. |
| public static | search | array | Contains an array with the searchable fields. |
| public | singular | void | Contains the singular name of the resource. |
| public | public | void | Contains the public name of the resource. |
| public static | perPageOptions | array | Set the available option of rows per page in the index view. |
| private | response | array | Will contain the values that will return. |

### Methods
These are the methods of the resources:

| Type | Name | Return | Description |
| --- | --- | --- | --- |
| public abstract | [fields](#public-abstract-fields) | array | Contains an array with the fields declarations | 
| public static | [getFields](#public-static-getfields) | Collection | Get the fields of the current resource |
| protected | [getAvailableLanguages](#protected-getavailablelanguages) | array | Get the available languages |
| public | [getCollection](#public-getcollection) | array | Get all the data of the resource |
| public | [toArray](#public-toarray) | array | Converts the resource into an array |
| public static | [hasSoftDeletes](#public-static-hassoftdeletes) | bool | Check if the model has SoftDeletes class |
| public static | [isTranslatable](#public-static-istranslatable) | bool | Check if the model has HasTranslations class |

#### public abstract fields
Contains an array with the fields declarations.

```php
fields()
```

**Return Value**
array

#### public static getFields
Get the fields of the current resource.

```php
getFields(mixed $resource)
```

**Return Value**
array

#### protected getAvailableLanguages
Get the available languages.

```php
getAvailableLanguages()
```

**Return Value**
array

#### public getCollection
Get all the data of the resource

```php
getCollection(Request $request, string $type)
```

**Return Value**
array

#### public toArray
Converts the resource into an array

```php
toArray(Request $request)
```

**Return Value**
array

#### public static hasSoftDeletes
Check if the model has SoftDeletes class

```php
hasSoftDeletes()
```

**Return Value**
bool

#### public static isTranslatable 
Check if the model has HasTranslations class

```php
isTranslatable()
```

**Return Value**
bool

## Vue components
> How to create a new component?
> Sections and requirements of a component

