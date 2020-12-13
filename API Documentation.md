# WAD-Mandatory-Assignment-2
WAD Mandatory Assignment 2

## API Documentation

#### Main usage:

http://_<server_name>_/WAD-MA2/_<end_point>_


#### Endpoints for API documentation:

| Method | Usage        | Description                         |
| ------ |:------------ |:----------------------------------- |
| GET    |/    | Returns the API description for GET methods     |

#### Examples:

GET http://localhost/WAD-MA2/

#### Endpoints for Artists:

| Method | Usage        | Description                         |
| ------ |:------------ |:----------------------------------- |
| GET    |/artists | Returns information of all artists |
| GET    |/artists?name=_<search_text>_ | Returns information of those artists whose name contains _<search_text>_ |
| GET    |/artists/_<artist_id>_ | Returns detailed information of the artist with ID _<artist_id>_ |
| POST   |/artists <br><br>Request body | Adds a new artist<br><br>name |
| POST   |/artists/_<artist_id>_<br><br>Request body<br> | Updates the artist with ID _<artist_id>_<br><br>name<br>|
| DELETE |/artists/_<artist_id>_ | Deletes the artist with ID _<artist_id>_ |

#### Examples:

GET http://localhost/WAD-MA2/artists

GET http://localhost/WAD-MA2/artists?name=John

GET http://localhost/WAD-MA2/artists/1

POST http://localhost/WAD-MA2/artists

POST http://localhost/WAD-MA2/artists/1

DELETE http://localhost/WAD-MA2/artists/1


#### Endpoints for Albums:

| Method | Usage        | Description                         |
| ------ |:------------ |:----------------------------------- |
| GET    |/albums | Returns information of all albums |
| GET    |/albums?name=_<search_text>_ | Returns information of those albums whose name contains _<search_text>_ |
| GET    |/albums/_<album_id>_ | Returns detailed information of the album with ID _<album_id>_ |
| POST   |/albums <br><br>Request body | Adds a new album<br><br>title |
| POST   |/albums/_<album_id>_<br><br>Request body<br> | Updates the album with ID _<album_id>_<br><br>title<br>|
| DELETE |/albums/_<album_id>_ | Deletes the album with ID _<album_id>_ |

#### Examples:

GET http://localhost/WAD-MA2/albums

GET http://localhost/WAD-MA2/albums?title=Album 1

GET http://localhost/WAD-MA2/albums/1

POST http://localhost/WAD-MA2/albums

POST http://localhost/WAD-MA2/albums/1

DELETE http://localhost/WAD-MA2/albums/1


#### Sample Output:

Get artist with id 1

```json
{
    "ArtistId": "1",
    "Name": "AC/DC"
}
```

#### Author:
Paul Panaitescu
