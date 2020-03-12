import TemplateAll from '../components/templates-all.vue';
import TemplateSumm from '../components/templates-summ.vue';
const StatisticDirectives = {
    name: 'statistic',
    el: '#statistic',
    data() {
        return {
            keywords: false,
            config: {
                filter: this.$session.get('statistics.filter', { order: 'putdate desc', order2: 'wcount desc', limit: 25 })
            },
            pages: 0,
            page: 0,
            count: 0,
            db_len: [],
            interval: this.$session.get('statistics.interval', 'today'),
            view: this.$session.get('statistics.view', 'template-all'),
            statistics: window.$data,
        }
    },
    components: {
        'template-all': TemplateAll,
        'template-summary': TemplateSumm
    },

    mounted() {
        if (!this.view) {
            this.view = this.$session.get('statistics.view', 'template-all');
        }

        if (!this.interval) {
            this.interval = this.$session.get('statistics.interval', 'today');
        }
        this.load();
    },

    ready() {
        this.$watch('config.page', this.load(), { immediate: true });
    },

    watch: {
        'config.filter': {
            handler: function(filter) {
                if (this.config.page) {
                    this.config.page = 0;
                } else {
                    this.load();
                }

                this.$session.set('statistics.filter', filter);
            },
            deep: true
        },

        view: function(view) {
            this.$session.set('statistics.view', view);
            this.load();
        },

        interval: function(interval) {
            this.$session.set('statistics.interval', interval);
            this.load();
        },
    },

    methods: {
        load() {
            console.log(this.config.filter);
            this.$http.post('api/search/statistics{/id}', { filter: this.config.filter, page: this.config.page, view: this.view, interval: this.interval })
                .then(res => {
                    this.keywords = res.data.keywords;
                    this.pages = res.data.pages;
                    this.count = res.data.count;
                    this.db_len = res.data.db_len;
                });
                
        },
    }
};

export default StatisticDirectives;
Vue.ready(StatisticDirectives);