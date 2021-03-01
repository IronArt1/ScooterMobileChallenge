What has been done and why in that particular way:
* I thought that it would be a great 
idea to show that I can deal with JWT and authentications matters at the whole 
extent so I've done the thing regardless the fact that it's not actually required...
* In the job description I saw RabbitMQ and thought that it would be nice to show 
that I can deal with the thing also, so I separated scooters activities from the 
mobile clients activities and put scooters activities in the RabbitMQ.
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
