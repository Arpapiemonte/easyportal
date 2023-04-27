all: init.dev build.dev dev.up
dev: init.dev bake.dev dev.up

bake.dev:
	@echo "🔨 Building dev image with bake command..."
	docker buildx build --no-cache --tag easyportal/base --file ./docker/baseimg/Dockerfile . \
	&& docker buildx bake -f docker-compose.yml --no-cache

bake.prod:
	@echo "🔨 Building production image with bake command..."
	docker buildx build --no-cache --tag easyportal/base --file ./docker/baseimg/Dockerfile . \
	&& docker buildx bake -f docker-compose.prod.yml --no-cache

bake.dev.74:
	@echo "🔨 Building dev image with bake command and PHP 7.4.22..."
	docker buildx build --no-cache --tag easyportal/base --build-arg "PHP_VERSION=7.4.22" --file ./docker/baseimg/Dockerfile . \
	&& docker buildx bake -f docker-compose.yml --no-cache

bake.prod.74:
	@echo "🔨 Building production image with bake command and PHP 7.4.22..."
	docker buildx build --no-cache --tag easyportal/base --build-arg "PHP_VERSION=7.4.22" --file ./docker/baseimg/Dockerfile . \
	&& docker buildx bake -f docker-compose.prod.yml --no-cache

build.dev:
	@echo "🔨 Building dev image with compose..."
	docker image build --no-cache --tag easyportal/base --file ./docker/baseimg/Dockerfile . \
	&& docker-compose build --no-cache

build.prod:
	@echo "🔨 Building production image with compose..."
	docker image build --no-cache --tag easyportal/base --file ./docker/baseimg/Dockerfile . \
	&& docker-compose -f docker-compose.prod.yml build --no-cache

build.dev.74:
	@echo "🔨 Building dev image with compose and PHP 7.4.22..."
	docker image build --no-cache --tag easyportal/base --build-arg "PHP_VERSION=7.4.22" --file ./docker/baseimg/Dockerfile . \
	&& docker-compose build --no-cache

build.prod.74:
	@echo "🔨 Building production image with compose and PHP 7.4.22..."
	docker image build --no-cache --tag easyportal/base --build-arg PHP_VERION=7.4.22 --file ./docker/baseimg/Dockerfile . \
	&& docker-compose -f docker-compose.prod.yml build --no-cache

init.dev:
	@echo "Copying .env.example into .env ..."
	-cp -fi .env.example .env
	@echo "Copying ./docker/Dockerfile.example into ./docker/Dockerfile ..."
	-cp -fi ./docker/Dockerfile.example ./docker/Dockerfile
	@echo "Init done"

init.prod:
	@echo "Copying .env.example into .env ..."
	-cp -fi .env.example .env
	-sed -i '' 's/local/production/g' .env
	@echo "Copying local.json into production.json. Edit this file to set your own menus and links ..."
	-cp -fi local.json production.json
	@echo "Creating /production folder ..."
	-mkdir -p production
	@echo "Copying ./docker/Dockerfile.example into ./docker/Dockerfile ..."
	-cp -fi ./docker/Dockerfile.example ./docker/Dockerfile
	@echo "Init done"
	@echo "Log in and access the admin page to complete setup. User permission files will be found in the /production folder"

dev.up:
	docker-compose up -d

dev.down:
	docker-compose down

