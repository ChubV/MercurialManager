MercurialManager
================

PHP class to get info about mercurial (hg) repository.

Installation
============

Add `"chub/mercurial-manager": "dev-master"` to `require` section of your `composer.json` and run `php composer.phar update`

Usage
=====

``` php
<?php
require_once 'vendor/autoload.php';
use ChubProduction\MercurialManager\MercurialManager;

// Current directory
$m = new MercurialManager();
// Tip revision node
$node = $m->getNode();

echo $node->getDate()->format('d.m.Y H:i:s'), "\n";
echo $node->getAuthor();
```
