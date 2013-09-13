<?php
/*
Template name: Grupos
 */

get_header('home'); ?>

			<div id="content" class="archive-grupos">
			<!-- <div id="area-select" class="agenda">
		<h2 class="filtro-agenda"><?php _e('Filtro'); ?></h2>
				<form>
				<select name="tag-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
				<option value='#'>Filtre...</option>
				<?php // $taxonomies = array('grupos_category');
				// $args = array('orderby'=>'name','hide_empty'=>true);
				// echo get_terms_dropdown($taxonomies, $args); ?>
				</select>
				</form>
			</div> -->
<br />
			
			<h1 class="entry-title">Grupos Participantes</h1>
			<br />

		 <!-- Inicio Loop -->
        <?php
        $args = array(
                'post_type' => 'grupos',
                'posts_per_page' => 66, /* Quantidade de Posts a exibir */
                'orderby' => 'meta_value',
                'order' => 'ASC', /* Ordem DESC > DECRESCENTE ou ASC > ACRESCENTE */
                );
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post(); 
		?>       
        <div id="cada-grupo">
		<?php $my_meta = get_post_meta($post->ID,'_my_meta',TRUE); ?>	
		<div id="thumb-grupos">
        <a href="<?php the_permalink() ?>" rel="bookmark">
		<?php if ( has_post_thumbnail()) the_post_thumbnail('thumb-grupos'); ?>
        </a>
        </div>

			<div id="grupos-titulo">
			<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
			</div>
			
				<div id="conteudo-post">
				<?php echo excerpt( 30 ); ?>
				<h3><?php echo $my_meta['ag_cidade']; ?> <?php echo $my_meta['ag_estado']; ?><br />
				<?php echo $my_meta['ag_pais']; ?></h3>
				</div>
				
				<div id="leia-mais-grupo" class="archive">
				<a href="<?php the_permalink() ?>">Leia mais</a>
				</div>
				
        </div><!-- #cada-grupo -->

		<?php endwhile;	?>

	    <!-- Fim Loop -->
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
		<?php /* Display navigation to next/previous pages when applicable */ ?>
		<?php if (  $wp_query->max_num_pages > 1 ) : ?>
						<div id="nav-below-big" class="navigation">
							<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
							<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
						</div><!-- #nav-below -->
		<?php endif; ?>
			</div><!-- #content -->

<?php get_footer(); ?>
