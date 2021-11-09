# Show First Post Only To Guest for phpBB3

Allows the admin to set an option that will only allow unregistered users/guests to view the first post of any topic. The rest of the posts in the topic will ask them to login or register.  Options are set by the admin on a per forum basis. Can choose to allow or not registered Bots to view the posts.

[![Build Status](https://github.com/rmcgirr83/sfpo/workflows/Tests/badge.svg)](https://github.com/rmcgirr83/sfpo/actions)

[![License](https://img.shields.io/github/license/rmcgirr83/sfpo.svg?style=flat-square)](https://github.com/rmcgirr83/sfpo)

![Screenshot](viewtopic.jpg)

## Installation

### 1. clone
Clone (or download and move) the repository into the folder ext/rmcgirr83/sfpo:

```
cd phpBB3
git clone https://github.com/rmcgirr83/sfpo.git ext/rmcgirr83/sfpo/
```
### 2. activate
Go to admin panel -> tab customise -> Manage extensions -> enable Show First Post Only to Guest


## Update instructions:
1. Go to your phpBB-Board > Admin Control Panel > Customise > Manage extensions > Show First Post Only to Guest: disable
2. Delete all files of the extension from ext/rmcgirr83/sfpo
3. Upload all the new files to the same location
4. Go to your phpBB-Board > Admin Control Panel > Customise > Manage extensions > Show First Post Only to Guest: enable
