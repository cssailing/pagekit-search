<?php $view->style('statistics-index', 'search:assets/css/search.css') ?>
<?php $view->script('statistics-index', 'search:app/bundle/statistics-index.js', 'vue') ?>

<div id="statistic" class="uk-margin" v-cloak>

	<div class="uk-grid pk-grid-large">
		<div class="pk-width-sidebar">
			<div class="uk-panel">
				<!--<h4>-->
				<ul class="uk-nav uk-nav-side pk-nav-large">

					<li :class="{'uk-active': view == 'template-all'}">
						<a @click.prevent="view = 'template-all'"><i class="fi-icon-large-bars uk-margin-right"></i>{{ 'All' | trans }}</a>
					</li>
					<li :class="{'uk-active': view == 'template-summary'}">
						<a @click.prevent="view = 'template-summary'"><i class="fi-icon-large-bar-chart uk-margin-right"></i>{{ 'Summary' | trans }}</a>
					</li>
					<li class="uk-nav-divider">
					</li>
				</ul>
				<!--</h4>-->
			</div>
			<h5 class="uk-margin-remove"> {{ 'Database size:' | trans }} {{ db_len.len_mb }} {{ 'Mb' }} </h5>
		</div>

		<div class="pk-width-content">

			<div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap">
				<div class="uk-flex uk-flex-middle uk-flex-wrap">
					<h3 class="uk-margin-remove">{{ '{0} %count% Records|{1} %count% Record|]1,Inf[ %count% Records' | transChoice(count, {count:count}) }}</h3>
					<div class="pk-search">
						<form class="uk-search uk-search-default">
							<a href="" class="uk-search-icon-flip" uk-search-icon></a>
							<input class="uk-search-input" type="search" v-model="config.filter.search" placeholder="Search..." />
						</form>
					</div>
				</div>

				<div class="uk-flex uk-flex-middle uk-flex-right uk-margin-right">
					<h4>
						<ul class="uk-subnav uk-subnav-pill uk-margin-remove">
							<!--  uk-subnav-line" data-uk-switcher="{connect:'#my-id'}">-->
							<li :class="{'uk-active': interval == 'today'}">
								<a @click.prevent="interval = 'today'">{{ 'Today' | trans }}</a>
							</li>
							<li :class="{'uk-active': interval == 'yesterday'}">
								<a @click.prevent="interval = 'yesterday'">{{ 'Yesterday' | trans }}</a>
							</li>
							<li :class="{'uk-active': interval == 'week'}">
								<a @click.prevent="interval = 'week'">{{ 'Week' | trans }}</a>
							</li>
							<li :class="{'uk-active': interval == 'month'}">
								<a @click.prevent="interval = 'month'">{{ 'Month' | trans }}</a>
							</li>
							<li :class="{'uk-active': interval == 'year'}">
								<a @click.prevent="interval = 'year'">{{ 'Year' | trans }}</a>
							</li>
						</ul>
					</h4>
				</div>
			</div>
			<transition mode="out-in">
				<componets :is="view" :keywords.sync="keywords"></componets>
			</transition>

			<h3 class="uk-h1 uk-text-muted uk-text-center" v-show="keywords && !keywords.length">{{ 'No records found.' | trans }}</h3>
			<v-pagination v-model="config.page" :pages="pages" v-show="pages > 1 || config.page > 0"></v-pagination>
		</div>
	</div>
</div>