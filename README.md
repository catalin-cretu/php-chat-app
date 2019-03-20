# php-chat-app
Super simple chat app, written in PHP.

## Tools
- PHP 7+
    - sqlite3 ext

## Scripts
- `build`
    - install dependencies
    - run tests
- `serve`
    - install dependencies
    - starts dev server

## Features 
- data should be stored in a SQLite DB
- clients and server can communicate over HTTP in JSON format
- a user can send a message to another user
- a user can see messages from another user

## Endpoints
```
GET : localhost:8008/api.php/users/{id}/messages
POST: localhost:8008/api.php/users/{id}/messages
```