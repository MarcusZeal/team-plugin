<?php
// Variables: $grid_id, $role_tax, $tab_tax

// Role filter terms
$role_terms = get_terms( [ 'taxonomy' => $role_tax, 'hide_empty' => true ] );

// Optional tab taxonomy terms
$tab_terms = [];
if ( $tab_tax ) {
    $tab_terms = get_terms( [ 'taxonomy' => $tab_tax, 'hide_empty' => true ] );
}
?>
<div class="tp-controls" data-grid="<?php echo esc_attr( $grid_id ); ?>">
  <?php if ( ! empty( $tab_terms ) ) : ?>
    <div class="tp-tabs" role="tablist" aria-label="<?php echo esc_attr( get_taxonomy( $tab_tax )->labels->name ); ?>">
      <button class="tp-tab is-active" data-term="__all__"><?php esc_html_e( 'Global', 'team-plugin' ); ?></button>
      <?php foreach ( $tab_terms as $t ) : ?>
        <button class="tp-tab" data-term="<?php echo esc_attr( $t->slug ); ?>"><?php echo esc_html( $t->name ); ?></button>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ( ! empty( $role_terms ) ) : ?>
    <div class="tp-filters" role="toolbar" aria-label="<?php echo esc_attr( get_taxonomy( $role_tax )->labels->name ); ?>">
      <button class="tp-filter is-active" data-term="__all__"><?php esc_html_e( 'Everyone', 'team-plugin' ); ?></button>
      <?php foreach ( $role_terms as $t ) : ?>
        <button class="tp-filter" data-term="<?php echo esc_attr( $t->slug ); ?>"><?php echo esc_html( $t->name ); ?></button>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

