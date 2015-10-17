<?php get_header(); ?>
<div id="container">
<div id="site-description">
	<h1><?php the_title(); ?></h1>
</div>
<div id="content">
	<?php //get_template_part( 'nav', 'above' ); ?>
<?php while ( have_posts() ) : the_post() ?>
	<?php get_template_part( 'entry' ); ?>
<?php comments_template(); ?>
<?php endwhile; ?>
	<?php get_template_part( 'nav', 'below' ); ?>

</div>
	<div id="container">
			
			<br>
			<style type="text/css">
				#example10 li {position:relative;}
				#example10 div.slider-bg {background:#000;top:300px;height:102px;width:600px;left:0;position:absolute;z-index:10;opacity:.5;}
				#example10 div.slider-info {top:300px;height:72px;left:0;position:absolute;width:65px;z-index:15;padding:15px;}
				#example10 div.slider-info strong {font-size:18px;color:#fff;margin-bottom:5px;}
				#example10 div.slider-info p {display:none;font-size:12px;line-height:14px;color:#fff;margin:0 !important;}
				#example10 li.slider-open div.slider-info {width:570px;}
				#example10 li.slider-open div.slider-info strong {font-size:22px;}
				#example10 li.slider-open div.slider-info p {display:block;}
			</style>
			<ul id="example10">
				<li>
					<img src="images/slide0.gif" width="600" height="400" alt="" />
					<div class="slider-bg"></div>
					<div class="slider-info">
						<strong>Lorem ipsum</strong>
						<p class="slider-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras porttitor lacus sollicitudin ligula sagittis a ultricies nulla ultricies. Ut odio nisi, posuere sed blandit at, bibendum non dolor.</p>
					</div>
				</li>
				<li>
					<img src="images/slide1.gif" width="600" height="400" alt="" />
					<div class="slider-bg"></div>
					<div class="slider-info">
						<strong>Dolor Sit</strong>
						<p class="slider-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras in condimentum sem. Aenean faucibus dignissim auctor. In ut libero vitae augue laoreet iaculis at a tellus.</p>
					</div>
				</li>
				<li>
					<img src="images/slide2.gif" width="600" height="400" alt="" />
					<div class="slider-bg"></div>
					<div class="slider-info">
						<strong>Donec Ultrices</strong>
						<p class="slider-text">Duis viverra velit orci. Sed vestibulum mi nec est imperdiet sed ullamcorper augue molestie. Donec ultrices facilisis erat at porttitor.</p>
					</div>
				</li>
				<li>
					<img src="images/slide3.gif" width="600" height="400" alt="" />
					<div class="slider-bg"></div>
					<div class="slider-info">
						<strong>Est Imper</strong>
						<p class="slider-text">Phasellus sed lectus nisl, eget cursus eros. Suspendisse posuere orci eu lorem luctus et porta nunc posuere. Cras sed lectus vitae leo accumsan adipiscing.</p>
					</div>
				</li>
			</ul>
	</div>
<?php get_sidebar(); ?>
<div class="clear"></div>
</div>
<?php get_footer(); ?>