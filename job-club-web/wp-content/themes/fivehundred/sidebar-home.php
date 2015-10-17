<aside id="sidebar">
<?php if ( is_active_sidebar('home-widget-area') ) : ?>
<div id="primary" class="widget-area">
<ul class="sid">
<?php dynamic_sidebar('home-widget-area'); ?>
</ul>
</div>
<?php endif; ?>
</aside>