Feature:
    In order to use filters
    As a user
    I need to be able to create, edit, delete and apply them

    Scenario: A user can get his filters
        Given I am logged in as "Gigachad"
        When I send a "GET" request to "/api/filters"
        Then the JSON node "data" should have 2 elements
        And the JSON nodes should contain:
        | data[0].id   | dc3f0458-cb7f-4fe7-8991-8e9a45ae9d42 |
        | data[0].name | Life filter                          |
        | data[1].id   | 6f8d4670-03b9-499e-bf23-05f6123eea2e |
        | data[1].name | Phys weapon filter                   |

    Scenario: A user can get a filter he has access to
        Given I am logged in as "Gigachad"
        When I send a "GET" request to "/api/filters/dc3f0458-cb7f-4fe7-8991-8e9a45ae9d42?include=mods"
        Then the JSON node "data.mods" should have 1 element
        And the JSON nodes should contain:
        | data.id                | dc3f0458-cb7f-4fe7-8991-8e9a45ae9d42 |
        | data.name              | Life filter                          |
        | data.mods[0].mod       | # to maximum Life                    |
        | data.mods[0].values[0] | 82                                   |
        | data.mods[0].condition | gte                                  |

    Scenario: A user can't get a filter he has no access to
        Given I am logged in as "Gigachad"
        When I send a "GET" request to "/api/filters/bf6a7fe2-2d47-4216-905b-b5f2361bde3d"
        Then the response status code should be 404
    
    Scenario: A user can create a new filter
        Given I am logged in as "Gigachad"
        When I send a "POST" request to "/api/filters" with body:
        """
        {"name": "Dex filter", "mods": [{"id": "3", "values": [13], "condition": "gt"}]}
        """
        And I send a "GET" request to "/api/filters"
        Then the JSON node "data" should have 3 elements
        And the JSON nodes should contain:
        | data[0].name | Dex filter |

    Scenario: A user can edit a filter he has access to
        Given I am logged in as "Gigachad"
        When I send a "PATCH" request to "/api/filters/dc3f0458-cb7f-4fe7-8991-8e9a45ae9d42" with body:
        """
        {"name": "Dex filter", "mods": [{"id": "3", "values": [13], "condition": "gt"}, {"id": "2", "values": [21], "condition": "eq"}]}
        """
        And I send a "GET" request to "/api/filters/dc3f0458-cb7f-4fe7-8991-8e9a45ae9d42?include=mods"
        Then the JSON node "data.mods" should have 2 elements
        And the JSON nodes should contain:
        | data.name              | Dex filter            |
        | data.mods[0].mod       | # to Dexterity        |
        | data.mods[0].values[0] | 13                    |
        | data.mods[0].condition | gt                    |
        | data.mods[1].mod       | #% to Cold Resistance |
        | data.mods[1].values[0] | 21                    |
        | data.mods[1].condition | eq                    |
    
    Scenario: A user can't edit a filter he has no access to
        Given I am logged in as "Gigachad"
        When I send a "PATCH" request to "/api/filters/bf6a7fe2-2d47-4216-905b-b5f2361bde3d" with body:
        """
        {"name": "Dex filter", "mods": [{"modId": "3", "values": [13], "condition": "gt"}, {"modId": "2", "values": [21], "condition": "eq"}]}
        """
        Then the response status code should be 404

    Scenario: A user can delete a filter he has access to
        Given I am logged in as "Gigachad"
        When I send a "DELETE" request to "/api/filters/dc3f0458-cb7f-4fe7-8991-8e9a45ae9d42"
        And I send a "GET" request to "/api/filters"
        Then the JSON node "data" should have 1 element

    Scenario: A user can delete a filter he has no access to
        Given I am logged in as "Gigachad"
        When I send a "DELETE" request to "/api/filters/bf6a7fe2-2d47-4216-905b-b5f2361bde3d"
        Then the response status code should be 404
