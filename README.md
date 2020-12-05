Show First Post Only to Guest
===============

phpBB Show First Post Only to Guest extension

Allows the admin to set an option that will only allow unregistered users/guests to view the first post of any topic. The rest of the posts in the topic will ask them to login or register.  Options are set by the admin on a per forum basis.

![Shields.IO](https://img.shields.io/badge/Shields-IO-8000FF.svg?style=flat-square) [![GitHub release](https://img.shields.io/github/release/rmcgirr83/sfpo.svg?style=flat-square) ![license](https://img.shields.io/github/license/rmcgirr83/sfpo.svg?style=flat-square)](https://github.com/rmcgirr83/sfpo)

![Screenshot](viewtopic.jpg)

## Installation

### 1. clone
Clone (or download and move) the repository into the folder ext/rmcgirr83/sfpo:

```
cd phpBB3
git clone https://github.com/rmcgirr83/sfpo.git ext/rmcgirr83/sfpo/
```

This extension has a dependency on another github repo.  Please make sure to run composer.phar install or the posts will not be truncated.

### 2. activate
Go to admin panel -> tab customise -> Manage extensions -> enable Show First Post Only to Guest


## Update instructions:
1. Go to your phpBB-Board > Admin Control Panel > Customise > Manage extensions > Show First Post Only to Guest: disable
2. Delete all files of the extension from ext/rmcgirr83/sfpo
3. Upload all the new files to the same location
4. Go to your phpBB-Board > Admin Control Panel > Customise > Manage extensions > Show First Post Only to Guest: enable
