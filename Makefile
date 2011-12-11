default: help

help:
	@echo "Comandos disponíveis:"
	@echo "test\t\t Roda os teste e gera relatório"
	@echo "coverage\t Abre o relatório de cobertura"

test:
	@echo "Rodando testes e gerando relatório de cobertura..."
	cd app/tests; phpunit

coverage:
	@echo "Abrindo relatório de cobertura de código..."
	open app/tests/web/coverage/index.html
