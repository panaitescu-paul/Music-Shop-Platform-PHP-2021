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
| GET    |/artists/_<artist_id>_ | Returns detailed information of the artist with ID _<artist_id>_ |
| GET    |/artists/?name=_<search_text>_ | Returns information of those artists whose name contains _<search_text>_ |
| POST   |/artists <br><br>Request body | Adds a new artist<br><br>name |
| POST   |/artists/_<artist_id>_<br><br>Request body<br> | Updates the artist with ID _<artist_id>_<br><br>name<br>|
| DELETE |/artists/_<artist_id>_ | Deletes the artist with ID _<artist_id>_ |

#### Examples for Artists:
GET http://localhost/WAD-MA2/artists  </br>
GET http://localhost/WAD-MA2/artists/1  </br>
GET http://localhost/WAD-MA2/artists/?name=John  </br>
POST http://localhost/WAD-MA2/artists  </br>
POST http://localhost/WAD-MA2/artists/1  </br> 
DELETE http://localhost/WAD-MA2/artists/1  </br>

#### Endpoints for Albums:
| Method | Usage        | Description                         |
| ------ |:------------ |:----------------------------------- |
| GET    |/albums | Returns information of all albums |
| GET    |/albums/_<album_id>_ | Returns detailed information of the album with ID _<album_id>_ |
| GET    |/albums/?title=_<search_text>_ | Returns information of those albums whose title contains _<search_text>_ |
| POST   |/albums <br><br>Request body | Adds a new album<br><br>title |
| POST   |/albums/_<album_id>_<br><br>Request body<br> | Updates the album with ID _<album_id>_<br><br>title<br>|
| DELETE |/albums/_<album_id>_ | Deletes the album with ID _<album_id>_ |

#### Examples for Albums:
GET http://localhost/WAD-MA2/albums  </br>
GET http://localhost/WAD-MA2/albums/1  </br>
GET http://localhost/WAD-MA2/albums/?title=Album1  </br>
POST http://localhost/WAD-MA2/albums  </br>
POST http://localhost/WAD-MA2/albums/1  </br>
DELETE http://localhost/WAD-MA2/albums/1  </br> 

#### Endpoints for Tracks:
| Method | Usage        | Description                         |
| ------ |:------------ |:----------------------------------- |
| GET    |/tracks | Returns information of all tracks |
| GET    |/tracks/_<track_id>_ | Returns detailed information of the track with ID _<track_id>_ |
| GET    |/tracks/?name=_<search_text>_ | Returns information of those tracks whose name contains _<search_text>_ |
| POST   |/tracks <br><br>Request body | Adds a new track<br><br>name, albumId, mediaTypeId, genderId, composer, milliseconds, bytes, unitPrice |
| POST   |/tracks/_<track_id>_<br><br>Request body<br> | Updates the track with ID _<track_id>_<br><br>name, albumId, mediaTypeId, genderId, composer, milliseconds, bytes, unitPrice |
| DELETE |/tracks/_<track_id>_ | Deletes the track with ID _<track_id>_ |

#### Examples for Tracks:
GET http://localhost/WAD-MA2/tracks  </br>
GET http://localhost/WAD-MA2/tracks/1  </br>
GET http://localhost/WAD-MA2/tracks/?name=Track1  </br>
POST http://localhost/WAD-MA2/tracks  </br>
POST http://localhost/WAD-MA2/tracks/1  </br>
DELETE http://localhost/WAD-MA2/tracks/1  </br> 

#### Endpoints for Customers:
| Method | Usage        | Description                         |
| ------ |:------------ |:----------------------------------- |
| GET    |/customers | Returns information of all customers |
| GET    |/customers/_<customer_id>_ | Returns detailed information of the customer with ID _<customer_id>_ |
| GET    |/customers/?email=_<search_text>_ | Returns information of those customers whose email is _<search_text>_ |
| POST   |/customers <br><br><br>Request body | Adds a new customer<br><br>firstName, lastName, password, company, address, city, state, country, postalCode, phone, fax, email |
| POST   |/customers/_<customer_id>_<br><br><br>Request body<br> | Updates the customer with ID _<customer_id>_<br><br>firstName, lastName, password, company, address, city, state, country, postalCode, phone, fax, email |
| DELETE |/customers/_<customer_id>_ | Deletes the customer with ID _<customer_id>_ |

#### Examples for Customers:
GET http://localhost/WAD-MA2/customers  </br>
GET http://localhost/WAD-MA2/customers/1  </br>
GET http://localhost/WAD-MA2/customers/?email=name@gmail.com  </br>
POST http://localhost/WAD-MA2/customers  </br>
POST http://localhost/WAD-MA2/customers/1  </br>
DELETE http://localhost/WAD-MA2/customers/1  </br> 


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
    "AlbumId": "1",
    "Title": "For Those About To Rock We Salute You",
    "ArtistId": "1"
}
```

Get Track with id 1

```json
{
    "TrackId": "1",
    "Name": "For Those About To Rock (We Salute You)",
    "AlbumId": "1",
    "MediaTypeId": "1",
    "GenreId": "1",
    "Composer": "Angus Young, Malcolm Young, Brian Johnson",
    "Milliseconds": "343719",
    "Bytes": "11170334",
    "UnitPrice": "0.99"
}
```

Get Customer with id 1

```json
{
    "CustomerId": "1",
    "FirstName": "Luís",
    "LastName": "Gonçalves",
    "Password": "$2y$10$WtD6WywiBP7qNi8yZj7gYuIhjTy1xsAwAKSEgXj/ftRZWTLjz1cpu",
    "Company": "Embraer - Empresa Brasileira de Aeronáutica S.A.",
    "Address": "Av. Brigadeiro Faria Lima, 2170",
    "City": "São José dos Campos",
    "State": "SP",
    "Country": "Brazil",
    "PostalCode": "12227-000",
    "Phone": "+55 (12) 3923-5555",
    "Fax": "+55 (12) 3923-5566",
    "Email": "luisg@embraer.com.br"
}
```

Get Artists with the name John

```json
[
    {
        "ArtistId": "222",
        "Name": "Academy of St. Martin in the Fields, John Birch, Sir Neville Marriner & Sylvia McNair"
    },
    {
        "ArtistId": "263",
        "Name": "Equale Brass Ensemble, John Eliot Gardiner & Munich Monteverdi Orchestra and Choir"
    },
    {
        "ArtistId": "170",
        "Name": "Jack Johnson"
    },
    {
        "ArtistId": "218",
        "Name": "Orchestre Révolutionnaire et Romantique & John Eliot Gardiner"
    }
]
```

Get Albums with the title Song

```json
[
    {
        "AlbumId": "262",
        "Title": "Quiet Songs",
        "ArtistId": "197"
    },
    {
        "AlbumId": "137",
        "Title": "The Song Remains The Same (Disc 1)",
        "ArtistId": "22"
    },
    {
        "AlbumId": "138",
        "Title": "The Song Remains The Same (Disc 2)",
        "ArtistId": "22"
    }
]
```



#### Author:
Paul Panaitescu
