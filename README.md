## Better Moderation Plugin <img src="https://github.com/NopeNotDark/BetterModeration/blob/main/logo.png" height="128" width="128" align="left"></img>
[![](https://poggit.pmmp.io/shield.state/BetterModeration)](https://poggit.pmmp.io/p/BetterModeration)
<a href="https://poggit.pmmp.io/p/BetterModeration"><img src="https://poggit.pmmp.io/shield.state/BetterModeration"></a> [![](https://poggit.pmmp.io/shield.api/Spyglass-Sniper)](https://poggit.pmmp.io/p/Spyglass-Sniper)
<a href="https://poggit.pmmp.io/p/BetterModeration"></a>

## Commands 
- `/ban <player> <reason> <time>` - Bans a player from the server.
- `/blacklist <player>` - Blacklists a player from the server.
- `/mute <player> <reason> <time>` - Mutes a player from the server.
- `/report <player> <reason>` - Sends a message to everyone who has permissions.
- `/history <player>` - Allows staff to check the past history of a player.
- `/pardon <player> <type>` - Unmute, unban, unblacklist a player from the server.

 __Available Variables for Commands__
- `<time>`: 1d, 1m, 1s, etc
- `<type>`: Ban, Mute, Blacklist

## Configuration
The configuration file for BetterModeration plugin is as follows:

```yaml
# BetterModeration Configuration File

database:
  # The database type. "sqlite" and "mysql" are supported.
  type: sqlite

  # Edit these settings only if you choose "sqlite".
  sqlite:
    # The file name of the database in the plugin data folder.
    # You can also put an absolute path here.
    file: data.sqlite
  # Edit these settings only if you choose "mysql".
  mysql:
    host: 127.0.0.1
    # Avoid using the "root" user for security reasons.
    username: root
    password: ""
    schema: your_schema
  # The maximum number of simultaneous SQL queries
  # Recommended: 1 for sqlite, 2 for MySQL. You may want to further increase this value if your MySQL connection is very slow.
  worker-limit: 1

# The Discord webhook to use for logging reports and bans.
discord-webhook: "https://discordapp.com/api/webhooks/1234567890/abcdefghijklmnopqrstuvwxyz"
```

## How to Contribute?
We welcome pull requests from anyone interested in improving this project. Before making major changes, please open an issue to discuss your proposed changes and gather feedback from the project maintainers.
When submitting a pull request, please ensure that you have updated the relevant tests to reflect your changes and ensure they pass successfully.
Other than that, Thank you for your contribution!

## Credits
- [PocketAI](https://thedarkproject.net/pocketai) - Code generating AI revolving around Pocketmine-MP
