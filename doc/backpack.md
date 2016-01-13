Make a POST request to the following URLs:

    http://arisgames.org/server/json.php/v2.backpack.getGroupUsers
    http://arisgames.org/server/json.php/v2.backpack.getUserBackpack

The POST body should be a JSON object, MIME type application/json.

## `v2.backpack.getGroupUsers`

Takes:

    {"group_name": <string>, "game_ids": <array of numbers>, "relogin": <bool>,
      "auth": {"user_id": <number>, "key": <string>}
    }

The `auth` is the authentication information for an owner of all the games in `game_ids`.
This is only needed if `relogin` is true.

Returns:

    {"returnCode": 0, "returnCodeDescription": null, "data": [
      <for each player in the group>
      "player_id": <number>,
      "player_pic": <string, a URL>,
      "player_thumb": <string, a URL>,
      "in_game_name": <string>,
      <the following key is present only if relogin was true>
      "read_write_key": <string>
    ]}

## `v2.backpack.getUserBackpack`

Takes:

    {"player_id": <number>, "game_ids": <array of numbers>}

Returns:

    {"returnCode": 0, "returnCodeDescription": null, "data": {
      "player_id": <number>,
      "player_pic": <string, a URL>,
      "player_thumb": <string, a URL>,
      "in_game_name": <string>,
      "games": {
        <for each game's game_id>
        <game_id>: {
          "inventory": [
            <for each item/attribute the player has at least one of>
            {"object_id": <number>, "qty": <number>, "name": <string>,
              "type": <one of "NORMAL", "HIDDEN", "ATTRIB", or "URL">,
              "tags": <array of numbers>
            }
          ],
          "quests": [
            <for each quest the player has completed>
            <number, a quest ID>
          ]
        }
      }
    }}

## `v2.users.logIn`

This is how you get the `auth` information for authenticated API requests.

Takes:

    {"user_name": <string>, "password": <string>, "permission": "read_write"}

Returns:

    {"returnCode": 0, "returnCodeDescription": null, "data": {
      "user_id": <number>,
      "read_wrte_key": <string>
    }}