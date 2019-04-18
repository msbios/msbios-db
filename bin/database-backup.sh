#!/usr/bin/env bash

echo "${RED}"
echo "[!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!![`date +%F-%H:%M:%S`]!!!!!!!!!!!!!!!!!!!!!!!!!!]"
echo "This is script is deprecated, please use msbios-backup.sh"
echo "[!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!]"
echo "${ColorOff}"

# Start backup
echo "[--------------------------------[`date +%F-%H:%M:%S`]--------------------------]"
echo "     ____.         .___      .__    .__            _____  .__.__                 "
echo "    |    |__ __  __| _/______|  |__ |__| ____     /     \ |__|  |   ____   ______"
echo "    |    |  |  \/ __ |\___   /  |  \|  |/    \   /  \ /  \|  |  | _/ __ \ /  ___/"
echo "/\__|    |  |  / /_/ | /    /|   Y  \  |   |  \ /    Y    \  |  |_\  ___/ \___ \ "
echo "\________|____/\____ |/_____ \___|  /__|___|  / \____|__  /__|____/\___  >____  >"
echo " info[at]msbios.com \/      \/    \/        \/          \/             \/     \/ "

# Database credentials
user="$1"
password="$2"
host="$3"
db_name="$4"

echo $db_name;

# Other options
backup_path="./data/cache"
date=$(date +"%F-%H:%M")

# Set default file permissions
umask 177

# Dump database into SQL file
mysqldump --routines --user=$user --password=$password --host=$host $db_name > $backup_path/$db_name-$date.sql

# Delete files older than 30 days
find $backup_path/* -mtime +30 -exec rm {} \;