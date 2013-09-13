<?php
/*
Plugin Name: Inscrições
Plugin URI: http://saopaulo.wp-brasil.org/inscricao/
Description: Gerenciador de inscrições para  WordCamps utilizando o PagSeguro
Version: 0.1
Author: Rafael Ehlers
Author URI: http://www.riseinternet.com.br
*/

/*  Copyright 2012 Rafael Ehlers (email : rafaehlers@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

function in_init(){
	
	$labels_inscricao = array(
		'name' => 'Inscrições',
		'singular_name' => 'Inscrição',
		'add_new' => 'Adicionar nova',
		'add_new_item' => 'Adicionar nova inscrição',
		'edit_item' => 'Editar inscrição',
		'new_item' => 'Nova inscrição',
		'view_item' => 'Ver inscrição',
		'search_items' => 'Pesquisar inscrições',
		'not_found' =>  'Nenhuma inscrição encontrada',
		'not_found_in_trash' => 'Nenhuma inscrição encontrada na lixeira', 
		//'parent_item_colon' => '',
		'menu_name' => 'Inscrições'	
	);
	
	register_post_type('inscricao', array(
		'labels' => $labels_inscricao,
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		//'rewrite' => true,
		'query_var' => true,
		'rewrite' => array("slug" => "inscricao"), // Permalinks
		'query_var' => "inscricao", // This goes to the WP_Query schema
		//'menu_icon' => get_bloginfo('template_url').'/img/produtos.png', // 16px16
		'menu_icon' => plugin_dir_url(__FILE__).'img/inscricoes.png',
		'supports' => array('title')
	));
	
	$labels_status = array(
		'name' => 'Status',
		'singular_name' => 'Status',
		'search_items' =>  'Buscar status',
		'all_items' => 'Todas os status',
		'parent_item' => 'Status anterior',
		'parent_item_colon' => 'Status anterior:',
		'edit_item' => 'Editar Status', 
		'update_item' => 'Alterar Status',
		'add_new_item' => 'Adicionar novo Status',
		'new_item_name' => 'Nome do novo Status',
		'menu_name' => 'Status',
	); 	

	register_taxonomy('status',array('inscricao'), array(
		'hierarchical' => true,
		'labels' => $labels_status,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'status' ),
	));
	 
	//https://pagseguro.uol.com.br/v2/guia-de-integracao/documentacao-da-biblioteca-pagseguro-em-php.html#PagSeguroTransactionStatus  
	if(!term_exists('registro', 'status')){
		wp_insert_term('Registro do Pedido','status', array(
		'description'=> 'O pedido foi registrado pelo sistema.',
		'slug' => 'registro'
		));
	}
	
	if(!term_exists('aguardando', 'status')){
		wp_insert_term('Aguardando pagamento','status', array(
		'description'=> 'o comprador iniciou a transação, mas até o momento o PagSeguro não recebeu nenhuma informação sobre o pagamento.',
		'slug' => 'aguardando'
		));
	}
	// Código API -> 1
	// Representação em String -> WAITING_PAYMENT
	
	if(!term_exists('analise', 'status')){
		wp_insert_term('Em análise','status', array(
		'description'=> 'o comprador optou por pagar com um cartão de crédito e o PagSeguro está analisando o risco da transação.',
		'slug' => 'analise'
		));
	}
	// Código API -> 2
	// Representação em String -> IN_ANALYSIS
	
	if(!term_exists('paga', 'status')){
		wp_insert_term('Paga','status', array(
		'description'=> 'a transação foi paga pelo comprador e o PagSeguro já recebeu uma confirmação da instituição financeira responsável pelo processamento.',
		'slug' => 'paga'
		));
	}
	// Código API -> 3
	// Representação em String -> PAID
	
	if(!term_exists('disponivel', 'status')){
		wp_insert_term('Disponível','status', array(
		'description'=> 'a transação foi paga e chegou ao final de seu prazo de liberação sem ter sido retornada e sem que haja nenhuma disputa aberta.',
		'slug' => 'disponivel'
		));
	}
	// Código API -> 4
	// Representação em String -> AVAILABLE
	
	if(!term_exists('disputa', 'status')){
		wp_insert_term('Em disputa','status', array(
		'description'=> 'o comprador, dentro do prazo de liberação da transação, abriu uma disputa.',
		'slug' => 'disputa'
		));
	}
	// Código API -> 5
	// Representação em String -> IN_DISPUTE
	
	if(!term_exists('devolvida', 'status')){
		wp_insert_term('Devolvida','status', array(
		'description'=> 'o valor da transação foi devolvido para o comprador.',
		'slug' => 'devolvida'
		));
	}
	// Código API -> 6
	// Representação em String -> REFUNDED

	if(!term_exists('cancelada', 'status')){
		wp_insert_term('Cancelada','status', array(
		'description'=> 'a transação foi cancelada sem ter sido finalizada.',
		'slug' => 'cancelada'
		));
	}
	// Código API -> 7
	// Representação em String -> CANCELLED
	
	if(!term_exists('enviado', 'status')){
		wp_insert_term('Pedido enviado','status', array(
		'description'=> 'o pedido está pago e foi enviado para o cliente.',
		'slug' => 'enviado'
		));
	}
	
	// INICIO CHECKOUT ***********************************************************************************************************************
	if(!empty($_POST['inscricao']) && wp_verify_nonce(esc_attr($_POST['inscricao']), 'wcsp-nonce') && is_email(esc_sql($_POST['email']))){
	
		//printr($_POST);
		
		$ip	=				(filter_var($_POST['ip'], FILTER_VALIDATE_IP)) ? esc_attr($_POST['ip']) : NULL;
		$nome = 			(!empty($_POST['nome'])) ? esc_attr($_POST['nome']) : NULL;
		$email = 			(!empty($_POST['email'])) ? esc_sql($_POST['email']) : NULL;
		$ddd = 				(!empty($_POST['ddd'])) ? esc_attr($_POST['ddd']) : NULL;
		$telefone = 		(!empty($_POST['telefone'])) ? esc_attr($_POST['telefone']) : NULL;
		
		$endereco = 		(!empty($_POST['endereco'])) ? esc_attr($_POST['endereco']) : NULL;
		$numero = 			(!empty($_POST['numero'])) ? esc_attr($_POST['numero']) : NULL;
		$complemento =      (!empty($_POST['complemento'])) ? esc_attr($_POST['complemento']) : NULL;
		$bairro = 			(!empty($_POST['bairro'])) ? esc_attr($_POST['bairro']) : NULL;	
		$cep =				(!empty($_POST['cep'])) ? esc_attr($_POST['cep']) : NULL;	
		$estado = 			(!empty($_POST['estado'])) ? esc_attr($_POST['estado']) : NULL;
		$cidade = 			(!empty($_POST['cidade'])) ? esc_attr($_POST['cidade']) : NULL;
		$outra_cidade = 	(!empty($_POST['outra_cidade'])) ? esc_attr($_POST['outra_cidade']) : NULL;
		$pais = 			(!empty($_POST['pais'])) ? esc_attr($_POST['pais']) : 'BRA';
		$pais_nome = 		(!empty($_POST['pais_nome'])) ? esc_attr($_POST['pais_nome']) : 'Brasil';
		
		$camiseta =			(!empty($_POST['camiseta'])) ? esc_attr($_POST['camiseta']) : NULL;
		
		$pagseguro_itens = array();
		
		//$cont = 0;
		$valor_inscricao = 0;
		
		//foreach($produtos as $key=>$value){
			$pagseguro_itens[$cont]['id'] = $camiseta;
			$pagseguro_itens[$cont]['description'] = 'Inscrição WordCamp São Paulo 2012';
			$pagseguro_itens[$cont]['quantity'] = 1;
			$pagseguro_itens[$cont]['amount'] = 30.00;
			//$valor_itens += $produtos[$key]['valortotal'];
			//$pagseguro_itens[$cont]['weight'] = $produtos[$key]['peso'];
			$pagseguro_itens[$cont]['shippingCost'] = 0.00;
			//if($cont == 0){$pagseguro_itens[$cont]['shippingCost'] = $valor_frete;}
			//$cont++;
		//}
		$valor_inscricao = 15000;
		
		// Cadastra Pedido no WP *********************************************************************************
		date_default_timezone_set('America/Sao_Paulo');
		
		$nova_inscricao = array(
			'post_title'	=> '',
			'post_content'	=> '',//$post_title,
			'post_status'	=> 'publish', // Choose: publish, preview, future, etc.
			'post_type'		=> 'inscricao', // Set the post type based on the IF is post_type X
			'post_author' => 1
		);
		$nova_inscricao_id = wp_insert_post($nova_inscricao); // http://codex.wordpress.org/Function_Reference/wp_insert_post
		if($nova_inscricao_id){ 
			wp_update_post(array('ID' => $nova_inscricao_id, 'post_title' => $nova_inscricao_id));
			
			$termo = term_exists( 'registro', 'status' );
			if(is_array($termo)){
				$termo = $termo['term_id'];
				wp_set_post_terms($nova_inscricao_id, $termo, 'status');
			}		
			
			update_post_meta($nova_inscricao_id, 'ip', $ip);
			update_post_meta($nova_inscricao_id, 'nome', $nome);
			update_post_meta($nova_inscricao_id, 'email', $email);
			update_post_meta($nova_inscricao_id, 'ddd', $ddd);
			update_post_meta($nova_inscricao_id, 'telefone', $telefone);
			update_post_meta($nova_inscricao_id, 'endereco', $endereco);
			update_post_meta($nova_inscricao_id, 'numero', $numero);
			update_post_meta($nova_inscricao_id, 'complemento', $complemento);
			update_post_meta($nova_inscricao_id, 'bairro', $bairro);
			update_post_meta($nova_inscricao_id, 'cep', $cep);
			update_post_meta($nova_inscricao_id, 'estado', $estado);
			update_post_meta($nova_inscricao_id, 'cidade', $cidade);
			update_post_meta($nova_inscricao_id, 'outra_cidade', $outra_cidade);
			update_post_meta($nova_inscricao_id, 'pais', $pais);
			update_post_meta($nova_inscricao_id, 'pais_nome', $pais_nome);
			update_post_meta($nova_inscricao_id, 'camiseta', $camiseta);
			
			update_post_meta($nova_inscricao_id, 'forma_pagamento', "pagseguro");
			
			update_post_meta($nova_inscricao_id, 'post', $_POST);
			
		}
		
		// Checkout PagSeguro *********************************************************************************

		//https://pagseguro.uol.com.br/v2/guia-de-integracao/tutorial-da-biblioteca-pagseguro-em-php.html
		//https://pagseguro.uol.com.br/v2/guia-de-integracao/documentacao-da-biblioteca-pagseguro-em-php.html
		require_once "PagSeguroLibrary/PagSeguroLibrary.php";
		
		$paymentRequest = new PagSeguroPaymentRequest();
		$paymentRequest->setReference($nova_inscricao_id);
		$paymentRequest->setCurrency("BRL");
		$paymentRequest->setItems($pagseguro_itens);
		
		/*
		if(strtoupper($frete_escolhido) == 'SEDEX'){
			$CODIGO = PagSeguroShippingType::getCodeByType('SEDEX');
		}
		if(strtoupper($frete_escolhido) == 'PAC'){
			$CODIGO = PagSeguroShippingType::getCodeByType('PAC');
		}
		*/
		$CODIGO = PagSeguroShippingType::getCodeByType('NOT_SPECIFIED');
		$paymentRequest->setShippingType($CODIGO);
		
		if(is_null($cidade)){$cidade = $outra_cidade;}
		
		$paymentRequest->setShippingAddress(
			array(
				'postalCode' => str_replace('-','',$cep),
				'street' => $endereco,
				'number' => $numero,
				'complement' => $complemento,
				'district' => $bairro,
				'city' => $cidade,
				'state' => $estado,
				'country' => $pais,
			)
		);
		
		$paymentRequest->setSender($nome, $email, $ddd, str_replace('-','',$telefone));
		
		$paymentRequest->setRedirectUrl("http://encontros.maracatu.org.br/retorno/");
		
		try {			
			$credentials = PagSeguroConfig::getAccountCredentials(); 
			$credentials = new PagSeguroAccountCredentials("diego@etedesign.com.br", "8BBE62C73B47420493D7EB5770003DB0");
			$url = $paymentRequest->register($credentials);
			wp_redirect($url);
			exit();
		} catch (PagSeguroServiceException $e) {
			//https://pagseguro.uol.com.br/v2/guia-de-integracao/tutorial-da-biblioteca-pagseguro-em-php.html#tratamento-de-excecoes
			wp_die($e->getMessage());
		}
		
	}
	// FIM CHECKOUT ***********************************************************************************************************************
	
	// Retorno PagSeguro ******************************************************************************************************************
	if($_GET['id_pagseguro']){
		
		$id_pagseguro = esc_attr($_GET['id_pagseguro']);
		
		$l1 = new WP_Query(array('posts_per_page' => '-1', 'post_type'=> 'inscricao', 'meta_key'=> 'pag_codigo_transacao', 'meta_value'=> $id_pagseguro)); 
		if($l1->have_posts()){ 
			
			$email = get_post_meta($l1->post->ID, 'email', TRUE);
			$nome = get_post_meta($l1->post->ID, 'nome', TRUE);
			
			$headers = "MIME-Version: 1.1\n";
			$headers .= "Content-type: text/html; charset=utf-8\n";
			$headers .= 'Reply-To: diego@etedesign.com.br';
			$subject = "Pedido de inscrição"; 
			$msg = "<img src='http://saopaulo.wp-brasil.org/wp-content/themes/wcsp/img/email.png'><br>";
			$msg.= "<p>Olá ".$nome.",<br>";
			$msg.= "recebemos sua inscrição com sucesso e estamos aguardando a confirmação do pagamento pelo PagSeguro.</p>";
			$msg.= "<p>Número da sua inscrição: ".$l1->post->ID."</p>";
			$msg.= "<p><br></p>";
			$msg.= "<p>Em caso de dúvida, entre em contato conosco respondendo a esta mensagem.</p>";
			$envia = wp_mail($email, $subject, $msg, $headers );
			//if($envia){ die('foi');}else{ die('nao foi');}

		}		
	}
	
	// Notificação PagSeguro ***********************************************************************************************************************
	if(isset($_POST['notificationCode']) && isset($_POST['notificationType'])){
		$code = (isset($_POST['notificationCode']) && trim($_POST['notificationCode']) !== ""  ? trim($_POST['notificationCode']) : null);
		$type = (isset($_POST['notificationType']) && trim($_POST['notificationType']) !== ""  ? trim($_POST['notificationType']) : null);
		
		if ( $code && $type ) {
			
			require_once "PagSeguroLibrary/PagSeguroLibrary.php";
			
			$notificationType = new PagSeguroNotificationType($type);
			$strType = $notificationType->getTypeFromValue();
			
			switch($strType) {
				
				case 'TRANSACTION':

					$credentials = PagSeguroConfig::getAccountCredentials(); 
					try {
						$transaction = PagSeguroNotificationService::checkTransaction($credentials, $code);
						//https://pagseguro.uol.com.br/v2/guia-de-integracao/documentacao-da-biblioteca-pagseguro-em-php.html#PagSeguroTransaction
						$inscricao_id = $transaction->getReference(); 
						
						$status = $transaction->getStatus(); 
						$status = $status->getValue();
						
						//https://pagseguro.uol.com.br/v2/guia-de-integracao/documentacao-da-biblioteca-pagseguro-em-php.html#PagSeguroPaymentMethod
						$paymentMethod = $transaction->getPaymentMethod();
						
						//https://pagseguro.uol.com.br/v2/guia-de-integracao/documentacao-da-biblioteca-pagseguro-em-php.html#PagSeguroPaymentMethodType
						$type = $paymentMethod->getType();  
						$type = $type->getValue();
						switch($type){
							case '1':
								$type = 'Cartão de crédito';
								break;
							case '2':
								$type = 'Boleto';
								break;
							case '3':
								$type = 'Débito online (TEF)';
								break;
							case '4':
								$type = 'Saldo PagSeguro';
								break;
							case '5':
								$type = 'Oi Paggo';
								break;
							default:
								$type = 'Erro';
								break;
								
						}
						//$type = $type->getTypeFromValue();
						update_post_meta($inscricao_id, 'pag_tipo_pagamento_tipo', $type);
						
						//https://pagseguro.uol.com.br/v2/guia-de-integracao/documentacao-da-biblioteca-pagseguro-em-php.html#PagSeguroPaymentMethodCode
						$code = $paymentMethod->getCode();
						$code = $code->getValue();
						switch($code){
							case '101';
								$code = 'Cartão de crédito Visa';
								break;
							case '102';
								$code = 'Cartão de crédito MasterCard';
								break;
							case '103';
								$code = 'Cartão de crédito American Express';
								break;
							case '104';
								$code = 'Cartão de crédito Diners';
								break;
							case '105';
								$code = 'Cartão de crédito Hipercard';
								break;
							case '106';
								$code = 'Cartão de crédito Aura';
								break;
							case '107';
								$code = 'Cartão de crédito Elo';
								break;
							case '201';
								$code = 'Boleto Bradesco';
								break;
							case '202';
								$code = 'Boleto Santander';
								break;
							case '301';
								$code = 'Débito online Bradesco';
								break;
							case '302';
								$code = 'Débito online Itaú';
								break;
							case '303';
								$code = 'Débito online Unibanco';
								break;
							case '304';
								$code = 'Débito online Banco do Brasil';
								break;
							case '305';
								$code = 'Débito online Banco Real';
								break;
							case '306';
								$code = 'Débito online Banrisul';
								break;
							case '401';
								$code = 'Saldo PagSeguro';
								break;
							case '501';
								$code = 'Oi Paggo';
								break;
							default:
								$code = 'Erro';
								break;

						}
						//$code = $code->getTypeFromValue();
						update_post_meta($inscricao_id, 'pag_tipo_pagamento_codigo', $code);
						
						$date = $transaction->getDate(); 
						update_post_meta($inscricao_id, 'pag_data_criacao', $date);
						
						$lastEventDate = $transaction->getLastEventDate(); 
						update_post_meta($inscricao_id, 'pag_data_ultimo_evento', $lastEventDate);
						
						$codigo = $transaction->getCode(); 
						update_post_meta($inscricao_id, 'pag_codigo_transacao', $codigo);
						
						$grossAmount = $transaction->getGrossAmount(); 
						update_post_meta($inscricao_id, 'pag_valor_bruto', $grossAmount);
						
						$discountAmount = $transaction->getDiscountAmount(); 
						update_post_meta($inscricao_id, 'pag_valor_desconto', $discountAmount);
						
						$feeAmount = $transaction->getFeeAmount(); 
						update_post_meta($inscricao_id, 'pag_valor_taxas', $feeAmount);
						
						$netAmount = $transaction->getNetAmount(); 
						update_post_meta($inscricao_id, 'pag_valor_liquido', $netAmount);
						
						$extraAmount = $transaction->getExtraAmount(); 
						update_post_meta($inscricao_id, 'pag_valor_extra', $extraAmount);

						$installmentCount = $transaction->getInstallmentCount(); 
						update_post_meta($inscricao_id, 'pag_parcelas_cc', $extraAmount);
						
						$items = $transaction->getItems(); 
						update_post_meta($inscricao_id, 'pag_array_itens', $items);
						
						/*
						//https://pagseguro.uol.com.br/v2/guia-de-integracao/documentacao-da-biblioteca-pagseguro-em-php.html#PagSeguroSender
						$sender = $transaction->getSender(); 
						
						$pag_nome = $sender->getName();
						update_post_meta($inscricao_id, 'pag_comprador_nome', $pag_nome);
						$pag_email = $sender->getEmail();
						update_post_meta($inscricao_id, 'pag_comprador_email', $pag_email);
						$pag_ddd = $sender->getPhone()->getAreaCode();
						update_post_meta($inscricao_id, 'pag_comprador_ddd', $pag_ddd);
						$pag_telefone = $sender->getPhone()->getNumber();
						update_post_meta($inscricao_id, 'pag_comprador_telefone', $pag_telefone);
						
						//https://pagseguro.uol.com.br/v2/guia-de-integracao/documentacao-da-biblioteca-pagseguro-em-php.html#PagSeguroShipping
						$shipping = $transaction->getShipping();
						$type = $shipping->getType(); // Objeto PagSeguroShippingType  
						$address = $shipping->getAddress(); // objeto PagSeguroAddress  
						$cost = $shipping->getCost(); // Float  
						  
						echo $type->getValue(); // imprime um valor numérico do tipo Integer, p.e. 1  
						echo $address->getCity(); // imprime uma String, São Paulo  
						echo $shipping->getCost(); // imprime um valor numérico do tipo Float, p.e. 10.54  
						*/
						
						switch($status){
							case '1':
								$termo = 'aguardando';
								break;
							case '2':
								$termo = 'analise';
								break;
							case '3':
								$termo = 'paga';
								break;
							case '4':
								$termo = 'disponivel';
								break;
							case '5':
								$termo = 'disputa';
								break;
							case '6':
								$termo = 'devolvida';
								break;
							case '7':
								$termo = 'cancelada';
								break;
						}
						
						$termo = term_exists($termo,'status');
						if(is_array($termo)){
							$termo = $termo['term_id'];
							wp_set_post_terms($inscricao_id, $termo, 'status');
							
							$flag = get_post_meta($inscricao_id, 'email_enviado_pago',TRUE);
							if($status == 3 && empty($flag)){//seta alguma flag importante
								update_post_meta($inscricao_id, 'email_enviado_pago', 'true');
								
								$email = get_post_meta($inscricao_id, 'email', TRUE);
								$nome = get_post_meta($inscricao_id, 'nome', TRUE);
								
								$headers = "MIME-Version: 1.1\n";
								$headers .= "Content-type: text/html; charset=utf-8\n";
								$headers .= 'Reply-To: diego@etedesign.com.br';
								$subject = "Inscrição confirmada"; 
								$msg = "<img src='http://saopaulo.wp-brasil.org/wp-content/themes/wcsp/img/email.png'><br>";
								$msg.= "<p>Olá ".$nome.",<br>";
								$msg.= "<p>parabéns! Sua inscrição no WordCamp São Paulo 2012 está confirmada!</h1>";
								$msg.= "<p>Número da sua inscrição: ".$inscricao_id."</p>";
								$msg.= "<p><br></p>";
								$msg.= "<p>Em caso de dúvida, entre em contato conosco respondendo a esta mensagem.</p>";
								$envia = wp_mail($email, $subject, $msg, $headers );
								//if($envia){ die('foi');}else{ die('nao foi');}

							}
							
							$temp = array();
							$temp = get_post_meta($inscricao_id, 'pag_historico',TRUE);
							if(!empty($temp) && is_array($temp)){
								$temp = array($temp);
								$historico = array(array('data' => $lastEventDate, 'status' => $termo));
								$merge = array_merge($temp, $historico);							
								update_post_meta($inscricao_id, 'pag_historico', $merge);

							}else{
								$historico = array(array('data' => $lastEventDate, 'status' => $termo));
								update_post_meta($inscricao_id, 'pag_historico', $historico);
							}

						}
						
					} catch (PagSeguroServiceException $e) {
						//https://pagseguro.uol.com.br/v2/guia-de-integracao/tutorial-da-biblioteca-pagseguro-em-php.html#tratamento-de-excecoes
						wp_die($e->getMessage());
					}
					
					break;
				
				default:
					LogPagSeguro::error("Unknown notification type [".$notificationType->getValue()."]");
					
			}
			
		} else {
			
			LogPagSeguro::error("Invalid notification parameters.");
			
		}
	}	
}

