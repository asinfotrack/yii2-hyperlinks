# Yii2-hyperlinks
Yii2-hyperlinks is a module and set of functionality to add hyperlinks to a model in a generic way

__WATCH OUT: this extension is still under development. Breaking changes can occur without notice until first release!__

## Installation

### Basic installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require asinfotrack/yii2-hyperlinks
```

or add

```
"asinfotrack/yii2-hyperlinks": "~0.8.0"
```

to the `require` section of your `composer.json` file.

### Migration
    
After downloading you need to apply the migration creating the required tables:

    yii migrate --migrationPath=@vendor/asinfotrack/yii2-hyperlinks/migrations
    
To remove the table just do the same migration downwards.

### Add the module to the yii-config

```php
    'modules'=>[
        
        //your other modules...
        
        'hyperlinks'=>[
            'class'=>'asinfotrack\yii2\hyperlinks\Module',
            
            'userRelationCallback'=>function ($model, $attribute) {
                return $model->hasOne('app\models\User', ['id'=>$attribute]);
            },
            'backendAccessControl'=>[
                'class'=>'yii\filters\AccessControl',
                'rules'=>[
                    ['allow'=>true, 'roles'=>['@']],
                ],
            ],
        ],
    ],
```

For a full list of options, see the attributes of the classes within the module. Especially check the classes
`asinfotrack\yii2\hyperlinks\Module`. Some examples are provided below.

## Changelog

###### [v0.8.0] (work in progress)
- main classes in a stable condition
- further features will be added in a backwards-compatible way from here on
- all breaking changes will lead to a new minor version.
