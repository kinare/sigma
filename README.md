## Sigma
This is a laravel package to integrate systems. It uses metadata for integration in three levels the Provider, Wrapper and fields.

## Installation
This release is hosted on github

To install sigma head over to your projects composer.json
and add the sigma repository on the repository option.
```json
"repositories": [
        {
            "type": "git",
            "url": "https://github.com/kinetics254/sigma"
        }
    ]
```

Add sigma on the require list in your composer.json
```json
"kinetics254/sigma": "dev-main",
```

Install sigma with composer require command
```shell
composer require kinetics254/sigma
```

This will install sigma package into your project and your are ready to go.
Sigma has package autodiscovery turned on for laravel 5.5 and later so no need to register its service provider manually.

Register SigmaServiceProvider in config app for laravel 5.5 and below
```php
\KTL\Sigma\Providers\SigmaServiceProvider::class;
```
Configure Sigma BC credentials on your .env
```dotenv
SIGMA_URL='http://domain:port/BC150/api/vendor/Sigma/version_no/'
SIGMA_USER='username'
SIGMA_PASSWORD='passsword'
SIGMA_SERVICE='PROVIDER'
```

Publish Sigma assets
```shell
php artisan vendor:publish --provider=kinetics254\Sigma\Providers\SigmaServiceProvider
```

Migrate to create sigma tables

```shell
php artisan migrate
```

## Usage

To use sigma use the registered facade
sigma has a static method to perform all request, its signature is
```php
Sigma::request('proivider', 'entity', [payload], 'method');
```

## Examples
### Get Request
```php
$res = Sigma::request('MAGNOLIA', 'refereeEntity', ['$filter' => "ProfileID eq '976I7C'"]);
```

### Post Request
```php
$data = [
     "ProfileID" => "976I7C",
     "Name" =>  "Testing Post res",
     "Address" =>  "The address",
     "Email" =>  "coboek@example.com",
     "PhoneNo" =>  "4789252996",
     "PlaceOfWork" =>  "Lubdaal Inc.",
     "designation"=>  "4090351720724285",
     "EntryNo" =>  1
];

$res = Sigma::request('MAGNOLIA', 'refereeEntity', $data, 'post');
```

### Patch Request

```php
$data = [
    "ProfileID" => "976I7C",
    "Name" =>  "Testing patch res",
    "Address" =>  "The addressssss",
    "Email" =>  "coboek@example.com",
    "PhoneNo" =>  "478925sd2996",
    "PlaceOfWork" =>  "Lubdaal Indc.",
    "designation"=>  "4090351720724285",
    "LineNo"=>  1000,
];

$res = Sigma::request('MAGNOLIA', 'refereeEntity', $data, 'patch');
```
### Delete Request
```php
$data = [
    "ProfileID" => "976I7C",
    "Name" =>  "Testing patch res",
    "Address" =>  "The addressssss",
    "Email" =>  "coboek@example.com",
    "PhoneNo" =>  "478925sd2996",
    "PlaceOfWork" =>  "Lubdaal Indc.",
    "designation"=>  "4090351720724285",
    "LineNo"=>  1000,
];

$res = Sigma::request('MAGNOLIA', 'refereeEntity', $data, 'delete');
```
### Calling Codeunits in BC Request
Set the method to be 'CU' for codeunits and the payload to be an array 
these will be used as parameters in the codeunit function.
```php
$res = Sigma::request('MEMBERSHIPMANAGER', 'getProfomaInvoice', ['applicationNo' => "APP000033"], 'CU');
```

###Sync Job
Schedule ```sigma:sync``` command to re-fetch providers, wrappers and fields from BC
```php
 $schedule->command('sigma:sync')->hourly();
```

### Documentation
To view sigma APi's definition go to ```/simga``` on your project

### Next Release
The next release will have ability to test sigma endpoints from the sigma UI



