IMAGE_NAME := projetjeu/web-mobile
TAG := latest

build: 
	@docker build --plateform linux/amd64 -t $(IMAGE_NAME):$(TAG)

push:
	@docker push $(IMAGE_NAME):$(TAG)

pull:
	@docker pull $(IMAGE_NAME):$(TAG)

.PHONY : build push pull


