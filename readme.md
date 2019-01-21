#  Restful API Using PHP

## Introduction

1. A RESTful HTTP API listening to port `8080`
2. 3 endpoints
    - create an order
    - take an order
    - list orders
3. Using distance api from google api
4. Mysql as DB and Laravel as PHP based Framework.
5. Add your google distance api key to .env file before running `start.sh`.
6. Run `start.sh` to do all the initialisation and installation using docker.

## Api interface example

#### Place order

  - Method: `POST`
  - URL path: `/api/orders`
  - Request body:

    ```
    {
        "origin": ["START_LATITUDE", "START_LONGTITUDE"],
        "destination": ["END_LATITUDE", "END_LONGTITUDE"]
    }
    ```

  - Response:

    Header: `HTTP 200`
    Body:
      ```
      {
          "id": <order_id>,
          "distance": <total_distance>,
          "status": "UNASSIGNED"
      }
      ```
    or

    Header: `HTTP <HTTP_CODE>`
    Body:
      ```json
      {
          "error": "ERROR_DESCRIPTION"
      }
      ```

#### Take order

  - Method: `PUT`
  - URL path: `/api/orders/:id`
  - Request body:
    ```
    {
        "status":"TAKEN"
    }
    ```
  - Response:
    Header: `HTTP 200`
    Body:
      ```
      {
          "status": "SUCCESS"
      }
      ```
    or

    Header: `HTTP 409`
    Body:
      ```
      {
          "error": "ORDER ALREADY BEEN TAKEN"
      }
      ```

#### Order list

  - Method: `GET`
  - Url path: `/api/orders?page=:page&limit=:limit`
  - Response:

    ```
    [
        {
            "id": <order_id>,
            "distance": <total_distance>,
            "status": <ORDER_STATUS>
        },
        ...
    ]
    ```
    or

    Header: `HTTP <HTTP_CODE>`
    Body:
      ```json
      {
          "error": "ERROR_DESCRIPTION"
      }
      ```