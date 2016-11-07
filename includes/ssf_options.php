<?php
function get_ssf_codeScript_cabecalho() {
    echo get_option('ssf_codeScript_header') , "\n";
}
add_action('wp_head', 'get_ssf_codeScript_cabecalho');

function get_ssf_codeScript_rodape() {
    echo get_option('ssf_codeScript_footer') , "\n";
}
add_action('wp_footer', 'get_ssf_codeScript_rodape');

function get_ssf_codeScript_pagina() {
	$Conversao_pagina_ID = get_option('ssf_codeScript_selectpage');
	if(!empty($Conversao_pagina_ID) && ($Conversao_pagina_ID > 0)){
		if(($Conversao_pagina_ID == get_the_ID()) && (!empty(get_option('ssf_codeScript_page')))){
			echo get_option('ssf_codeScript_page') , "\n";
		}
	}
}
add_action('wp_footer', 'get_ssf_codeScript_pagina');

//Shortcode Telefone
function get_ssf_shortcode_telefone(){
	$telefone = get_option('ssf_shortcode_telefone');
	return $telefone;
}
add_shortcode('telefone','get_ssf_shortcode_telefone');

//Shortcode Texto
function get_ssf_shortcode_text() {
    
    $descricaoShortcode = get_option('ssf_shortcode_text_link');
    return '<a href="'.get_ssf_shortcode_link_page().'">'.$descricaoShortcode.'</a>';
}
add_shortcode('cotacao', 'get_ssf_shortcode_text');

//Página para link do shortcode
function get_ssf_shortcode_link_page() {
    $link_shortcode = get_permalink(get_option('ssf_shortcode_selectpage'));
	
    return $link_shortcode;
}

//Ativar link desatualizado
function is_ativar_link_desatualizado(){
	if(get_option('ssf_ativar_link_desatualizado') == true){
		return true;
	}else{
		return false;
	}
}

function get_localizacao_link_desatualizado(){
	switch( get_option('ssf_posicionamento_link')) {
		case 'Rodapé':
			$posicao = 'wp_footer';
		break;
		case 'Conteúdo':
			$posicao = 'the_content';
		break;
	}
	return $posicao;
}

function insere_link_desatualizado($content) {
	 
	if(!empty(get_option('ssf_texto_link_desatualizado'))){
		$texto_link = get_option('ssf_texto_link_desatualizado');
	}else{
		$texto_link = "Informar se o conteúdo estiver desatualizado";
	}
	
	$get_texto = '<div id="ssfLinkDesatualizado" style="padding:30px">			
					<form name="email_link" method="post" action="#agradecimento">
						<input type="hidden" value="URL" name="sendto" />
						<input type="submit" id="teste" value="'.$texto_link.'" style="background-color:transparent; border:none; font-size:100%; cursor:pointer">
					</form>
				</div>'
				;
	 
	if(is_ativar_link_desatualizado()){
		if(!is_home() && !is_feed()){
			$content .= $get_texto;
			
			if(!empty($_POST['sendto'])){
			
				global $wp;
				
				$sendto =  empty(get_option('ssf_enviar_email_link_desatualizado_para')) ? get_option ('admin_email') : get_option('ssf_enviar_email_link_desatualizado_para');
				$subject = 'Link Desatualizado';
				$message = "
							<h1>Página desatualizada</h1> <br>
							Data de envio: ".date('d/m/Y')."<br>
							Hora de envio: ".date('H:m:s')."<br>
							<p>Este email foi enviado para informar que a página abaixo encontra-se desatualizada.</p> 
							<p>Por favor atualize a página o mais rápido possivel. </p>
							URL: ".home_url(add_query_arg(array(), $wp->request))."
							";
							
				$fromMail = empty(get_option('ssf_enviar_email_link_desatualizado_de')) ? get_option ('admin_email') : get_option('ssf_enviar_email_link_desatualizado_de');
				$email_copia = get_option('ssf_enviar_email_link_desatualizado_copia');
				$assuntoMail = 'Atualizar site '.site_url();
				$the_title = get_option('blogname');
				$headers = implode("\r\n", array(
											"From: {$the_title} <{$fromMail}>",
											"Reply-To: {$the_title} <{$fromMail}>",
											"Cc: {$email_copia} <{$email_copia}>",
											"Content-Type: text/plain;charset=utf-8",
											));
				
				$attachments = null;
				
				$emailEnviado = wp_mail($sendto, $subject, strip_tags($message), $headers, $attachments);
				
				if($emailEnviado){
				
					$agradecimento_link_desatualizado ='Obrigado por nos informar sobre o conteúdo desatualizado iremos atualizar o mais rápido possivel.';
					do_shortcode('<div id="agradecimento">[box type="download"]'.$agradecimento_link_desatualizado.'[/box]</div>');
					
					
					echo "<script>
							$(document).ready(function(){

									$('#teste').click(function(){
										$('#ssfLinkDesatualizado').css('backgroundColor','#000');  
									})									
							});							
					</script>";
				}
			}
        }
	}
	echo $content;
}
add_filter(get_localizacao_link_desatualizado(), 'insere_Link_desatualizado', 999, 999);

// function enfileirar_plugin_scripts(){
	
		// wp_deregister_script( 'jquery' );
		// wp_register_script( 'jquery', plugin_dir_url( __FILE__ ).'/includes/js/jquery-1.7.2.min.js');
		// wp_enqueue_script( 'jquery' );
	    
	
// }
// add_action('wp_enqueue_scripts','enfileirar_plugin_scripts');










