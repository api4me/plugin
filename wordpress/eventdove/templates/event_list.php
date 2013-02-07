<?php
/**
 * The template for displaying an eventdove-meeting list page
 *
 * @package EventDove Meeting (plug-in)
 * @since 1.0.0
 */
global $eventdove_meeting;
//Call the template header
get_header(); ?>

		<!-- This template follows the TwentyTwelve theme-->
		<div id="primary" class="site-content">
			<div id="content" role="main">
            
            <?php $meetings = $eventdove_meeting->list_meeting(); ?>
            <?php if (!$meetings) : ?>
                <div><?php echo __("Oops, There is not any event."); ?></div>
            <?php else : ?>
            <?php foreach ($meetings as $key => $val) : ?>
            <?php foreach ($val as $v) : ?>
            <article class="post type-post status-publish format-standard hentry category-events fpost">
            <div class="hentry-pad">
                <section class="post-meta fix post-nocontent  media">
                <a class="post-thumb img fix" href="<?php echo $eventdove_meeting->url($v["id"]); ?>" rel="bookmark" style="width: 25%; max-width: 150px">
                    <span class="c_img">
                    <img width="150" height="150" src="<?php echo $v["logoUrl"]; ?>" class="attachment-thumbnail wp-post-image" alt="big data"></span>
                </a>
                <section class="bd post-header fix">
                    <section class="bd post-title-section fix">
                        <hgroup class="post-title fix">
                        <h2 class="entry-title">
                            <a href="<?php echo $eventdove_meeting->url($v["id"]); ?>" title="<?php echo $v["title"]; ?>" rel="bookmark"><?php echo $v["title"]; ?></a>
                        </h2>
                        </hgroup>
                        <div class="metabar">
                            <div class="metabar-pad">
                                <em>
                                    <?php echo $v["startTime"] ?>
                                </em>
                            </div>
                        </div>
                    </section>
                    <aside class="post-excerpt">
                        <?php echo $v["brief"] ?>
                    </aside>
                    <a class="continue_reading_link" href="<?php echo $eventdove_meeting->url($v["id"]); ?>" title="<?php echo __("View") , $v["title"]; ?>">-&gt; <?php echo __("Read More"); ?></a>
                </section> 
                </section> 
            </div>
            </article>
            <?php endforeach; ?>
            <?php endforeach; ?>

            <?php endif ?>
			</div><!-- #content -->
		</div><!-- #primary -->

<!-- Call template sidebar and footer -->
<?php get_footer(); ?>
