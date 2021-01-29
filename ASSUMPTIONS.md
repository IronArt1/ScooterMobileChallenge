What has been done and why in that particular way:
* Since your company is called "Nord Security" I thought that it would be a great 
idea to show that I can deal with JWT and authentications matters at the whole 
extent so I've done the thing regardless the fact that it's not actually required...
* In the job description I saw RabbitMQ and thought that it would be nice to show 
that I can deal with the thing also, so I separated scooters activities from the 
mobile clients activities and put scooters activities in the RabbitMQ.
* Scooters activities are almost done, meaning that there is only a lack of a proper
algorithm that calculates movements. The major line of scooters activities, like, 
scooters can change their status and location is done.
* Mobile clients will be done in React or NodeJS.
* The very reason why I implemented the Builder/Command pattern is just I like PHP
patterns and I am aware of 48 of those, although I sustain in memory only 15, like, the 
most useful ones. Also, please, consider the following. Let's say that scooter can 
change its status (occupied - true/false) only by lines like:
```php
    // pseudo code's
    public function setScooterStatus($incommingToken, $appToken, $scooter, $status, $em)
    {
         if ($incommingToken == $appToken) {
            $scooter->setOccupied($status);
            $em->persist($location);
            $em->flush();
         } else {
            throw new \Exception('Not Authorized ...', 401);
         }
         return JsonResponse(...);
    }
```
Does the code tell you a lot about capacity of a certain programmer? Well, I don't
think so... My approach, on the other hand, tells you a lot about my ability to 
code and deal/create a complex systems. Isn't it what we are after in the assessment
process after all? So having taken in account that range of thoughts I've done the project in 
that peculiar way, if you will...
* The rest of the task, like, Mobile clients part + tests + Swagger(API docs) will 
be done during the next week if you do not mind(we all have to make living, you know + 
  I do not work during the weekends...), but here is a question. In the job
description I haven't seen JS abilities, but you can be rest assured that I've got 
plenty of those, actually you can see it in my profile... Also I've done numbers of testing activities that you can see from
my profile, so that will not be a problem in any way also. So the question is since the
assessment process is all about evaluation of person's capabilities haven't we got 
already enough from the current state of the project? Please, get back to me on the matter
as soon as possible. Would very much appreciate it! 