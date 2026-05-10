# DiguinhoMax

DiguinhoMax é um sistema web em PHP para cadastro e controle de uma locadora de filmes.
O projeto utiliza programação orientada a objetos, Bootstrap e sessões do PHP para simular
um painel administrativo funcional sem depender de banco de dados.

## Objetivo

O sistema permite cadastrar clientes, cadastrar filmes, consultar o catálogo, realizar locações,
registrar devoluções e visualizar relatórios simples sobre o funcionamento da locadora.

## Classes principais

- `Cliente`: armazena os dados pessoais do cliente, calcula a idade e verifica se ele pode alugar um filme conforme a classificação indicativa.
- `Filme`: armazena os dados do filme, controla o estoque e possui métodos para alugar e devolver unidades.
- `Locacao`: representa uma locação, calcula o valor, verifica atraso, calcula multa e finaliza a devolução.
- `SistemaLocadora`: gerencia os clientes, filmes e locações, além de centralizar cadastros, buscas e listagens.

## Telas do sistema

- Dashboard com resumo de filmes, clientes, locações e alertas de estoque.
- Cadastro e listagem de clientes.
- Detalhes do cliente com histórico de locações.
- Cadastro e catálogo de filmes.
- Tela de nova locação com validação de idade e estoque.
- Tela de locações ativas e devolução.
- Relatórios com total arrecadado, multas e histórico.

## Como testar e usar o sistema

1. Abra o projeto em um servidor PHP local.
   - Pelo XAMPP, coloque a pasta do projeto dentro de `htdocs` e acesse `http://localhost/ProjetoLocadora/index.php`.
   - Pelo servidor embutido do PHP, execute `php -S 127.0.0.1:8104 -t "C:\Users\Gabriel Claro\Documents\GitHub\ProjetoLocadora"`.

2. Acesse a tela inicial:
   - `http://127.0.0.1:8104/index.php`

3. Para reiniciar os dados de teste, acesse:
   - `http://127.0.0.1:8104/index.php?resetar=1`

4. No Dashboard, confira os cards de resumo:
   - filmes cadastrados;
   - clientes cadastrados;
   - locações ativas;
   - devoluções atrasadas.

5. Acesse a tela Clientes e clique em Novo cliente para cadastrar uma pessoa.

6. Depois do cadastro, volte para Clientes e use os botões da tabela para:
   - visualizar os detalhes do cliente;
   - iniciar uma nova locação para esse cliente.

7. Acesse a tela Filmes para consultar o catálogo.

8. Clique em Novo filme para cadastrar um novo título, informando:
   - título;
   - gênero;
   - ano;
   - classificação indicativa;
   - quantidade em estoque;
   - preço por dia.

9. Acesse Nova locação para alugar um filme.
   - Selecione um cliente.
   - Selecione um filme disponível.
   - Informe a quantidade de dias.
   - Confira a validação de idade, estoque e valor estimado.
   - Clique em Confirmar locação.

10. Acesse Locações para acompanhar os filmes alugados.

11. Clique em Devolver para finalizar uma locação.
    - Informe a data real da devolução.
    - Confira se existe multa por atraso.
    - Clique em Confirmar devolução.

12. Acesse Relatórios para conferir:
    - total arrecadado;
    - total em multas;
    - filmes mais alugados;
    - clientes com mais locações;
    - histórico completo.

## Conhecimentos utilizados

- Classes, atributos, métodos e construtores.
- Instanciação de objetos.
- Estruturas condicionais com `if`, `else` e validações.
- Estruturas de repetição com `foreach`.
- Organização visual com Bootstrap.
- Uso de sessão para manter os dados enquanto o sistema está aberto.
