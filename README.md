# the following command should be run:
$ docker-compose up
$ docker ps -a
$ sudo docker exec -it myscootercom_webserver_1 bash
$ bin/console doctrine:schema:create
$ bin/console doctrine:fixtures:load

# locally (meaning not in the docker container)
$ mysql --host=127.0.0.1 --port=33066 --user=root --password=root
mysql> use scooter;
mysql> select * from api_token;
# grab any token you like. an example is: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmYWtlUGFyYW0xIjoiMTBjYzIwOGYtOTA1MS0zNGNiLTg2NGMtYzFkNTc2ZGZjZDE4IiwiZmFrZVBhcmFtMiI6IjYyMzM3NDU4ODAiLCJmYWtlUGFyYW0zIjoiQmFydG9sZXR0aSBHcm91cCIsImZha2VQYXJhbTQiOiJCRFQiLCJleHAiOjE2MTE4NTYyMjN9.760235a77727922927a7045e86964aba6dfae78a796cfed7b946b9156111e977
# and in the docker run(we do not have Admin user so scooter entry will do the thick):
# curl -ivL -X GET -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmYWtlUGFyYW0xIjoiMTBjYzIwOGYtOTA1MS0zNGNiLTg2NGMtYzFkNTc2ZGZjZDE4IiwiZmFrZVBhcmFtMiI6IjYyMzM3NDU4ODAiLCJmYWtlUGFyYW0zIjoiQmFydG9sZXR0aSBHcm91cCIsImZha2VQYXJhbTQiOiJCRFQiLCJleHAiOjE2MTE4NTYyMjN9.760235a77727922927a7045e86964aba6dfae78a796cfed7b946b9156111e977" 'http://127.0.0.1/deploy/init'

# if you have `Status Code` = 204 then in your browser locally run:
http://127.0.0.1:15672/ 
# with credentials guest|guest.
# Make sure that there is a message in a queue `messages_high`
# if there is a message run in the docker:
$ bin/console messenger:consume -vv async_priority_high async
# and if there is a constant flow of messages you can locally go to you browser:
http://127.0.0.1:15672/
# and see what is happening with queues...
# as long as `messages_high` queue is empty you can go to the DB and periodically run:
mysql> select * from location;
# and see that scooters are changing their location (now there is only testing 
# data just how it works in general.)

# if case we have failed messages:
$ bin/console messenger:failed:retry -vv

# to make sure that messenger-consumers are running in PROD we have to install `supervisor` through yum/apt-get
# and deal with the following:
# $ vim /etc/supervisord.conf // there is a need to set up additional configuration parameters
# but for testing purposes we can skip the step.



