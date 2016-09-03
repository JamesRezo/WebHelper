# WebHelper
A Generic Httpd Configuration Helper

[![Build Status](https://api.travis-ci.org/JamesRezo/WebHelper.svg?branch=master)](https://travis-ci.org/JamesRezo/WebHelper)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/57e3dc27-e915-42d4-9bde-863a8f3bf5f8/mini.png)](https://insight.sensiolabs.com/projects/57e3dc27-e915-42d4-9bde-863a8f3bf5f8)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JamesRezo/WebHelper/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/JamesRezo/WebHelper/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/JamesRezo/WebHelper/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/JamesRezo/WebHelper/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/57aacb4bf27cc20050102f19/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/57aacb4bf27cc20050102f19)
[![Code Climate](https://codeclimate.com/github/JamesRezo/WebHelper/badges/gpa.svg)](https://codeclimate.com/github/JamesRezo/WebHelper)

## Installation

For a global installation via Composer, run:

```composer global require jamesrezo/webhelper=dev-master```

Then, copy ```app/config/parameters.yml.dist``` into ```~/.config/webhelper/parameters.yml```

Make sure you have `~/.composer/vendor/bin/` or `~/.config/composer/vendor/bin/` in your path. 

## Usage

```wh generate <webserver> <directive1>..<directiveN>```

* `<webserver>` means actually **apache** and an optional version (see below)
* `<directive>` can be a list of any configuration directive known by the webserver (for now *alias*, *vhost* and *directory*)

> Well, <webserver> syntax is weird... 
> There is the webserver *name* and optionally the webserver *version*, separated with a colon (`:`).
> * name is required, could be apache, nginx, lighttpd, openlightspeed or whatever this helper can find in its repository.
> * version is optional but useful when configuration syntax changes as the webserver evolves.
> * apache means the lowest version of Apache webserver
> * apache:2 means any 2.x version of Apache webserver
> * apache:2.2.16 means... Yes! Apache/2.2.16 precisely.
> * apache:2.4 means any 2.4.x version of Apache webserver...

This will output some text that you'll put into a httpd server configuration file.

## Contributions

...are welcome, of course ;-)
