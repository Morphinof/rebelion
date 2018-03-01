#!/bin/sh
set -xe

varnishd -a :8000 -f /etc/varnish/default.vcl -s malloc,256m
varnishlog
