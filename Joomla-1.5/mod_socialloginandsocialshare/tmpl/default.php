<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php jimport('joomla.plugin.helper');?>
<?php if(!JPluginHelper::isEnabled('system','socialloginandsocialshare')) :
         JError::raiseNotice ('sociallogin_plugin', JText::_ ('MOD_LOGINRADIUS_PLUGIN_ERROR')); 
endif; ?> 

<?php
if(!isset($lr_settings['enableSocialLogin']) || $lr_settings['enableSocialLogin'] == "1"){
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='Off' && !empty($_SERVER['HTTPS']))
	{
		$http='https://';
	}
	else
	{
		$http='http://';
	}
	$loc = (isset($_SERVER['REQUEST_URI']) ? urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']) : urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'])); 
	?>
	<script src="//hub.loginradius.com/include/js/LoginRadius.js" ></script> <script type="text/javascript"> var options={}; options.login=true; LoginRadius_SocialLogin.util.ready(function () { $ui = LoginRadius_SocialLogin.lr_login_settings; $ui.interfacesize = "<?php if(isset($lr_settings['iconSize'])){ echo trim($lr_settings['iconSize']); }?>"; <?php if(isset($lr_settings['iconsPerRow']) && trim($lr_settings['iconsPerRow']) != ""){ echo '$ui.noofcolumns = '.trim($lr_settings['iconsPerRow']).';'; } ?> $ui.lrinterfacebackground = "<?php if(isset($lr_settings['interfaceBackground'])){ echo trim($lr_settings['interfaceBackground']); } ?>"; $ui.apikey = "<?php echo $lr_settings['apikey'] ?>";$ui.callback="<?php echo $loc; ?>"; $ui.lrinterfacecontainer ="interfacecontainerdiv"; LoginRadius_SocialLogin.init(options); }); </script>
	<?php
}
?>

<?php if($type == 'logout') : ?>
<?php $session =& JFactory::getSession();
  if ($lr_settings['showlogout'] == 1) :?>
<form action="index.php" method="post" name="login" id="form-login">
    <div>
	  <?php $db = JFactory::getDBO();
	   $user_lrid = $session->get('user_lrid');
	   $query = "SELECT * FROM ".$db->nameQuote('#__LoginRadius_users')." WHERE id = '".$user->get('id')."' AND LoginRadius_id=".$db->Quote ($user_lrid);
       $db->setQuery($query);
       $find_id = $db->loadResult();
       $query = "SELECT COUNT(*) FROM ".$db->nameQuote('#__LoginRadius_users')." WHERE id = ".$user->get('id');
       $db->setQuery($query);
       $count = $db->loadResult();
	   if (empty($find_id)) {
	     $count = $count;
	   }
	   else {
	     $count = ($count == 0 ? $count : $count -1 );
	   }?>
	    <div style="float:left;"><a href="<?php echo 'index.php?option=com_socialloginandsocialshare&view=user&task=edit';?>" title="My Profile">
		<?php $user_picture = $session->get('user_picture');?>
<img src="<?php if (!empty($user_picture)) { echo JURI::root().'images'.DS.'sociallogin'.DS. $session->get('user_picture');} else {echo JURI::root().'media' . DS . 'com_socialloginandsocialshare' . DS .'images' . DS . 'noimage.png';}?>" alt="<?php echo $user->get('name');?>" style="width:50px; height:auto;background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #CCCCCC; display: block; margin: 2px 4px 4px 0; padding: 2px;"></a>
        </div>
		<div>
		 <div style=" font-weight:bold;">
	<?php if($lr_settings['showname'] == 0) : {
	        echo JText::sprintf( 'HINAME', $user->get('name') );
	      } else : {
		    echo JText::sprintf( 'HINAME', $user->get('username') );
		  } endif; ?></div>
		  <?php echo JText::_('MOD_LOGINRADIUS_VALUE_MAP'); ?> <b><?php echo $count;?></b><br /> <?php echo JText::_('MOD_LOGINRADIUS_VALUE_MAPONE'); ?>
	 <div><a href="<?php echo 'index.php?option=com_socialloginandsocialshare&view=user&task=edit';?>"><?php echo JText::_('MOD_LOGINRADIUS_VALUE_ACCOUNT'); ?></a></div><br />
      
	<div align="center">
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'BUTTON_LOGOUT'); ?>" />
	</div></div>
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="logout" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" /></div>
</form>
<?php endif; ?>
<?php else : ?>
<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
   <?php if ($lr_settings['showicons'] == 0) {
	      echo $params->get('pretext');
	        if (!empty($lr_settings['apikey']) && (!isset($lr_settings['enableSocialLogin']) || $lr_settings['enableSocialLogin'] == "1") ) {?>
              <br />
              <div id="interfacecontainerdiv" class="interfacecontainerdiv"> </div>
      <?php }
		 }?>
<?php if ($lr_settings['showwithicons'] == 1): ?>
<div id='usetrad' name='usetrad'>
	<p id="form-login-username" style="margin-left:10px;">
		<label for="modlgn_username"><?php echo JText::_('Username') ?></label><br />
		<input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="18" />
	</p>
	<p id="form-login-password" style="margin-left:10px;">
		<label for="modlgn_passwd"><?php echo JText::_('Password') ?></label><br />
		<input id="modlgn_passwd" type="password" name="passwd" class="inputbox" size="18" alt="password" />
	</p>
	<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
	<p id="form-login-remember" style="margin-left:10px;">
		<label for="modlgn_remember"><?php echo JText::_('Remember me') ?></label>
		<input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me" />
	</p>
	<?php endif; ?>
	<input type="submit" name="Submit"  style="margin-left:10px;" class="button" value="<?php echo JText::_('LOGIN') ?>" />
	</div><?php endif; ?>
	
	<?php 
	if ($lr_settings['showicons'] == 1) {
	        echo $params->get('pretext');
	        if (!empty($lr_settings['apikey']) && (!isset($lr_settings['enableSocialLogin']) || $lr_settings['enableSocialLogin'] == "1") ){?>
              <br />
              <div id="interfacecontainerdiv" class="interfacecontainerdiv"> </div>
      <?php }
		 }
	if ($lr_settings['showwithicons'] == 1): ?>
<div id='usetrad1' name = 'usetrad1'>
	<ul>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>">
			<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
		</li>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>">
			<?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
		</li>
		<?php
		$usersConfig = &JComponentHelper::getParams( 'com_users' );
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=register' ); ?>">
				<?php echo JText::_('REGISTER'); ?></a>
		</li>
		<?php endif; ?>
	</ul></div><?php endif; ?>
	<?php echo $params->get('posttext'); ?>
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHTML::_( 'form.token' );?>
	</form>
	<?php 
	// Adding column if uses old version.
	/*$provider_exists = false;$pic_exists = false;
	$db =& JFactory::getDBO();
    $columns = "show columns from #__LoginRadius_users";
    $db->setQuery( $columns );
    if ($rows = $db->loadObjectList())  {
      foreach ($rows as $row)  {
         if ($row->Field == 'provider') {
            $provider_exists = true;
            break;
          }
		  if ($row->Field == 'lr_picture') {
            $pic_exists = true;
            break;
          }
      }
    }     
    if (!$provider_exists) {
       $query = "ALTER TABLE #__LoginRadius_users ADD provider varchar(255) NULL";
       $db->setQuery( $query );
       $db->query();
    }
	if (!$pic_exists) {
       $query = "ALTER TABLE #__LoginRadius_users ADD lr_picture varchar(255) NULL";
       $db->setQuery( $query );
       $db->query();
    }*/
	 endif; ?>
