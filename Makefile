test:
	docker-compose -f dev/docker-compose.yml run php php vendor/bin/phpunit tests