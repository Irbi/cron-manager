#!/bin/bash

SERVICE='cron-manager'
FILE='/tmp/php.cron.manager.log'


check_process(){
        # check the args
        if [ "$1" = "" ];
        then
                return 0
        fi
        #PROCESS_NUM => get the process number regarding the given thread name
        PROCESS_NUM=$(ps -ef | grep "$1" | grep -v "grep" | wc -l)      
        if [ $PROCESS_NUM -eq 2 ];
        then
                return 1
        else
                return 0
        fi
}

# timestamp
ts=`date "+%F %T"`
echo "$ts: begin checking..."
check_process $SERVICE
CHECK_RET=$?
echo $CHECK_RET
if [ $CHECK_RET -eq 1 ];
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

