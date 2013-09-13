<!-- start footer -->
				</div>
<?php //$this->get_sidebar(); ?>
			</div>
			<div style='clear:both;'></div>
			<table <?php $this->classes('nopmb ftable'); ?>>
				<tr>	
					<td <?php $this->classes('fltd'); ?>>
						<b>
							Enviado por encontros.maracatu.org.br
						</b>
					</td>
					<td <?php $this->classes('frtd'); ?>>
						<b>
							com MailPress
						</b>
					</td>	
				</tr>
			</table>
		</div>
<?php if (isset($this->args->unsubscribe)) { ?>
			<div <?php $this->classes('mail_link'); ?>>
				Se n&atilde;o quiser mais receber nossas mensagens <a href='{{unsubscribe}}'  <?php $this->classes('mail_link_a a'); ?>>Clique Aqui</a>
			</div>
<?php } ?>
		</div>
	</body>
</html>