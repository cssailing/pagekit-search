module.exports = [{
        entry: {
            //'settings': './app/components/settings.vue',
            'statistics-index': './app/views/statistics-index.js',
            'settings': './app/views/settings.js',
            'search': './app/views/search.js',
            'search-widget': './app/components/search-widget.vue',
        },
        output: {
            filename: './app/bundle/[name].js',
        },
        module: {
            rules: [
                { test: /\.vue$/, use: 'vue-loader' },
                { test: /\.html$/, use: 'html-loader' }
            ],
        }
    }

];