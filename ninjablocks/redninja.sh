#!/bin/bash

# script that sets up a publish and subscripe on MQTT topics and
# connects to the arduino tty for the ninjablock. These topics
# are then published/subscribed to by various clients anywhere on
# the local LAN

HWID=`serialnumber`

echo "Serial Number: $HWID"

dopub() {
        while read line; do
                # Note: filter out accelerometer readings... a bit spammy
                if [[ "$line" == *\"D\":2* ]]
                then
                        continue;
                fi
                mosquitto_pub -t RedNinja/$HWID/read -h dns.lan -m "$line";
        done;
}


case $1 in
        read)
                dopub < /dev/ttyO1
        ;;
        write)
                mosquitto_sub -t RedNinja/`serialnumber`/write -h dns.lan > /dev/ttyO1
        ;;
        *)
                /bin/bash $0 read &
                /bin/bash $0 write &
                wait $(jobs -p)
                echo End
        ;;
esac
