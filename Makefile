test:
	php bin/phpunit
dev:
	symfony server:start
spec:
	docker run --rm -p 8080:8080 -d --name swaggerui -v ./:/spec -e SWAGGER_JSON=/spec/openapi.yaml swaggerapi/swagger-ui
	sleep 1
	open http://localhost:8080
