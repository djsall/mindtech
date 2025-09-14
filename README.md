## About the solution

- Because of time constraints, I did not provide a login solution for the restaurants, that part of the API is public.
- Because of the missing authentication, I used route model binding to select the appropriate restaurant to perform the action on
- I could have omitted the restaurant part from the URL's pointing to specific orders, but I did choose not to do so for a more consistent DX 

## Running the application
- use `make up` to install the application
- if running for the first time, use `make setup` to set up laravel
- use `make test` to run the tests
- use `make down` to tear down the application

## Accessing the open-api documentation
- http://localhost:8080/docs/api
- on the `overview` tab in the top right corner, the OpenAPI document can be exported

