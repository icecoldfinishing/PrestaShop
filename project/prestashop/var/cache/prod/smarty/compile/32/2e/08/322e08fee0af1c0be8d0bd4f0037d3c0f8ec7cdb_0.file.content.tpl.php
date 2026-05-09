<?php
/* Smarty version 4.3.4, created on 2026-05-08 10:25:58
  from '/var/www/html/admin3295/themes/new-theme/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_69fd9e16824b13_58095402',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '322e08fee0af1c0be8d0bd4f0037d3c0f8ec7cdb' => 
    array (
      0 => '/var/www/html/admin3295/themes/new-theme/template/content.tpl',
      1 => 1777368018,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69fd9e16824b13_58095402 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div id="ajax_confirmation" class="alert alert-success" style="display: none;"></div>
<div id="content-message-box"></div>


<?php if ((isset($_smarty_tpl->tpl_vars['content']->value))) {?>
  <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

<?php }
}
}
