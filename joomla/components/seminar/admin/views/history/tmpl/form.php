<?php defined('_JEXEC') or die('Restricted access'); ?>

<div class="width-100 fltlft">
    <fieldset class="adminform">
    <?php echo $this->data->content; ?>
    </fieldset>
</div>
<div>
    <table class="admintable">
      <tr>
        <td align="right" class="key"><?php echo JText::_('Created Date'); ?>: </td>
        <td>
        <?php echo $this->data->created ?>
        </td>
      </tr>
      <tr>
        <td align="right" class="key"><?php echo JText::_('Modified Date'); ?>: </td>
        <td>
        <?php echo $this->data->updated ?>
        </td>
      </tr>
    </table>
</div>

