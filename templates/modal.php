<?php
// Variables: $post_id, $data
$name = get_the_title( $post_id );
$title = get_field( 'tp_title', $post_id );
$specialty = get_field( 'tp_specialty', $post_id );
$bio = get_field( 'tp_bio', $post_id );
$intro = get_field( 'tp_intro', $post_id );
$headshot_id = get_field( 'tp_headshot', $post_id );
if ( ! $headshot_id && has_post_thumbnail( $post_id ) ) {
    $headshot_id = get_post_thumbnail_id( $post_id );
}
$img = $headshot_id ? wp_get_attachment_image( $headshot_id, 'x-large', false, [ 'class' => 'tp-modal-img', 'alt' => esc_attr( $name ) ] ) : '';

$focus_tax = 'tp_focus';
$focus_terms = wp_get_post_terms( $post_id, $focus_tax );

// Ensure Font Awesome is available.
if ( class_exists( 'TP_Assets' ) ) { TP_Assets::ensure_fontawesome(); }
?>
<section class="tp-modal" data-tp-modal-id="<?php echo esc_attr( $post_id ); ?>" data-grid-id="<?php echo isset( $data['grid_id'] ) ? esc_attr( $data['grid_id'] ) : ''; ?>" aria-hidden="true" aria-labelledby="tp-modal-title-<?php echo esc_attr( $post_id ); ?>" role="dialog">
  <div class="tp-modal-backdrop" data-tp-close></div>
  <div class="tp-modal-dialog" role="document">
    <header class="tp-modal-header">
      <h2 id="tp-modal-title-<?php echo esc_attr( $post_id ); ?>" class="tp-modal-name"><?php echo esc_html( $name ); ?></h2>
      <div class="tp-modal-sub">
        <?php if ( $specialty ) : ?>
          <span class="tp-modal-specialty"><strong><?php esc_html_e( 'Specialty', 'team-plugin' ); ?>:</strong> <?php echo esc_html( $specialty ); ?></span>
        <?php endif; ?>
        <?php if ( $title ) : ?>
          <span class="tp-modal-title"><?php echo esc_html( $title ); ?></span>
        <?php endif; ?>
      </div>
      <div class="tp-modal-nav">
        <button class="tp-prev" data-tp-prev aria-label="<?php esc_attr_e( 'Previous', 'team-plugin' ); ?>">&#10094;</button>
        <button class="tp-next" data-tp-next aria-label="<?php esc_attr_e( 'Next', 'team-plugin' ); ?>">&#10095;</button>
        <button class="tp-close" data-tp-close aria-label="<?php esc_attr_e( 'Close', 'team-plugin' ); ?>">&times;</button>
      </div>
    </header>
    <div class="tp-modal-body">
      <div class="tp-modal-media">
        <?php echo $img; ?>
        <?php if ( have_rows( 'tp_social', $post_id ) ) : ?>
          <div class="tp-modal-social">
            <?php while ( have_rows( 'tp_social', $post_id ) ) : the_row();
              $platform = sanitize_key( get_sub_field( 'platform' ) );
              $label    = get_sub_field( 'label' );
              $url_fld  = get_sub_field( 'url' );
              $email    = get_sub_field( 'email' );

              $href = '';
              if ( 'email' === $platform && $email ) {
                $href = 'mailto:' . sanitize_email( $email );
              } elseif ( $url_fld ) {
                $href = esc_url( $url_fld );
              }

              $icons = [
                'linkedin'  => 'fa-brands fa-linkedin-in',
                'x'         => 'fa-brands fa-x-twitter',
                'twitter'   => 'fa-brands fa-twitter',
                'github'    => 'fa-brands fa-github',
                'dribbble'  => 'fa-brands fa-dribbble',
                'medium'    => 'fa-brands fa-medium',
                'website'   => 'fa-solid fa-globe',
                'email'     => 'fa-solid fa-envelope',
                'facebook'  => 'fa-brands fa-facebook-f',
                'instagram' => 'fa-brands fa-instagram',
                'youtube'   => 'fa-brands fa-youtube',
                'vimeo'     => 'fa-brands fa-vimeo-v',
                'tiktok'    => 'fa-brands fa-tiktok',
                'threads'   => 'fa-brands fa-threads',
                'angellist' => 'fa-brands fa-angellist',
              ];
              $icon_class = isset( $icons[ $platform ] ) ? $icons[ $platform ] : 'fa-solid fa-link';
              $sr         = $label ?: ( $platform ? ucfirst( $platform ) : ( $href ?: 'link' ) );
              if ( $href ) : ?>
                <a href="<?php echo esc_url( $href ); ?>" <?php echo ( 'email' === $platform ) ? '' : 'target="_blank" rel="noopener"'; ?> class="tp-social" title="<?php echo esc_attr( $sr ); ?>">
                  <i class="<?php echo esc_attr( $icon_class ); ?>" aria-hidden="true"></i>
                  <span class="screen-reader-text"><?php echo esc_html( $sr ); ?></span>
                </a>
              <?php endif; endwhile; ?>
          </div>
        <?php endif; ?>
        <?php if ( ! empty( $focus_terms ) ) : ?>
          <div class="tp-modal-tags">
            <?php foreach ( $focus_terms as $term ) : ?>
              <span class="tp-tag"><?php echo esc_html( $term->name ); ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="tp-modal-content">
        <?php if ( $intro ) : ?>
          <p class="tp-modal-intro"><?php echo esc_html( $intro ); ?></p>
        <?php endif; ?>
        <div class="tp-modal-bio"><?php echo wp_kses_post( wpautop( $bio ) ); ?></div>
      </div>
    </div>
  </div>
</section>