/*
function requires_wordpress_version() {
	global $wp_version;
	$plugin = plugin_basename( __FILE__ );
	$plugin_data = get_plugin_data( __FILE__, false );

	if ( version_compare($wp_version, "3.3", "<" ) ) {
		if( is_plugin_active($plugin) ) {
			deactivate_plugins( $plugin );
			wp_die( "'".$plugin_data['Name']."' requires WordPress 3.3 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
		}
	}
}
add_action( 'admin_init', 'requires_wordpress_version' );
*/

register_activation_hook(__FILE__, 'in_activation');
register_uninstall_hook(__FILE__, 'in_uninstall');
//register_deactivation_hook(__FILE__, 'in_deactivation');

add_action('init', 'in_init' );
add_action('admin_init', 'in_admin_init' );

add_action('admin_menu', 'in_admin_menu');
add_action('wp_insert_post', 'in_insert_post', 10, 2);

add_filter('manage_edit-inscricao_columns', 'in_edit_columns');
add_action('manage_inscricao_posts_custom_column', 'in_custom_columns');

add_filter('manage_edit-inscricao_sortable_columns', 'in_column_register_sortable' );
add_filter('request', 'in_column_orderby');

add_action('restrict_manage_posts', 'in_filtro_status');

add_action('admin_print_scripts-post-new.php', 'in_admin_script', 11 );
add_action('admin_print_scripts-post.php', 'in_admin_script', 11 );

