<?php
/**
 * @package WordPress
 * @subpackage WCSP
 */
get_header('home'); ?>

    
<?php if(have_posts()): while(have_posts()): the_post();?>
	
	<article class="post drop-shadow lifted" id="post-<?php the_ID()?>">
		<header>
			<h1><?php the_title()?></h1>
		</header>
		<div class="post-content">
			<?php the_content()?>
			
			<form id="form" action="" method="post">
				<?php wp_nonce_field('wcsp-nonce','inscricao'); ?>
				<input type="hidden" name="ip" value="<?php echo get_client_ip();?>"/>
				
				<label for="nome">Nome completo</label>
					<input tabindex="1" type="text" name="nome" id="nome" maxlength="250" size="50" required="required">
								
				<label for="email">Email <a class="email-sugestao"></a></label>
					<input tabindex="2" type="email" name="email" id="email" maxlength="250" size="50" required="required">
				
				<label for="ddd">Telefone</label>
					<input tabindex="3" type="text" name="ddd" id="ddd" maxlength="2" size="2" required="required">
					<input tabindex="4" type="text" name="telefone" id="telefone" maxlength="9" size="9" required="required">
					
				<label for="endereco">Endereço (Rua, Avenida,...)</label>
					<input tabindex="5" type="text" name="endereco" id="endereco" maxlength="250" size="50" required="required">
				
				<label for="numero">Número</label>
					<input tabindex="6" type="text" name="numero" id="numero" maxlength="250" size="50" required="required">
				
				<label for="complemento">Complemento</label>
					<input tabindex="7" type="text" name="complemento" id="complemento" maxlength="250" size="50">
				
				<label for="bairro">Bairro</label>
					<input tabindex="8" type="text" name="bairro" id="bairro" maxlength="250" size="50" required="required">

				<label for="cep">CEP</label>
					<input tabindex="9" type="text" name="cep" id="cep" maxlength="250" size="10" required="required">
				
				<label for="estado">Estado</label>
					<select tabindex="10" tabindex="15" name="estado" id="estado" required="required"></select>
				
				<label for="cidade">Cidade<a tabindex="16" class="outra_cidade down">Não achou sua cidade?</a></label>
					<select tabindex="11" name="cidade" id="cidade" required="required"></select>
								
					<input tabindex="12" type="text" name="outra_cidade" id="outra_cidade" value="Digite aqui o nome da sua cidade" disabled="disabled" style="display:none"
					onBlur="if(this.value=='')this.value='Digite aqui o nome da sua cidade';" 
					onFocus="if(this.value=='Digite aqui o nome da sua cidade')this.value='';" 
					maxlength="250" size="50" required="required">
				
				<label for="pais">País</label>
					<input tabindex="13" type="text" id="pais" name="pais_nome" value="Brasil" size="50" disabled="disabled">
				
				<label for="camiseta">Tamanho da camiseta <span style="color:#F00">*</span></label>
					<select tabindex="14" id="camiseta" name="camiseta" required="required">
						<option value="">Selecione um tamanho</option>
						<option value="P">P</option>
						<option value="M">M</option>
						<option value="G">G</option>
                        <option value="GG">GG</option>
					</select>
                    <p><small><span style="color:#F00">*</span> juntamente com a inscrição, você ganha uma camiseta do evento</small></p>
					
				<input tabindex="15" type="submit" value="Realizar inscrição">
				
			</form>
		</div>
	</article>
	
<?php endwhile; endif; ?>
<?php get_footer(); ?>
