# Solr 9 Cheat Sheet

## Service Management
| Command | Description |
| :--- | :--- |
| `sudo systemctl status solr` | Check Solr service status |
| `sudo systemctl restart solr` | Restart Solr service |
| `tail -f /var/solr/logs/solr.log` | View live Solr logs |
| `/opt/solr/bin/solr --version` | Check Solr version |

## CLI: Core & Collection Management
| Command | Description |
| :--- | :--- |
| `solr create -c <name>` | Create a new core (standalone) or collection (cloud) |
| `solr delete -c <name>` | Delete a core or collection |
| `solr status` | View status of all running Solr instances |
| `solr healthcheck -c <name>` | (Cloud) Check health of a collection |

## Common API Endpoints
Base URL: `http://localhost:8983/solr`

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/<core>/select` | GET/POST | Query data |
| `/<core>/update` | POST | Index, update, or delete documents |
| `/<core>/schema` | GET/POST | Read or modify the schema |
| `/<core>/admin/ping` | GET | Health check for a specific core |
| `/admin/collections` | GET | Manage collections (Create, Shard, Alias) |

## Lucene Query Syntax
| Syntax | Example | Description |
| :--- | :--- | :--- |
| `field:value` | `name:iphone` | Basic field search |
| `*` | `*:*` | Match all documents |
| `OR` / `AND` | `iphone OR ipad` | Boolean operators (Case-sensitive) |
| `+` / `-` | `+iphone -refurbished` | Must include iphone, must not include refurbished |
| `[X TO Y]` | `price:[100 TO 500]` | Range query (Inclusive) |
| `{X TO Y}` | `price:{100 TO 500}` | Range query (Exclusive) |
| `~` | `jakarta~1` | Fuzzy search (Damerau-Levenshtein distance) |
| `^` | `name:iphone^2.0` | Boosting a term's importance |

## Important Query Parameters
*   `q`: The main query (e.g., `name:solr`).
*   `fq`: Filter query (cached, does not affect score).
*   `fl`: Field list (comma-separated fields to return).
*   `df`: Default field to search if none specified.
*   `wt`: Response writer (default: `json`).
*   `indent=on`: Pretty-print the response.

## Indexing via CURL
**Add a document:**
```bash
curl -X POST -H 'Content-Type: application/json' \
  'http://localhost:8983/solr/my_core/update?commit=true' \
  --data-binary '[{"id":"1", "name":"Item A"}]'
```

**Delete all documents:**
```bash
curl -X POST -H 'Content-Type: application/json' \
  'http://localhost:8983/solr/my_core/update?commit=true' \
  --data-binary '{"delete":{"query":"*:*"}}'
```