//add_action('admin_print_styles-post-new.php','in_admin_style');
add_action('admin_print_styles-post.php','in_admin_style');

add_action('admin_print_scripts', 'in_admin_script_generic' );

add_filter('plugin_action_links', 'in_plugin_action_links', 10, 2 );

add_action('admin_notices', 'in_admin_notice');
add_action('admin_init', 'in_nag_ignore');

function in_uninstall() {
	delete_option('in_options');
}

function in_deactivation() {
	delete_option('in_options');
}

function in_activation() {
	in_init();
	flush_rewrite_rules(); //http://codex.wordpress.org/Function_Reference/register_post_type#Flushing_Rewrite_on_Activation
	
	$tmp = get_option('in_options');
    if(!is_array($tmp)){
		
	}
}

/* Display a notice that can be dismissed */

function in_admin_notice() {
    global $current_user;
	
	$options = get_option('in_options');
	$ignore = $options['in_ignore_notice'];
	
    /* Check that the user hasn't already clicked to ignore the message */
    if (!$ignore){
		?>
        <div class="updated">
		<p>
			Você precisa configurar os <strong>dados de acesso</strong> ao PagSeguro | 
			<a href="<?php echo admin_url('edit.php?post_type=inscricao&page=inscricoes/inscricoes.php')?>">Configurar agora</a> | 
			<a href="<?php echo admin_url()?>?in_nag_ignore=0">Ocultar Notificação</a>
        </p>
		</div>
		<?php
    }
}

