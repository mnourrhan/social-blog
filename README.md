# social-blog API
[social-blog](https://github.com/mnourrhan/social-blog) API - Micro blogging social network service built with Laravel framework on which users post and interact with messages known as "tweets". Users can post tweets and only the followers of them can see that tweets.

## Installation
- Clone the repo
- Run "php artisan config:cache"
- Run the scripts exist in/database/migration/database-init.sql with replacing the db server
- Change the .env.local file DB_HOST with your local host.
- Run "php artisan migrate"
- You can run "vendor/bin/phpunit" for testing the APIs


## Authentication
Aside from the initial call to the `/sessions` endpoint, `POST`, `PATCH`, `DELETE` and sensitive `GET` requests will need to be authenticated.

An `Authorization: Basic` header with a Base64 encoding of `access_token`, where `access_token` is retrieved from the JWT authentication.

## Media Types
This API uses the JSON format, given limited client support `Content-Type` and `Accept` should still be set to `application/json`.

Requests with a message-body are using plain JSON to set or update resource states.

## Error States
statu with fail value will be returned when error occur

Specifally, this API uses:

- 200: "Successful", often return from a GET/POST request
- 400: "Failed", often return from a GET/POST request

# User Auth

## User Register [/register]
A single User object.

The User resource has the following attributes: 

- id
- email
- name
- birth_date
- password
- image_name
- created_at
- updated_at

The states *id*, *created_at* and *updated_at* are assigned by the API at the moment of creation. 

+ Request (application/json)

    + Headers
        
            Accept: application/json
         
    + Body

            {
                "name": "user",
                "email": "user@test.com",
                "password": "12345678",
                "birth_date": "1990-10-02",
                "image": ** type is file **
            }

+ Response 200

        {
            "status": "success",
            "data": {
                "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9yZWdpc3RlciIsImlhdCI6MTU4MjA4NTczOSwiZXhwIjoxNTgyMDg5MzM5LCJuYmYiOjE1ODIwODU3MzksImp0aSI6IjIyMXpBMHludnpsVTkxRVgiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.C4pp9epzwi-OGjS11_RqVC6yzLMJa44VwjN5YSEPzxo",
                "token_type": "bearer",
                "expires_in": 3600
            }
        }
        
+ Response 400    
        {
            "status": "fail",
            "data": [
                "Password is required!"
            ]
        }

### User Login [/login]
+ Request (application/json)

    + Headers
        
            Accept: application/json
         
    + Body

            {
                "email": "user@test.com",
                "password": "12345678",
            }

+ Response 200

        {
            "status": "success",
            "data": {
                "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTU4MjA3OTk0NCwiZXhwIjoxNTgyMDgzNTQ0LCJuYmYiOjE1ODIwNzk5NDQsImp0aSI6IkdDeXRPRUU3Y2RWMFoxbW4iLCJzdWIiOjMsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.myH2uIdoVFksH3AkaU4stC_ljrUYg1LeuSwNwAiQGJQ",
                "token_type": "bearer",
                "expires_in": 3600
            }
        }

+ Response 400

        {
            "status": "fail",
            "data": {
                "message": "Incorrect username or password"
            }
        }

# Tweet Resources

## Store Tweet [/tweet/create]

+ Request (application/json)

    + Headers
        
            Accept: application/json
            Authorization: Bearer +$token
         
    + Body

            {
                "content": "testing tweet",
            }

+ Response 200

        {
            "status": "success",
            "data": {
                "content": "testing tweet",
                "user_id": 2,
                "updated_at": "2020-02-19 06:00:44",
                "created_at": "2020-02-19 06:00:44",
                "id": 1
            }
        }

+ Response 400

        {
            "status": "fail",
            "data": [
                "Tweet content is required!"
            ]
        }


## Delete Tweet [/tweet/delete/{id}]

+ Request (application/json)

    + Headers
        
            Accept: application/json
            Authorization: Bearer +$token
         
    + Body

            {
            }

+ Response 200

        {
            "status": "success",
            "data": {
                "message": "Tweet successfully deleted!"
            }
        }

+ Response 400

        {
            "status": "fail",
            "data": {
                "message": "The tweet your are trying to delete not exist!"
            }
        }

## User Tweets[/timeline]

+ Request (application/json)

    + Headers
        
            Accept: application/json
            Authorization: Bearer +$token
         
    + Body

            {
            }

+ Response 200

        {
            "status": "success",
            "data": {
                "message": "Successfully unfollowed the user!"
            }
        }

+ Response 400

        {
            "status": "fail",
            "data": {
                "message": "You can't unfollow your account as you already can see your tweets!"
            }
        }
        
# User Follow/UnFollow

## Follow User [/user/follow/{id}]

+ Request (application/json)

    + Headers
        
            Accept: application/json
            Authorization: Bearer +$token
         
    + Body

            {
            }

+ Response 200

        {
            "status": "success",
            "data": {
                "message": "Successfully followed the user!"
            }
        }

+ Response 400

        {
            "status": "fail",
            "data": {
                "message": "You can't follow your account as you already can see your tweets!"
            }
        }
        
## UnFollow User [/user/unfollow/{id}]

+ Request (application/json)

    + Headers
        
            Accept: application/json
            Authorization: Bearer +$token
         
    + Body

            {
            }

+ Response 200

        {
            "status": "success",
            "data": {
                "current_page": 1,
                "data": [
                    {
                        "id": 2,
                        "content": "Testing tweet",
                        "user_id": "2",
                        "created_at": "2020-02-19 06:13:10",
                        "updated_at": "2020-02-19 06:13:10"
                    }
                ],
                "first_page_url": "http://127.0.0.1:8000/api/timeline?page=1",
                "from": 1,
                "last_page": 1,
                "last_page_url": "http://127.0.0.1:8000/api/timeline?page=1",
                "next_page_url": null,
                "path": "http://127.0.0.1:8000/api/timeline",
                "per_page": 7,
                "prev_page_url": null,
                "to": 1,
                "total": 1
            }
        }