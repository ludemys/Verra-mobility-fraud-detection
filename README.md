# Word List

## Summary

This is a project that serves an API REST with an endpoint that finds fraudulents records in a set of orders by the following rules:
- Two orders have the same email address and deal id, but different credit card information, regardless of street address.
- Two orders have the same Address/City/State/Zip and deal id, but different credit card information, regardless of email address.


## Execution

To execute, first run composer install.
```bash
composer install
```

Then start the server with the symfony console on the project root.
```bash
symfony serve
```

Finally you can request a POST to the endpoint with the orders. 
```
POST /api/fraud-detection HTTP/1.1
Host: 127.0.0.1:8000
Content-Type: application/json
Cookie: main_deauth_profile_token=ea19c3
Content-Length: 123965

{
  "orders": [
    {
      "orderId": 316,
      "dealId": 2007,
      "email": "sara1007@sample.co",
      "streetAddress": "420 Sesame St.",
      "city": "Los Angeles",
      "state": "CA",
      "zipCode": "51440",
      "creditCard": "5100000000000015"
    },
    ...
  ]
}
```

## Testing

To run tests, run the command 'vendor/bin/phpunit' with the symfony console on the project root.
```bash
vendor/bin/phpunit
```
