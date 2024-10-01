# Test Task

## Endpoints

### 1. Create Organization
* **Endpoint:** `/organizations`
* **Method:** `POST`
* **Description:** Creates a new organization.

**Request Body**

```
{
  "org_name": "Paradise Island",
  "daughters": 
  [
    {
      "org_name": "Banana tree",
      "daughters":
      [
        {
          "org_name": "Yellow Banana"
        },
        {
          "org_name": "Brown Banana"
        },
        {
          "org_name": "Black Banana"
        }
      ]
    },
    {
      "org_name": "Big banana tree",
      "daughters": 
      [
        {
          "org_name": "Yellow Banana"
        },
        {
          "org_name": "Brown Banana"
        },
        {
          "org_name": "Green Banana"
        },
        {
          "org_name": "Black Banana",
          "daughters": [
            {
              "org_name": "Phoneutria Spider"
            }
          ]
        }
      ]
    }
  ]
}

```

### **Response**

* **Success (201 Created):**
```
  {
    "message": "Organization and relations stored successfully"
  }
```

* **Error (400 Bad Request)**

```
    {
    "error": "org_name is required."
    }
```

### 2. Get Organization Relations

* **Endpoint:** `/organizations/{name}`
* **Method:** `GET`
* **Description:** Retrieve parents, daughters and sisters of organization.

#### Path Parameters
* `name`: Name of organization (spaces should be replaced with underscores => `_`)

#### Query Parameters
* `page`: (Optional) The page number for pagination.
* `per_page`: (Optional) The number of results per page (default is 100).

### **Response**

* **Success (200 OK):**

```
{
    "data": [
        {
            "relationship_type": "parent",
            "org_name": "Banana tree"
        },
        {
            "relationship_type": "parent",
            "org_name": "Big banana tree"
        },
        {
            "relationship_type": "sister",
            "org_name": "Black Banana"
        },
        {
            "relationship_type": "sister",
            "org_name": "Black Banana"
        },
        {
            "relationship_type": "sister",
            "org_name": "Brown Banana"
        },
        {
            "relationship_type": "sister",
            "org_name": "Brown Banana"
        },
        {
            "relationship_type": "daughter",
            "org_name": "Giant Yellow Banana"
        },
        {
            "relationship_type": "sister",
            "org_name": "Green Banana"
        },
        {
            "relationship_type": "sister",
            "org_name": "Green Banana"
        },
        {
            "relationship_type": "sister",
            "org_name": "Pink Banana"
        },
        {
            "relationship_type": "sister",
            "org_name": "Red Banana"
        }
    ]
}

```

* **Error (404 Not Found):**
```
{"error":"Organization not found."}
```
