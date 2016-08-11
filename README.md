# WebHelper
A Generic Httpd Configuration Helper

[![Build Status](https://api.travis-ci.org/JamesRezo/WebHelper.svg?branch=new-architecture)](https://travis-ci.org/JamesRezo/WebHelper)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/57e3dc27-e915-42d4-9bde-863a8f3bf5f8/mini.png)](https://insight.sensiolabs.com/projects/57e3dc27-e915-42d4-9bde-863a8f3bf5f8)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JamesRezo/WebHelper/badges/quality-score.png?b=new-architecture)](https://scrutinizer-ci.com/g/JamesRezo/WebHelper/?branch=new-architecture)
[![Code Coverage](https://scrutinizer-ci.com/g/JamesRezo/WebHelper/badges/coverage.png?b=new-architecture)](https://scrutinizer-ci.com/g/JamesRezo/WebHelper/?branch=new-architecture)
[![Dependency Status](https://www.versioneye.com/user/projects/57aacb4bf27cc20050102f19/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/57aacb4bf27cc20050102f19)
[![Code Climate](https://codeclimate.com/github/JamesRezo/WebHelper/badges/gpa.svg)](https://codeclimate.com/github/JamesRezo/WebHelper)

## Installation

 Just add a dependency on `jamesrezo/webhelper` to your project's `composer.json` file because you obviously will use [Composer](https://getcomposer.org) to manage the dependencies of your project. For example:

```
{
    "require": {
        "jamesrezo/webhelper": "dev-master"
    }
}
```

For a global installation via Composer, run:

```composer global require "jamesrezo/webhelper=0.1"```

Make sure you have `~/.composer/vendor/bin/` in your path. 

## Usage

```wh web:generate <webserver> <directive1>..<directiveN>```

* `<webserver>` means actually **apache** and an optional version (see below)
* `<directive>` can be a list of any configuration directive known by the webserver (for now *alias*, *vhost* and *directory*)

> Well, <webserver> syntax is weird... 
> There is the webserver *name* and the webserver *version*, all attached.
> * name is required, could be apache, nginx, lighttpd, openlightspeed or whatever this helper can find in its repository.
> * version is optional but useful when configuration syntax changes as the webserver evolves.
> * apache means the lowest version of Apache webserver
> * apache2 means any 2.x version of Apache webserver
> * apache2.2.16 means... Yes! Apache/2.2.16 precisely.
> * apache2.4 means any 2.4.x version of Apache webserver...

This will output some text that you'll put into a httpd server configuration file.

## Contributions

...are welcome, of course ;-)
