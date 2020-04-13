# Instalação
```bash
1 - git clone https://github.com/charlanalves/charlanScraping.git
2 - Dê permissão para a pasta clonada 
3 - Execute o arquivo ./install.sh
```
# Uso
Quando voc realizar a instalação o script automaticamente tentará executar a primeira vez.
Entretanto para rodar aplicação após a instalação basta executar o arquivo abaixo.
```bash
./run.sh
```

# Libs utilizadas e Tecnologias
```bash
puphpeteer's - lib headless browser para muito utilizada para scraping
Docker e docker composer 
```
# Conceito do projeto
O teste desenvolvido tem como objetivo além de atender os requisitos propostos prover isolamento do codigo para sua reutilização e portabilidade para isso foi criado uma mini Engine De Scraping através dos arquivos CharlanScraping.php,PagesConfig.php e OutputConfig. 

Basicamente o conceito geral consiste em realizar configuraçoes nos arquivos PagesConfig.php e OutputConfig
e "automaticamente" a mini engine faria a leitura destas executando as açoes e comandos definidos.

# Justificativa 
Eu poderia ter feito um script single page com linguagem estruturada porém optei por criar essa mini engine global com orientação objeto aplicando conceitos de herança, polimorfismo e SOLID etc, com o intuito de demonstrar meus conhecimentos de forma prática com O.O e desenvolvimento de componentes globais, objeto esse que  parte ativa da minha carreira uma vez que ja tive a oportunidade de desenvolver um Micro-Framework.

Entretanto, destaco que entendo perfeitamente que existem momentos que um script simplificado e até mesmo com estruturado atende perfeitamente.


# Limitações
Como se tratava apenas de um teste o foco do objeto desenvolvido não visa ser o mais performático apesar de eu deter o conhecimento para tal. Nessa oportunidade preferi focar do design e funcionalidade, mas nada me impediria por exemplo de desenvolver com recursos multithreads ou mesmo com complexidade ciclomática baixíssima.

Api - Pensei em desenvolver uma api para aumentar ainda mais a reutilização entre sistemas mas acabei tendo outros compromissos no fim de semana e acabou no entrandando nessa versão :) nessa 1.0 acabei entregando para uso via cli/cron como fosse uma espécie de rotina.


## Arquivos de configuração PageConfig.php
```bash
 * Arquivo de configuração da extração de dados na web
 * 
 * Documentação de exemplo:
 * GOTO_PAGE => 'COMPARAR_PRECOS' -> nesse ponto ja estamos na pagina inicial  e definiremos um simples label para a proxima pagina (pode ser qualquer nome)
 * CLICK_SELECTOR' => '.C1iIFb.IHk3ob a' -> o seletor que quando clicado levará para pagina definida no GOTO_PAGE
 * EXTRACT_DATA => array de parametros para as extrações de dados da pagina do GOTO_PAGE
 *  [
 *     'DATA_LABEL' => 'PRODUTO', ->  Label do dado que sera extraido. Essa informacao podera ser recuperada pelo label posteriormente
 *     'SELECTOR'   => '#sg-product__pdp-container .sh-t__title' -> seletor css3 que será  utilizado para extracao 
 *     'REGEX'      => '/(?<=total-price">R\$).*?(?=<)/m' -> Além de seletores css3 é possivel utilizar um regex
 *     'ACTION'     => 'innerText' -> acao javascript que sera acionada apos selecinado                
 *  ],
 * 
 * Apos executar cada extração ele montará um array conforme o exemplo abaixo que 
 * podera ser utilizado posteriormente nas classes da Engine e do EiPriceScraping
 * EX: 
 * 'PRODUTO' => 'dados_extraidos',
 * 'PRICE_TABLE' => 'dados_extraidos',
 * 'SELLER' =>  'dados_extraidos'
 */
```

## Arquivos de configuração OutputConfig.php
```bash
/**
 *
 * OutputConfig
 * 
 * Arquivo de configuração da saida de dados CSV, DATABASE ETC
 * Sendo que só foi implementado CSV
 * 
 * Documentação de exemplo:
 * CSV => tipo da saida, aqui poderiamos ter outras se quisessemos como database e etc. * 
 *  [
 *     'FILE_NAME' => 'layout_entrega_resumo_item.csv' - path com o nome do arquivo
 *     'ID'   => CSV_RESUMO - nome de identificaço para ser utilizado internamente
 *     'DATA'  => Colunas e origem dos dados
 *            [
 *            'PRODUTO'     =>  ['SOURCE' => 'extractedData'], // dados obtidos atraves da extracao 
 *            'EAN'         =>  ['SOURCE' => 'initialCsv'], // dados obtidos direto do csv inicial
 *            'PRECO REF'   =>  ['SOURCE' => 'initialCsv'],  // dados obtidos direto do csv inicial 
 *             
 *                  Dados obtidos a partir de funcoes internas. Fiz dessa forma objetivando 
 *                  reutilização uma  vez que é so indicar o nome da funcao na chave SOURCE
 *                  e em PARAMS indicar o DATA_LABEL do dado extraido  
 * 
 *            'MIN'         =>  ['SOURCE' => 'internalFnGetMin',  'PARAMS' => 'PRICE_TABLE'],
 *            'MAX'         =>  ['SOURCE' => 'internalFnGetMax',  'PARAMS' => 'PRICE_TABLE'],
 *            'MEDIA'       =>  ['SOURCE' => 'internalFnGetAVG',  'PARAMS' => 'PRICE_TABLE'],
 *            'QTDE SELLER' =>  ['SOURCE' => 'internalFnGetCount','PARAMS' => 'SELLER'],
 *            'DATA'        =>  ['SOURCE' => 'internalFnGetDate', 'PARAMS' => 'd/m/Y'],
 *                 
 *                   se precisar criar alguma funcao customizada so utlizar 
 *                   o SOURCE com o nome da funcao presente no arquivo EiPriceScraping.php
 * 
 *                   como o exemplo abaixo:
 *            'GAP'         =>  ['SOURCE' => 'customFnGetGap', 'PARAMS' => 'PRICE_TABLE'],
 * 
 *          ]
 *  ],
 * 
 * Apos executar cada extração ele montará um array conforme o exemplo abaixo que 
 * podera ser utilizado posteriormente nas classes da Engine e do EiPriceScraping
 * EX: 
 * 'PRODUTO' => 'dados_extraidos',
 * 'PRICE_TABLE' => 'dados_extraidos',
 * 'SELLER' =>  'dados_extraidos'
 */
```

