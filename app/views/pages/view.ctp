<div id="static-content">
<?php
if($this->params['pass'][0]=='home'){ ?>

	<h2><?php echo $page['Page']['title']; ?></h2> 
	<?php echo $page['Page']['content']; ?>

<?php }else if($this->params['pass'][0]=='about-us'){ ?>

	<h2 class="about"><?php echo $page['Page']['title']; ?></h2>

	<?php echo $page['Page']['content']; ?>

<?php }else if($this->params['pass'][0]=='career'){ ?>
	

	<h2 class="career-head"><?php echo $page['Page']['title']; ?></h2> 

	<?php echo $page['Page']['content']; ?>
	
	

<?php }elseif($this->params['pass'][0]=='distributor'){ ?>
	

	<h2 class="ditributor-head"><?php echo $page['Page']['title']; ?></h2> 

	<?php echo $page['Page']['content']; ?>

	

<?php }elseif($this->params['pass'][0]=='contactus'){ ?>


	<h2 class="contact-head"><?php echo $page['Page']['title']; ?></h2>

	<?php echo $page['Page']['content']; ?>


<?php }elseif($this->params['pass'][0]=='privacy-policy'){ ?>
	

	<h2 class="privacy-head"><?php echo $page['Page']['title']; ?></h2>

	<?php echo $page['Page']['content']; ?>
	

<?php }elseif($this->params['pass'][0]=='disclaimer'){ ?>


	<h2 class="disclaimer-head"><?php echo $page['Page']['title']; ?></h2>
	
	<?php echo $page['Page']['content']; ?>

<?php }elseif($this->params['pass'][0]=='terms-of-use'){ ?>
	

	<h2 class="terms-head"><?php echo $page['Page']['title']; ?></h2> 

	<?php echo $page['Page']['content']; ?>

<?php }elseif($this->params['pass'][0]=='company'){ ?>
    <div class="register-block">
        <div class="deal-side2 login-side2 deal">
            <div class="deal-inner-block deal-bg round-15 clearfix">
              <h3><?php echo __l('Business'); ?></h3>
              <h3><?php echo __l('Sign Up / Sign In'); ?></h3>
              <p> <?php echo $html->link(__l('Login'), array('controller' => 'users', 'action' => 'login'), array('title' => __l('Login')));?></p>
               <p> <?php echo $html->link(__l('Register'), array('controller' => 'company', 'action' => 'user', 'register'), array('title' => __l('Register')));?>
              </p>
              <div class="deal-bot-bg"> </div>
            </div>
          </div>
     </div>

        <?php echo $page['Page']['content']; ?>
    
<?php
}else if($this->params['pass'][0]=='api' || $this->params['pass'][0]=='api-terms-of-use' || $this->params['pass'][0]=='api-branding-requirements' || $this->params['pass'][0]=='api-instructions'){ ?>

		<h2 class="newsletter-head"><?php echo $page['Page']['title']; ?></h2>

			<?php echo $page['Page']['content']; ?>

		<ul class="api-list">
			<li><?php echo $html->link(__l('Terms of Use'), array('controller' => 'pages', 'action' => 'view', 'api-terms-of-use'), array('title' => __l('Terms of Use'), 'target' => '_blank'));?></li>
			<li><?php echo $html->link(__l('Branding Requirements'), array('controller' => 'pages', 'action' => 'view', 'api-branding-requirements'), array('title' => __l('Branding Requirements'), 'target' => '_blank'));?></li>
			<li><?php echo $html->link(__l('API Instructions'), array('controller' => 'pages', 'action' => 'view', 'api-instructions'), array('title' => __l('API Instructions'), 'target' => '_blank'));?></li>
		</ul>

<?php } else { ?>

	<h2 class="newsletter-head"><?php echo $page['Page']['title']; ?></h2>
	<?php if(!empty($this->params['named']['type']) && $this->params['named']['type']): ?>
	    <span><?php echo $html->link(__l('Continue Editing'), array('action' => 'edit', $page['Page']['id']), array('class' => 'edit js-edit', 'title' => __l('Continue Editing')));?></span>
		<?php endif; ?>

	<?php echo $page['Page']['content']; ?>

<?php } ?>
</div>