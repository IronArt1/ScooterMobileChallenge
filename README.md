# the following command should be run:
$ mkdir /tmp/scooter && cd /tmp/scooter
$ git clone https://github.com/IronArt1/NordLockerScooter.git
$ cd NordLockerScooter
$ git checkout -b develop origin/develop
$ composer install
$ docker-compose up
# open another terminal and run:
$ docker ps -a
$ sudo docker exec -it nordlockerscooter_webserver_1 bash
$ chmod -R 744 bin
$ bin/console doctrine:schema:create
$ bin/console doctrine:fixtures:load

# locally run in the root of the project (meaning not in the docker container):
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

# just in case if there is a need to call API from the console:
# updates a scooter status:
$ curl -ivL -X POST -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmYWtlUGFyYW0xIjoiYmQ5NTBmY2ItMGRhOC0zMGU5LWEzYzAtZmJhNDNjYWNiNWUzIiwiZmFrZVBhcmFtMiI6IjEzMzI1NDYyMDY3NjE1NyIsImZha2VQYXJhbTMiOiJCYWlsZXkgYW5kIFNvbnMiLCJmYWtlUGFyYW00IjoiS1dEIiwiZXhwIjoxNjExODAyMjY5fQ.82f37d6dc727b2aab087384274709547e8e5c27aa894773d8b581a821e4f332a" -H "Content-type: application/json" -d '{"occupied": true}' -b cookies.txt 'http://my.scooter.com/scooter/ac11fb43-87b4-4f91-9035-fff87b2df269/update-status'

# updates a scooter location:
$ curl -ivL -X POST -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmYWtlUGFyYW0xIjoiYzU5ZmRmYjMtYTQzMS0zZTU3LWEwZWItZDI4NzZjZmJlZGVkIiwiZmFrZVBhcmFtMiI6IjY3OTAxNDMyNTc2OTE4OCIsImZha2VQYXJhbTMiOiJHbGVpY2huZXIsIFBhZ2FjIGFuZCBCb2RlIiwiZmFrZVBhcmFtNCI6IkNVQyIsImV4cCI6MTYxMTgxMjUwNX0.424477a229dc304568736f6c20b7600c2ae4f6f83ef1758627d2ec41ff9117fe" -H "Content-type: application/json" -d '{"latitude": "43.9999", "longitude": "79.1111", "updatedAt": "1010-10-10 10:10:10"}' -b cookies.txt 'http://my.scooter.com/scooter/f5e86469-e0a3-4f68-a1e2-9c7296dc1794/update-location'

# and just in case the one I've already mentioned above:
# deploys scooters:
$ curl -ivL -X GET -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmYWtlUGFyYW0xIjoiMTBjYzIwOGYtOTA1MS0zNGNiLTg2NGMtYzFkNTc2ZGZjZDE4IiwiZmFrZVBhcmFtMiI6IjYyMzM3NDU4ODAiLCJmYWtlUGFyYW0zIjoiQmFydG9sZXR0aSBHcm91cCIsImZha2VQYXJhbTQiOiJCRFQiLCJleHAiOjE2MTE4NTYyMjN9.760235a77727922927a7045e86964aba6dfae78a796cfed7b946b9156111e977" 'http://my.scooter.com/deploy/init'

