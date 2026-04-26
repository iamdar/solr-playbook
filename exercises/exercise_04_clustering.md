# Exercise 4: Scaling with SolrCloud

In this final exercise, you will step into the world of distributed search.

## Concepts
*   **Shard**: A slice of the total index.
*   **Replica**: A copy of a shard for high availability.
*   **ZooKeeper**: The "brain" that tracks where everything is.

## Tasks

1.  **Restart Solr in Cloud Mode**:
    By default, our lab installed Solr in "standalone" mode. Let's restart both nodes in Cloud mode.
    On **both** `solr-01` and `solr-02`:
    ```bash
    # Stop the service
    sudo systemctl stop solr
    
    # Start manually in cloud mode (simulated for this lab)
    # In a real production environment, you'd use an external ZooKeeper quorum.
    # Here, we'll use the embedded ZK on solr-01.
    ```
    On `solr-01`:
    ```bash
    sudo su - solr -c "/opt/solr/bin/solr start -cloud -p 8983"
    ```
    On `solr-02`:
    ```bash
    sudo su - solr -c "/opt/solr/bin/solr start -cloud -p 8983 -z 192.168.56.30:9983"
    ```

2.  **Create a Distributed Collection**:
    Create a collection with 2 shards and 2 replicas.
    ```bash
    sudo su - solr -c "/opt/solr/bin/solr create -c global_store -shards 2 -replicationFactor 2"
    ```

3.  **Inspect the Topology**:
    Go to Admin UI -> **Cloud** -> **Graph**.
    *   You should see how the `global_store` collection is split across both nodes.

4.  **Test High Availability**:
    *   Index some data into `global_store`.
    *   Stop the `solr` process on `solr-02`.
    *   Query `solr-01`. Is the data still there?

## Knowledge Check
*   What happens if the ZooKeeper node goes down?
*   How does Solr decide which shard a document belongs to? (Hint: CompositeId routing).
