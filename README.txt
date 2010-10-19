Apontador Jr
============

O objetivo do projeto é criar um site, leve e fácil de acessar, que permita encontrar locais próximos.
Este site deve ser acessível o bastante para rodar em um navegador de celular (wap) ou ainda um navegador modo texto (lynx).
Na primeiro release a funcionalidade de buscar locais por um determinado CEP deve estar implementada.

Outras features desejadas:
- Salvar a última localização.
- Criar uma lista de pontos de busca (CEPs) favoritos.
- Permitir selecionar quais pontos de busca (CEPs) devem ficar no histórico.


Tecnologia usada

Apache 2 <http://httpd.apache.org/>
Apache Module mod_rewrite <http://httpd.apache.org/docs/current/mod/mod_rewrite.html>
PHP 5.3 <http://br.php.net/>
Framework Lithium <http://lithify.me/>
Apontador API <http://api.apontador.com.br/>


Setup do projeto

Depois de fazer o pull do projeto, de acesso de escrita na pasta app/resources
chmod -R 0777 app/resources
