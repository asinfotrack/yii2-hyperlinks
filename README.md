# Yii2-hyperlinks
Yii2-hyperlinks is a module and set of functionality to add hyperlinks to a model in a generic way

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

###### [v1.0.0](https://github.com/asinfotrack/yii2-hyperlinks/releases/tag/1.0.0)
- dependency update (__potential breaking change!__)

###### [v0.8.5](https://github.com/asinfotrack/yii2-hyperlinks/releases/tag/0.8.5)
- bottons arrangement changed

###### [v0.8.4](https://github.com/asinfotrack/yii2-hyperlinks/releases/tag/0.8.4)
- bug fix hyper link create

###### [v0.8.3](https://github.com/asinfotrack/yii2-hyperlinks/releases/tag/0.8.3)
- bug fix label translation
- bottons arrangement changed

###### [v0.8.2](https://github.com/asinfotrack/yii2-hyperlinks/releases/tag/0.8.2)
- bug fix pop up client validation
- changed url attribute in grid widget for @web

###### [v0.8.1](https://github.com/asinfotrack/yii2-hyperlinks/releases/tag/0.8.1)
- changed url validator to use @web

###### [v0.8.0](https://github.com/asinfotrack/yii2-hyperlinks/releases/tag/0.8.0)
- main classes in a stable condition
- further features will be added in a backwards-compatible way from here on
- all breaking changes will lead to a new minor version.
