<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="width-100 fltlft">
    <fieldset class="adminform">
    <table class="admintable">
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
<div style="float: right;">
    <input type="submit" class="button" value="<?php echo JText::_("Save"); ?>" />
</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="id" value="<?php echo $this->data->id; ?>" />
<input type="hidden" name="task" value="<?php echo $this->task; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>
