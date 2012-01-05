<?php use \app\models\User; ?>
<?php use \app\models\UserStats; ?>

<h2 style="margin:0;">
	<?php echo $this->html->link($user->getName(), "/profile/show/" . $user->getId(), array("rel"=>"nofollow")); ?>
</h2>
<?php if ($user->getPhotoUrl()) { ?>
	<a href="/profile/show/<?php echo $user->getId() ?>">
		<img src="<?php echo $user->getPhotoUrl(); ?>" alt="Foto de <?php echo $user->getName(); ?>" width="200" height="200"/>
	</a>
<?php } ?>
<p>
	<?php echo $user->getUserInfo(); ?>
</p>
<p>
	<?php echo $user->getLastVisitInfo(); ?>
</p>

<ul data-role="listview" data-inset="true" data-theme="<?php echo THEME_LIST; ?>" data-dividertheme="<?php echo THEME_MAIN; ?>">
	<?php if($iFollow) { ?>
    	<li>
    		<?php echo $this->html->link("Parar de seguir", "/profile/unfollow/" . $user->getId()); ?>
    	</li>
    <?php } else { ?>
    	<li>
    		<?php echo $this->html->link("Seguir", "/profile/follow/" . $user->getId(), array("rel" => "external")); ?>
    	</li>
    <?php } ?>
</ul>
<ul data-role="listview" data-inset="true" data-theme="<?php echo THEME_LIST; ?>" data-dividertheme="<?php echo THEME_MAIN; ?>">
	<li>
		<?php echo $this->html->link("Conquistas", "/profile/achievements/" . $user->getId()); ?>
	</li>
	<li>
		<?php echo $this->html->link("Últimas visitas", "/profile/visits/" . $user->getId()); ?>
	</li>
	<li>
		<?php echo $this->html->link("Amigos", "/profile/following/" . $user->getId()); ?>
	</li>
	<li>
		<?php echo $this->html->link("Seguidores", "/profile/followers/" . $user->getId()); ?>
	</li>
	<li>
		<?php echo $this->html->link("Avaliações (" . $user->getStats()->getReviews() . ")", "/profile/reviews/" . $user->getId(), array("rel" => "nofollow")); ?>
	</li>
	<li>
		<?php echo $this->html->link("Locais Cadastrados (" . $user->getStats()->getPlaces() . ")", "/profile/places/" . $user->getId()); ?>
	</li>
<?php /* Não disponível na API Apontador :(
	<li>
		<?php echo $this->html->link("Fotos (" . $user->getStats()->getPhotos() . ")", "/profile/photos/" .$user->getId(), array("rel" => "nofollow")); ?>
	</li>
*/?>	
	<li>
		<?php echo $this->html->link("Ver no Apontador", 'http://www.apontador.com.br/profile/'.$user->getId().'.html', array("rel" => "external", "target" => "_blank")); ?>
	</li>
</ul>
