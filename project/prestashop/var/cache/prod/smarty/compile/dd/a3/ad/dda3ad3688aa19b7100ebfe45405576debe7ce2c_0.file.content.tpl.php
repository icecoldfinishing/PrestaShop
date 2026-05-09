<?php
/* Smarty version 4.3.4, created on 2026-05-08 10:32:18
  from '/var/www/html/admin3295/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_69fd9f926bb587_69769108',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dda3ad3688aa19b7100ebfe45405576debe7ce2c' => 
    array (
      0 => '/var/www/html/admin3295/themes/default/template/content.tpl',
      1 => 1777368018,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69fd9f926bb587_69769108 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>
<div id="content-message-box"></div>

<?php if ((isset($_smarty_tpl->tpl_vars['content']->value))) {?>
	<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

<?php }
}
}
