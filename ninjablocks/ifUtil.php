<?php

function getValues( $ifId )
{
	return array(
		'in' => snmpget( 'router', 'router', '1.3.6.1.2.1.2.2.1.10.' . $ifId ),
		'out' => snmpget( 'router', 'router', '1.3.6.1.2.1.2.2.1.16.' . $ifId ),
		'time' => time()
	);
}

$ifId = $argv[1];

snmp_set_quick_print(1);

$period = 30;
$delta = 5;

$vals = array_fill( 0, $period / $delta, "" );

array_unshift( $vals, getValues( $ifId ) );


while ( true )
{
	sleep( $delta );
	$currentVal = getValues( $ifId );
	$previousVal = array_pop( $vals );

	if ( $previousVal !== "" )
	{
		$dIn = $currentVal['in'] - $previousVal['in'];
		$dOut = $currentVal['out'] - $previousVal['out'];
		$dt = $currentVal['time'] - $previousVal['time'];

		$sIn = $dIn * 8 / ( 10000 * $dt );
		$sOut = $dOut * 8 / ( 10000 * $dt );

		`mosquitto_pub -t "RedNinja/1012BB013284/read" -m '{"DEVICE":[{"G":"$ifId","V":0,"D":530,"DA":'$sIn'}]}'`;
		`mosquitto_pub -t "RedNinja/1012BB013284/read" -m '{"DEVICE":[{"G":"$ifId","V":0,"D":540,"DA":'$sOut'}]}'`;
	}

	array_unshift( $vals, getValues( $ifId ) );
}

/**

IF-MIB::ifDescr.1 = STRING: ATM0
IF-MIB::ifDescr.2 = STRING: FastEthernet0
IF-MIB::ifDescr.3 = STRING: FastEthernet1
IF-MIB::ifDescr.4 = STRING: FastEthernet2
IF-MIB::ifDescr.5 = STRING: FastEthernet3
IF-MIB::ifDescr.6 = STRING: Null0
IF-MIB::ifDescr.7 = STRING: ATM0-atm layer
IF-MIB::ifDescr.8 = STRING: ATM0.0-atm subif
IF-MIB::ifDescr.9 = STRING: ATM0-aal5 layer
IF-MIB::ifDescr.10 = STRING: ATM0.0-aal5 layer
IF-MIB::ifDescr.11 = STRING: ATM0-adsl
IF-MIB::ifDescr.12 = STRING: Vlan1
IF-MIB::ifDescr.13 = STRING: ATM0.1-atm subif
IF-MIB::ifDescr.14 = STRING: ATM0.1-aal5 layer
IF-MIB::ifDescr.15 = STRING: NVI0
IF-MIB::ifDescr.16 = STRING: Dialer1
IF-MIB::ifDescr.17 = STRING: Virtual-Access1


*/

?>
