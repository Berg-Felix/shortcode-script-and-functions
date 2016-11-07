<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();
 
// Deleta as opções
delete_option( 'ssf_codeScript_header' );
delete_option( 'ssf_codeScript_footer' );
delete_option( 'ssf_codeScript_selectpage' );
delete_option( 'ssf_codeScript_page' );
delete_option( 'ssf_shortcode_telefone' );
delete_option( 'ssf_shortcode_text_link' );
delete_option( 'ssf_shortcode_selectpage' );
delete_option( 'ssf_ativar_link_desatualizado' );
delete_option( 'ssf_posicionamento_link' );
delete_option( 'ssf_texto_link_desatualizado' );
delete_option( 'ssf_enviar_email_link_desatualizado_de' );
delete_option( 'ssf_enviar_email_link_desatualizado_para' );
delete_option( 'ssf_enviar_email_link_desatualizado_copia' );

//para opções de sites em Multisite
delete_site_option( 'ssf_codeScript_header' );
delete_site_option( 'ssf_codeScript_footer' );
delete_site_option( 'ssf_codeScript_selectpage' );
delete_site_option( 'ssf_codeScript_page' );
delete_site_option( 'ssf_shortcode_telefone' );
delete_site_option( 'ssf_shortcode_text_link' );
delete_site_option( 'ssf_shortcode_selectpage' );
delete_site_option( 'ssf_ativar_link_desatualizado' );
delete_site_option( 'ssf_posicionamento_link' );
delete_site_option( 'ssf_texto_link_desatualizado' );
delete_site_option( 'ssf_enviar_email_link_desatualizado_de' );
delete_site_option( 'ssf_enviar_email_link_desatualizado_para' );
delete_site_option( 'ssf_enviar_email_link_desatualizado_copia' );