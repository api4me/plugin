<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="width-70 fltlft">
    <?php if (isset($this->notice)): ?>
    <div style="color:red;">
        <?php echo $this->notice; ?>
    </div>
    <?php endif; ?>
    <fieldset class="adminform">
    <legend><?php echo JText::_('Main'); ?></legend>
    <table class="admintable">
      <tr>
        <td align="right" class="key"><label for="started"><?php echo JText::_('Seminar Date'); ?>: </label></td>
        <td>
        <?php echo JHTML::_('calendar', $this->data->started, 'started', 'started', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
        </td>
      </tr>
      <tr>
        <td align="right" class="key"><label for="content"><?php echo JText::_('Content'); ?>: </label></td>
         <td><?php echo $this->content; ?></td>
      </tr>
      <?php if (isset($this->data->created)): ?>
      <tr>
        <td align="right" class="key"><?php echo JText::_('Created Date'); ?>: </td>
        <td>
        <?php echo $this->data->created ?>
        </td>
      </tr>
      <?php endif; ?>
      <?php if (isset($this->data->updated)): ?>
      <tr>
        <td align="right" class="key"><?php echo JText::_('Modified Date'); ?>: </td>
        <td>
        <?php echo $this->data->updated ?>
        </td>
      </tr>
      <?php endif; ?>
    </table>
    </fieldset>
</div>

<div class="width-30 fltlft">
<?php echo JHtml::_('sliders.start', 'content-sliders-1', array('useCookie'=>1)); ?>
    <?php echo JHtml::_('sliders.panel', JText::_('Template'), 'template-details'); ?>
    <fieldset class="panelform">
        <ul class="adminformlist">
            <li style="padding: 5px 0"><a href="index.php?option=<?php echo $this->option?>&load=true"><?php echo JText::_("Load template"); ?></a></li>
            <li>
            <a class="modal" href="index.php?option=<?php echo $this->option?>&task=template&tmpl=component" rel="{handler: 'iframe', size: {x: 875, y: 550}, onClose: function() {}}"><?php echo JText::_("Edit template"); ?></a>
            </li>
        </ul>
    </fieldset>

    <?php if (isset($this->history)): ?>
    <?php echo JHtml::_('sliders.panel', JText::_('History'), 'history-details'); ?>
        <ul class="adminformlist history">
        <?php foreach ($this->history as $key => $val): ?>
        <li style="padding: 5px 0 0 15px; "><?php echo ($key + 1); ?>:
        <a class="modal" href="index.php?option=<?php echo $this->option ?>&task=history&id=<?php echo $val->id; ?>&tmpl=component" rel="{handler: 'iframe', size: {x: 875, y: 550}, onClose: function() {}}"><?php echo $val->updated ?></a></li>
      <?php endforeach; ?>
      </ul>
    <?php echo JHtml::_('sliders.end'); ?>
      <?php endif; ?>
</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="id" value="<?php echo $this->data->id; ?>" />
<input type="hidden" name="task" value="<?php echo $this->task; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>
