<?php
/*
Plugin Name: Shortcode Script and Functions
Plugin URI: http://
Description: Adicione Scripts, Shortcode e funcionalidades em suas páginas web.
Version: 0.1
Author: Berg Felix
Author URI: https://github.com/Berg-Felix/codigo-de-conversao
Text Domain: ssf-text-domain
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, see http://www.gnu.org/licenses/gpl-2.0.html.

*/
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; //Sai do plugin se acessado diretamente
}

require_once('includes/ssf_options.php');

// Verifica se não existe nenhum classe com o mesmo nome
if ( ! class_exists('ssf_page_options') ) :
 
	class ssf_page_options{
		
		public function __construct(){
			add_action( 'plugins_loaded', array( $this, 'ssf_load_textdomain' ) );
			add_action( 'admin_menu', array( $this, 'ssf_register_menu' ) );
			add_action( 'admin_init', array( $this, 'ssf_page_init' ) );
		}
		
		//Carrega Arquivos de tradução
		public function ssf_load_textdomain() {
			load_plugin_textdomain( 'ssf-text-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		} 
		
		//Cria um Submenu em configurações
		public function ssf_register_menu(){
			add_options_page('shortcode script and functions',__('Shortcode Script and Functions','ssf-text-domain'),'manage_options','painel_opcoes',array( $this, 'ssf_admin_page' ) );
		}
		
		//Cria a página de personalização 
		public function ssf_admin_page(){
			
			//Deve verificar se o usuário tem a permissões necessária 
			if (! current_user_can ( 'manage_options')) {
				wp_die (__ ( 'Você não tem permissões suficientes para acessar essa página.','ssf-text-domain'));
			}
			?>
			<div class="wrap">
			
				<h1><span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e('Shortcode Script and Functions','ssf-text-domain')?></h1>
				
				<?php
					$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'script';
				?>
				<h2 class="nav-tab-wrapper">
					<a href="?page=painel_opcoes&tab=script" class="nav-tab <?php echo $active_tab == 'Script' ? 'nav-tab-active' : ''; ?>">Script</a>
					<a href="?page=painel_opcoes&tab=shortcode" class="nav-tab <?php echo $active_tab == 'Shortcode' ? 'nav-tab-active' : ''; ?>">Shortcode</a>
					<a href="?page=painel_opcoes&tab=funcionalidades" class="nav-tab <?php echo $active_tab == 'Funcionalidades' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Funcionalidades','ssf-text-domain');?></a>
				</h2>
				
				<form method="post" action="options.php">
				<?php
					if($active_tab == 'script'){
						settings_fields( 'ssf_script_options' );
						do_settings_sections( 'ssf_script_setting_admin' );
					}else if($active_tab == 'shortcode'){
						settings_fields( 'ssf_shortcode_options' );
						do_settings_sections( 'ssf_shortcode_setting_admin' );
					}else if($active_tab == 'funcionalidades'){
						settings_fields( 'ssf_page_options' );
						do_settings_sections( 'ssf_page_setting_admin' );
					}
					
					submit_button();
				?>
				</form>
			</div>
			<?php
		}
		
		//registra e adicona os campos
		public function ssf_page_init() {    
			
			//script
			add_settings_section('ssf_section_codeScript','Script',array( $this, 'ssf_description_section_codeScript' ),'ssf_script_setting_admin'); 
				  
			add_settings_field( 'ssf_codeScript_header',__('Script Header :','ssf-text-domain'), array( $this, 'ssf_input_textarea' ), 'ssf_script_setting_admin', 'ssf_section_codeScript', array('ssf_codeScript_header') );      
			register_setting( 'ssf_script_options','ssf_codeScript_header', array($this,'ssf_validate_setting') );
			
			add_settings_field( 'ssf_codeScript_footer',__('Script Footer :','ssf-text-domain'), array( $this, 'ssf_input_textarea' ), 'ssf_script_setting_admin', 'ssf_section_codeScript', array('ssf_codeScript_footer') );      
			register_setting( 'ssf_script_options','ssf_codeScript_footer', array($this,'ssf_validate_setting') );
			
			add_settings_field( 'ssf_codeScript_selectpage',__('Script em uma página especifica :','ssf-text-domain'), array( $this, 'ssf_select_options' ), 'ssf_script_setting_admin', 'ssf_section_codeScript', array('ssf_codeScript_selectpage') );      
			register_setting( 'ssf_script_options','ssf_codeScript_selectpage', array($this,'ssf_validate_setting') );
					
			add_settings_field( 'ssf_codeScript_page',__('Script página :','ssf-text-domain'), array( $this, 'ssf_input_textarea' ), 'ssf_script_setting_admin', 'ssf_section_codeScript', array('ssf_codeScript_page') );      
			register_setting( 'ssf_script_options','ssf_codeScript_page', array($this,'ssf_validate_setting') );
			
			//Shortcode
			add_settings_section('ssf_section_shortcode','Shortcode',array( $this, 'ssf_description_section_shortcode' ),'ssf_shortcode_setting_admin'); 
			
			add_settings_field( 'ssf_shortcode_telefone',__('Shortcode Telefone :','ssf-text-domain'), array( $this, 'ssf_input_textbox' ), 'ssf_shortcode_setting_admin', 'ssf_section_shortcode', array('ssf_shortcode_telefone', 'Modo de uso :[telefone]') );      
			register_setting( 'ssf_shortcode_options','ssf_shortcode_telefone', array($this,'ssf_validate_setting') );
		
			add_settings_field( 'ssf_shortcode_text_link',__('Shortcode Descrição Link :','ssf-text-domain'), array( $this, 'ssf_input_textbox') , 'ssf_shortcode_setting_admin', 'ssf_section_shortcode', array('ssf_shortcode_text_link', 'Modo de uso : [cotacao]') );      
			register_setting( 'ssf_shortcode_options','ssf_shortcode_text_link', array($this,'ssf_validate_setting') );
				
			add_settings_field( 'ssf_shortcode_selectpage',__('Selecione a página do Link :','ssf-text-domain'), array( $this, 'ssf_select_options') , 'ssf_shortcode_setting_admin', 'ssf_section_shortcode', array('ssf_shortcode_selectpage') );      
			register_setting( 'ssf_shortcode_options','ssf_shortcode_selectpage', array($this,'ssf_validate_setting') );
			
			//Link desatualizado
			add_settings_section('ssf_section_funcionalidades',__('Avisar Conteúdo Desatualizado','ssf-text-domain'),array( $this, 'ssf_description_section_funcionalidades' ),'ssf_page_setting_admin');  
			
			add_settings_field( 'ssf_ativar_link_desatualizado',__('Ativar Link para conteúdo desatualizado :','ssf-text-domain'), array( $this, 'ssf_input_checkbox') , 'ssf_page_setting_admin', 'ssf_section_funcionalidades', array('ssf_ativar_link_desatualizado', __('Sim','ssf-text-domain') ) );      
			register_setting( 'ssf_page_options','ssf_ativar_link_desatualizado', array($this,'ssf_validate_setting') );
			
			add_settings_field( 'ssf_posicionamento_link_rodape',__('Posicionamento do Link :','ssf-text-domain'), array( $this, 'ssf_input_radiobutton') , 'ssf_page_setting_admin', 'ssf_section_funcionalidades', array('ssf_posicionamento_link', __('Rodapé','ssf-text-domain') ) );
			add_settings_field( 'ssf_posicionamento_link_conteudo','', array( $this, 'ssf_input_radiobutton') , 'ssf_page_setting_admin', 'ssf_section_funcionalidades', array('ssf_posicionamento_link', __('Conteúdo','ssf-text-domain')) );      		
			register_setting( 'ssf_page_options','ssf_posicionamento_link', array($this,'ssf_validate_setting') );
			
			add_settings_field( 'ssf_texto_link_desatualizado',__('Texto para o Link :','ssf-text-domain'), array( $this, 'ssf_input_textbox') , 'ssf_page_setting_admin', 'ssf_section_funcionalidades', array('ssf_texto_link_desatualizado') );      
			register_setting( 'ssf_page_options','ssf_texto_link_desatualizado', array($this,'ssf_validate_setting') );
			
			add_settings_field( 'ssf_enviar_email_link_desatualizado_de',__('Enviar email de :','ssf-text-domain'), array( $this, 'ssf_input_textbox') , 'ssf_page_setting_admin', 'ssf_section_funcionalidades', array('ssf_enviar_email_link_desatualizado_de') );      
			register_setting( 'ssf_page_options','ssf_enviar_email_link_desatualizado_de', array($this,'ssf_validate_setting') );
			
			add_settings_field( 'ssf_enviar_email_link_desatualizado_para',__('Enviar email para :','ssf-text-domain'), array( $this, 'ssf_input_textbox') , 'ssf_page_setting_admin', 'ssf_section_funcionalidades', array('ssf_enviar_email_link_desatualizado_para') );      
			register_setting( 'ssf_page_options','ssf_enviar_email_link_desatualizado_para', array($this,'ssf_validate_setting') );
			
			add_settings_field( 'ssf_enviar_email_link_desatualizado_copia',__('Enviar cópia do email para :','ssf-text-domain'), array( $this, 'ssf_input_textbox') , 'ssf_page_setting_admin', 'ssf_section_funcionalidades', array('ssf_enviar_email_link_desatualizado_copia') );      
			register_setting( 'ssf_page_options','ssf_enviar_email_link_desatualizado_copia', array($this,'ssf_validate_setting') );
			
		}
	 
		//validação do campo
		public function ssf_validate_setting($ssf_script_options) {
			return $ssf_script_options;
		}
		
		//Descrição da Sessão
		public function ssf_description_section_codeScript(){
			_e( 'Adicione Script e estilo para suas páginas', 'ssf-text-domain' );
		}
		
		public function ssf_description_section_shortcode(){
			_e( 'Adicione Shortcode em suas páginas', 'ssf-text-domain' );
		}
		
		public function ssf_description_section_funcionalidades(){
			_e( 'Opções de Funcionalidades para suas páginas', 'ssf-text-domain' );
		}
		//Cria um input
		public function ssf_input_textbox($args){
			$option = esc_attr( get_option($args[0]) );
			echo '<input type="text" size="40" id="'.$args[0].'" name="'.$args[0].'" value="'.$option.'" />
				<span>'.$args[1].'</span>';
		}
		
		//Cria uma textarea
		public function ssf_input_textarea($args){
			$option = esc_attr( get_option($args[0]) );
			 echo '<textarea cols="60" rows="6" name="'.$args[0].'"/>'.$option.' </textarea>
				 <span>'.$args[1].'</span>';
		}
		
		//Cria um checkbox
		public function ssf_input_checkbox($args){
			$option = esc_attr( get_option($args[0]) );
			echo '<label><input type="checkbox" name="'.$args[0].'" value="true" '. checked( $option , 'true' , false  ) .'/>
			<span>'.$args[1].'</span></label>';
		}	
		
		//Cria um radioButton
		public function ssf_input_radiobutton($args){
			$option = esc_attr( get_option($args[0]) );
			echo '<label><input type="radio" name="'.$args[0].'" value="'.$args[1].'" '. checked( $option , $args[1] , false).' />			  
				<span>'.$args[1].'</span></label>';
		}	

		//Cria um select para os scripts
		function ssf_select_options($args){ 
			$args = array(
				'show_option_none' => __('Nenhuma página','ssf-text-domain'),
				'show_option_no_change' => __('Selecione uma página','ssf-text-domain'),
				'option_none_value' => '',
				'name' => $args[0],
				'selected' => get_option($args[0]),
				);
			wp_dropdown_pages($args);
		}
	}
	
if( is_admin() )
    $novoCodigoConversao = new ssf_page_options();
	
endif;

