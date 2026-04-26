# Exercise 3: Search & Facets

Now that you have data, it's time to build complex queries like a pro.

## Scenario
The "Tech Store" manager wants to filter products by category and see a count of how many items are in each category (Faceting).

## Tasks

1.  **Basic Search**:
    Find all "Apple" products.
    ```bash
    curl 'http://localhost:8983/solr/tech_store/select?q=name:Apple&indent=on'
    ```

2.  **Filter Query (`fq`)**:
    Find smartphones but *without* affecting the search score.
    ```bash
    curl 'http://localhost:8983/solr/tech_store/select?q=*:*&fq=category:Smartphone&indent=on'
    ```

3.  **Faceting**:
    Get a breakdown of how many products are in each category.
    ```bash
    curl 'http://localhost:8983/solr/tech_store/select?q=*:*&facet=true&facet.field=category&rows=0&indent=on'
    ```
    *   **Question**: Why did we set `rows=0`?

4.  **Range Query**:
    Find products between $500 and $1000.
    ```bash
    curl 'http://localhost:8983/solr/tech_store/select?q=price:[500 TO 1000]&indent=on'
    ```

5.  **Boosting with eDisMax**:
    Try to search for "iPhone" and boost the `name` field over the `description`.
    ```bash
    curl 'http://localhost:8983/solr/tech_store/select?defType=edismax&q=iPhone&qf=name^2.0 description&indent=on'
    ```

## Knowledge Check
*   Why is `fq` better than putting everything in `q`? (Hint: Caching).
*   What is the difference between a `facet` and a `filter`?
