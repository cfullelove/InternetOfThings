#!/bin/bash


# Script that polls my Cisco 877 ADSL router for the
# current sync speed and pubishes to Ninjablocks


BLOCKID=1012BB013284

while true ; do

  # ADSL rx and tx speed on Cisco 877
  RX=$(expr `snmpwalk -c router -v 1 -O qv router.lan 1.3.6.1.2.1.10.94.1.1.4.1.2` / 1000)
  TX=$(expr `snmpwalk -c router -v 1 -O qv router.lan 1.3.6.1.2.1.10.94.1.1.5.1.2` / 1000)

  # Publish to ninjablocks sensor topic
  mosquitto_pub -t "RedNinja/${BLOCKID}/read" -m '{"DEVICE":[{"G":"1","V":0,"D":540,"DA":'$RX'}]}'
  mosquitto_pub -t "RedNinja/${BLOCKID}/read" -m '{"DEVICE":[{"G":"1","V":0,"D":530,"DA":'$TX'}]}'

        sleep 30
done;
