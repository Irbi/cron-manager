#!/bin/bash

SERVICE='cron-manager'
FILE='/tmp/php.cron.manager.log'

if ps ax | grep -v grep | grep $SERVICE > /dev/null
then
    echo "$SERVICE service running, everything is fine"
else
    echo "$SERVICE is not running"
    #echo "$SERVICE is not running!" | mail -s "$SERVICE down" root
        if [ -f $FILE ] 
	then
                echo "Remove all php.cron files"
                rm -rf /tmp/php.cron.*
        fi
fi

