Feature:
    In order to use mods
    As a user
    I need to be able to retrieve them

    Scenario: A user can get the list of mods
        Given I am logged in as "Gigachad"
        When I send a "GET" request to "/api/mods"
        Then the JSON node "data" should have 6 elements
        And the JSON nodes should contain:
        | data[0].id           | 1                                      |
        | data[0].text         | # to maximum Life                      |
        | data[0].palceholders | 1                                      |
        | data[1].id           | 2                                      |
        | data[1].text         | #% to Cold Resistance                  |
        | data[1].palceholders | 1                                      |
        | data[2].id           | 3                                      |
        | data[2].text         | # to Dexterity                         |
        | data[2].palceholders | 1                                      |
        | data[3].id           | 4                                      |
        | data[3].text         | #% increased Physical Damage           |
        | data[3].palceholders | 1                                      |
        | data[4].id           | 5                                      |
        | data[4].text         | Adds # to # Physical Damage to Attacks |
        | data[4].palceholders | 2                                      |
        | data[5].id           | 6                                      |
        | data[5].text         | #% increased Attack Speed              |
        | data[5].palceholders | 1                                      |

    Scenario: You can refresh the list of mods
        Given I execute the command "du:mo:re"
        And I am logged in as "Gigachad"
        When I send a "GET" request to "/api/mods"
        Then the JSON node "data" should have 4 elements
        And the JSON nodes should contain:
        | data[0].id | 1 |
        | data[1].id | 3 |
        | data[2].id | 4 |
        | data[3].id | 7 |
