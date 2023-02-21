Feature:
    In order to use stash tabs
    As a user
    I need to be able to retrieve them

    Scenario: It sees the test Route
        When I send a "GET" request to "/api/tabs/1"
        Then the JSON node "data[0]" should be equal to "tab 1"