<span>
	<form class="ui-ajax-form comment-form ui-stream-item clearfix" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<div class="content">
				<fieldset>
					<textarea name="status" data-default-text="<?php echo $status;?>" maxlength="100"><?php echo $status;?></textarea>
				</fieldset>
				<input type="submit" value="Check-in" class="form-btn">
					<div data-role="fieldcontain">
						<?php if(is_array($providers)) { ?>
							<fieldset data-role="controlgroup">
								<?php foreach($providers as $provider => $providerName) { ?>
										<input name="providers[<?php echo $provider;?>]" id="providers[<?php echo $provider;?>]" type="checkbox" checked="checked">
										<label for="providers[<?php echo $provider;?>]"><?php echo $providerName;?></label>
								<?php } ?>
							</fieldset>
						<?php } ?>
					</div>
			</div>
	</form>
</span>
