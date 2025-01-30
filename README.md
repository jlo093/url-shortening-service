## URL Shortening Service

The service provides two endpoints - one to decode a full (or "lengthy") URL into a shorter URL. There's no persistence layer (i.e. database) - instead the shortened URL's and their full URL counterpart are stored in Redis for faster access.

The endpoints are located at:
````
/api/encode
/api/decode
````

## Configuration

There's no specific configuration required to run the project, using Docker the necessary containers are created automatically.

However, you can amend the *APP_URL* environment variable to change the host/base of the short url.

## Setup

You'll require a running installation of Docker.

Before we start, create a copy of 
``
.env.example
``
and name it
``.env
``.

You can either use sail or docker-compose to spin up the necessary containers:

``
./vendor/bin/sail up -d
``
or 
``
docker-compose up -d
``

Once the containers are created, the service is available at
``
http://localhost
``

**Note**: Since the environment runs on standard ports ensure there's no other projects running containers with the same ports, otherwise start-up will fail.

## Tests

The project uses unit tests to ensure functionality remains stable. Ensure the containers are running, then execute 
``
php artisan test
``

## Request Examples

Shorten a full URL:

````bash
curl --location 'http://localhost/api/encode' \
--header 'Content-Type: application/json' \
--header 'Accept: application/json' \
--data '{
"url": "https://www.atarim.io/this-is-a-test-url-only?param=abc#anchor"
}'
````

Resolve a shortened URL:
````bash
curl --location 'http://localhost/api/decode' \
--header 'Content-Type: application/json' \
--header 'Accept: application/json' \
--data '{
    "url": "{yourShortUrl}"
}'
````

## Bonus

- Not specifically requested, but the shortened URL's do actually work. The endpoint is configured in web.php to redirect accordingly
