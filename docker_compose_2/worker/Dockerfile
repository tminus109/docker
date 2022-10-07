FROM alpine:3.6

COPY cronjobs /etc/crontabs/root

CMD ["crond", "-f", "-d", "8"]
