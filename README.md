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

# The database type to use. Valid options are "sqlite" and "mysql"
database: "sqlite"

# The Discord webhook to use for logging reports and bans.
discord-webhook: "https://discordapp.com/api/webhooks/1234567890/abcdefghijklmnopqrstuvwxyz"

# The database connection information. This is only used if the database type is set to "mysql"
host: "localhost"
port: 3306
database: "database"
username: "username"
password: "password"
```

## How to Contribute?
We welcome pull requests from anyone interested in improving this project. Before making major changes, please open an issue to discuss your proposed changes and gather feedback from the project maintainers.
When submitting a pull request, please ensure that you have updated the relevant tests to reflect your changes and ensure they pass successfully.
Other than that, Thank you for your contribution!

## Credits
- [PocketAI](https://thedarkproject.net/pocketai) - Code generating AI revolving around Pocketmine-MP
