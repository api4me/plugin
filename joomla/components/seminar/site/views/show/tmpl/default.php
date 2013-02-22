<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="seminar">
    <div class="category-list">
        <h2><?php echo JText::_("Announcement"); ?></h2>
    </div>
    <div class="seminar">
        <div>
        <?php if (isset($this->data['message'])) : ?>
            <div style="padding:20px;width:100%;height:200px"><p><?php echo $this->data['message'] ?></p></div>
        <?php else : ?>
            <?php echo $this->data['content']; ?>
        <?php endif; ?>
        </div>
    </div>

</div>
