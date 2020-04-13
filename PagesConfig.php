<?php
/**
 *
 * PagesConfig
 * 
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
 *
 * @author Charlan Santos
 */ 
return 
[   
    [
        'GOTO_PAGE'      => 'COMPARAR_PRECOS',
        'CLICK_SELECTOR' => '.C1iIFb.IHk3ob a',
        'EXTRACT_DATA'   =>
        [
                [
                    'DATA_LABEL' => 'PRODUTO',
                    'SELECTOR'   => '#sg-product__pdp-container .sh-t__title',
                    'ACTION'     => 'innerText'
                    
                ],
                [
                    'DATA_LABEL'      => 'PRICE_TABLE',
                    'SELECTOR'        => 'table#sh-osd__online-sellers-grid',
                    'ACTION'          => 'innerHTML',
                    'REGEX'           => '/(?<=total-price">R\$).*?(?=<)/m'
                ],
                [
                    'DATA_LABEL'      => 'SELLER',
                    'SELECTOR'        => 'table#sh-osd__online-sellers-grid',
                    'ACTION'          => 'innerHTML',
                    'REGEX'           => '/(?<="_blank"><span>).*?(?=<)/m'
                ]
            
        ],				
    ]
];