function in_nag_ignore() {
	/* If user clicks to ignore the notice, add that to the options */
	if ( isset($_GET['in_nag_ignore']) && '0' == $_GET['in_nag_ignore'] ) {
		
		$options = get_option('in_options');
		$options['in_ignore_notice'] = 'true';
		
		update_option('in_options',$options);
		wp_redirect(get_admin_url().'edit.php?post_type=inscricao&page=inscricoes/inscricoes.php');
		exit();
    }
}

function in_admin_init(){
	add_meta_box('in_meta-box','Detalhes do Pedido','in_meta_box','inscricao','normal','high');
	
	register_setting( 'in_plugin_options', 'in_options', 'in_validate_options' );
}

function in_admin_script() {
    global $post_type;
    if( 'inscricao' == $post_type ){
		wp_enqueue_script('inscricao', plugin_dir_url(__FILE__).'js/inscricoes.js');
		wp_dequeue_script('autosave');
	}
}

function in_admin_script_generic() {
    global $parent_file;
    if('edit.php?post_type=inscricao' == $parent_file){
		wp_enqueue_script('inscricao', plugin_dir_url(__FILE__).'js/inscricoes.js');
	}
}

function in_admin_style() {
	wp_register_style('inscricao-styles', plugin_dir_url(__FILE__).'inscricoes.css');
	wp_enqueue_style('inscricao-styles');
}
//-----------------------------------------------------

