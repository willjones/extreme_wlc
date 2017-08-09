<?php
/**
 * Get SNMP stats from Extreme WLC and insert into Graphite/Carbon.
 *
 * @author: Will Jones <email@willjones.eu>
 */

include("config.php");
include("common.php");

if ($graphite_send) $fsock = fsockopen($graphite_ip, $graphite_port);

foreach ($controllers as $c_name => $ip) {
	/**
     *  Total APs
     */
	$wlc_total_ap = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.5.1.1.0", "INTEGER"));
	sendGraphite("wlc_total_ap", $wlc_total_ap[0]);

	/**
     *  Total Active APs
     */
	$wlc_total_ap_act = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.5.2.1.0", "INTEGER"));
	sendGraphite("wlc_total_ap_act", $wlc_total_ap_act[0]);

    /**
     * WLC Licence Capacity
     */
	$temp = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.2.10.1.4.0", "Gauge32"));
	sendGraphite("wlc_lic_available_ap", $temp[0]);

	$temp = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.2.10.1.7.0", "Gauge32"));
	sendGraphite("wlc_lic_ap_local", $temp[0]);

	$temp = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.2.10.1.8.0", "Gauge32"));
	sendGraphite("wlc_lic_ap_foreign", $temp[0]);

	$temp = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.2.10.1.5.0", "Gauge32"));
	sendGraphite("wlc_lic_available_radar", $temp[0]);

	$temp = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.2.10.1.9.0", "Gauge32"));
	sendGraphite("wlc_lic_ap_radar_local", $temp[0]);

	$temp = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.2.10.1.10.0", "Gauge32"));
	sendGraphite("wlc_lic_ap_radar_foreign", $temp[0]);

	/**
     *  Total Associated Stations
     */
	$wlc_total_sta = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.7.1.0", "INTEGER"));
	sendGraphite("wlc_total_sta", $wlc_total_sta[0]);
	
	/**
     * Total Associated Stations by Protocol
     */
	$wlc_total_sta_11a = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.2.10.2.1.0", "Gauge32"));
	sendGraphite("wlc_total_sta_11a", $wlc_total_sta_11a[0]);

	$wlc_total_sta_11b = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.2.10.2.2.0", "Gauge32"));
	sendGraphite("wlc_total_sta_11b", $wlc_total_sta_11b[0]);

	$wlc_total_sta_11g = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.2.10.2.3.0", "Gauge32"));
	sendGraphite("wlc_total_sta_11g", $wlc_total_sta_11g[0]);

	$wlc_total_sta_11n2 = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.2.10.2.4.0", "Gauge32"));
	sendGraphite("wlc_total_sta_11n2", $wlc_total_sta_11n2[0]);

	$wlc_total_sta_11n5 = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.2.10.2.5.0", "Gauge32"));
	sendGraphite("wlc_total_sta_11n5", $wlc_total_sta_11n5[0]);

	$wlc_total_sta_11ac = explode(" ", get_snmp("1.3.6.1.4.1.4329.15.3.2.10.2.8.0", "Gauge32"));
	sendGraphite("wlc_total_sta_11ac", $wlc_total_sta_11ac[0]);
	}

	/**
 	 * 5 Sec Average CPU Utilsation
 	 */
     $out = snmp2_real_walk($ip, $community, "1.3.6.1.4.1.5624.1.2.49.1.1.1.1.2");

     	foreach ($out as $key => $value) {
        	$tmp = explode(".",$key);
			$cpu_id = $tmp[count($tmp)-1];
         	sendGraphite("cpu_util.5sec.cpu_{$cpu_id}", sanatize_snmp("INTEGER", $value));
    }

	/**
 	 * 1 Min Average CPU Utilsation
 	 */
     $out = snmp2_real_walk($ip, $community, "1.3.6.1.4.1.5624.1.2.49.1.1.1.1.3");

     	foreach ($out as $key => $value) {
        	$tmp = explode(".",$key);
			$cpu_id = $tmp[count($tmp)-1];
         	sendGraphite("cpu_util.1min.cpu_{$cpu_id}", sanatize_snmp("INTEGER", $value));
    }

    /**
 	 * 5 Min Average CPU Utilsation
 	 */
     $out = snmp2_real_walk($ip, $community, "1.3.6.1.4.1.5624.1.2.49.1.1.1.1.4");

     	foreach ($out as $key => $value) {
        	$tmp = explode(".",$key);
			$cpu_id = $tmp[count($tmp)-1];
         	sendGraphite("cpu_util.5min.cpu_{$cpu_id}", sanatize_snmp("INTEGER", $value));
    }

   	/**
     * WLC Memory
     */
	$total_memory = explode(" ", get_snmp("1.3.6.1.4.1.5624.1.2.49.1.3.1.1.4.1.2.0", "Gauge32"));
	sendGraphite("wlc_total_memory", $total_memory[0]);
	$free_memory = explode(" ", get_snmp("1.3.6.1.4.1.5624.1.2.49.1.3.1.1.5.1.2.0", "Gauge32"));
	sendGraphite("wlc_free_memory", $free_memory[0]);

	/**
 	 * Clients per ESS
 	 */
	$ess = getEssTable();
	$clientsPerEss = getClientsPerEss($ess);

