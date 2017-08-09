<?php
/**
 * Get SNMP stats from Extreme WLC and insert into Graphite/Carbon.
 *
 * @author: Will Jones <email@willjones.eu>
 */
 
function sendGraphite($field, $value) {
        global $graphite_send, $graphite_prefix, $c_name, $fsock;

        $send = $graphite_prefix . $c_name . "." . $field . " " . $value . " " . time() . "\n";

        if ($graphite_send) {
                 fwrite($fsock, $send, strlen($send));
        }

        echo $send;
}

function get_snmp($oid, $type = "Gauge32") {
        global $ip, $community;

        return $value = sanatize_snmp($type, snmp2_get($ip, $community, $oid));
}

function sanatize_snmp($type, $value) {
        switch ($type) {
                case "Gauge32":
                        $value = str_replace("Gauge32: ", "", $value);
                        break;

                case "STRING":
                        $value = str_replace("\"", "", str_replace("STRING: ", "", $value));
                        break;

                case "INTEGER":
                        $value = str_replace("INTEGER: ", "", $value);
                        break;

                case "Counter32":
                        $value = str_replace("Counter32: ", "", $value);
                        break;

                case "Counter64":
                        $value = str_replace("Counter64: ", "", $value);
                        break;
        }
        return $value;
}

function getEssTable() {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.3.4.4.1.5");

        foreach ($temp as $key => $value) {
                $tmp = explode(".",$key);
                $table[$tmp[count($tmp)-1]] = str_replace(" ", "_", str_replace(".", "_", sanatize_snmp("STRING", $value)));
        }

        return $table;
}

function getClientsPerEss($essTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.3.4.5.1.2");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$essTable[$id]] = sanatize_snmp("Counter32", $value);

        }

        return $table;
}

function getApTable() {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.1.2.1.2");

        foreach ($temp as $key => $value) {
                $tmp = explode(".",$key);
                $table[$tmp[count($tmp)-1]] = str_replace(" ", "_", str_replace(".", "_", sanatize_snmp("STRING", $value)));
        }

        return $table;
}

function getClientsPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.14");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Gauge32", $value);

        }

        return $table;
}

function get11aClientsPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.16");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Gauge32", $value);

        }

        return $table;
}

function get11bClientsPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.17");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Gauge32", $value);

        }

        return $table;
}

function get11gClientsPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.18");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Gauge32", $value);

        }

        return $table;
}

function get11n5ClientsPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.19");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Gauge32", $value);

        }

        return $table;
}

function get11n2ClientsPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.20");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Gauge32", $value);

        }

        return $table;
}

function get11acClientsPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.24");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Gauge32", $value);

        }

        return $table;
}

function getTxOctPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.3");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getRxOctPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.8");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getTxOct11aPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.26");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getRxOct11aPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.25");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getTxOct11bPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.28");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getRxOct11bPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.27");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getTxOct11gPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.30");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getRxOct11gPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.29");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getTxOct11n2PerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.34");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getRxOct11n2PerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.33");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getTxOct11n5PerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.32");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getRxOct11n5PerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.31");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getTxOct11acPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.36");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getRxOct11acPerAp($apTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.5.2.2.1.35");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$apTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getTopologyTable() {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.4.2.1.1.1");

        foreach ($temp as $key => $value) {
                $tmp = explode(".",$key);
                $table[$tmp[count($tmp)-1]] = str_replace(" ", "_", str_replace(".", "_", sanatize_snmp("STRING", $value)));
        }

        return $table;
}

function getTxOctPerTopology($topologyTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.4.2.1.1.4");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$topologyTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

function getRxOctPerTopology($topologyTable) {
        global $ip, $community;

        $table = array();
        $temp = snmp2_real_walk($ip, $community, ".1.3.6.1.4.1.4329.15.3.4.2.1.1.5");

        foreach ($temp as $key => $value) {
                $tmp = explode(".", $key);
                $id = array();
                for ($i = (count($tmp)-1); $i < count($tmp); $i++) {
                        $id[] = $tmp[$i];
                }
                $id = implode(".",$id);

                $table[$topologyTable[$id]] = sanatize_snmp("Counter64", $value);

        }

        return $table;
}

?>