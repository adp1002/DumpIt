Feature:
    In order to use stash tabs
    As a user
    I need to be able to retrieve and refresh them

    Scenario: A user can see his tabs
        Given I am logged in as "Gigachad"
        When I send a "GET" request to "/api/tabs"
        Then the JSON node "data" should have 2 elements

    Scenario: A user can get a tab he has access to
        Given I am logged in as "Gigachad"
        When I send a "GET" request to "/api/tabs/1"
        Then the JSON nodes should contain:
        | data.id     | 1              |
        | data.name   | DumpTab        |
        | data.league | ssf_boatleague |

    Scenario: A user can access the items of a tab
        Given I am logged in as "Gigachad"
        When I send a "GET" request to "/api/tabs/1?include=items"
        Then the JSON node "data.items" should have 1 element
        And the JSON node "data.items[0].mods" should have 3 elements
        And the JSON nodes should contain:
        | data.id                         | 1                     |
        | data.name                       | DumpTab               |
        | data.league                     | ssf_boatleague        |
        | data.items[0].name              | Amulet                |
        | data.items[0].mods[0].mod       | #% to Cold Resistance |
        | data.items[0].mods[0].values[0] | 30                    |
        | data.items[0].mods[1].mod       | # to Dexterity        |
        | data.items[0].mods[1].values[0] | 12                    |
        | data.items[0].mods[2].mod       | # to maximum Life     |
        | data.items[0].mods[2].values[0] | 82                    |


    Scenario: A user can't get a tab he has no access to
        Given I am logged in as "Gigachad"
        When I send a "GET" request to "/api/tabs/3"
        Then the response status code should be 404

    Scenario: A user can refresh the tabs
        Given I am logged in as "Gigachad"
        When I send a "POST" request to "/api/tabs/refresh" with body:
        """
        {"leagueId": "ssf_boatleague"}
        """
        And I send a "GET" request to "/api/tabs"
        Then the JSON node "data" should have 3 elements
        And the JSON nodes should contain:
        | data[0].id | 1 |
        | data[1].id | 2 |
        | data[2].id | 4 |

    Scenario: A user can refresh a tab
        Given I am logged in as "Gigachad"
        When I send a "PUT" request to "/api/tabs/1/refresh"
        And I send a "GET" request to "/api/tabs/1?include=items"
        Then the JSON node "data.items" should have 3 elements
        And the JSON nodes should contain:
        | data.index         | 1                |
        | data.name          | Changed name tab |
        | data.index         | 4                |
        | data.items[0].name | Mind Charm       |

    Scenario: A user can apply a filter to a tab
        Given I am logged in as "Gigachad"
        When I send a "POST" request to "/api/tabs/1/filter" with body:
        """
        {"filters": ["dc3f0458-cb7f-4fe7-8991-8e9a45ae9d42"]}
        """
        Then the JSON node "data" should have 1 element
        And the JSON node "data[0].mods" should have 3 elements
        And the JSON nodes should contain:
        | data[0].name | Amulet |

    Scenario: A user can't apply a filter he has no access to to a tab
        Given I am logged in as "Gigachad"
        When I send a "POST" request to "/api/tabs/1/filter" with body:
        """
        {"filters": ["dc3f0458-cb7f-4fe7-8991-8e9a45ae9d4a"]}
        """
        Then the response status code should be 404