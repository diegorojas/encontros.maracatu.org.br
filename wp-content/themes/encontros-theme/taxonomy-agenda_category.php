<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
get_header(); ?>

		<div id="container">
		<div id="area-select" class="agenda">
		<h2 class="filtro-agenda"><?php _e('Filtro'); ?></h2>
		<form>
		<select name="tag-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
		<option value='#'>Munic&iacute;pios</option>
		<?php $taxonomies = array('agenda_category');
		$args = array('orderby'=>'name','hide_empty'=>true);
		echo get_terms_dropdown($taxonomies, $args); ?>
		</select>
		</form>
			</div><!-- #area-select -->	
			<div id="content" role="main">
			<div id="resumo">	
			<h1 class="category-agenda">AGENDA</h1>
        </div><!-- #resumo -->
		<div id="primeira-linha">			
		
		 <!-- Inicio Loop -->
        <?php while ( have_posts() ) : the_post();	?>       
        <div id="cada-dia">
        <div id="agenda-geral">	
				<div class="evento-agenda">
						<a href="<?php the_permalink() ?>">
						<div class="data-evento-agenda">
							<?php global $post;
							$my_meta = get_post_meta($post->ID,'_refactord-datepicker',TRUE);
							$data_mysql = "$my_meta";
							$timestamp = strtotime($data_mysql); // Gera o timestamp de $data_mysql
							?>
							<div id="mes-agenda"><?php echo date('M', $timestamp); ?></div>
							<div id="dia-agenda"><?php echo date('d', $timestamp); ?></div>

						</div><!-- .data-evento-agenda -->
						</a>
				</div><!-- #evento-agenda -->
			</div><!-- #agenda-geral -->
			<div id="agenda-archive-titulo"> <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2></div>
				<?php $my_meta = get_post_meta($post->ID,'_my_meta',TRUE); ?>
				<div id="info-evento" class="archive">
				<h1 class="negrito">Endere&ccedil;o:</h1>
                <p class="archive"><?php echo $my_meta['ag_endereco']; ?></p>
				<h1 class="negrito">Hor&aacute;rio:</h1>
                <p class="archive"><?php echo $my_meta['ag_hora']; ?></p>
				</div>

				<div id="leia-mais-agenda" class="archive">
				<a href="<?php the_permalink() ?>">Leia mais</a>
				</div>
        </div><!-- #cada-dia -->

		<?php endwhile;	?>

	    <!-- Fim Loop -->
        </div><!-- #primeira-linha -->
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
		<?php /* Display navigation to next/previous pages when applicable */ ?>
		<?php if (  $wp_query->max_num_pages > 1 ) : ?>
						<div id="nav-below-big" class="navigation">
							<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
							<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
						</div><!-- #nav-below -->
		<?php endif; ?>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
