<div class="card service-card">
	<?php if ( has_post_thumbnail() ) : ?>
	<?php the_post_thumbnail( 'full', [ 'class' => 'card-img' ] ); ?>
	<?php ; else : ?>
	<img src="https://placehold.co/600x400/efefef/efefef/" alt="<?php echo esc_attr( get_the_title() ); ?>" class="card-img" />
	<?php endif; ?>
	<div class="card-img-overlay service-card--body">
		<h3 class="card-title service-card--title"><a href="<?php the_permalink(); ?>" class="stretched-link"><?php the_title(); ?></a></h3>
	</div>
</div>