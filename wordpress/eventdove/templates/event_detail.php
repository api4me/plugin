<?php
/**
 * The template for displaying an eventdove-meeting list page
 *
 * @package EventDove Meeting (plug-in)
 * @since 1.0.0
 */
global $eventdove_meeting;
$data = $eventdove_meeting->detail_meeting();
//Call the template header
get_header(); ?>

<div class="hentry-pad">
    <?php if ($data["find"]) : ?>
    <section class="post-meta fix post-nothumb  media">
        <section class="bd post-header fix">
            <section class="bd post-title-section fix">
                <hgroup class="post-title fix">
                    <h1 class="entry-title"><?php echo $data["title"] ?></h1>
                </hgroup>
            <div class="metabar">
                <div class="metabar-pad"> <em><?php echo $data["startTime"]; ?></em> 
                </div>
            </div>
            </section>
        </section>
    </section>
    <div class="entry_wrap fix">
        <div class="entry_content">
            <p>
                <iframe src="<?php echo $data["iframe"]; ?>" frameborder="0" width="100%" height="2900"></iframe>
            </p>
        </div>
    </div>
    <?php else : ?>
    <?php echo __("Oops, event has been remvoed."); ?>
    <?php endif; ?>
</div>

<!-- Call template sidebar and footer -->
<?php get_footer(); ?>
