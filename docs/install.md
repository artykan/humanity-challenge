# Installing and running the Humanity coding challenge

Add an entry `127.0.0.1 humanity.challenge.docker` to your `/etc/hosts` file:
```sh
sudo echo "127.0.0.1 humanity.challenge.docker" >> /etc/hosts
```
Run from the following from the project root:
```
docker-compose build
docker-compose up -d
docker exec -it humanity_challenge_php bash -c "composer install"
```

Composer install command will create a configuration skeleton file for you here `/config/app.php`.

## Authentication

Authentication works using HMAC approach. So authentication header should be provided in the following format:
```
{email}:{nonce}:{digest}
```

Here is the example of a request command:
```
curl -X GET -H 'Authentication: admin@humanity.challenge.docker:98765:MGQzNWQ5Nz' humanity.challenge.docker/user-requests -v
```

Nonce is just a random number.

Digest should be generated. For example you may use this PHP code to generate:
```
$method = 'GET';
$uri = '/user-requests'
$nonce = 98765; // random number
$secret = 'kY73BD*l^&'; // the same string as in /config/app.php
$digestData = $method . '+' . $uri . '+' . $nonce;
$digest = substr(base64_encode(hash_hmac('sha256', $digestData, $secret)), 0, 10);
print_r($digest);
```

The authentication is simplified - there is no checking of nonce uniqueness regarding a particular request URI.

## Testing Requests

```
curl -X POST -H 'Authentication: user@humanity.challenge.docker:98765:NzIwNDEzZj' -d 'date_start=2018-12-24&date_end=2019-01-10' humanity.challenge.docker/user-requests -v
curl -X PUT -H 'Authentication: user@humanity.challenge.docker:98765:M2NiZWQxMj' -d 'date_start=2019-03-27&date_end=2019-03-29' humanity.challenge.docker/user-requests/1 -v
curl -X POST -H 'Authentication: admin@humanity.challenge.docker:98765:NDRmYTJkZj' humanity.challenge.docker/user-requests/1/approve -v
curl -X POST -H 'Authentication: admin@humanity.challenge.docker:98765:ZmIxOWQzNm' humanity.challenge.docker/user-requests/1/reject -v
curl -X DELETE -H 'Authentication: user@humanity.challenge.docker:98765:N2RiNjU2Nj' humanity.challenge.docker/user-requests/1 -v
curl -X GET -H 'Authentication: admin@humanity.challenge.docker:98765:MGQzNWQ5Nz' humanity.challenge.docker/user-requests -v
curl -X GET -H 'Authentication: user@humanity.challenge.docker:98765:ODU5MzA5Ym' humanity.challenge.docker/user-requests/remainder -v
curl -X GET -H 'Authentication: user@humanity.challenge.docker:98765:MWQzOTI1OT' humanity.challenge.docker/user-requests/remainder/year/2019 -v
```

## Unit Tests

Tests can be run with: 
```
docker exec -it humanity_challenge_php bash -c "vendor/bin/phpunit"
```
or
```
docker exec -it humanity_challenge_php bash -c "vendor/bin/phpunit --testdox"
```