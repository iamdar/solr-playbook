# Exercise 1: Core Management

In this exercise, you will create your first Solr core and explore the underlying file structure.

## Scenario
You are setting up a search engine for a library. You need a core named `library_catalog` to store book details.

## Tasks

1.  **SSH into the primary node**:
    ```bash
    vagrant ssh solr-01
    ```

2.  **Create the core**:
    Use the `solr create` command.
    ```bash
    # Run as the solr user for correct permissions
    sudo su - solr -c "/opt/solr/bin/solr create -c library_catalog"
    ```

3.  **Verify via Admin UI**:
    Open [http://192.168.56.30:8983/solr](http://192.168.56.30:8983/solr) and check the "Core Selector" dropdown on the left.

4.  **Explore the file system**:
    Navigate to the Solr data directory:
    ```bash
    cd /var/solr/data/library_catalog
    ls -R
    ```
    *   **Question**: What is inside the `conf` directory?
    *   **Question**: Where are the actual index files stored?

5.  **Check Core Status**:
    ```bash
    /opt/solr/bin/solr status
    ```

## Knowledge Check
*   What is the difference between a **Core** and a **Collection**?
*   What happens if you delete the `core.properties` file?
