<div class="my_meta_control">
	
	    
    <div id="campo_meta">
    <label>Site do Grupo</label>
    <p>
		<input type="text" name="_my_meta[ag_site]" value="<?php if(!empty($meta['ag_site'])) echo $meta['ag_site']; ?>"/>
		<span>Coloque o endere&ccedil;o (URL) do site (com http://)</span>
	</p>
    </div>
    
	<div id="campo_meta">
    <label>Grupo no FaceBook</label>
	<p>
		<input type="text" name="_my_meta[ag_face]" value="<?php if(!empty($meta['ag_face'])) echo $meta['ag_face']; ?>"/>
		<span>Coloque o endere&ccedil;o (URL) do Grupo no FaceBook (com http://)</span>
	</p>
    </div>
	
	<div id="campo_meta">
    <label>E-mail Oficial</label>
	<p>
		<input type="text" name="_my_meta[ag_email]" value="<?php if(!empty($meta['ag_email'])) echo $meta['ag_email']; ?>"/>
		<span>Coloque o e-mail de contato do Grupo</span>
	</p>
    </div>
	
	<div id="campo_meta">
    <label>Cidade</label>
	<p>
		<input type="text" name="_my_meta[ag_cidade]" value="<?php if(!empty($meta['ag_cidade'])) echo $meta['ag_cidade']; ?>"/>
		<span>Coloque a Cidade do grupo</span>
	</p>
    </div>
	
	<div id="campo_estado">
    <label>Estado</label>
	<p>
		<input type="text" name="_my_meta[ag_estado]" value="<?php if(!empty($meta['ag_estado'])) echo $meta['ag_estado']; ?>"/>
		<span>Coloque o Estado do grupo abreviado, exemplo: PE, SP, RJ. Se estiver no exterior deixe em branco</span>
	</p>
    </div>

	<div id="campo_meta">
    <label>Pa&iacute;s</label>
	<p>
		<input type="text" name="_my_meta[ag_pais]" value="<?php if(!empty($meta['ag_pais'])) echo $meta['ag_pais']; ?>"/>
		<span>Coloque o pa&iacute;s do grupo. Se estiver no Brasil deixe em branco</span>
	</p>
    </div>
	
	<div id="campo_meta">
    <label>Integrantes</label>
	<p>
		<input type="text" name="_my_meta[ag_integrantes]" value="<?php if(!empty($meta['ag_integrantes'])) echo $meta['ag_integrantes']; ?>"/>
		<span>Coloque a quantidade atual de integrantes do grupo</span>
	</p>
    </div>
	
    <div id="hack-meta"></div>  
	
</div>