## Setup

- navigato to the project location and run `composer install`
- run `symfony serve` command to run the application locally
- you can use postman to test the requests, I added the requests ready to import to postman in the file `beerDispenser.postman_collection.json`
- configure the `.env` file with your `DATABASE_URL`, you can create a `.env.local` file with the url
- edit the `.env.test` file with yout `DATABASE_URL`
- create a schema named `beer` and `beer_test` with default collation and charset
- run `php bin/console doctrine:schema:create --env=test`
- run `php bin/console doctrine:migrations:migrate`
- to run tests run `./vendor/bin/phpunit`

If you're working in windows consider to change the `/` to `\` in the commands.

## Endpoints

### Create Beer Dispenser

- **URL:** `/beerDispensers`
- **Method:** POST
- **Request Body:** JSON object
  - `flow_volume` (required, float): The flow volume of the dispenser.
  - `price` (optional, float): The price per liter of the beer served by the dispenser if this is null or 0 the dispenser will remain inactive.

Create a new beer dispenser with the specified flow volume and price (if provided).

### List Beer Dispensers

- **URL:** `/beerDispensers`
- **Method:** GET

Retrieves a list of all beer dispensers wit all it's information

### Get Beer Dispenser

- **URL:** `/beerDispensers/{beerDispenserId}`
- **Method:** GET
- **Parameters:**
  - `beerDispenserId` (required, integer): The ID of the beer dispenser.

Retrieves the details of a specific beer dispenser identified by its `id`.

### Update Beer Dispenser

- **URL:** `/beerDispensers/{beerDispenserId}`
- **Method:** PUT
- **Request Body:** JSON object
  - `flow_volume` (optional, float): The new flow volume of the dispenser.
  - `price` (optional, float): The new price of the beer served by the dispenser, if it´s null 0 or negative the dispenser will remain inactive
- **Parameters:**
  - `beerDispenserId` (required, integer): The ID of the beer dispenser.

Updates the flow volume and/or price of a specific beer dispenser identified by its `id`.

### Open Beer Tap

- **URL:** `/beerDispensers/{beerDispenserId}/tap`
- **Method:** PUT
- **Parameters:**
  - `beerDispenserId` (required, integer): The ID of the beer dispenser.

Opens the tap of a specific beer dispenser identified by its `id`.

### Close Beer Tap

- **URL:** `/beerDispensers/{beerDispenserId}/tap`
- **Method:** DELETE
- **Parameters:**
  - `beerDispenserId` (required, integer): The ID of the beer dispenser.

Closes the tap of a specific beer dispenser identified by its `id`. It also calculates the total money collected and the duration the dispenser was open, and records the revenue.


### All Revenues

- **URL:** `/revenues`
- **Method:** GET

Retrieves revenue information for all dispensers.

### Dispenser Revenues

- **URL:** `/revenues/{dispenserId}`
- **Method:** GET
- **Parameters:**
  - `dispenserId` (required, integer): The ID of the dispenser.

Retrieves revenue information for a specific dispenser identified by its `id`.


## Error Handling

The Beer Dispenser Controller handles various error scenarios and returns appropriate JSON responses with the following structure:

```json
{
  "error": "Error message"
}
```

The HTTP status code of the response indicates the nature of the error.