function in_column_register_sortable( $columns ) {
	$columns['forma_pagamento'] = 'forma_pagamento'; 
	return $columns;
}

function in_column_orderby( $vars ) {
	if ( isset( $vars['orderby'] ) && 'forma_pagamento' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'forma_pagamento',
			'orderby' => 'meta_value'
		) );
	}
 
	return $vars;
}



function in_filtro_status() {
	//http://wordpress.stackexchange.com/questions/578/adding-a-taxonomy-filter-to-admin-list-for-a-custom-post-type
	global $typenow;
	if ($typenow == 'inscricao') {
		$filters = array('status');
		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);

			echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
			echo "<option value=''>Mostrar todos os status</option>";
			foreach ($terms as $term) {
				echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
			}
			echo "</select>";
		}
	}
}


function in_edit_columns($columns){
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "ID",
		"date" => "Data",
		"nome" => "Nome",
		"email" => "Email",
		"camiseta" => "Tamanho Camiseta",
		"pago" => "Pago?",
		"pag_tipo_pagamento_tipo" => "Forma de Pagamento",		
		"categorias" => "Status",
	);
	
	return $columns;
}

function in_custom_columns($column){
	global $post;
	switch ($column){
		
		case "nome":
			echo get_post_meta($post->ID, 'nome', TRUE).'<br />';
			break;

		case "email":
			echo get_post_meta($post->ID, 'email', TRUE).'<br />';
			break;

		case "pag_tipo_pagamento_tipo":
			echo get_post_meta($post->ID, 'pag_tipo_pagamento_tipo',TRUE);
			break;
			
		case "camiseta":
			echo get_post_meta($post->ID, 'camiseta', TRUE);
			break;			
		
		case "pago":
			$pago = get_post_meta($post->ID, 'email_enviado_pago', TRUE);
			if(!empty($pago)) echo '<img src="'.plugin_dir_url(__FILE__).'img/yes.png">';
			break;
		
		/*
		case "forma_pagamento":
			$forma = get_post_meta($post->ID, 'forma_pagamento', TRUE);
			if($forma == 'pagseguro') echo '<img src="'.plugin_dir_url(__FILE__).'img/pagseguro.png">';
			if($forma == 'paypal') echo '<img src="'.plugin_dir_url(__FILE__).'img/paypal.png">';
			break;		
		*/
		
		case "categorias":
			$categorias = get_the_terms($post->ID, "status");
			if(!empty($categorias)){
				$categorias_html = array();
				foreach ($categorias as $categoria)
					//array_push($categorias_html, '<a href="' . get_term_link($categoria->slug, "status") . '">' . $categoria->name . '</a>');
					array_push($categorias_html, $categoria->name);				
				echo implode($categorias_html, ", ");
			}
			else{
				$img =  "<img src=\"".plugin_dir_url(__FILE__)."img/no.png\" style=\"margin-top:10px\" />";	
				echo $img;
			}
			break;		
	}
}

