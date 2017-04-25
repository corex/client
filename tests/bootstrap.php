<?php

// Load composer environment.
$rootPath = dirname(dirname(dirname(dirname(__DIR__))));
require_once($rootPath . '/vendor/autoload.php');

// Load helpers.
require_once(__DIR__ . '/Helpers/PropertiesHelper.php');
require_once(__DIR__ . '/Helpers/EntityHelper.php');
require_once(__DIR__ . '/Helpers/CollectionHelper.php');
