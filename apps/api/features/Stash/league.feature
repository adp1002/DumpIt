Feature:
    In order to use mods
    As a user
    I need to be able to retrieve them

    Scenario: You can refresh the list of leagues
        Given I execute the command "du:le:re"
        And I am logged in as "Gigachad"
        When I send a "GET" request to "/api/leagues"
        Then the JSON node "data" should have 2 elements
        And the JSON nodes should contain:
        | data[0].id | ssf-boatleague    |
        | data[1].id | ssf-hc-boatleague |

