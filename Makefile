default: help

help:
	@echo "Comandos disponíveis:"
	@echo "install\t\t Instala as dependencias"
	@echo "update\t\t Atualiza as dependencias"
	@echo "test\t\t Roda os teste e gera relatório"
	@echo "coverage\t Abre o relatório de cobertura"
	@echo "perms\t\t Ajusta as permisões"
	@echo "config\t\t Cria o arquivo de configuração"

composer:
	@echo "Verificando Composer.phar"
	wget -nc http://getcomposer.org/composer.phar

install: composer
	@echo "Instalando dependencias..."
	php composer.phar install

update: composer
	@echo "Atualizando dependencias..."
	php composer.phar update

test:
	@echo "Rodando testes e gerando relatório de cobertura..."
	vendor/bin/phpunit

coverage:
	@echo "Abrindo relatório de cobertura de código..."
	open app/tests/web/coverage/index.html

perms:
	mkdir -p app/resources/tmp/cache
	chmod -R 0777 app/resources

config:
	cp -fr app/config/config.exemplo.php app/config/config.php
