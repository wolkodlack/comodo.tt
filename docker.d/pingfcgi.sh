#!/bin/sh
# set -e

# Ping FCGI server.  Uses cgi-fcgi program from libfcgi library.
# Retrieves the root path (/) from host:port specified on command line.

if [ -z "$1" ] ; then
    echo "Usage: $0 host:port|path/to/socket-file" >&2
    exit 1
fi

###############################################################
# To Get it working, execute following on Ubuntu:
#       apt-get install libfcgi0ldbl
###############################################################

exec env \
    REQUEST_METHOD=GET \
    SERVER_NAME=localhost \
    SERVER_PORT=8000 \
    SERVER_PROTOCOL=HTTP/1.0 \
    PATH_INFO=/ \
    cgi-fcgi -bind -connect $1

# Returns:
#    0  - if available
#  out:
#       X-Powered-By: PHP/7.1.2
#       Content-type: text/html; charset=UTF-8
#
#  111  - not available
#  out:
#       Could not connect to localhost:9001
#

###############################################################
# WD :: How could it be improved:
#       http://stackoverflow.com/a/1353398
