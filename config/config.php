<?php
use asinfotrack\yii2\hyperlinks\Module;

return [

	'defaultRoute'=>'hyperlink/index',

	'urlInputCallback'=>[Module::className(), 'defaultUrlInput'],

];
