# Apache Solr 9 Masterclass: Study Guide and Lab

This repository contains a structured study guide and a Vagrant-based lab environment to master Apache Solr 9, from basic indexing to advanced SolrCloud clustering and performance tuning.

## Lab Infrastructure

The lab uses Vagrant to provision two Ubuntu 22.04 virtual machines with OpenJDK 17 and Solr 9.6.0:

1.  **`solr-01` (192.168.56.30)**: Primary node for standalone and SolrCloud exercises.
2.  **`solr-02` (192.168.56.31)**: Secondary node for clustering and replication testing.

## Curriculum

### Phase 1: Fundamentals & Architecture
Understand the "Magic" of the Inverted Index and how Solr organizes data.
*   **The Inverted Index**: How Lucene allows near-instant searching across millions of documents.
*   **Core Concepts**: Documents, Fields, Cores (Standalone), and Collections (SolrCloud).
*   **Installation & CLI**: Navigating the `/opt/solr` directory and using the `bin/solr` script to manage services and cores.
*   **The Solr Admin UI**: Overview of the dashboard, Query tool, and Schema Browser.

### Phase 2: Data Modeling & Schemas
Learn to define how your data is stored and analyzed for search.
*   **Managed Schema vs. Classic Schema**: Understanding why Managed Schema is the modern standard.
*   **Field Types & Analyzers**: Configuring Tokenizers and Filters (e.g., `LowercaseFilter`, `StopFilter`, `StemmerOverride`).
*   **Dynamic Fields & Copy Fields**: Strategies for flexible indexing and multi-field searching.
*   **The Update API**: Indexing data via JSON, XML, and CSV. Handling atomic updates and optimistic concurrency.

### Phase 3: Search Mastery
Master the query syntax to build powerful search experiences.
*   **Standard Query Parser**: Using `q`, `fq` (Filter Query), `fl` (Field List), and `rows/start`.
*   **DisMax & eDisMax**: Building user-friendly search parsers that handle typos and field boosting.
*   **Faceting & Filtering**: Categorizing search results (Field facets, Range facets, Query facets).
*   **Highlighting & Suggestions**: Improving UX with "Did you mean?" (Spellcheck) and hit highlighting.
*   **Function Queries**: Sorting and boosting based on mathematical functions (e.g., "recency boosting").

### Phase 4: SolrCloud & Scalability
Transition from a single node to a distributed, high-availability cluster.
*   **SolrCloud Architecture**: Role of Apache ZooKeeper in managing cluster state.
*   **Sharding & Replication**: How Solr splits data across nodes and ensures data safety.
*   **Collections API**: Creating and managing distributed collections.
*   **Leader Election**: What happens when a node fails.
*   **Routing**: Understanding how Solr knows which shard holds which document.

### Phase 5: Production Readiness
Squeeze maximum performance and secure your cluster.
*   **JVM Tuning**: Optimizing Garbage Collection (`G1GC`) and Heap memory for Solr.
*   **Security**: Enabling Basic Authentication and Role-Based Access Control (RBAC).
*   **Monitoring**: Using the Prometheus Exporter and Grafana for observability.
*   **Performance Troubleshooting**: Identifying "Slow Queries" and using the `debugQuery=true` parameter.

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
