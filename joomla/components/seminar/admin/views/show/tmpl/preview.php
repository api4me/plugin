<?php defined('_JEXEC') or die('Restricted access'); ?>
<div>
  <form action="" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <table class="admintable">
      <tr>
        <td class="key"><?php echo JText::_('To'); ?>: </td>
        <td>
          <input type="text" name="to" value="<?php echo $this->data->to; ?>" size="65" />
          <em><?php echo JText::_("Use semicolon(;) to split") ?></em>
        </td>
      </tr>
      <tr>
        <td class="key"><?php echo JText::_('BCC'); ?>: </td>
        <td>
          <input type="text" name="bcc" value="<?php echo $this->data->bcc; ?>" size="65"/>
          <em><?php echo JText::_("Use semicolon(;) to split") ?></em>
        </td>
      </tr>
      <tr>
        <td class="key"><?php echo JText::_('Subject'); ?>: </td>
        <td>
          <input type="text" name="subject" value="<?php echo $this->data->subject; ?>" size="65"/>
        </td>
      </tr>
      <tr>
        <td class="key" valign="top"><?php echo JText::_('Content'); ?>: </td>
        <td valign="top" style="border:solid 1px #ccc; background-color:#F5F5F5; padding:10px;">
          <div style="height: 350px; overflow-y:auto">
          <?php echo $this->data->content; ?>
          </div>
        </td>
      </tr>
      <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
      <tr>
        <td class="key"><?php echo JText::_('Attachment'); ?>: </td>
        <td style="border:solid 1px #ccc; background-color:#F5F5F5;">
          <?php echo JText::_("If you want to add attachment, please choose a docment or photo file to upload.") ?><br/>
          <em>(jpg, gif, png, jpeg, bmp, pdf, ppt, ppts, doc, docx, xls, xlsx, txt, zip)</em><br/>
          <input type="file" name="attachment" />
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right">
          <input type="submit" name="sendmail" value="<?php echo JText::_("Send"); ?>" class="button"
           style="background-color:red; color:white; font-size:14px; width:100px; height:30px"/>
        </td>
      </tr>
    </table>
<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="task" value="<?php echo $this->task; ?>" />
<?php echo JHTML::_('form.token'); ?>
  </form>
</div>
