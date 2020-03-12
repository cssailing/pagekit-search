<?php $view->script('search-settings', 'search:app/bundle/settings.js', ['vue']) ?>

<div id="settings" class="uk-form-horizontal" v-cloak>

    <div class="pk-grid-large" uk-grid>
        <div class="pk-width-sidebar">
            <div class="uk-panel">
                <ul class="uk-nav uk-nav-default pk-nav-large" uk-switcher="connect: #tab-content">
                    <li><a><i class="pk-icon-large-settings uk-margin-right"></i> {{ 'General' | trans }}</a></li>
                    <li><a><i class="pk-icon-large-server uk-margin-right"></i> {{ 'Additional' | trans }}</a></li>
                </ul>
            </div>

        </div>
        <div class="pk-width-content">

            <ul id="tab-content" class="uk-switcher uk-margin">
                <li>
                    <div class="uk-margin uk-flex uk-flex-between uk-flex-wrap">
                        <div>
                            <h2 class="uk-h3 uk-margin-remove">{{ 'General' | trans }}</h2>
                        </div>
                        <div>
                            <button class="uk-button uk-button-primary" @click.prevent="save">{{ 'Save' | trans }}</button>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label">{{ 'Limit search result' | trans }}</label>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-form-controls-condensed">
                                <!--<input type="number" v-model="config.defaults.limit_search_result" min="1" max="100" step="5" class="uk-form-width-small">-->
                                <select class="uk-form-small uk-select" v-model="config.defaults.limit_search_result">
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="150">150</option>
                                    <option value="250">250</option>
                                    <option value="0">{{ 'Unlimit' | trans }}</option>
                                </select>
                            </p>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label">{{ 'Result per page' | trans }}</label>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-form-controls-condensed">
                                <select class="uk-form-small uk-select" v-model="config.defaults.result_per_page">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="25">25</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="0">{{ 'All' | trans }}</option>
                                </select>
                            </p>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <span class="uk-form-label">{{ 'Default Search settings' | trans }}</span>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-form-controls-condensed">
                                <label><input class="uk-checkbox" type="checkbox" v-model="config.defaults.use_areas_search"> {{ 'Use areas of search'| trans }}</label>
                            </p>
                            <p class="uk-form-controls-condensed">
                                <label><input class="uk-checkbox" type="checkbox" v-model="config.defaults.show_pages_counter"> {{ 'Show pages counter' | trans }}</label>
                            </p>
                            <p class="uk-form-controls-condensed">
                                <label><input class="uk-checkbox" type="checkbox" v-model="config.defaults.show_posted_in"> {{ 'Show Posted In' | trans }}</label>
                            </p>
                            <p class="uk-form-controls-condensed">
                                <label><input class="uk-checkbox" type="checkbox" v-model="config.defaults.data_creation"> {{ 'Consider Date' | trans }}</label>
                            </p>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <span class="uk-form-label">{{ 'Default layout settings' | trans }}</span>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-form-controls-condensed">
                                <label>
                                    <input class="uk-checkbox" type="checkbox" v-model="config.defaults.show_title" value="title">
                                    {{ 'Show Title' | trans }}
                                </label>
                                <p>
                                    <input class="uk-form-small uk-input" type="text" v-model="config.defaults.title">
                                </p>
                            </p>
                            <p class="uk-form-controls-condensed">
                                <label><input class="uk-checkbox" type="checkbox" v-model="config.defaults.markdown_enabled"> {{ 'Enable Markdown' | trans }}</label>
                            </p>

                        </div>
                    </div>

                </li>

                <li>
                    <div class="uk-margin uk-flex uk-flex-between uk-flex-wrap">
                        <div>
                            <h3 class="uk-h3 uk-margin-remove">{{ 'Additional' | trans }}</h3>
                        </div>
                        <div>
                            <button class="uk-button uk-button-primary" @click.prevent="save">{{ 'Save' | trans }}</button>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <span class="uk-form-label">{{ 'Search Statistics' | trans }}</span>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-form-controls-condensed">
                                <label><input class="uk-checkbox" type="checkbox" v-model="config.advanced.statistics_enabled"> {{ 'Enable Search Statistics'| trans }}</label>
                            </p>
                            <p>
                                <button class="uk-button uk-button-primary" type="button" href="#" data-uk-tooltip title="{delay:200}" :title="'Clear Statistics BD'| trans" @click.prevent="$refs.modal.open()" v-show="'1' == config.advanced.statistics_enabled">{{ 'Clear Statistics' | trans }}</button>
                            </p>
                        </div>
                    </div>

                </li>
            </ul>

        </div>
    </div>

    <v-modal ref="modal" :options="{bgClose: false}">
        <form class="uk-form-stacked">

            <div class="uk-modal-header">
                <h3>{{ 'Select Interval of Time to Clear Search Statistics' | trans }}</h3>
            </div>

            <div class="uk-modal-body">
                <p class="uk-form-controls-condensed">
                    <label><input class="uk-radio" type="radio" v-model="interval" value="all"> {{ 'Clear all' | trans }}</label>
                </p>

                <p class="uk-form-controls-condensed">
                    <label><input class="uk-radio" type="radio" v-model="interval" value="yesterday"> {{ 'Older, than a yesterday' | trans }}</label>
                </p>

                <p class="uk-form-controls-condensed">
                    <label><input class="uk-radio" type="radio" v-model="interval" value="week"> {{ 'Older, than a week' | trans }}</label>
                </p>

                <p class="uk-form-controls-condensed">
                    <label><input class="uk-radio" type="radio" v-model="interval" value="month"> {{ 'Older, than a month' | trans }}</label>
                </p>

                <p class="uk-form-controls-condensed">
                    <label><input class="uk-radio" type="radio" v-model="interval" value="6month"> {{ 'Older, than a 6 months' | trans }}</label>
                </p>

                <p class="uk-form-controls-condensed">
                    <label><input class="uk-radio" type="radio" v-model="interval" value="year" checked="checked"> {{ 'Older, than a year' | trans }}</label>
                </p>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button" autofocus>{{ 'Cancel' | trans }}</button>
                <button class="uk-button uk-button-primary" @click.prevent="clear">{{ 'Clear' | trans }}</button>
            </div>

        </form>
    </v-modal>

</div>