function in_insert_post($post_id, $post = null){
	if ($post->post_type == "inscricao"){
	
		// verify this came from the our screen and with proper authorization.
		if (!wp_verify_nonce($_POST['metabox-inscricao'], 'inscricao-nonce')) {
		//if (!check_admin_referer('inscricao-nonce', 'metabox-inscricao' ) ) {
			return $post_id;
		}
	 
		// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return $post_id;
	 
		// Check permissions
		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	 
	 
		// OK, we're authenticated: we need to find and save the data	
		$post = get_post($post_id);
		if ($post->post_type == 'inscricao') { 
			
			
		}
		return $post_id;
	}// end if
}

function in_meta_box() {
	global $post;
	
	$pag_codigo_transacao = get_post_meta($post->ID, 'pag_codigo_transacao',TRUE);
	$pag_tipo_pagamento_tipo = get_post_meta($post->ID, 'pag_tipo_pagamento_tipo',TRUE);
	$pag_tipo_pagamento_codigo = get_post_meta($post->ID, 'pag_tipo_pagamento_codigo',TRUE);
	$pag_valor_bruto = get_post_meta($post->ID, 'pag_valor_bruto',TRUE);
	$pag_valor_taxas = get_post_meta($post->ID, 'pag_valor_taxas',TRUE);
	$pag_valor_liquido = get_post_meta($post->ID, 'pag_valor_liquido',TRUE);
	$pag_valor_desconto = get_post_meta($post->ID, 'pag_valor_desconto',TRUE);
	$pag_valor_extra = get_post_meta($post->ID, 'pag_valor_extra',TRUE);
	$pag_parcelas_cc = get_post_meta($post->ID, 'pag_parcelas_cc',TRUE);
	$pag_data_criacao = get_post_meta($post->ID, 'pag_data_criacao',TRUE);
	$pag_data_ultimo_evento	 = get_post_meta($post->ID, 'pag_data_ultimo_evento',TRUE);
		
	$historico = get_post_meta($post->ID, 'pag_historico',TRUE);
	
	$inscricao_data = get_the_date('d/m/Y G:i:s');
	$ip = get_post_meta($post->ID, 'ip',TRUE);
	
	$nome = get_post_meta($post->ID, 'nome',TRUE);
	$email = get_post_meta($post->ID, 'email',TRUE);
	$ddd = get_post_meta($post->ID, 'ddd',TRUE);
	$telefone = get_post_meta($post->ID, 'telefone',TRUE);
	
	$endereco = get_post_meta($post->ID, 'endereco',TRUE);
	$numero = get_post_meta($post->ID, 'numero',TRUE);
	$complemento = get_post_meta($post->ID, 'complemento',TRUE);
	$bairro = get_post_meta($post->ID, 'bairro',TRUE);
	$cep = get_post_meta($post->ID, 'cep',TRUE);
	$estado = get_post_meta($post->ID, 'estado',TRUE);
	$cidade = get_post_meta($post->ID, 'cidade',TRUE);
	$outra_cidade = get_post_meta($post->ID, 'outra_cidade',TRUE);
	$pais = get_post_meta($post->ID, 'pais',TRUE);
	$pais_nome = get_post_meta($post->ID, 'pais_nome',TRUE);
	
	$camiseta = get_post_meta($post->ID, 'camiseta',TRUE);
	
	$forma_pagamento = get_post_meta($post->ID, 'forma_pagamento',TRUE);
	
	//$in_post = get_post_meta($post->ID, 'post',TRUE);
	?>        
	<?php wp_nonce_field('inscricao-nonce','metabox-inscricao'); ?>
	<input type="hidden" name="lang" value="<?php echo $lang;?>">
	
	<div id="inscricao">
		<h1>DETALHES DA TRANSAÇÃO</h1>
		<table class="tabela-1">
			<tr>
				<th>Status atual:</th>
				<td>
					<?php
					$categorias = get_the_terms($post->ID, "status");
					if(!empty($categorias)){
						$categorias_html = array();
						foreach ($categorias as $categoria)
							//array_push($categorias_html, '<a href="' . get_term_link($categoria->slug, "status") . '">' . $categoria->name . '</a>');
							array_push($categorias_html, $categoria->name);				
						echo implode($categorias_html, ", ");
					}
					?>
				</td>
			</tr>
			<tr><th>Data de criação:</th><td><?php echo $inscricao_data;?></td></tr>
			<tr><th>Número do Pedido:</th><td><?php echo $post->ID;?></td></tr>
			
			<tr><th>&nbsp;</th><td>&nbsp;</td></tr>
			
			<tr>
				<th>Sistema de Pagamento:</th>
				<td>
					<?php
					if($forma_pagamento == 'pagseguro') echo '<img src="'.plugin_dir_url(__FILE__).'img/pagseguro.png">';
					if($forma_pagamento == 'paypal') echo '<img src="'.plugin_dir_url(__FILE__).'img/paypal.png">';
					?>
				</td>
			</tr>
			
			<?php if($forma_pagamento == 'pagseguro'){?>
			<tr><th>Código PagSeguro:</th><td><?php echo $pag_codigo_transacao;?></td></tr>			
			<tr><th>Taxas PagSeguro:</th><td>R$ <?php echo number_format($pag_valor_taxas, 2, ',', '.');?></td></tr>
			<tr><th>Líquido a receber PagSeguro:</th><td>R$ <?php echo number_format($pag_valor_liquido, 2, ',', '.');?></td></tr>
			<tr><th>Forma de pagamento:</th><td><?php echo $pag_tipo_pagamento_codigo;?> <?php if($pag_tipo_pagamento_tipo == 'Cartão de crédito'){ echo 'em '.$pag_parcelas_cc.' parcelas'; }?></td></tr>
			<tr><th>Valor desconto:</th><td>R$ <?php echo number_format($pag_valor_desconto, 2, ',', '.');?></td></tr>
			<tr><th>Valor extra:</th><td>R$ <?php echo number_format($pag_valor_extra, 2, ',', '.');?></td></tr>
			<?php } ?>
			
		</table>
	
		<h2>Dados do participante</h2>
		<table class="tabela-1">
			<tr><th>Nome:</th><td><?php echo $nome;?></td></tr>
			<tr><th>E-mail:</th><td><?php echo $email;?></td></tr>
			<tr><th>Telefone:</th><td>(<?php echo $ddd;?>) <?php echo $telefone;?></td></tr>
			<tr><th>IP:</th><td><?php echo $ip;?></td></tr>
			<tr><th>Endereço:</th><td><?php echo $endereco;?></td></tr>
			<tr><th>Número:</th><td><?php echo $numero;?></td></tr>
			<tr><th>Complemento:</th><td><?php echo $complemento;?></td></tr>
			<tr><th>Bairro:</th><td><?php echo $bairro;?></td></tr>
			<tr><th>CEP:</th><td><?php echo $cep;?></td></tr>
			<tr><th>Cidade:</th><td><?php if(empty($cidade)){ echo $outra_cidade;}else{ echo $cidade;} ?></td></tr>
			<tr><th>Estado</th><td><?php echo $estado;?></td></tr>
			<tr><th>País:</th><td><?php echo $pais_nome.' ('.$pais.')';?></td></tr>
			<tr><th>&nbsp;</th><td>&nbsp;</td></tr>
			<tr><th>Tamanho da Camiseta</th><td><?php echo $camiseta;?></td></tr>
		</table>
		
		<h2>Histórico de mudanças de status</h2>
		<table class="widefat">
			<thead>
				<tr>
					<th>DATA</th>
					<th>STATUS</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $inscricao_data;?></td>
					<td>Registro da inscrição</td>
				</tr>
				<?php 
				date_default_timezone_set('America/Sao_Paulo');
				if(!empty($historico) && is_array($historico)){
					foreach ($historico as $key => $value){ ?>
					<tr>
						<td><?php echo date('d/m/Y G:i:s', strtotime($value['data']));?></td>
						<td><?php $termo = get_term($value['status'], 'status'); echo $termo->name;?></td>
					</tr>
					<?php }}?>
			</tbody>
		</table>
	<?php
}
		
