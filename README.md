# php-chat-app
Super simple RESTful API for a chat app, written in PHP.

## Tools
- PHP 7+
- composer
- bash (for scripts)

## Scripts
- `build`
    - install dependencies
    - run tests
- `serve`
    - install dependencies
    - starts built-in web server

## Features 
- data can be stored in a SQLite DB
- clients and server can communicate over HTTP in JSON format
- a user can send a message to another user
- a user can see messages from another user

## Endpoints
```
GET : localhost:8008/api.php/users/{id}/messages
POST: localhost:8008/api.php/users/{id}/messages
```

## Improvements
- use a production-ready REST API library
    - unit tests could be reduced to focus more on business logic (services)
    when using well known, tested libraries
    - full support for restful endpoints
- API versioning
- user security