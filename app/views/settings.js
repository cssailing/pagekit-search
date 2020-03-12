var Settings = {
    name: 'search-settings',
    el: '#settings',
    data() {
        return _.merge({
            interval: ('year'),
        }, window.$data);
    },

    mixins: [Theme.Mixins.Helper],
    theme: {
        hiddenHtmlElements: ['.pk-width-content li > div.uk-flex'],
        elements() {
            var vm = this;
            return {
                'submit': {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Save',
                    class: 'uk-button uk-button-primary',
                    on: {
                        click: () => vm.save()
                    },
                    priority: 0,
                }
            }
        }
    },


    methods: {
        save() {
            this.$http.post('admin/system/settings/config', { name: 'search', config: this.config }).then(function() {
                this.$notify('Settings saved.');
            }, function(data) {
                this.$notify(data, 'danger');
            });
        },

        open() {
            this.$refs.modal.open();
        },
        clear() {
            this.$http.delete('admin/search/statistics/clear', {body: {interval: this.interval }}).then(function(res) {
                if (res.data.count) {
                    this.$notify(res.data.count + ' records deleted.' + '\n\r' + 'Log cleared.');
                } else {
                    this.$notify(res.data.message);
                }
            });
            this.$refs.modal.close();
        }

    },

};

export default Settings;

Vue.ready(Settings);