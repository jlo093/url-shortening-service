## URL Shortening Service

The service provides two endpoints - one to decode a full (or "lengthy") URL into a shorter URL. There's no persistence layer (i.e. database) - instead the shortened URL's and their full URL counterpart are stored in Redis for faster access.

## Configuration

There's no specific configuration required to run the project, using Docker the necessary containers are created automatically.

However, you can amend the *APP_URL* environment variable to change the host/base of the short url.

## Setup

You'll require a running installation of Docker.

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
http://localhost:8080
``

**Note**: Since the environment runs on standard ports ensure there's no other projects running containers with the same ports, otherwise start-up will fail.

## Tests

The project uses unit tests to ensure functionality remains stable. 
