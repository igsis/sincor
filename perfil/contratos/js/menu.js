		var myMenu =
		[
			[null,'Home','index.php',null,'Control Panel'],			
			[null,'Cadastros de Pessoas',null,null,'Cadastro de Pessoas',
				['<img src="../ThemeOffice/language.png" />','Pessoa F&iacute;sica',null,null,'Cadastrar',
  					['<img src="../ThemeOffice/language.png" />','Cadastrar','frm_cadastra_pf.php',null,'Cadastrar'],
  					['<img src="../ThemeOffice/language.png" />','Alterar/Listar','frm_lista_pf.php',null,'Alterar/Listar'],
  				],
				['<img src="../ThemeOffice/language.png" />','Pessoa Jur&iacutedica',null,null,'Cadastrarr',
					['<img src="../ThemeOffice/language.png" />','Cadastrar','frm_cadastra_pj.php',null,'Cadastrar'],
  					['<img src="../ThemeOffice/language.png" />','Alterar/Listar','frm_lista_pj.php',null,'Alterar/Listar'],
				],
			],
			[null,'Pedido de Contrata��o',null,null,'Pedido de Contrata&ccedil;&atilde;o',				
  				['<img src="../ThemeOffice/language.png" />','Pessoa F&iacutesica',null,null,'Cadastrar/remover',
  					['<img src="../ThemeOffice/language.png" />','Cadastrar','frm_listapf_cadastrapedidocontratacaopf.php',null,'Cadastrar'],
  					['<img src="../ThemeOffice/language.png" />','Alterar/Listar','frm_lista_pedidocontratacaopf.php',null,'Alterar/Listar'],
					['<img src="../ThemeOffice/language.png" />','Cancelar','frm_listaedita_pedidocontratacaopf.php',null,'Cancelar'],
  				],
				['<img src="../ThemeOffice/language.png" />','Pessoa Ju&iacutedica',null,null,'Cadastrar/remover',
					['<img src="../ThemeOffice/language.png" />','Cadastrar','frm_listapf_cadastra_pedidocontratacaopj.php',null,'Cadastrar'],
  					['<img src="../ThemeOffice/language.png" />','Alterar/Listar','frm_lista_pedidocontratacaopj.php',null,'Alterar/Listar'],
				],  				
			],
			[null,'Ficha Produ��o',null,null,'Ficha Produ&ccedil;&atilde;o',				
  				['<img src="../ThemeOffice/language.png" />','Pessoa F&iacutesica',null,null,'Cadastrar/remover',
  					['<img src="../ThemeOffice/language.png" />','Cadastrar','frm_listapedidocontratacaopf_producao',null,'Cadastrar'],
  					['<img src="../ThemeOffice/language.png" />','Alterar/Listar','frm_lista_producao.php',null,'Alterar/Listar'],
  				],
				['<img src="ThemeOffice/language.png" />','Pessoa Jur&iacutedica',null,null,'Cadastrar/remover',
					['<img src="../ThemeOffice/language.png" />','Cadastrar','frm_cadastra_producao.asp',null,'Cadastrar'],
  					['<img src="../ThemeOffice/language.png" />','Alterar/Listar','listapf.asp',null,'Alterar/Listar'],
				],  				
			],
		
			[null,'Ajuda','ajuda.php',null,null],
			[null,'Sair','../index.php',null,null]
		];
		cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');