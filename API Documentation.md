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

#### Examples for Artists:
GET http://localhost/WAD-MA2/artists  </br>
GET http://localhost/WAD-MA2/artists?name=John  </br>
GET http://localhost/WAD-MA2/artists/1  </br>
POST http://localhost/WAD-MA2/artists  </br>
POST http://localhost/WAD-MA2/artists/1  </br> 
DELETE http://localhost/WAD-MA2/artists/1  </br>

#### Endpoints for Albums:
| Method | Usage        | Description                         |
| ------ |:------------ |:----------------------------------- |
| GET    |/albums | Returns information of all albums |
| GET    |/albums?title=_<search_text>_ | Returns information of those albums whose title contains _<search_text>_ |
| GET    |/albums/_<album_id>_ | Returns detailed information of the album with ID _<album_id>_ |
| POST   |/albums <br><br>Request body | Adds a new album<br><br>title |
| POST   |/albums/_<album_id>_<br><br>Request body<br> | Updates the album with ID _<album_id>_<br><br>title<br>|
| DELETE |/albums/_<album_id>_ | Deletes the album with ID _<album_id>_ |

#### Examples for Albums:
GET http://localhost/WAD-MA2/albums  </br>
GET http://localhost/WAD-MA2/albums?title=Album1  </br>
GET http://localhost/WAD-MA2/albums/1  </br>
POST http://localhost/WAD-MA2/albums  </br>
POST http://localhost/WAD-MA2/albums/1  </br>
DELETE http://localhost/WAD-MA2/albums/1  </br> 

#### Endpoints for Tracks:
| Method | Usage        | Description                         |
| ------ |:------------ |:----------------------------------- |
| GET    |/tracks | Returns information of all tracks |
| GET    |/tracks?name=_<search_text>_ | Returns information of those tracks whose name contains _<search_text>_ |
| GET    |/tracks/_<track_id>_ | Returns detailed information of the track with ID _<track_id>_ |
| POST   |/tracks <br><br><br><br><br><br><br><br>Request body | Adds a new track<br>name <br>albumId<br>mediaTypeId<br>genderId<br>composer<br>milliseconds<br>bytes<br>unitPrice |
| POST   |/tracks/_<track_id>_<br><br><br><br><br><br><br><br>Request body<br> | Updates the track with ID _<track_id>_<br>name<br>albumId<br>mediaTypeId<br>genderId<br>composer<br>milliseconds<br>bytes<br>unitPrice |
| DELETE |/tracks/_<track_id>_ | Deletes the track with ID _<track_id>_ |

#### Examples for Tracks:
GET http://localhost/WAD-MA2/tracks  </br>
GET http://localhost/WAD-MA2/tracks?name=Track1  </br>
GET http://localhost/WAD-MA2/tracks/1  </br>
POST http://localhost/WAD-MA2/tracks  </br>
POST http://localhost/WAD-MA2/tracks/1  </br>
DELETE http://localhost/WAD-MA2/tracks/1  </br> 


#### Sample Output:

Get Artist with id 1

```json
{
    "ArtistId": "1",
    "Name": "AC/DC"
}
```
Get Album with id 1

```json
{
..
}
```

Get Track with id 1

```json
{
..
}
```

Get Customer with id 1

```json
{
..
}
```

#### Author:
Paul Panaitescu
