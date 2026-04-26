# Apache Solr 9 Masterclass: Study Guide and Lab

This repository contains a structured study guide and a Vagrant-based lab environment to master Apache Solr 9, from basic indexing to advanced SolrCloud clustering and performance tuning.

## Lab Infrastructure

The lab uses Vagrant to provision two Ubuntu 22.04 virtual machines with OpenJDK 17 and Solr 9.6.0:

1.  **`solr-01` (192.168.56.30)**: Primary node for standalone and SolrCloud exercises.
2.  **`solr-02` (192.168.56.31)**: Secondary node for clustering and replication testing.

## Curriculum

### Phase 1: Fundamentals & Architecture
Understand the "Magic" of the Inverted Index and how Solr organizes data.
*   **The Inverted Index**: Unlike a database that scans every row, Solr uses an Inverted Index—similar to the index at the back of a textbook. It maps every unique word (token) to the list of documents where it appears.
    *   *Example*: A search for "Smartphone" instantly retrieves IDs `prod_01` and `prod_02` without reading the entire dataset.
*   **Core Concepts**: 
    *   **Documents**: The basic unit of data (like a row).
    *   **Fields**: Key-value pairs within a document (like a column).
    *   **Cores**: A single physical index on a machine (Standalone).
    *   **Collections**: A logical index spread across multiple cores/nodes (SolrCloud).
*   **Installation & CLI**: The `bin/solr` script is your primary tool for managing the service.
    *   *Example*: `sudo su - solr -c "/opt/solr/bin/solr create -c my_core"` creates a new index.
*   **The Solr Admin UI**: Accessible at `http://192.168.56.30:8983/solr`. Use the **Analysis** tool to see how your text is being broken down into tokens.

### Phase 2: Data Modeling & Schemas
Learn to define how your data is stored and analyzed for search.
*   **Managed Schema vs. Classic Schema**: Managed Schema (`managed-schema.xml`) is the default and is modified via the Schema API. Classic (`schema.xml`) is manually edited and requires a reload.
*   **Field Types & Analyzers**: Analyzers "clean" your text. A typical pipeline includes a **Tokenizer** (splitting text into words) and **Filters** (lowercase, removing "the/a/an", or stemming "running" to "run").
    *   *Example*: `text_en` field type turns "iPhone 15s" into tokens `iphon` and `15`.
*   **Dynamic Fields & Copy Fields**: 
    *   **Dynamic**: Catch fields by suffix/prefix (e.g., `*_s` for strings).
    *   **Copy**: Merge multiple fields into one for a "catch-all" search.
    *   *Example*: Copying `title` and `author` into `text` allows searching both simultaneously.
*   **The Update API**: Sending data to Solr.
    *   *Example*: `curl -X POST -H 'Content-Type: application/json' --data-binary '[{"id":"1","name":"Test"}]' '.../update?commit=true'`.

### Phase 3: Search Mastery
Master the query syntax to build powerful search experiences.
*   **Standard Query Parser**: Basic Lucene syntax. Use `q` for the main search and `fq` (Filter Query) for restrictive filters that don't affect scoring.
    *   *Example*: `q=camera&fq=price:[500 TO 1000]&fl=name,price` (Search for cameras between $500-$1000, returning only name and price).
*   **DisMax & eDisMax**: "Extended Disjunction Maximum". It's designed to be "user-proof"—it won't throw errors on special characters and allows boosting specific fields.
    *   *Example*: `q=iPhone&qf=name^5.0 description^1.0` (Find "iPhone", but give 5x more importance if it's in the name).
*   **Faceting & Filtering**: Calculating counts for categories.
    *   *Example*: `facet=true&facet.field=category` returns how many items are in "Smartphone", "Laptop", etc.
*   **Highlighting & Suggestions**: 
    *   **Highlighting**: Wraps search terms in `<em>` tags in results.
    *   **Suggestions**: "Did you mean?" functionality using the SpellCheck component.

### Phase 4: SolrCloud & Scalability
Transition from a single node to a distributed, high-availability cluster.
*   **SolrCloud Architecture**: Uses **ZooKeeper** to store configuration and track node health.
*   **Sharding & Replication**: 
    *   **Sharding**: Splitting a large index into smaller pieces (Shards) across nodes.
    *   **Replication**: Creating copies of shards for high availability.
*   **Collections API**: Managing the cluster state (Creating collections, splitting shards, adding replicas).
    *   *Example*: `bin/solr create_collection -c products -shards 2 -replicationFactor 2`.

### Phase 5: Production Readiness
Squeeze maximum performance and secure your cluster.
*   **JVM Tuning**: Solr is a Java app. Configuring the Heap (`Xms`/`Xmx`) and using the `G1GC` garbage collector is critical for stability.
*   **Security**: Enabling Basic Auth and RBAC (Role-Based Access Control) to prevent unauthorized access.
*   **Monitoring**: Tracking metrics like "Requests Per Second" and "Slow Queries" using the Prometheus Exporter.

## Sample PHP Application
To see Solr in action with a real UI, we've included a sample PHP app in the `php-app/` directory.

### 1. Start the App
Inside the `solr-01` VM:
```bash
cd /vagrant/php-app
php -S 0.0.0.0:8000
```

### 2. Access the UI
Open [http://192.168.56.30:8000](http://192.168.56.30:8000) in your host browser.

### 3. Features
*   **Live Search**: Search against the `tech_store` core.
*   **Dynamic Facets**: Filter by category on the sidebar.
*   **Hit Highlighting**: See exactly where your search terms match in the description.
*   **Update API**: Use the "Add Product" form to see how indexing new data works in real-time.

## Getting Started

1.  **Provision the lab**:
    ```bash
    vagrant up
    ```

2.  **Access the Solr Admin UI**:
    Open [http://192.168.56.30:8983/solr](http://192.168.56.30:8983/solr) in your browser.

3.  **SSH into the primary node**:
    ```bash
    vagrant ssh solr-01
    ```

4.  **Start the exercises**:
    Begin with `exercises/exercise_01_cores.md`.

## Exercises

*   **[Exercise 1: Core Management](exercises/exercise_01_cores.md)**: Create your first core, understand the `core.properties` and directory structure.
*   **[Exercise 2: The E-Commerce Index](exercises/exercise_02_indexing.md)**: Index a dataset of tech products and learn about field types.
*   **[Exercise 3: Search & Facets](exercises/exercise_03_search.md)**: Build a product search page with categories and price range filters.
*   **[Exercise 4: Scaling with SolrCloud](exercises/exercise_04_clustering.md)**: Convert your nodes into a cluster and create a shared collection.
