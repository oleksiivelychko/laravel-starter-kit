FROM rabbitmq:3.9-management

ARG RABBITMQ_USER
ARG RABBITMQ_PASSWORD

ENV RABBITMQ_USER $RABBITMQ_USER
ENV RABBITMQ_PASSWORD $RABBITMQ_PASSWORD

ENV RABBITMQ_PID_FILE /var/lib/rabbitmq/mnesia/rabbitmq

COPY rabbitmq.config /etc/rabbitmq/
COPY definitions.json /etc/rabbitmq/

COPY init.sh /init.sh
RUN chmod +x /init.sh

EXPOSE 5672 15672

CMD ["/init.sh"]
