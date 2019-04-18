#!/usr/bin/env bash

ASCIILOGO=$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )/msbios.sh;

if [ -f $ASCIILOGO ]; then
    bash $ASCIILOGO;
else
    bash $(pwd)/vendor/bin/msbios.sh
fi

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