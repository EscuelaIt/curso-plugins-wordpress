<?php

// Generar shortcode para listar las películas
function eit_films_generar_shortcode($atts) {
    $atts = shortcode_atts(array(
        'cols' => 1,
    ), $atts, 'eit_films');

    ob_start();

    /**
     * Setup query to show the ‘services’ post type with ‘8’ posts.
     * Output the title with an excerpt.
     */
    $args = array(  
      'post_type' => 'film',
      'post_status' => 'publish',
      'posts_per_page' => -1, 
      'orderby' => 'title', 
      'order' => 'ASC', 
    );



    $loop = new WP_Query( $args ); 

    if ( $loop->have_posts() ) {

      $class = 'eit-cols-'.$atts['cols'];

      ?>
      <section class="eit-films <?php echo $class;?>">
      <?php
      
      while ( $loop->have_posts() ) : $loop->the_post(); 
        
        $post_id = get_the_ID();

        $actores = (get_field('actores')) ? get_field('actores') : __('No hay actores', 'eit-films');
        
      ?>
        <article class="eit-film-card">
          <h2 class="eit-film-card__title"><?php echo get_the_title(); ?></h2>
          <?php the_post_thumbnail('eit-films', array('class' => 'eit-film-card__img')); ?>
          <div clas="eit-film-card__actores">Actores: <?php echo $actores;?></div>
          <div class="eit-film-card__director">Director: <?php the_field('director');?></div>
          <?php
          if ( get_field('es_saga')) { ?>
            <div class="eit-film-card__parte">Parte: <?php the_field('parte');?></div>
          <?php 
          }
          ?>
        </article>
      <?php
      endwhile;
      ?>
      </section>
      <?php

    }

    wp_reset_postdata(); 

  	// echo ob_get_clean();
  	return ob_get_clean();


    // return $html;
}
add_shortcode('eit_films', 'eit_films_generar_shortcode');

