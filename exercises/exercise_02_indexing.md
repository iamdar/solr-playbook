# Exercise 2: The E-Commerce Index

In this exercise, you will index your first set of documents and explore how Solr handles different data types.

## Scenario
You are building a "Tech Store" search. You have a list of products in JSON format that need to be searchable.

## Tasks

1.  **Create the `tech_store` core**:
    ```bash
    sudo su - solr -c "/opt/solr/bin/solr create -c tech_store"
    ```

2.  **Prepare the data**:
    Create a file named `products.json` on `solr-01`:
    ```json
    [
      {
        "id": "prod_01",
        "name": "Apple iPhone 15",
        "category": "Smartphone",
        "price": 999.99,
        "inStock": true,
        "description": "The latest iPhone with A16 Bionic chip."
      },
      {
        "id": "prod_02",
        "name": "Samsung Galaxy S23",
        "category": "Smartphone",
        "price": 849.50,
        "inStock": true,
        "description": "Flagship Android phone with amazing camera."
      },
      {
        "id": "prod_03",
        "name": "MacBook Pro 14",
        "category": "Laptop",
        "price": 1999.00,
        "inStock": false,
        "description": "Powerful laptop for professionals."
      }
    ]
    ```

3.  **Index the data**:
    Use `curl` to send the JSON to Solr:
    ```bash
    curl -X POST -H 'Content-Type: application/json' \
      'http://localhost:8983/solr/tech_store/update?commit=true' \
      --data-binary @products.json
    ```

4.  **Verify the index**:
    Run a match-all query:
    ```bash
    curl 'http://localhost:8983/solr/tech_store/select?q=*:*&indent=on'
    ```

5.  **Explore "Schemaless" mode**:
    Solr 9 defaults to `ManagedIndexSchema`. 
    *   Go to the Admin UI -> Schema Browser.
    *   Find the `price` field. What is its type? (`pdouble`, `plong`?)
    *   How did Solr know what type to use? (Hint: Check `solrconfig.xml` for `add-unknown-fields-to-the-schema`).

## Knowledge Check
*   What does `commit=true` do?
*   What happens if you index a document with the same `id` twice?
