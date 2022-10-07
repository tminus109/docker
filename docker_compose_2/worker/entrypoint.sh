FILE = "/etc/crontabs/root"
/bin/cat <<EOM >$FILE
* * * * * wget -O- -q --post-data "key=syAsXYL4VykdTpAX" $SEND_MAILS_URL
EOM
crond -f -d 8
