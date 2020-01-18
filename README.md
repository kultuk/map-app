# map-app
use this app requires a couple of stages.

first: 
adding the google map api key on this file:
```
client\src\creds.json
```

first run the server side. inside the `/server/public` folder run
```
php -S localhost:8888
```

for the client side, inside the client folder run
```
npm run serve
```

There's jwt key on the server files that should not be used on production. 

# API

Both the `/register` and the `/login` methods return a jwt token (`auth_token`) on the body of the response.
That token has to be sent on any other api call (for getting and adding locations).

All the API calls will return an error message on any problem.
```
{
  "success": false,
  "error": "The error message",
  "invalidToken": true
  
}
```
when the object returns `"invalidToken":true`, the user should be logged out.


## Register
```
POST /register

{
  "username":"ugly",
  "password": "duckling"
}
```

## Login
```
POST /login

{
  "username":"ugly",
  "password": "duckling"
}
```

## Get Saved Locations
```

GET /locations
auth_token: jwt token received from the login/register

```
retuned:
```
[{lng:32,lat:35},{}...]
```
## Adding Location
```

POST /locations/add
auth_token: jwt token received from the login/register

{
  "lan": 32.44,
  "lng": 35.34
}
```
