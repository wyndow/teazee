## Teazee

A simple interface to find the timezone and time offset data for a position on the surface of the earth.

* [Installation](#installation)
* [Usage](#usage)
  - [ZoneInfo](#zoneinfo)
  - [Providers](#providers)
    - [TimeZoneDB](#timezonedb)
  - [HTTP Clients](#http-clients)
* [Extending Things](#extending-things)
* [Versioning](#versioning)


Installation
------------

The recommended way to install Teazee is through
[Composer](http://getcomposer.org):

```
$ composer require wyndow/teazee
```


Usage
-----

The `Teazee` interface, which all providers implement, exposes a single method:

* `find($lat, $lng, $timestamp)`

### ZoneInfo

The `find()` method returns a `ZoneInfo` object, which extends PHP's [`DateTimeZone`](http://php.net/manual/en/class.datetimezone.php) and exposes the following additional methods:

* `getDateTime()` will return a [`DateTimeImmutable`](http://php.net/manual/en/class.datetimeimmutable.php) representing the specified `timestamp`.
* `getTimestamp()` will return a UNIX timestamp (`int`) for the specified `timestamp`. Generally this value is used to determine whether or not Daylight Savings Time should be applied. Not all providers require a timestamp. If the timestamp is required, but not provided, the current time will be used.
* `getUtcOffset()` will return the offset (`int`) from UTC (in seconds) for the given location.
* `isDst()` will return a `boolean` representing whether or not the timezone is in Daylight Savings Time during the specified `timestamp`.
* `getCountry()` will return the 2-digit country code for the timezone.

> **Note**: You can use `ZoneInfo` as a drop-in replacement for `DateTimeZone` in your code.


### Providers

Providers perform the black magic for you: talking to the APIs, fetching results, dealing with errors, etc.

#### TimeZoneDB

A valid `apiKey` is required to use the [TimeZoneDB](https://timezonedb.com) provider. You can [register for a free account](https://timezonedb.com/register) to obtain an API key.

```php
$teazee = new \Teazee\Provider\TimeZoneDB($apiKey);
```


### HTTP Clients

In order to talk to time zone APIs, you need an HTTP client. Teazee relies on the
[PSR-7
Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md) and attempts to relieve you from worrying too much about its implementation.

You'll be required to include a package that provides an [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation), but the package you choose is up to you. By default, as long as you have a client implementation package included, Teazee will figure out the rest.

#### Advanced: Discovery

In order to determine the correct client and message factory implementations to use, the `AbstractHttpProvider` will default to using [Http Discovery](https://github.com/php-http/discovery) to find your installed `HttpClient` implementation.  You can override discovery by passing concrete `HttpClient` and `MessageFactory` implementations to the Provider's constructor.


Extending Things
----------------

You can write your own `provider` by implementing the `Provider` interface.


Versioning
----------

Teazee follows [Semantic Versioning](http://semver.org/).


Contributing
------------

See
[`CONTRIBUTING`](https://github.com/wyndow/teazee/blob/master/CONTRIBUTING.md#contributing)
file.


Unit Tests
----------

In order to run the test suite, install the development dependencies:

```
$ composer install --dev
```

Then, run the following command:

```
$ bin/kahlan
```


Credits
-------

* Michael Crumm <mike@crumm.net>
* [All contributors](https://github.com/wyndow/teazee/contributors)

Special thanks goes to William Durand and the [Geocoder](https://github.com/geocoder-php/) project, upon which we based the application structure and documentation for Teazee.


Contributor Code of Conduct
---------------------------

As contributors and maintainers of this project, we pledge to respect all people
who contribute through reporting issues, posting feature requests, updating
documentation, submitting pull requests or patches, and other activities.

We are committed to making participation in this project a harassment-free
experience for everyone, regardless of level of experience, gender, gender
identity and expression, sexual orientation, disability, personal appearance,
body size, race, age, or religion.

Examples of unacceptable behavior by participants include the use of sexual
language or imagery, derogatory comments or personal attacks, trolling, public
or private harassment, insults, or other unprofessional conduct.

Project maintainers have the right and responsibility to remove, edit, or reject
comments, commits, code, wiki edits, issues, and other contributions that are
not aligned to this Code of Conduct. Project maintainers who do not follow the
Code of Conduct may be removed from the project team.

Instances of abusive, harassing, or otherwise unacceptable behavior may be
reported by opening an issue or contacting one or more of the project
maintainers.

This Code of Conduct is adapted from the [Contributor
Covenant](http:contributor-covenant.org), version 1.0.0, available at
[http://contributor-covenant.org/version/1/0/0/](http://contributor-covenant.org/version/1/0/0/)


License
-------

Teazee is released under the MIT License. See the bundled LICENSE file for
details.