foreach ($clientsPerEss as $ess => $no) {
		sendGraphite("assoc.ess." . $ess, $no);
	}

	/**
 	 * Clients per AP per Protocol
 	 */
	$ap = getApTable();
	$ClientsPerAp = getClientsPerAp($ap);
	$Clients11aPerAp = get11aClientsPerAp($ap);
	$Clients11bPerAp = get11bClientsPerAp($ap);
	$Clients11gPerAp = get11gClientsPerAp($ap);
	$Clients11n5PerAp = get11n5ClientsPerAp($ap);
	$Clients11n2PerAp = get11n2ClientsPerAp($ap);
	$Clients11acPerAp = get11acClientsPerAp($ap);

foreach ($ClientsPerAp as $ap => $no) {
		sendGraphite("assoc.ap." . $ap . ".total", $no);
	}
foreach ($Clients11aPerAp as $ap => $no) {
		sendGraphite("assoc.ap." . $ap . ".11a", $no);
	}
foreach ($Clients11bPerAp as $ap => $no) {
		sendGraphite("assoc.ap." . $ap . ".11b", $no);
	}
foreach ($Clients11gPerAp as $ap => $no) {
		sendGraphite("assoc.ap." . $ap . ".11g", $no);
	}
foreach ($Clients11n5PerAp as $ap => $no) {
		sendGraphite("assoc.ap." . $ap . ".11n5", $no);
	}
foreach ($Clients11n2PerAp as $ap => $no) {
		sendGraphite("assoc.ap." . $ap . ".11n2", $no);
	}
foreach ($Clients11acPerAp as $ap => $no) {
		sendGraphite("assoc.ap." . $ap . ".11ac", $no);
	}

	/**
 	 * Tx+Rx Octets per AP per Protocol
 	 */
	$ap = getApTable();
	$TxOctPerAp = getTxOctPerAp($ap);
	$RxOctPerAp = getRxOctPerAp($ap);
	$TxOct11aPerAp = getTxOct11aPerAp($ap);
	$RxOct11aPerAp = getRxOct11aPerAp($ap);
	$TxOct11bPerAp = getTxOct11bPerAp($ap);
	$RxOct11bPerAp = getRxOct11bPerAp($ap);
	$TxOct11gPerAp = getTxOct11gPerAp($ap);
	$RxOct11gPerAp = getRxOct11gPerAp($ap);
	$TxOct11n2PerAp = getTxOct11n2PerAp($ap);
	$RxOct11n2PerAp = getRxOct11n2PerAp($ap);
	$TxOct11n5PerAp = getTxOct11n5PerAp($ap);
	$RxOct11n5PerAp = getRxOct11n5PerAp($ap);
	$TxOct11acPerAp = getTxOct11acPerAp($ap);
	$RxOct11acPerAp = getRxOct11acPerAp($ap);

foreach ($TxOctPerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".total-tx", $no);
	}
foreach ($RxOctPerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".total-rx", $no);
	}
foreach ($TxOct11aPerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".11a-tx", $no);
	}
foreach ($RxOct11aPerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".11a-rx", $no);
	}
foreach ($TxOct11bPerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".11b-tx", $no);
	}
foreach ($RxOct11bPerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".11b-rx", $no);
	}
foreach ($TxOct11gPerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".11g-tx", $no);
	}
foreach ($RxOct11gPerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".11g-rx", $no);
	}
foreach ($TxOct11n2PerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".11n2-tx", $no);
	}
foreach ($RxOct11n2PerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".11n2-rx", $no);
	}
foreach ($TxOct11n5PerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".11n5-tx", $no);
	}
foreach ($RxOct11n5PerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".11n5-rx", $no);
	}
foreach ($TxOct11acPerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".11ac-tx", $no);
	}
foreach ($RxOct11acPerAp as $ap => $no) {
		sendGraphite("bytes.ap." . $ap . ".11ac-rx", $no);
	}

	/**
 	 * Tx+Rx Octets per Toplogy
 	 */
	$topology = getTopologyTable();
	$TxOctPerToplogy = getTxOctPerTopology($topology);
	$RxOctPerToplogy = getRxOctPerTopology($toploogy);

foreach ($TxOctPerToplogy as $topology => $no) {
		sendGraphite("bytes.topology." . $topology . ".tx", $no);
	}
foreach ($RxOctPerToplogy as $topology => $no) {
		sendGraphite("bytes.topology." . $topology . ".rx", $no);
	}

if ($graphite_send) fclose($fsock);
?>