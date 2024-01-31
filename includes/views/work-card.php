<div class="card work-card">
	<img src="<?php echo esc_url( $Work->get_image() ); ?>" alt="<?php echo esc_attr( $Work->get_title() ); ?>" class="card-img-top work-card--image">
	<div class="card-body work-card--body">
		<h3 class="work-card--title">
			<a href="<?php echo esc_url( $Work->get_permalink() ); ?>" class="stretched-link">
				<?php echo $Work->get_title(); ?>
			</a>
		</h3>
	</div>
</div>