function in_admin_menu() {
	//add_options_page('Plugin Options Starter Kit Options Page', 'Plugin Options Starter Kit', 'manage_options', __FILE__, 'in_options_page');
	 add_submenu_page('edit.php?post_type=inscricao','Opções de Pedidos','Opções','delete_others_pages',__FILE__, 'in_options_page');	
}

function in_options_page() {
	?>
	<div class="wrap">
		
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Configurações do plugin de Inscrições</h2>
		<p>Você precisa inserir os dados da sua conta do PagSeguro abaixo para registrar as inscrições corretamente.</p>

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('in_plugin_options'); ?>
			<?php $options = get_option('in_options'); ?>
			<input type="hidden" name="in_options[in_ignore_notice]" value="<?php echo $options['in_ignore_notice']; ?>">
			<table class="form-table">

				<tr>
					<th scope="row"><img src="<?php echo plugin_dir_url(__FILE__)?>img/pagseguro.png"> E-mail da conta</th>
					<td>
						<input type="text" size="90" name="in_options[pagseguro_email]" value="<?php echo $options['pagseguro_email']; ?>" maxlength="249" />
					</td>
				</tr>

				<tr>
					<th scope="row"><img src="<?php echo plugin_dir_url(__FILE__)?>img/pagseguro.png"> TOKEN</th>
					<td>
						<input type="text" size="90" name="in_options[pagseguro_token]" value="<?php echo $options['pagseguro_token']; ?>" maxlength="32" />
					</td>
				</tr>
				
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function in_validate_options($input) {
	 // strip html from textboxes
	 
	$input['in_ignore_notice'];
	 
	$input['pagseguro_email'] =  (is_email(esc_attr($input['pagseguro_email']))) ? esc_attr($input['pagseguro_email']) : 'E-mail incorreto!';
	$input['pagseguro_token'] =  esc_attr($input['pagseguro_token']);
	
	return $input;
}

// Display a Settings link on the main Plugins page
function in_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$in_links = '<a href="'.get_admin_url().'edit.php?post_type=inscricao&page=inscricoes/inscricoes.php">'.__('Settings').'</a>';
		array_unshift( $links, $in_links );
	}

	return $links;
}