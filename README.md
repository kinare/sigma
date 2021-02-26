## Sigma
This is a laravel package to integrate systems. It uses metadata for integration in three levels the Provider, Wrapper and fields.

## Installation

Release One of sigma is manually installed
Clone the sigma repo
```shell
git clone git@github.com:kinetics254/sigma.git
```
Checkout the staging branch for latest release
```shell
git checkout staging
```

place sigma in package directory at the root of your project

configure composer to discover sigma package

```json
"autoload": {
    "psr-4": {
        "kinetics254\\Sigma\\": "packages/kinetics254/sigma/src/",
    }
},
"autoload-dev": {
    "psr-4": {
        "kinetics254\\Sigma\\": "packages/kinetics254/sigma/src/",
    }
}
```

Register SigmaServiceProvider in config app
```php
\KTL\Sigma\Providers\SigmaServiceProvider::class;
```
Configure Sigma BC credentials on your .env
```dotenv
SIGMA_URL='http://domain:port/BC150/api/vendor/Sigma/version_no/'
SIGMA_USER='username'
SIGMA_PASSWORD='passsword'
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

### Documentation
To view sigma APi's definition go to ```/simga``` on your project

