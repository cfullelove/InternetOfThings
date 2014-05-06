#!/bin/bash


# 
# Sets the color of the Ninjablock LED every second
# depending on what the status of the internet connection is
#
# Note: assumes MQTT for 

BLOCKID=1012BB013284

while [ true ]; do
  COLOR=`/usr/bin/php internetStatus.php`
  mosquitto_pub \
    -t RedNinja/${BLOCKID}/write \
    -m "{\"DEVICE\":[{\"G\":\"0\",\"V\":0,\"D\":1000,\"DA\":\"${COLOR}\"}]}"

  sleep 1;
done;
