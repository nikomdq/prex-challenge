Endpoints

post: http://127.0.0.1:8000/api/login

json example
{
  "email": "test@example.com",
  "password": "password"
}


Endpoints (auth required)
Add header:
"Authorization: Bearer 1|[token]" 

get: http://127.0.0.1:8000/api/giphy/search?query=funny+cats&limit=5&offset=0

get: http://127.0.0.1:8000/api/giphy/search/{id}

post: http://127.0.0.1:8000/api/giphy/favorite

json example
{
    "gif_id": 12345,
    "alias": "Mi GIF favorito",
    "user_id": 1
}
