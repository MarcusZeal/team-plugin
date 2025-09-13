<?php
// Variables: $post_id, $grid_id, $role_slugs, $tab_slugs, $focus_slugs

$name = get_the_title( $post_id );
$headshot_id = get_field( 'tp_headshot', $post_id );
if ( ! $headshot_id && has_post_thumbnail( $post_id ) ) {
    $headshot_id = get_post_thumbnail_id( $post_id );
}
$img = $headshot_id ? wp_get_attachment_image( $headshot_id, 'large', false, [ 'class' => 'tp-card-img', 'alt' => esc_attr( $name ) ] ) : '';
$intro = get_field( 'tp_intro', $post_id );
$specialty = get_field( 'tp_specialty', $post_id );

$data_terms = implode( ' ', array_map( 'sanitize_title', (array) $role_slugs ) );
$data_tabs  = implode( ' ', array_map( 'sanitize_title', (array) $tab_slugs ) );
$data_focus = implode( ' ', array_map( 'sanitize_title', (array) $focus_slugs ) );
?>
<article class="tp-card" role="listitem" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-roles="<?php echo esc_attr( $data_terms ); ?>" data-tabs="<?php echo esc_attr( $data_tabs ); ?>" data-focus="<?php echo esc_attr( $data_focus ); ?>">
  <div class="tp-card-inner">
    <div class="tp-card-media">
      <?php echo $img; ?>
      <div class="tp-card-overlay">
        <div class="tp-card-meta">
          <?php if ( $specialty ) : ?>
            <div class="tp-card-specialty"><span><?php esc_html_e( 'Specialty', 'team-plugin' ); ?></span> <?php echo esc_html( $specialty ); ?></div>
          <?php endif; ?>
          <?php if ( $intro ) : ?>
            <div class="tp-card-intro"><?php echo esc_html( wp_trim_words( $intro, 12 ) ); ?></div>
          <?php endif; ?>
        </div>
        <button class="tp-card-open" data-modal-target="<?php echo esc_attr( $post_id ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Open profile for %s', 'team-plugin' ), $name ) ); ?>">&rarr;</button>
      </div>
    </div>
    <div class="tp-card-name"><?php echo esc_html( $name ); ?></div>
  </div>
</article>

