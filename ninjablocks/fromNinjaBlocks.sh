#!/bin/bash

# Polls the Ninjablocks REST API for block commands and publishes
# on the associated MQTT topic (this is subscribed to by a another
# script writing to the arduino)

while true; do

        CMD=`curl -H 'X-Ninja-Token: 02c1959a-a7b7-44cb-9eee-bd713ed9a0b7' \
             -H 'Content-Type: application/json' \
             https://api.ninja.is/rest/v0/block/1012BB013284/commands 2>/dev/null`;

        echo $CMD;
        mosquitto_pub -t 'RedNinja/1012BB013284/write' -m "$CMD"
done;
