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

Composer install command will create an empty configuration skeleton file for you here `/config/app.php`, so please fill in everything needed.