# WAD-Mandatory-Assignment-2
WAD Mandatory Assignment 2

## API Documentation

####Main usage:

http://_<server_name>_/WAD-MA2/_<end_point>_

####Endpoints for Artists:

| Method | Usage        | Description                         |
| ------ |:------------ |:----------------------------------- |
| GET    |/    | Returns the API description for GET methods     |
| GET    |/artists | Returns information of all artists |
| GET    |/artists?name=_<search_text>_ | Returns information of those artists whose name contains _<search_text>_ |
| GET    |/artists/_<artist_id>_ | Returns detailed information of the artist with ID _<artist_id>_ |
| POST   |/artists <br><br>Request body | Adds a new artist<br><br>name |
| POST   |/artists/_<artist_id>_<br><br>Request body<br> | Updates the artist with ID _<artist_id>_<br><br>name<br>|
| DELETE |/artists/_<artist_id>_ | Deletes the artist with ID _<artist_id>_ |

####Examples:

GET http://localhost/WAD-MA2/
GET http://localhost/WAD-MA2/artists
GET http://localhost/WAD-MA2/artists?name=John
GET http://localhost/WAD-MA2/artists/5
POST http://localhost/WAD-MA2/artists
POST http://localhost/WAD-MA2/artists/5
DELETE http://localhost/WAD-MA2/artists/5

####Sample Output:

Get artists

```json
{}
```

####Author:
Paul Panaitescu
