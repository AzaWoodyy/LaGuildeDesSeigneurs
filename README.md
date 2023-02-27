# La Guilde des seigneurs

## Start the project

### Start the docker containers

```bash
docker compose up
```

### Install the dependencies in the php container

```bash
composer install
```

### Connect to the database

```bash
`user: app`
`password: in .env`
`db: app`
`port: check the container port`
```

### Go to your localhost