<?php

/**

This script subscripts to the MQTT topic that is represents the sensors
for a particular block id.

The data received is then send to the "Ninja Cloud" using the REST API

The effectively replaces the NinjaBlock client for publishing sensor
data.

*/

define( 'BLOCK_ID', '1012BB013284' );
define( 'NINJA_TOKEN', '02c1959a-a7b7-44cb-9eee-bd713ed9a0b7' );

        $obj = json_decode( $argv[1] );

        if ( isset( $obj->DEVICE ) )
        {
                $device = $obj->DEVICE[0];

                echo json_encode( $device );

                $ch = curl_init( sprintf( "https://api.ninja.is/rest/v0/block/%s/data", BLOCK_ID ) );

                curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'X-Ninja-Token: ' . NINJA_TOKEN
                ) );

                curl_setopt( $ch, CURLOPT_POST, true );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $device ) );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );

                echo curl_exec( $ch );

                echo PHP_EOL;

                curl_close( $ch );

        }
?>
