<?php $view->script('search-submit', 'search:app/bundle/search.js', 'vue') ?>

<?php if ($params['show_title']) : ?>
	<h3 class="uk-heading-bullet">
		<?= ($params['title']) ?>
	</h3>
<?php endif ?>

<div class="uk-container">
	<div id="system-message-container"></div>

	<form id="search-form" class="uk-form-horizontal uk-margin" action="<?= $view->url('@search/submit') ?>" method="post">
		<div class=" uk-search-form">
			<div class=" uk-text-center">
				<input class="uk-input uk-width-3-4@s" name="search[searchword]" v-model="search.searchword" placeholder="<?= __('Search...') ?>" size="30" maxlength="<?= $upper_limit; ?>" value="<?= $this->escape($origkeyword); ?>">
				<button class="uk-button uk-width-1-5@s uk-button-primary" type="submit"><?= __('Submit') ?></button>
			</div>
		</div>
		<div>
			<label for="limit">
				<?php if (!empty($searchword)) : ?>
					<?= __($lists['searchkeywordnresult'], ['%s' =>  '<span class="uk-badge uk-badge-default">' . $total . '</span>']) ?>
			</label>
		<?php endif; ?>

		<?php if ($total > 0) : ?>
			<label> <?= __('Display #') ?> <?php echo $pagination->getLimitBox(); ?></label>
		<?php endif; ?>
		</div>

		<?php if ($params['show_pages_counter']) : ?>
			<p class="counter"> <?php echo $pagination->getPagesCounter(); ?> </p>
		<?php endif; ?>

		<?php $view->token()->get() ?>
	</form>


	<?php if ($error) : ?>
		<div class="error">
			<?= __($error) ?>
		</div>
	<?php else : ?>


		<?php foreach ($results as $result) : ?>
			<article class="uk-article uk-animation-scale-up uk-transform-origin-bottom-center">

				<h3>
					<?php if ($result->href) : ?>
						<a class="uk-link-reset" href="<?= $result->href ?>" title="<?php echo $this->escape($result->title); ?>" <?php if ($result->browsernav == 1) : ?> target="_blank" <?php endif; ?>>
							<?php echo $this->escape($result->title); ?></a>
					<?php else : ?>
						<?php echo $this->escape($result->title); ?>
					<?php endif; ?>
				</h3>

				<?php if ($result->section) : ?>
					<div class="uk-article-meta">
						<?php if ($params['show_posted_in']) : ?>

							<span>
								<?= __('Posted in: ') ?><?php echo $this->escape($result->section); ?>
							</span>

						<?php endif ?>
						<?php if ($params['data_creation'] && $result->created) : ?>

							<span>
								<?php $date = new DateTime($result->created); ?>
								<?= __('Created on:  %s', ['%s' =>  '<time datetime="' . $date->format(\DateTime::W3C) . '" v-cloak>{{ "' . $date->format(\DateTime::W3C) . '" | date("longDate")  }}</time>']) ?>
							</span>

						<?php endif ?>
					</div>
				<?php endif; ?>

				<div class="uk-article-content">
					<?php echo $result->text; ?>
				</div>

				<div class="uk-grid-small uk-child-width-auto" uk-grid>
					<div>
						<a class="uk-button uk-button-text" href="<?= $result->href ?>"><?= __('Read more') ?></a>
					</div>
				</div>

			</article>
			<hr>
		<?php endforeach; ?>

		<div class="uk-flex-center">
			<P><?php echo $pagination->getPagesLinks(); ?></P>
		</div>

	<?php endif; ?>
</div>
</div>