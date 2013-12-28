#!/bin/bash

echo "
# IP address settings -> 'ip address show'
######################################################
#"
ip address show

echo "
# IP routing table    -> 'ip route show'
######################################################
#"
ip route show

echo "
# ARP table           -> 'arp -n'
######################################################
#"
arp -n

echo "
# Interfaces status   -> 'ip link show'
######################################################
#"
ip link show

